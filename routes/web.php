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

Route::resource('posts', 'PostController');

Route::get('/contacts', "FeedbackController@create");

Route::post('/contacts', "FeedbackController@store");

Route::get('/admin/feedbacks', "FeedbackController@index")->middleware('auth');;

Route::get('/tags/create', "TagController@create")->middleware('auth');;

Route::post('/tags/create', "TagController@store")->middleware('auth');;

Route::get('/tag/{id}/posts', "PostController@indexTags");

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
