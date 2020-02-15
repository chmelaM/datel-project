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

Route::get('/rozdelit', ['as' =>'rozdelitGet', 'uses' => 'rozdelitController@rozdelitGet']);
Route::post('/rozdelit', ['as' =>'rozdelitPost', 'uses' => 'rozdelitController@rozdelitPost']);
Route::get('/SaveXls', ['as' =>'SaveXlsGet', 'uses' => 'rozdelitController@SaveXlsGet']);
Route::post('/saveRow', ['as' =>'saveRow', 'uses' => 'rozdelitController@saveRow']);
