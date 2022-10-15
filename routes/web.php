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
Route::middleware(['auth','permission'])->group(static function () {
    Route::namespace('App\Http\Controllers')->group(static function () {
        Route::get('/home', 'HomeController@index')->name('home');
    });
    Route::namespace('App\Http\Controllers\User')->group(static function(){
        Route::prefix('/settings_users')->group(static function(){
            Route::get('/', 'UsersController@index')->name('users');
            Route::post('/', 'UsersController@store')->name('users.store');
            Route::get('/create', 'UsersController@create')->name('users.create');
            Route::get('/{id}/edit', 'UsersController@edit')->name('users.edit');
            Route::put('/{id}', 'UsersController@update')->name('users.update');
            Route::get('/{id}/show', 'UsersController@show')->name('users.show');
            Route::get('/{id}/delete', 'UsersController@destroy')->name('users.delete');
        });        
        Route::prefix('/settings_roles')->group(static function(){
            Route::get('/', 'RolesController@index')->name('roles');
            Route::post('/', 'RolesController@store')->name('roles.store');
            Route::get('/create', 'RolesController@create')->name('roles.create');
            Route::get('/{id}/edit', 'RolesController@edit')->name('roles.edit');
            Route::put('/{id}', 'RolesController@update')->name('roles.update');
            Route::get('/{id}/show', 'RolesController@show')->name('roles.show');
            Route::get('/{id}/delete', 'RolesController@destroy')->name('roles.delete');
        });   
        Route::prefix('/settings_permissions')->group(static function(){
            Route::get('/', 'PermissionsController@index')->name('permissions');
            Route::post('/', 'PermissionsController@store')->name('permissions.store');
            Route::get('/create', 'PermissionsController@create')->name('permissions.create');
            Route::get('/{id}/edit', 'PermissionsController@edit')->name('permissions.edit');
            Route::put('/{id}', 'PermissionsController@update')->name('permissions.update');
            Route::get('/{id}/show', 'PermissionsController@show')->name('permissions.show');
            Route::get('/{id}/delete', 'PermissionsController@destroy')->name('permissions.delete');
        });   
    });
    Route::namespace('App\Http\Controllers')->group(static function(){     
    });
});
