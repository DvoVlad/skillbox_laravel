<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\Report;
use App\{Post, News, Comment, Tag, User};

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
    public function __construct($postRequest, $newsRequest, $commentRequest, $tagRequest, $userRequest)
    {
		if ($postRequest == 'on') {
			$this->postCount = Post::count();
		}
		$newsCount = '';
		if ($newsRequest == 'on') {
			$this->newsCount = News::count();
		}
		$commentCount = '';
		if ($commentRequest == 'on') {
			$this->commentCount = Comment::count();
		}
		$tagCount = '';
		if ($tagRequest == 'on') {
			$this->tagCount = Tag::count();
		}
		$userCount = '';
		if ($userRequest == 'on') {
			$this->userCount = User::count();
		}
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
