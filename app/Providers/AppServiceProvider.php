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

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		Blade::if('admin', function() {
			$arrGroups = [];
			foreach(auth()->user()->groups as $group) {
				$arrGroups[] = $group['name'];
			}
			return in_array('admin', $arrGroups);
		});
        view()->composer('admin_articles', function($view) {
			$view->with('tags', \App\Tag::all());
		});
		view()->composer('post.admin_post_update', function($view) {
			$view->with('tags', \App\Tag::all());
		});
		view()->composer('main', function($view) {
			$view->with('tags', \App\Tag::all());
		});		view()->composer('post.post_create', function($view) {
			$view->with('tags', \App\Tag::all());
		});
		view()->composer('post.post_update', function($view) {
			$view->with('tags', \App\Tag::all());
		});
    }
}
