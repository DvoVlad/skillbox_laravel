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
		dispatch(new ReportMeJob($request->has('posts'), $request->has('news'), $request->has('comments'), $request->has('tags'), $request->has('users')));
		/*\Mail::to(config('myMails.admin_email'))->send(
			new Report($postCount, $newsCount, $commentCount, $tagCount, $userCount)
		);*/
		return back()->with('success', 'Отчет создан успешно');
	}
}
