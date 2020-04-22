<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
	
	private function userGroups()
    {
		return auth()->user()->groups->pluck("name")->all();
	} 
	
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		Blade::if('admin', function() {
			$arrGroups = $this->userGroups();
			return in_array('admin', $arrGroups);
		});
        view()->composer('layouts.allTags', function($view) {
			$view->with('tags', \App\Tag::has('posts')->get());
		});
		view()->composer('post.admin_post_update', function($view) {
			$view->with('tags', \App\Tag::all());
		});
		view()->composer('post.post_create', function($view) {
			$view->with('tags', \App\Tag::all());
		});
		view()->composer('post.post_update', function($view) {
			$view->with('tags', \App\Tag::all());
		});
    }
}
