<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeamMember;
use App\Models\Category;
use App\Services\FirebaseStorageService;
use Carbon\Carbon;
class TeamMemberController extends Controller
{
    public function create()
{
    $categories = Category::all();
    return view('admin.teammembers.create', compact('categories'));
}

    public function store(Request $request,  FirebaseStorageService $firebase)
    {
        $request->validate([
            'member_id' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email|unique:team_members,email',
            'gender' => 'required|string',
            'contact' => 'required|string',
            'dob' => 'required|date',
            'address' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'member_pic' => 'nullable|image|max:2048',
            'resume' => 'nullable|file|max:5120',
        ]);
    
        $memberId = $request->member_id;

        $picUrl = $request->hasFile('member_pic')
            ? $firebase->upload($request->file('member_pic'), "Maid/photo/{$memberId}")
            : null;
    
        $resumeUrl = $request->hasFile('resume')
            ? $firebase->upload($request->file('resume'), "Maid/resume/{$memberId}")
            : null;

        TeamMember::create([
            'member_id' => $request->member_id,
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'contact' => $request->contact,
            'dob' => $request->dob,
            'address' => $request->address,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'experience' => $request->experience,
            'photo_url' => $picUrl,
            'resume_url' => $resumeUrl,
        ]);
    
        return back()->with('success', 'Member added successfully!');
    }

    public function index()
    {
        $members = TeamMember::with('category')->get();

        $events = [];

        foreach ($members as $member) {
            foreach ($member->requests as $request) {
                $events[] = [
                    'title' => $member->name,
                    'start' => Carbon::parse($request->start_date . ' ' . $request->work_shift_from)->toIso8601String(),
                    'end' => Carbon::parse($request->start_date . ' ' . $request->work_shift_to)->toIso8601String(),
                    'backgroundColor' => '#0d6efd',
                    'borderColor' => '#0d6efd',
                ];
            }
        }

        return view('admin.teammembers.index', compact('members', 'events'));
    }

    public function show($id)
    {
        $member = TeamMember::findOrFail($id);
        return view('admin.teammembers.show', compact('member'));
    }

    public function search(Request $request)
    {
        $term = $request->get('term');
        $members = \App\Models\TeamMember::where('email', 'like', "%$term%")->limit(10)->get();
    
        return response()->json($members->map(function ($member) {
            return [
                'label' => $member->email,
                'value' => $member->email,
            ];
        }));
    }

    public function showCalendar()
    {
        $members = TeamMember::with(['requests' => function($q) {
            $q->where('status', 'assigned'); // or any filter
        }])->get();

        $events = [];

        foreach ($members as $member) {
            foreach ($member->requests as $request) {
                $events[] = [
                    'title' => $member->name . ' - ' . $request->category->name,
                    'start' => Carbon::parse($request->start_date . ' ' . $request->work_shift_from)->toDateTimeString(),
                    'end' => Carbon::parse($request->start_date . ' ' . $request->work_shift_to)->toDateTimeString(),
                    'backgroundColor' => '#007bff',
                    'borderColor' => '#007bff',
                    'extendedProps' => [
                        'member' => $member->name,
                        'email' => $member->email
                    ]
                ];
            }
        }

        return view('admin.calendar', compact('events'));
    }

    public function searchRequest(Request $request)
    {
        $query = $request->get('term', '');

        $results = TeamMember::where('email', 'LIKE', '%' . $query . '%')
            ->pluck('email');

        return response()->json($results);
    }

    public function destroy($id)
    {
        $member = TeamMember::findOrFail($id);
        
        // Check if the team member is assigned to any requests
        if ($member->requests()->count() > 0) {
            return redirect()->route('team-members.index')
                ->with('error', 'Cannot delete this team member because they are assigned to one or more service requests.');
        }

        $member->delete();

        return redirect()->route('team-members.index')->with('success', 'Team member deleted successfully.');
    }

    public function edit($id)
    {
        $member = TeamMember::findOrFail($id);
        $categories = Category::all();
        return view('admin.teammembers.edit', compact('member', 'categories'));
    }

    public function update(Request $request, $id, FirebaseStorageService $firebase)
    {
        $member = TeamMember::findOrFail($id);
    
        $request->validate([
            'category_id' => 'required',
            'member_id' => 'required',
            'name' => 'required|string',
            'email' => 'required|email',
            'gender' => 'required',
            'contact' => 'required',
            'dob' => 'required|date',
        ]);
    
        $data = $request->only([
            'category_id', 'member_id', 'name', 'email', 'gender',
            'contact', 'experience', 'dob', 'address', 'description'
        ]);
    
        $memberId = $request->member_id;
    
        // Upload new photo to Firebase if present
        if ($request->hasFile('member_pic')) {
            $data['photo_url'] = $firebase->upload($request->file('member_pic'), "Maid/photo/{$memberId}");
        } else {
            $data['photo_url'] = $request->input('existing_photo_url');
        }
    
        // Upload new resume to Firebase if present
        if ($request->hasFile('resume')) {
            $data['resume_url'] = $firebase->upload($request->file('resume'), "Maid/resume/{$memberId}");
        } else {
            $data['resume_url'] = $request->input('existing_resume_url');
        }
    
        $member->update($data);
    
        return redirect()->route('team-members.index')->with('success', 'Team member updated successfully.');
    }

}
