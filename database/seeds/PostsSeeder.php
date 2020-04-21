<?php

use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Post', 50)->create()->each(function($post) {
			$post->tags()->attach(rand(1,2));
			$post->user_id = rand(2,3);
			$post->save();
		});
    }
}
