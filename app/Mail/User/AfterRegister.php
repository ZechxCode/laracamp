<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AfterRegister extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    public $message;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Registration on Laracamp')->markdown('emails.user.afterRegister', [
            'user' => $this->user
        ]);
    }
}
