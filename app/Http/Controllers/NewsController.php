<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\{News,Tag};

class NewsController extends Controller
{
	public function adminEdit(News $new)
    {
		Gate::authorize('admin', $new);
		return view("new.admin_new_update", ['new' => $new, 'tags' => Tag::all()]);
    }
    
	public function admin()
	{
		Gate::authorize('admin');
		$news = News::all();
        return view('admin_news', ['news' => $news]);
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::all();
        return view('main_news', ['news' => $news]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize("create", 'App\News');
		return view("new.new_create", ['tags' => Tag::all()]);
    }

	private function validateForm($create, $request, $news=null)
	{
		$validate = [
			'name' => 'required|min:5|max:100',
			'anonce' => 'required|max:255',
			'content' => 'required',
			'user_id' => ''
		];
		$validate['slug'] = 'required|alpha_dash|unique:news';
		if(!$create) {
			$validate['slug'] = $validate['slug'] . ',' . $news->id;
		}
		$v = $request->validate($validate);
		return $v;
	}

	private function createTags($new, $request)
	{
		if($request->has('tags')){
			foreach ($request->get('tags') as $tag) {
				$new->tags()->attach($tag);
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
		$this->authorize("create", 'App\News');
        //Gate::authorize('createNew');
		$v = $this->validateForm(true, $request);
		$new = News::create($v);
		$this->createTags($new, $request);
		/*\Mail::to(config('myMails.admin_email'))->send(
			new PostCreated($v["name"], url("/news/{$v["slug"]}"))
		);*/
		return back()->with('success', 'Новость успешно создана');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        return view('new.detailed_new', ['new' => $news, 'title' => $news->name]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
		$this->authorize("update", $news);
		//Gate::authorize('editNew', $news);
		return view("new.new_update", ['new' => $news, 'tags' => Tag::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
		$this->authorize("update", $news);
        //Gate::authorize('editNew', $news)
		$v = $this->validateForm(false, $request, $news);
		$news->update($v);
		$news->tags()->detach();
		$this->createTags($news, $request);
		/*\Mail::to(config('myMails.admin_email'))->send(
			new PostUpdated($v["name"], url("/news/{$v["slug"]}"))
		);*/
		return back()->with('success','Новость успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
		$this->authorize("update", $news);
        /*$deletedName = $new->name;*/
		//Gate::authorize('editPost', $news)
		$news->delete();
		/*\Mail::to(config('myMails.admin_email'))->send(
			new PostDeleted($deletedName)
		);*/
		return back()->with('success','Новость успешно удалена');
    }
}
