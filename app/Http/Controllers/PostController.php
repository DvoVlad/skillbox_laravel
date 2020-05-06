<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\{Post, Tag, History};
use Illuminate\Support\Facades\Gate;
use App\Mail\{PostCreated, PostUpdated, PostDeleted};
use Illuminate\Support\Facades\Auth;
use DB;
use App\Service\DataUpdater;

class PostController extends Controller
{
	public function adminEdit(Post $post)
    {
		Gate::authorize('admin');
		return view("post.admin_post_update", ['post' => $post]);
    }
    
	public function admin()
	{
		Gate::authorize('admin');
		$posts = Post::all();
        return view('admin_articles', ['posts' => $posts]);
	}
	
	public function indexTags($id)
	{
		$posts = Tag::find($id)->posts->where("publish", "=", 1);
		$news = Tag::find($id)->news;
        return view('main', ['posts' => $posts, 'news' => $news]);
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$posts = Post::where("publish", "=", 1)->latest()->get();
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
		return view("post.post_create");
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
		if($create) {
			$validate['slug'] = 'required|alpha_dash|unique:posts';
			$v = $request->validate($validate);
		} else {
			$validate['slug'] = 'required|alpha_dash|unique:posts,id,' . $post->id;
			$v = $request->validate($validate);
		}
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
    public function show(Post $post)
    {
		if ($post->publish == 1) {
			return view('post.detailed_post', ['post' => $post, 'title' => $post->name]);
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
		return view("post.post_update", ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post, ModelDataUpdater $dataUpdater)
    {
		$this->authorize("update", $post);
		//Gate::authorize('editPost', $post);
		$v = $this->validateForm(false, $request, $post);
		$dataUpdater->update($post, $v);
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
