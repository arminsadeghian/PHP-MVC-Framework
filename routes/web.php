<?php

use App\Core\Routing\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// define route per method
Route::get('/', 'HomeController@index');
Route::get('/post/{slug}', 'PostController@single');
Route::get('/post/{slug}/comment/{cid}', 'PostController@comment');


