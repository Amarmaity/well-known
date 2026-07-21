<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SuperAddUser;

class AnniversaryEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $completedYears;

    public function __construct(SuperAddUser $user, int $completedYears)
    {
        $this->user = $user;
        $this->completedYears = $completedYears;
    }

    public function build()
    {
        return $this->subject("Congratulations on Your {$this->completedYears}-Year Work Anniversary!")
            ->view('emails.anniversary');
    }
}
