<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_histories', function (Blueprint $table) {
            $table->id();
            $table->text('before')->nullable();
            $table->text('after')->nullable();
            $table->timestamps();
			$table->bigInteger('post_id')->unsigned()->index()->nullable();
			$table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
			$table->bigInteger('user_id')->unsigned()->index()->nullable();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_histories');
    }
}
