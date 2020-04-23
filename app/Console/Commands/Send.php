<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserAboutArticle;
use App\{Post, User};

class Send extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:send-created-articles {date1} {date2}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$posts = Post::whereBetween("created_at", [(string)$this->argument('date1'), (string)$this->argument('date2')])->get();
		$users = User::all(); 
		/*foreach($posts as $post){
			$this->info("Building! " . $post->name);
		}*/
		Notification::send($users, new UserAboutArticle($posts));
    }
}
