<?php

use App\Http\Controllers\AuthorizeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

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
    return view('auth/login');
});
Route::get('/study_test',function (Request $request){
    return $request->all();
});
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
Route::middleware(['auth:sanctum','verified'])->get('/again_authorize',[AuthorizeController::class,'again_authorize']);
Route::middleware(['auth:sanctum','verified'])->get('/authorize2',[AuthorizeController::class,'redirect']);
Route::middleware(['auth:sanctum','verified'])->get('/authorize2/callback',[AuthorizeController::class,'callback']);
Route::middleware('auth:api')->group(function (){
   Route::get('/Oauth20/login{id}',function ($id){
       return View('auth.Oauth20login')->with(['client_id'=>$id]);
   });
});
