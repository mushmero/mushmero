<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();
Route::middleware(['auth'])->group(static function () {
    Route::namespace('App\Http\Controllers')->group(static function () {
        Route::get('/home', 'HomeController@index')->name('home');
    });
    Route::namespace('App\Http\Controllers\User')->group(static function(){
        Route::prefix('/users')->group(static function(){
            Route::get('/', 'UsersController@index')->name('users');
            Route::post('/', 'UsersController@store')->name('users.store');
            Route::get('/create', 'UsersController@create')->name('users.create');
            Route::get('/{id}/edit', 'UsersController@edit')->name('users.edit');
            Route::put('/{id}', 'UsersController@update')->name('users.update');
            Route::get('/{id}/show', 'UsersController@show')->name('users.show');
            Route::get('/{id}/delete', 'UsersController@destroy')->name('users.delete');
        });        
    });
});
