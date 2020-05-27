<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Relations\Relation;
use DB;
use App\{Post, News};
use Illuminate\Support\Facades\Cache;

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
			$cacheTime = 60 * 60 * 24;
			$tags = Cache::tags(['tags', 'news', 'posts'])->remember("all_tags", $cacheTime, function() {
				return \App\Tag::has('posts')->orHas('news')->get();
			});
			$view->with('tags', $tags);
		});
    }
}
