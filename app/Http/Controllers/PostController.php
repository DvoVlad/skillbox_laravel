<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\{Post,Tag};

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
		$tags = Tag::all();
        return view("post_create", ['tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$v = $request->validate([
			'name' => 'required|min:5|max:100',
			'slug' => 'required|alpha_dash|unique:posts',
			'anonce' => 'required|max:255',
			'content' => 'required',
		]);
		$v["publish"] = $request->publish;
		$post = Post::create($v);
		if(!empty($request->tags)){
			foreach ($request->tags as $tag) {
				$post->tags()->attach($tag);
			}
		}
        return back()->with('success','Статья успешно создана');
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
			return view('detailed_post', ['post' => $post, 'title' => $post->name]);
		} else {
			return $post->slug;
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
		$tags = Tag::all();
		return view("post_update", ['post' => $post, 'tags' => $tags]);
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
        $post->delete();
        return back()->with('success','Статья успешно удалена');
    }
}
