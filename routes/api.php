<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\RegisterController;
use \App\Http\Controllers\API\LoginController;
use \App\Http\Controllers\API\TodoController;


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

Route::post('register',[RegisterController::class,'register']);
Route::post('login',[LoginController::class,'login']);

Route::post('create_new_working_day',[TodoController::class,'create_new_working_day']);
Route::post('create_new_job_in_date',[TodoController::class,'create_new_job_in_date']);


Route::post('get_all_work_day',[TodoController::class,'get_all_work_day']);
Route::post('get_all_job_in_day',[TodoController::class,'get_all_job_in_day']);


Route::post('update_NOT_COMPLETED_job_in_day',[TodoController::class,'update_NOT_COMPLETED_job_in_day']);
Route::post('update_done_job_in_day',[TodoController::class,'update_done_job_in_day']);
Route::post('delete_job_in_day',[TodoController::class,'delete_job_in_day']);

Route::post('logout',[LoginController::class,'logout']);

