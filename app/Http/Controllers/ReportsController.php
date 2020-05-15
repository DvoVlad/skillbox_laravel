<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\{Post, News, Comment, Tag, User};
use App\Mail\Report;
use App\Jobs\ReportMeJob;

class ReportsController extends Controller
{
    public function create()
	{
		Gate::authorize('admin');
		return view("reports");
	}
	public function makeReport(Request $request)
	{
		Gate::authorize('admin');
		//dd($request);
		$postCount = '';
		if ($request->posts == 'on') {
			$postCount = Post::count();
		}
		$newsCount = '';
		if ($request->news == 'on') {
			$newsCount = News::count();
		}
		$commentCount = '';
		if ($request->comments == 'on') {
			$commentCount = Comment::count();
		}
		$tagCount = '';
		if ($request->tags == 'on') {
			$tagCount = Tag::count();
		}
		$userCount = '';
		if ($request->users == 'on') {
			$userCount = User::count();
		}
		dispatch(new ReportMeJob($postCount, $newsCount, $commentCount, $tagCount, $userCount));
		/*\Mail::to(config('myMails.admin_email'))->send(
			new Report($postCount, $newsCount, $commentCount, $tagCount, $userCount)
		);*/
		return back()->with('success', 'Отчет создан успешно');
	}
}
