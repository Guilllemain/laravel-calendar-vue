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


Auth::routes(['verify' => true]);


Route::get('/', 'HomeController@index')->name('home')->middleware('verified');

Route::prefix('/admin')->namespace('Admin')->middleware('admin')->group(function () {
    Route::get('/', 'HomeController@index');

    Route::get('/users', 'UsersController@index');
    Route::get('/user/{user}', 'UsersController@edit');
    Route::patch('/user/{user}', 'UsersController@update');
    Route::delete('/user/{user}', 'UsersController@destroy');

    Route::get('/reservations', 'ReservationsController@index');
    Route::get('/reservation/{reservation}', 'ReservationsController@edit');
    // Route::patch('/reservation/{reservation}', 'ReservationsController@update');
    Route::delete('/reservation/{reservation}', 'ReservationsController@destroy');
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return "Cache is cleared";
});
