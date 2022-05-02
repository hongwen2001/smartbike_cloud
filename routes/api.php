<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/v1/usertoken','App\Http\Controllers\UserapiController@index');
Route::post('/v1/testcreate','App\Http\Controllers\UserapiController@create');

Route::middleware('auth:api')->group(function (){
    Route::post('/v1/test','App\Http\Controllers\UserapiController@index');
    Route::post('/v1/testin','App\Http\Controllers\Controller@test');

});
