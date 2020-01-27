<?php

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

Route::group(['namespace' => 'Codewiser\UAC\Laravel', 'middleware' => ['web']], function() {
    Route::get('/oauth/callback', 'Controller@callback');
    Route::get('/oauth/logout', 'Controller@logout');
    Route::get('/oauth', 'Controller@info');
});

