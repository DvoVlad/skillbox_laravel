<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Relations\Relation;
use DB;
use App\{Post, News};

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

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		Relation::morphMap([
			'post' => 'App\Post',
			'tag' => 'App\Tag',
			'news' => 'App\News'
		]);
		Blade::if('admin', function() {
			return auth()->user()->isAdmin();
		});
        view()->composer('layouts.allTags', function($view) {
			$view->with('tags', \App\Tag::has('posts')->orHas('news')->get());
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
		view()->composer('new.admin_new_update', function($view) {
			$view->with('tags', \App\Tag::all());
		});
		view()->composer('new.new_create', function($view) {
			$view->with('tags', \App\Tag::all());
		});
		view()->composer('new.new_update', function($view) {
			$view->with('tags', \App\Tag::all());
		});
    }
}
