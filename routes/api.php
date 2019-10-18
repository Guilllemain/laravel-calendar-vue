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

Route::middleware('auth:api')->group(function () {
    Route::post('/reservation', 'ReservationsController@store'); 
    Route::get('/reservations', 'ReservationsController@index');
    Route::get('/reservation/{reservation}', 'ReservationsController@show');
    Route::delete('/reservation/{reservation}', 'ReservationsController@destroy');
});