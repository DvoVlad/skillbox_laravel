<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\{Post, Tag, History};
use Illuminate\Support\Facades\Gate;
use App\Mail\{PostCreated, PostUpdated, PostDeleted};
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
	public function adminEdit(Post $post)
    {
		Gate::authorize('admin');
		return view("post.admin_post_update", ['post' => $post, 'tags' => Tag::all()]);
    }
    
	public function admin()
	{
		Gate::authorize('admin');
		$posts = Post::all();
        return view('admin_articles', ['posts' => $posts]);
	}
	
	public function indexTags($id)
	{
		$cacheTime = 60 * 60 * 24;
		$posts = Cache::tags(['posts', 'tag_' . $id])->remember('post_tag_' . $id, $cacheTime, function() use($id) {
			return Tag::find($id)->posts->publish();
		});
		//$posts = Tag::find($id)->posts->where("publish", "=", 1);
		$news = Cache::tags(['news', 'tag_' . $id])->remember('news_tag_' . $id, $cacheTime, function() use($id) {
			return Tag::find($id)->news;
		});
		//$news = Tag::find($id)->news;
        return view('main', ['posts' => $posts, 'news' => $news]);
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$cacheTime = 60 * 60 * 24;
		$posts = Cache::tags(["posts"])->remember('all_posts', $cacheTime, function () {
			return Post::publish()->latest()->get();
		});
		//$posts = Post::where("publish", "=", 1)->latest()->get();
        return view('main', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$this->authorize("create", 'App\Post');
		//Gate::authorize('createPost');
		return view("post.post_create", ['tags' => Tag::all()]);
    }

	private function validateForm($create, $request, $post=null)
	{
		$validate = [
			'name' => 'required|min:5|max:100',
			'anonce' => 'required|max:255',
			'content' => 'required',
			'publish' => '',
			'user_id' => ''
		];
		$validate['slug'] = 'required|alpha_dash|unique:posts';
		if(!$create) {
			$validate['slug'] = $validate['slug'] . ',id,' . $post->id;
			//dd($validate['slug']);
		}
		$v = $request->validate($validate);
		return $v;
	}

	private function createTags($post, $request)
	{
		if($request->has('tags')){
			foreach ($request->get('tags') as $tag) {
				$post->tags()->attach($tag);
			}
		}
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$this->authorize("create", 'App\Post');
		//Gate::authorize('createPost');
		$v = $this->validateForm(true, $request);
		$post = Post::create($v);
		$this->createTags($post, $request);
		\Mail::to(config('myMails.admin_email'))->send(
			new PostCreated($v["name"], url("/posts/{$v["slug"]}"))
		);
		return back()->with('success', 'Статья успешно создана');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($post)
    {
		$cacheTime = 60 * 60 * 24;
		$post = Cache::tags(["post_" . $post])->remember('post_' . $post, $cacheTime, function() use ($post) {
			return Post::where('slug', '=', $post)->get()->first();
		});
		//dd($post);
		if ($post->publish()) {
			return view('post.detailed_post', ['post' => $post, 'title' => $post->name]);
		} else {
			return "Статья не опубликована или её нет";
		}
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
		$this->authorize("update", $post);
		//Gate::authorize('editPost', $post);
		return view("post.post_update", ['post' => $post, 'tags' => Tag::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
		$this->authorize("update", $post);
		//Gate::authorize('editPost', $post);
		$v = $this->validateForm(false, $request, $post);
		$post->update($v);
		$post->tags()->detach();
		$this->createTags($post, $request);
		\Mail::to(config('myMails.admin_email'))->send(
			new PostUpdated($v["name"], url("/posts/{$v["slug"]}"))
		);
		return back()->with('success','Статья успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
		$this->authorize("delete", $post);
		Gate::authorize('editPost', $post);
		$deletedName = $post->name;
		$post->delete();
		\Mail::to(config('myMails.admin_email'))->send(
			new PostDeleted($deletedName)
		);
		return back()->with('success','Статья успешно удалена');
    }
}
