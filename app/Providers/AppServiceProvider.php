<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        view()->composer('main', function($view) {
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
