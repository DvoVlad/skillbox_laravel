<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
	protected $table = 'news';
	
	protected $fillable = ['name', 'anonce', 'content', 'slug', 'user_id'];
	
	public function getRouteKeyName()
	{
		return 'slug';
	}
	
	public function user(){
	
		return $this->belongsTo("App\User");
	}
	
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'tagable');
    }
    
    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }
}
