<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Report extends Mailable
{
    use Queueable, SerializesModels;

	 public $postCount;
     public $newsCount;
     public $commentCount;
     public $tagCount;
     public $userCount;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($postCount, $newsCount, $commentCount, $tagCount, $userCount)
    {
        $this->postCount = $postCount;
        $this->newsCount = $newsCount;
        $this->commentCount = $commentCount;
        $this->tagCount = $tagCount;
        $this->userCount = $userCount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.reports');
    }
}
