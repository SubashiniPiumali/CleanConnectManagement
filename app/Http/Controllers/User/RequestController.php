<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Request as UserRequest;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function store(Request $request)
    {
        $userId = Auth::id();
        $request->validate([
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'work_shift_from' => 'required',
            'work_shift_to' => 'required',
            'start_date' => 'required|date',
            'notes' => 'nullable|string',
           
        ]);

        $user = Auth::user();
        UserRequest::create([
            'user_id'=>$userId,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'category_id' => $request->category_id,
            'work_shift_from' => $request->work_shift_from,
            'work_shift_to' => $request->work_shift_to,
            'start_date' => $request->start_date,
            'notes' => $request->notes,
            'status' => 'pending', 
            'team_member_id' => null,  
        ]);

        return back()->with('success', 'Request posted successfully!');
    }

    public function index()
    {
        $userId = Auth::id();
        $categories = \App\Models\Category::all();
        $pending = UserRequest::where('user_id', $userId)
                    ->where('status', 'pending')
                    ->with('category') 
                    ->get();
    
        $assigned = UserRequest::where('user_id', $userId)
                    ->where('status', 'assigned')
                    ->with(['teamMember', 'category']) 
                    ->get();
    
        $canceled = UserRequest::where('user_id', $userId)
                    ->where('status', 'canceled')
                    ->with('category') 
                    ->get();
    
        return view('user.requests.index', compact('pending', 'assigned', 'canceled','categories'));

      
    }

    public function edit($id)
    {
        $request = UserRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending') // only allow editing if pending
            ->firstOrFail();

        $categories = \App\Models\Category::all();

        return view('user.requests.edit', compact('request', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $eRequest = UserRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $request->validate([
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'work_shift_from' => 'required',
            'work_shift_to' => 'required',
            'start_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $eRequest->update([
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'category_id' => $request->category_id,
            'work_shift_from' => $request->work_shift_from,
            'work_shift_to' => $request->work_shift_to,
            'start_date' => $request->start_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('user.requests')->with('success', 'Request updated successfully.');
    }

    public function updateMultiple(Request $request)
    {
        foreach ($request->input('requests', []) as $data) {
            $request = UserRequest::find($data['id']);

            if ($request && $request->user_id === auth()->id() && $request->status === 'pending') {
                $request->update([
                    'start_date' => $data['start_date'],
                    'work_shift_from' => $data['work_shift_from'],
                    'work_shift_to' => $data['work_shift_to'],
                    'address' => $data['address'],
                    'contact_number' => $data['contact_number'],
                ]);
            }
        }

        return back()->with('success', 'Updated successfully!');
    }

    public function destroy($id)
    {
        $request = UserRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $request->delete();

        return redirect()->route('user.requests')->with('success', 'Request deleted successfully.');
    }
}