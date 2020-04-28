<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Relations\Relation;
use DB;

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
		view()->composer('main', function($view) {
			$countPosts = DB::table('posts')->count();
			$countNews = DB::table('news')->count();
			$morePostsAutorResult = DB::select("SELECT users.name FROM `posts` LEFT JOIN `users` On posts.user_id = users.id group by user_id order by count(posts.user_id) desc limit 0, 1");
			if (!empty($morePostsAutorResult)) {
				$morePostsAutor = $morePostsAutorResult[0]->name;
			} else {
				$morePostsAutor = '';
			}
			$nameLongestPostResult = DB::select("select name, slug From posts WHERE LENGTH(content) = (SELECT max(LENGTH(content)) FROM posts)");
			if (!empty($nameLongestPostResult)) {
				$nameLongestPost = $nameLongestPostResult[0]->name;
				$slugLongestPost = $nameLongestPostResult[0]->slug;
			} else {
				$nameLongestPost = '';
				$slugLongestPost = '';
			}
			$nameShortestPostResult = DB::select("select name, slug From posts WHERE LENGTH(content) = (SELECT min(LENGTH(content)) FROM posts)");
			if (!empty($nameLongestPostResult)) {
				$nameShortestPost = $nameShortestPostResult[0]->name;
				$slugShortestPost = $nameShortestPostResult[0]->slug;
			} else {
				$nameShortestPost = '';
				$slugShortestPost = '';
			}
			$avgPostsResult = DB::select("SELECT avg(count) as result FROM (SELECT count(*) as count FROM `posts` GROUP BY user_id HAVING count(*) > 1) as counts");
			if (!empty($avgPostsResult)) {
				$avgPosts = $avgPostsResult[0]->result;
			} else {
				$avgPosts = '';
			}
			$changablePostResult = DB::select("SELECT DISTINCT histories.post, posts.slug FROM `histories` Left Join `posts` ON histories.post = posts.name WHERE post_id = (SELECT post_id FROM `histories` GROUP BY post_id ORDER BY count(*) DESC LIMIT 0, 1)");
			if (!empty($changablePostResult)) {
				$changablePostName = $changablePostResult[0]->post;
				$changablePostSlug = $changablePostResult[0]->slug;
			} else {
				$changablePostName = '';
				$changablePostSlug = '';
			}
			$view->with('countPosts', $countPosts);
			$view->with('countNews', $countNews);
			$view->with('morePostsAutor', $morePostsAutor);
			$view->with('nameLongestPost', $nameLongestPost);
			$view->with('slugLongestPost', $slugLongestPost);
			$view->with('nameShortestPost', $nameShortestPost);
			$view->with('slugShortestPost', $slugShortestPost);
			$view->with('avgPosts', $avgPosts);
			$view->with('changablePostName', $changablePostName);
			$view->with('changablePostSlug', $changablePostSlug);
			//$view->with();
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
