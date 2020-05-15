<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\Report;

class ReportMeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $postCount;
	public $newsCount;
	public $commentCount;
	public $tagCount;
	public $userCount;

    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Mail::to(config('myMails.admin_email'))->send(
			new Report($this->postCount, $this->newsCount, $this->commentCount, $this->tagCount, $this->userCount)
		);
    }
}
