<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
	public function scopePublish($query)
    {
        return $query->where('publish','=', 1);
    }
	
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
		static::creating(function (Post $post) {
			Cache::tags("posts")->flush();
        }); 
		static::deleting(function(Post $post) {
			Cache::tags("post_" . $post->slug)->flush();
			Cache::tags("posts")->flush();
		});
		static::updating(function(Post $post){
			Cache::tags(["posts"])->flush();
			Cache::tags(["post_" . $post->slug])->flush();
			$after = $post->getDirty();
			$post->history()->attach(auth()->user()->id, [
				'before' => json_encode(Arr::only($post->fresh()->toArray(), array_keys($after))), 
				'after' => json_encode($after)
			]);
			$messageBefore = json_encode(Arr::only($post->fresh()->toArray(), array_keys($after)));
			$messageAfter = json_encode($after);
			$message = "<a href='/posts/{$post->slug}'>{$post->name}</a> Было: {$messageBefore} Стало: {$messageAfter}";
			event(new \App\Events\AdminNotify($message));
		});
	}
}
