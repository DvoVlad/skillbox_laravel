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

	public $postRequest;
	public $newsRequest;
	public $commentRequest;
	public $tagRequest
	public $userRequest;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($postRequest, $newsRequest, $commentRequest, $tagRequest, $userRequest)
    {
		$this->postRequest = $postRequest;
		$this->newsRequest = $newsRequest;
		$this->commentRequest = $commentRequest;
		$this->tagRequest = $tagRequest;
		$this->userRequest = $userRequest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		if ($this->postRequest == true) {
			$this->postCount = Post::count();
		}
		$newsCount = '';
		if ($this->newsRequest == true) {
			$this->newsCount = News::count();
		}
		$commentCount = '';
		if ($this->commentRequest == true) {
			$this->commentCount = Comment::count();
		}
		$tagCount = '';
		if ($this->tagRequest == true) {
			$this->tagCount = Tag::count();
		}
		$userCount = '';
		if ($this->userRequest == true) {
			$this->userCount = User::count();
		}
        \Mail::to(config('myMails.admin_email'))->send(
			new Report($this->postCount, $this->newsCount, $this->commentCount, $this->tagCount, $this->userCount)
		);
    }
}
