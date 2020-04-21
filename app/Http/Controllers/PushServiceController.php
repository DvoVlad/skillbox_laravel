<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Service\Pushall;

class PushServiceController extends Controller
{
	public function form()
	{
		return view("service");
	}
	
	public function send(Pushall $pushall)
	{
		$data = \request()->validate([
			'title' => 'required|max:80',
			'text' => 'required|max:500'
		]);
		$pushall->send($data["title"], $data["text"]);
		return back()->with("success", "Сообщение успешно отправлено!");
	}
}
