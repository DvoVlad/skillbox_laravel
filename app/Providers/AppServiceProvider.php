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
		view()->composer('stats', function($view) {
			$countPosts = Post::count();
			$countNews = News::count();
			$morePostsAutorResult = DB::table('posts')->leftJoin('users', 'posts.user_id', '=', 'users.id')->select('users.name')->groupBy('user_id')->orderBy(\DB::raw('count(posts.user_id)'), 'desc')->first();
			//$morePostsAutorResult = DB::select("SELECT users.name FROM `posts` LEFT JOIN `users` On posts.user_id = users.id group by user_id order by count(posts.user_id) desc limit 0, 1");
			if (!empty($morePostsAutorResult)) {
				$morePostsAutor = $morePostsAutorResult->name;
			} else {
				$morePostsAutor = '';
			}
			$nameLongestPostResult = DB::table('posts')->select('name','slug')->where(\DB::raw('LENGTH(content)'), "=", \DB::raw('(SELECT max(LENGTH(content)) FROM posts)'))->first();
			//$nameLongestPostResult = DB::select("select name, slug From posts WHERE LENGTH(content) = (SELECT max(LENGTH(content)) FROM posts)");
			if (!empty($nameLongestPostResult)) {
				$nameLongestPost = $nameLongestPostResult->name;
				$slugLongestPost = $nameLongestPostResult->slug;
			} else {
				$nameLongestPost = '';
				$slugLongestPost = '';
			}
			$nameShortestPostResult = DB::table('posts')->select('name','slug')->where(\DB::raw('LENGTH(content)'), "=", \DB::raw('(SELECT min(LENGTH(content)) FROM posts)'))->first();
			//$nameShortestPostResult = DB::select("select name, slug From posts WHERE LENGTH(content) = (SELECT min(LENGTH(content)) FROM posts)");
			if (!empty($nameLongestPostResult)) {
				$nameShortestPost = $nameShortestPostResult->name;
				$slugShortestPost = $nameShortestPostResult->slug;
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
			$changablePostResult = DB::select("SELECT DISTINCT posts.name as name, posts.slug as slug FROM `post_histories` Left Join `posts` ON post_histories.post_id = posts.id WHERE post_id = (SELECT post_id FROM `post_histories` GROUP BY post_id ORDER BY count(*) DESC LIMIT 0, 1);");
			if (!empty($changablePostResult)) {
				$changablePostName = $changablePostResult[0]->name;
				$changablePostSlug = $changablePostResult[0]->slug;
			} else {
				$changablePostName = '';
				$changablePostSlug = '';
			}
			$mostCommentablePostResult = DB::select("SELECT posts.name, posts.slug FROM `comments` LEFT JOIN `posts` ON comments.commentable_id = posts.id GROUP by comments.commentable_id, comments.commentable_type HAVING comments.commentable_type='post' ORDER by COUNT(*) DESC LIMIT 0,1");
			if (!empty($mostCommentablePostResult)) {
				$mostCommentablePostName = $mostCommentablePostResult[0]->name;
				$mostCommentablePostSlug = $mostCommentablePostResult[0]->slug;
			} else {
				$mostCommentablePostName = '';
				$mostCommentablePostSlug = '';
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
			$view->with('mostCommentablePostName', $mostCommentablePostName);
			$view->with('mostCommentablePostSlug', $mostCommentablePostSlug);
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
