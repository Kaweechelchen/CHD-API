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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', 'PetitionController@index');
Route::get('/page/{page}', 'PetitionController@index')->where('page', '[0-9]+');
Route::get('{petition}', 'PetitionController@show')->where('petition', '[0-9]+');
