<?php

use Illuminate\Support\Facades\Route;

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
Route::view('/login', 'login');
Route::post('check', 'User@check');

Route::middleware('checkLogin')->group(function () {
    Route::get('/', 'Index@getIndexInfo');
    Route::prefix('/website')->group(function () {
        Route::get('/getinfo/{url}', 'Website@get_siteurl_curlinfo');
        Route::post('/store', 'Website@store');
        Route::get('/list/{genre_id}', 'Website@getWebsiteByGenre');
        Route::get('/click/{id}', 'Website@clickInc');
        Route::get('/delete/{id}', 'Website@del');
    });
    Route::prefix('/blog')->group(function () {
        Route::post('/store', 'Blog@store');
        Route::post('/update', 'Blog@update');
        Route::get('/list/{id}/{page}', 'Blog@getBlogByGenre');
        Route::get('/dtl/{id}', 'Blog@getBlogById');
        Route::get('/delete/{id}', 'Blog@del');
        Route::get('/search/{title}', 'Blog@getBlogByTitle');
    });
    Route::prefix('/exp')->group(function () {
        Route::post('/store', 'Experience@store');
        Route::post('/update', 'Experience@update');
        Route::get('/list/{id}/{page}', 'Experience@getExpByLabel');
        Route::get('/dtl/{id}', "Experience@getExpById");
        Route::get('/delete/{id}', 'Experience@del');

    });
});
