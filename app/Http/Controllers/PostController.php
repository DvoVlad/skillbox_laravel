<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\{Post,Tag};
use Illuminate\Support\Facades\Gate;
use App\Mail\{PostCreated, PostUpdated, PostDeleted};

class PostController extends Controller
{
	public function indexTags($id)
	{
		$posts = Tag::find($id)->posts->where("publish", "=", 1);
		$tags = Tag::all();
        return view('main', ['posts' => $posts, 'tags' => $tags]);
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$posts = Post::where("publish", "=", 1)->latest()->get();
		$tags = Tag::all();
        return view('main', ['posts' => $posts, 'tags' => $tags]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		if(Gate::authorize('createPost')){
			$tags = Tag::all();
			return view("post.post_create", ['tags' => $tags]);
		} else {
			return back()->with('success', "Вы не авторизованы поэтому не можете писать статьи!");
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
		if(Gate::authorize('createPost')){
			$v = $request->validate([
				'name' => 'required|min:5|max:100',
				'slug' => 'required|alpha_dash|unique:posts',
				'anonce' => 'required|max:255',
				'content' => 'required',
			]);
			$v["publish"] = $request->publish;
			$v["user_id"] = $request->user_id;
			$post = Post::create($v);
			if(!empty($request->tags)){
				foreach ($request->tags as $tag) {
					$post->tags()->attach($tag);
				}
			}
			\Mail::to(config('myMails.admin_email'))->send(
				new PostCreated($v["name"], '/posts/' . $v["slug"])
			);
			return back()->with('success', 'Статья успешно создана');
		} else {
			return back()->with('success', "Вы не авторизованы поэтому не можете писать статьи!");
		}
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
		if(Gate::authorize('editPost', $post)){
			$tags = Tag::all();
			return view("post.post_update", ['post' => $post, 'tags' => $tags]);
		} else {
			return back()->with('success',"У вас нет прав на редактирование статьи");
		}
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
		if(Gate::authorize('editPost', $post)){
			$v = $request->validate([
				'name' => 'required|min:5|max:100',
				'slug' => 'required|alpha_dash|unique:posts,id,' . $post->id,
				'anonce' => 'required|max:255',
				'content' => 'required',
			]);
			$v["publish"] = $request->publish;
			$post->update($v);
			$post->tags()->detach();
			if(!empty($request->tags)){
				foreach ($request->tags as $tag) {
					$post->tags()->attach($tag);
				}
			}
			\Mail::to(config('myMails.admin_email'))->send(
				new PostUpdated($v["name"], '/posts/' . $v["slug"])
			);
			return back()->with('success','Статья успешно обновлена');
		} else {
			return back()->with('success',"У вас нет прав на редактирование статьи");
		}
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
		if(Gate::authorize('editPost', $post)){
			$post->delete();
			\Mail::to(config('myMails.admin_email'))->send(
				new PostDeleted($deletedName)
			);
			return back()->with('success','Статья успешно удалена');
		} else {
			return back()->with('success', "Только владелец статьи может её удалить!");
		}
    }
}
