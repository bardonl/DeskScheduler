<?php

use App\Http\Controllers\PythonController;
use App\Http\Controllers\ScheduleController;
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

Route::delete('/schedules/delete',[ScheduleController::class, "deleteTime"]);
Route::post('/schedules/save',[ScheduleController::class, "saveTime"]);
Route::get('/schedules/get',[ScheduleController::class, "getScheduled"]);

Route::get('/python/runTest',[PythonController::class, "runTest"]);
