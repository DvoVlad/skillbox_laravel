<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\User;
use App\Post;
use App\News;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
     
    public function boot()
    {
        $this->registerPolicies();
		Gate::define('admin', function($user) {
			return auth()->user()->isAdmin();
		});
		Gate::define('createPost', function($user) {
			return $user->id > 0 || auth()->user()->isAdmin();
		});
        Gate::define('editPost', function ($user, Post $post) {
			return $user->id == $post->user_id || auth()->user()->isAdmin();
		});
		Gate::define('createNew', function($user) {
			return $user->id > 0 || auth()->user()->isAdmin();
		});
        Gate::define('editNew', function ($user, News $post) {
			return $user->id == $post->user_id || auth()->user()->isAdmin();
		});
    }
}
