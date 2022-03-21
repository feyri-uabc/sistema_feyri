<?php

use App\Http\Controllers\CalendarInstructorsController;
use App\Http\Controllers\CalendarLabsController;
use App\Http\Controllers\CalendarReservationsController;
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

Route::resource('calendar_instructors', CalendarInstructorsController::class);
Route::resource('calendar_labs', CalendarLabsController::class);
Route::resource('calendar_reservations', CalendarReservationsController::class);
