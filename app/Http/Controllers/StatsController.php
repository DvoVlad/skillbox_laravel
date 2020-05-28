<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Post, News, User, PostHistory, Comment};
use DB;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    public function index()
    {
		$cacheTime = 60 * 60 * 24;
		$countPosts = Cache::tags("posts")->remember('count_posts', $cacheTime, function() {
			return Post::count();
		});
		//$countPosts = Post::count();
		$countNews = Cache::tags("news")->remember('count_news', $cacheTime, function() {
			return News::count();
		});
		//$countNews = News::count();
		$morePostsAutorResult = Cache::tags("posts")->remember('more_posts_autor', $cacheTime, function() {
			return User::whereHas('posts')->orderByDesc('posts_count')->withCount('posts')->first();
		});
		//$morePostsAutorResult = User::whereHas('posts')->orderByDesc('posts_count')->withCount('posts')->first();
		if (!empty($morePostsAutorResult)) {
			$morePostsAutor = $morePostsAutorResult->name;
		} else {
			$morePostsAutor = '';
		}
		$nameLongestPostResult = Cache::tags("posts")->remember('name_longest_post', $cacheTime, function() {
			return Post::select('name','slug')->where(\DB::raw('LENGTH(content)'), "=", \DB::raw('(SELECT max(LENGTH(content)) FROM posts)'))->first();
		});
		//$nameLongestPostResult = Post::select('name','slug')->where(\DB::raw('LENGTH(content)'), "=", \DB::raw('(SELECT max(LENGTH(content)) FROM posts)'))->first();
		if (!empty($nameLongestPostResult)) {
			$nameLongestPost = $nameLongestPostResult->name;
			$slugLongestPost = $nameLongestPostResult->slug;
		} else {
			$nameLongestPost = '';
			$slugLongestPost = '';
		}
		$nameShortestPostResult = Cache::tags("posts")->remember('name_shortest_post', $cacheTime, function() {
			return Post::select('name','slug')->where(\DB::raw('LENGTH(content)'), "=", \DB::raw('(SELECT min(LENGTH(content)) FROM posts)'))->first();
		});
		//$nameShortestPostResult = Post::select('name','slug')->where(\DB::raw('LENGTH(content)'), "=", \DB::raw('(SELECT min(LENGTH(content)) FROM posts)'))->first();
		if (!empty($nameLongestPostResult)) {
			$nameShortestPost = $nameShortestPostResult->name;
			$slugShortestPost = $nameShortestPostResult->slug;
		} else {
			$nameShortestPost = '';
			$slugShortestPost = '';
		}
		$avgPostsResult = Cache::tags("posts")->remember('avg_post', $cacheTime, function() {
			$sub = \App\User::has('posts', '>', '1')->withCount('posts');
			return DB::table(DB::raw("({$sub->toSql()}) as sub"))->avg('posts_count');
		});
		//$sub = \App\User::has('posts', '>', '1')->withCount('posts');
		//$avgPostsResult = DB::table(DB::raw("({$sub->toSql()}) as sub"))->avg('posts_count');
		if (!empty($avgPostsResult)) {
			$avgPosts = $avgPostsResult;
		} else {
			$avgPosts = '';
		}
		$changablePostResult = Cache::tags("posts")->remember('changeble_post', $cacheTime, function() {
			return Post::whereHas('history')->orderByDesc('history_count')->withCount('history')->first();
		});
		//$changablePostResult = Post::whereHas('history')->orderByDesc('history_count')->withCount('history')->first();
		if (!empty($changablePostResult)) {
			$changablePostName = $changablePostResult->name;
			$changablePostSlug = $changablePostResult->slug;
		} else {
			$changablePostName = '';
			$changablePostSlug = '';
		}
		$mostCommentablePostResult = Cache::tags("posts")->remember('commentabke_post', $cacheTime, function() {
			return Post::whereHas('comments')->orderByDesc('comments_count')->withCount('comments')->first();
		});
		//$mostCommentablePostResult = Post::whereHas('comments')->orderByDesc('comments_count')->withCount('comments')->first();
		//$mostCommentablePostResult = Comment::selectRaw("posts.name, posts.slug")->leftJoin('posts', 'comments.commentable_id', 'posts.id')->groupBy('comments.commentable_id', 'comments.commentable_type')->having('comments.commentable_type', '=', 'post')->orderBy(\DB::raw('COUNT(*)'), "DESC")->first();
		//dd($mostCommentablePostResult->name);
		//$mostCommentablePostResult = DB::select("SELECT posts.name, posts.slug FROM `comments` LEFT JOIN `posts` ON comments.commentable_id = posts.id GROUP by comments.commentable_id, comments.commentable_type HAVING comments.commentable_type='post' ORDER by COUNT(*) DESC LIMIT 0,1");
		if (!empty($mostCommentablePostResult)) {
			$mostCommentablePostName = $mostCommentablePostResult->name;
			$mostCommentablePostSlug = $mostCommentablePostResult->slug;
		} else {
			$mostCommentablePostName = '';
			$mostCommentablePostSlug = '';
		}
			
		return view("stats", ['countPosts' => $countPosts, 'countNews' => $countNews, 'morePostsAutor' => $morePostsAutor, 'nameLongestPost' => $nameLongestPost, 'slugLongestPost' => $slugLongestPost, 'nameShortestPost' => $nameShortestPost, 'slugShortestPost' => $slugShortestPost, 'avgPosts' => $avgPosts, 'changablePostName' => $changablePostName, 'changablePostSlug' => $changablePostSlug, 'mostCommentablePostName' => $mostCommentablePostName, 'mostCommentablePostSlug' => $mostCommentablePostSlug]);
			
	}
}
