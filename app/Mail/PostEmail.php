<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $author, $text, $time;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($author, $text, $time)
    {
        $this->author = $author;
        $this->text = $text;
        $this->time = $time;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.new_view');
    }
}
