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
	
	private function addData($data)
	{
		$v = $this->validateData($request);
		
		$data->comments()->save(new Comment($v));
		return back()->with('success','Ваше сообщение отправлено');
	}
	
    public function addPostComment(Request $request)
    {
		$post = Post::find($v['commentable_id']);
		$this->addData($post);
	}
	
	public function addNewsComment(Request $request)
    {
		$news = News::find($v['commentable_id']);
		$this->addData($news);
	}
}
