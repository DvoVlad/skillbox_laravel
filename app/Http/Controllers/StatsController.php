<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Post, News, User, PostHistory, Comment};
use DB;

class StatsController extends Controller
{
    public function index()
    {
		$countPosts = Post::count();
		$countNews = News::count();
		$morePostsAutorResult = User::whereHas('posts')->orderByDesc('posts_count')->withCount('posts')->first();
		//$morePostsAutorResult = DB::select("SELECT users.name FROM `posts` LEFT JOIN `users` On posts.user_id = users.id group by user_id order by count(posts.user_id) desc limit 0, 1");
		if (!empty($morePostsAutorResult)) {
			$morePostsAutor = $morePostsAutorResult->name;
		} else {
			$morePostsAutor = '';
		}
		$nameLongestPostResult = Post::select('name','slug')->where(\DB::raw('LENGTH(content)'), "=", \DB::raw('(SELECT max(LENGTH(content)) FROM posts)'))->first();
		//$nameLongestPostResult = DB::select("select name, slug From posts WHERE LENGTH(content) = (SELECT max(LENGTH(content)) FROM posts)");
		if (!empty($nameLongestPostResult)) {
			$nameLongestPost = $nameLongestPostResult->name;
			$slugLongestPost = $nameLongestPostResult->slug;
		} else {
			$nameLongestPost = '';
			$slugLongestPost = '';
			}
		$nameShortestPostResult = Post::select('name','slug')->where(\DB::raw('LENGTH(content)'), "=", \DB::raw('(SELECT min(LENGTH(content)) FROM posts)'))->first();
		//$nameShortestPostResult = DB::select("select name, slug From posts WHERE LENGTH(content) = (SELECT min(LENGTH(content)) FROM posts)");
		if (!empty($nameLongestPostResult)) {
			$nameShortestPost = $nameShortestPostResult->name;
			$slugShortestPost = $nameShortestPostResult->slug;
		} else {
			$nameShortestPost = '';
			$slugShortestPost = '';
		}
		$sub = \App\User::has('posts', '>', '1')->withCount('posts');
		$avgPostsResult = DB::table(DB::raw("({$sub->toSql()}) as sub"))->avg('posts_count');
		//$avgPostsResult = DB::select("SELECT avg(count) as result FROM (SELECT count(*) as count FROM `posts` GROUP BY user_id HAVING count(*) > 1) as counts");
		if (!empty($avgPostsResult)) {
			$avgPosts = $avgPostsResult;
		} else {
			$avgPosts = '';
		}
		$changablePostResult = PostHistory::selectRaw('DISTINCT COUNT(*), posts.name as name, posts.slug as slug')->leftJoin('posts', 'post_histories.post_id', '=', 'posts.id')->groupBy('post_id')->orderBy(\DB::raw('COUNT(*)'), "DESC")->first();
		//dd($changablePostResult->name);
		//$changablePostResult = DB::select("SELECT DISTINCT posts.name as name, posts.slug as slug FROM `post_histories` Left Join `posts` ON post_histories.post_id = posts.id WHERE post_id = (SELECT post_id FROM `post_histories` GROUP BY post_id ORDER BY count(*) DESC LIMIT 0, 1);");
		if (!empty($changablePostResult)) {
			$changablePostName = $changablePostResult->name;
			$changablePostSlug = $changablePostResult->slug;
		} else {
			$changablePostName = '';
			$changablePostSlug = '';
		}
		$mostCommentablePostResult = Comment::selectRaw("posts.name, posts.slug")->leftJoin('posts', 'comments.commentable_id', 'posts.id')->groupBy('comments.commentable_id', 'comments.commentable_type')->having('comments.commentable_type', '=', 'post')->orderBy(\DB::raw('COUNT(*)'), "DESC")->first();
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
