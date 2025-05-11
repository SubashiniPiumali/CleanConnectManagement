<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Request as AdminRequest;
use App\Models\TeamMember;
use App\Mail\TeamMemberAssignedMail;
use App\Mail\RequesterAssignmentNotification;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function new()
    {
        $requests = AdminRequest::where('status', 'pending')
            ->with('user')
            ->with('category') 
            ->orderBy('created_at', 'desc')
            ->get();
    
        $teamMembers = TeamMember::all();
    
        return view('admin.requests.new', compact('requests', 'teamMembers'));
    }

    public function assigned()
    {
        $requests = AdminRequest::where('status', 'assigned')->with('user', 'teamMember')->with('category') ->get();
        return view('admin.requests.assigned', compact('requests'));
    }

    public function rejected()
    {
        $requests = AdminRequest::where('status', 'canceled')->with('user')->with('category') ->get();
        return view('admin.requests.rejected', compact('requests'));
    }

    public function assignByEmail(Request $request, $id)
    {
        $request->validate([
            'team_member_email' => 'required|email|exists:team_members,email',
        ]);
    
        $teamMember = TeamMember::where('email', $request->team_member_email)->firstOrFail();
        $requests = AdminRequest::with(['user', 'category'])->findOrFail($id);
        $requests->team_member_id = $teamMember->id;
        $requests->status = 'assigned';
        $requests->save();
        
        
        $fromEmail = Auth::user()->email;
        $fromName = Auth::user()->name;
    
        // Send to team member
        Mail::to($teamMember->email)->send(
            (new TeamMemberAssignedMail($requests, $teamMember))
                ->from($fromEmail, $fromName)
        );
    
        // Send to user who posted the request
        Mail::to($requests->user->email)->send(
            (new RequesterAssignmentNotification($requests, $teamMember))
                ->from($fromEmail, $fromName)
        );
    
        return redirect()->back()->with('success', 'Team member assigned and emails sent!');
    }
    
    public function cancel(Request $request, $id)
{
    $requests = AdminRequest::findOrFail($id);

    if ($requests->status !== 'pending') {
        return back()->with('error', 'Only pending requests can be canceled.');
    }

    $request->validate([
        'cancel_reason' => 'required|string|max:1000',
    ]);

    $requests->status = 'canceled';
    $requests->cancel_reason = $request->cancel_reason;
    $requests->save();

    return back()->with('success', 'reuest canceled with reason.');
}


public function search(Request $request)
{
    $query = $request->get('term', '');

    $results = TeamMember::where('email', 'LIKE', '%' . $query . '%')
        ->pluck('email');

    return response()->json($results);
}

public function unassign($id)
{
    $requests = AdminRequest::findOrFail($id);

    $requests->team_member_id = null;
    $requests->status = 'pending';
    $requests->save();

    return redirect()->back()->with('success', 'Request unassigned successfully.');
}
}