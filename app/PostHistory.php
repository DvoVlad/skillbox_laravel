<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostHistory extends Model
{
    protected $fillable = ["post_id", "changes", "user_id"];
    
    public function user()
    {
		return $this->belongsTo("App\User");
	}
	
	public function post()
    {
		return $this->belongsTo("App\Post");
	}
}
