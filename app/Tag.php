<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Tag extends Model
{
    protected $fillable = ["name"];
    
    public function posts(){
		return $this->morphedByMany('App\Post', 'tagable');
	}
	public function news(){
		return $this->morphedByMany('App\News', 'tagable');
	}
	public static function boot()
    {
		parent::boot();
		static::creating(function (News $news) {
			Cache::tags(["tags"])->flush();
        }); 
		static::deleting(function(Tag $tag) {
			Cache::tags(["tag_" . $tag->id])->flush();
			Cache::tags(["tags"])->flush();
		});
	}
}
