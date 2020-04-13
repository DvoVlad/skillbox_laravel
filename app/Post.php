<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	public function getRouteKeyName()
	{
		return 'slug';
	}
	
    protected $fillable = ['name', 'anonce', 'content', 'publish', 'slug'];
    
    public function tags() {
		return $this->belongsToMany('App\Tag');
	}
}