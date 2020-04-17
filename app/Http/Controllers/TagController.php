<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class TagController extends Controller
{
	 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return view("tag_create");
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
			'name' => 'required|min:5|max:100|unique:tags',
		]);
		$post = Tag::create($v);
        return back()->with('success','Тег успешно создан');
    }
}
