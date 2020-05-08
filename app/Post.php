<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

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
    
    public function history()
    {
		return $this->belongsToMany('App\User', 'post_histories')
			->withPivot("before", "after")->withTimestamps();
	}
    
    public static function boot()
    {
		parent::boot();
		static::updating(function(Post $post){
			$after = $post->getDirty();
			$post->history()->attach(auth()->user()->id, [
				'before' => json_encode(Arr::only($post->fresh()->toArray(), array_keys($after))), 
				'after' => json_encode($after)
			]);
		});
	}
}
