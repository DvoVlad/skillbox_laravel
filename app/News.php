<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
    public static function boot()
    {
		parent::boot();
		static::creating(function (News $news) {
			Cache::tags("news")->flush();
        }); 
		static::deleting(function(News $news) {
			Cache::tags("news_" . $news->slug)->flush();
			Cache::tags("news")->flush();
		});
		static::updating(function(News $news){
			Cache::tags("news_" . $news->slug)->flush();
			Cache::tags("news")->flush();
		});
	}
}
