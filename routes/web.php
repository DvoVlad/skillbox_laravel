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

Route::get('/', "PostController@index");

Route::view('/about', "about");

Route::get('/contacts', "FeedbackController@create");

Route::post('/contacts', "FeedbackController@store");

Route::get('/posts/create', "PostController@create");

Route::post('/posts/create', "PostController@store");

Route::get('/posts/{post}', "PostController@show");

Route::get('/posts/{post}/update', "PostController@edit");

Route::patch('/posts/{post}/update', "PostController@update");

Route::delete('/posts/{post}/delete', "PostController@destroy");

Route::get('/admin/feedbacks', "FeedbackController@index");

Route::get('/tags/create', "TagController@create");

Route::post('/tags/create', "TagController@store");

Route::get('/tag/{id}/posts', "PostController@indexTags");

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
