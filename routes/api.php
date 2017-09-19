<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/tasks', 'TaskController');
Route::post('/tasks/search', 'TaskController@index');


Route::get('/', function () {
    $redis = app()->make('redis.connection');
    $redis->set('popo', 'cachai');
    return $redis->get('popo');
});