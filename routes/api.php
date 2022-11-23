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
Route::post('/v1/fast_GetToken','App\Http\Controllers\UserapiController@fast_GetToken');
Route::post('/v1/usercreate','App\Http\Controllers\UserapiController@fastcreate');
Route::middleware('auth:api')->group(function (){

    Route::post('/v1/test','App\Http\Controllers\TestController@index');

});
