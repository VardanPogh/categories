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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('', 'CategoryController@index');
Route::put('', 'CategoryController@update');
Route::put('/lang', 'CategoryController@lang');
Route::post('', 'CategoryController@create');
