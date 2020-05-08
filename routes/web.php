<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AboutController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/post/comment', "CommentController@addPostComment");

Route::post('/news/comment', "CommentController@addNewsComment");

Route::get('/', "PostController@index");

Route::view('/about', "about");

Route::get('/stats', "StatsController@index");

Route::resource('posts', "PostController");

Route::resource('news', "NewsController");

Route::get('/contacts', "FeedbackController@create");

Route::post('/contacts', "FeedbackController@store");

Route::get('/admin/feedbacks', "FeedbackController@index")->middleware('auth');

Route::get('/admin/articles', "PostController@admin")->middleware('auth');;

Route::get('/admin/news', "NewsController@admin")->middleware('auth');;

Route::get('/admin/posts/{post}/edit', "PostController@adminEdit")->middleware('auth');;

Route::get('/admin/news/{new}/edit', "NewsController@adminEdit")->middleware('auth');;

Route::get('/tags/create', "TagController@create")->middleware('auth');

Route::post('/tags/create', "TagController@store")->middleware('auth');

Route::get('/tag/{id}/posts', "PostController@indexTags");

Route::get('/service', "PushServiceController@form");

Route::post('/service', "PushServiceController@send");

Auth::routes();
