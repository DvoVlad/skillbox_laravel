<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Post,Comment,News};

class CommentController extends Controller
{
	public function validateData($request)
	{
		return $request->validate([
			'message' => 'required',
			'user_id' => '',
			'commentable_id' => ''
		]);
	}
	
	private function addData($model, $v)
	{	
		$model->comments()->save(new Comment($v));
	}
	
    public function addPostComment(Request $request)
    {
		$v = $this->validateData($request);
		$post = Post::find($v['commentable_id']);
		$this->addData($post, $v);
		return back()->with('success','Ваше сообщение отправлено');
	}
	
	public function addNewsComment(Request $request)
    {
		$v = $this->validateData($request);
		$news = News::find($v['commentable_id']);
		$this->addData($news, $v);
		return back()->with('success','Ваше сообщение отправлено');
	}
}
