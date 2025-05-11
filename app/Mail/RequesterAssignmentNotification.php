<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\Request; 
use App\Models\TeamMember;

class RequesterAssignmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $teamMember;
    
    public function __construct(Request $request, TeamMember $teamMember)
    {
        $this->request = $request;
        $this->teamMember = $teamMember;
    }
    
    public function build()
    {
        return $this->subject('Your maid request has been assigned')
                    ->view('emails.requester_assignment')
                    ->with([
                        'request' => $this->request,
                        'teamMember' => $this->teamMember,
                    ]);
    }
}
