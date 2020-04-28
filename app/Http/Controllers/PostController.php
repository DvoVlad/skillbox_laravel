<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\{Post, Tag, History};
use Illuminate\Support\Facades\Gate;
use App\Mail\{PostCreated, PostUpdated, PostDeleted};
use DB;

class PostController extends Controller
{
	public function adminEdit(Post $post)
    {
		if(! Gate::authorize('admin', $post)){
			return back()->with('errors', "У вас нет прав для входа в админ раздел.");
		}
		return view("post.admin_post_update", ['post' => $post]);
    }
    
	public function admin()
	{
		if(! Gate::authorize('admin')){
			return back()->with('errors',"У вас нет прав для входа в админ раздел.");
		}
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
		$countPosts = DB::table('posts')->count();
		$countNews = DB::table('news')->count();
		$posts = Post::where("publish", "=", 1)->latest()->get();
        return view('main', ['posts' => $posts, 'countPosts' => $countPosts, 'countNews' => $countNews]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		if(! Gate::authorize('createPost')){
			return back()->with('errors', "Вы не авторизованы поэтому не можете писать статьи!");
		}
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
		if(! Gate::authorize('createPost')){
			return back()->with('errors', "Вы не авторизованы поэтому не можете писать статьи!");
		}
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
		if(! Gate::authorize('editPost', $post)){
			return back()->with('errors',"У вас нет прав на редактирование статьи");
		}
		return view("post.post_update", ['post' => $post]);
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
		if (! Gate::authorize('editPost', $post)) {
			return back()->with('errors',"У вас нет прав на редактирование статьи");
		} 
		$v = $this->validateForm(false, $request, $post);
		$postHistoryId = $post->id;
		$postHistoryName = $post->name;
		$postHistoryFieldsChanged = '';
		if($post->name != $v["name"]) {
			$postHistoryFieldsChanged .= 'name changed ';
		}
		if($post->slug != $v["slug"]) {
			$postHistoryFieldsChanged .= 'slug changed ';
		}
		if($post->anonce != $v["anonce"]) {
			$postHistoryFieldsChanged .= 'anonce changed ';
		}
		if($post->anonce != $v["anonce"]) {
			$postHistoryFieldsChanged .= 'content changed ';
		}
		if((boolean)$post->publish != (boolean)$v["publish"]) {
			$postHistoryFieldsChanged .= 'publish changed ';
		}
		$userHistoryName = auth()->user()->name;
		$History = History::create(["post_id" => $postHistoryId, "post" => $postHistoryName, "fields" => $postHistoryFieldsChanged, "user" => $userHistoryName]);
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
		$deletedName = $post->name;
		if(! Gate::authorize('editPost', $post)){
			return back()->with('errors', "Только владелец статьи может её удалить!");
		}
		$post->delete();
		\Mail::to(config('myMails.admin_email'))->send(
			new PostDeleted($deletedName)
		);
		return back()->with('success','Статья успешно удалена');
    }
}
