<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\User;
use App\Post;

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
     
    private function userGroups()
    {
		return auth()->user()->groups->pluck("name")->all();
	} 
     
    public function boot()
    {
        $this->registerPolicies();
		Gate::define('admin', function($user) {
			$arrGroups = $this->userGroups();
			return in_array('admin', $arrGroups);
		});
		Gate::define('createPost', function($user) {
			$arrGroups = $this->userGroups();
			return $user->id > 0 || in_array('admin', $arrGroups);
		});
        Gate::define('editPost', function ($user, Post $post) {
			$arrGroups = $this->userGroups();
			return $user->id == $post->user_id || in_array('admin', $arrGroups);
		});
    }
}
