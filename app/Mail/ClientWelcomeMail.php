<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $password;
    public $loginUrl;

    public function __construct($client, string $password)
    {
        $this->client = $client;
        $this->password = $password;
        $this->loginUrl = config('app.url');
    }

    public function build()
    {
        return $this->subject('Welcome to Evalon')
            ->view('emails.client_welcome')
            ->with([
                'client' => $this->client,
                'password' => $this->password,
                'loginUrl' => $this->loginUrl,
            ]);
    }
}