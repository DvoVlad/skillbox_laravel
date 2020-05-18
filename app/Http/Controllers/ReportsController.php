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
		dispatch(new ReportMeJob($request->posts, $request->news, $request->comments, $request->tags, $request->users));
		/*\Mail::to(config('myMails.admin_email'))->send(
			new Report($postCount, $newsCount, $commentCount, $tagCount, $userCount)
		);*/
		return back()->with('success', 'Отчет создан успешно');
	}
}
