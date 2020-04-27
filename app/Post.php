<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	public function getRouteKeyName()
	{
		return 'slug';
	}
	
    protected $fillable = ['name', 'anonce', 'content', 'publish', 'slug', 'user_id'];
    
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'tagable');
    }
	
	public function user()
	{
		return $this->belongsTo('App\User');
	}
	
	public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }
}
