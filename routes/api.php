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

Route::post('/v1/usercreate','App\Http\Controllers\UserapiController@usercreate');
Route::post('/v1/check_account','App\Http\Controllers\UserapiController@check_account');
Route::post('/v1/FB_Google_GetToken','App\Http\Controllers\UserapiController@FB_Google_GetToken');

Route::middleware('auth:api')->group(function (){
    Route::post('/v1/test','App\Http\Controllers\TestController@index');
    Route::post('/v1/save_HeartRateBloodOxygen','App\Http\Controllers\SmartBikeController@save_HeartRateBloodOxygen');
    Route::post('/v1/save_Mapchange','App\Http\Controllers\SmartBikeController@save_Mapchange');
    Route::post('/v1/save_PersonData','App\Http\Controllers\SmartBikeController@save_PersonDataChange');
    Route::post('/v1/get_PersonData','App\Http\Controllers\SmartBikeController@get_PersonData');
    Route::middleware('get_DataCheck')->group(function (){
        Route::post('/v1/get_HeartRateBloodOxygen','App\Http\Controllers\SmartBikeController@get_HeartRateBloodOxygen');
        Route::post('/v1/get_Maphistore','App\Http\Controllers\SmartBikeController@get_Maphistore');
    });

});
