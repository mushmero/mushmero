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
        Route::prefix('/user_access')->group(static function(){
            Route::get('/', 'ActivityLogController@index')->name('user_access');
            Route::get('/{id}/show', 'ActivityLogController@show')->name('user_access.show');
        });
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
    Route::namespace('App\Http\Controllers\Settings')->group(static function(){     
        Route::prefix('/settings_social')->group(static function(){
            Route::get('/', 'SocialsController@index')->name('settings_social');
            Route::post('/', 'SocialsController@store')->name('settings_social.store');
            Route::get('/create', 'SocialsController@create')->name('settings_social.create');
            Route::get('/{id}/edit', 'SocialsController@edit')->name('settings_social.edit');
            Route::put('/{id}', 'SocialsController@update')->name('settings_social.update');
            Route::get('/{id}/show', 'SocialsController@show')->name('settings_social.show');
            Route::get('/{id}/delete', 'SocialsController@destroy')->name('settings_social.delete');
        }); 
        Route::prefix('/settings_education')->group(static function(){
            Route::get('/', 'EducationsController@index')->name('settings_education');
            Route::post('/', 'EducationsController@store')->name('settings_education.store');
            Route::get('/create', 'EducationsController@create')->name('settings_education.create');
            Route::get('/{id}/edit', 'EducationsController@edit')->name('settings_education.edit');
            Route::put('/{id}', 'EducationsController@update')->name('settings_education.update');
            Route::get('/{id}/show', 'EducationsController@show')->name('settings_education.show');
            Route::get('/{id}/delete', 'EducationsController@destroy')->name('settings_education.delete');
        }); 
        Route::prefix('/settings_language')->group(static function(){
            Route::get('/', 'LanguagesController@index')->name('settings_language');
            Route::post('/', 'LanguagesController@store')->name('settings_language.store');
            Route::get('/create', 'LanguagesController@create')->name('settings_language.create');
            Route::get('/{id}/edit', 'LanguagesController@edit')->name('settings_language.edit');
            Route::put('/{id}', 'LanguagesController@update')->name('settings_language.update');
            Route::get('/{id}/show', 'LanguagesController@show')->name('settings_language.show');
            Route::get('/{id}/delete', 'LanguagesController@destroy')->name('settings_language.delete');
        }); 
        Route::prefix('/settings_project')->group(static function(){
            Route::get('/', 'ProjectsController@index')->name('settings_project');
            Route::post('/', 'ProjectsController@store')->name('settings_project.store');
            Route::get('/create', 'ProjectsController@create')->name('settings_project.create');
            Route::get('/{id}/edit', 'ProjectsController@edit')->name('settings_project.edit');
            Route::put('/{id}', 'ProjectsController@update')->name('settings_project.update');
            Route::get('/{id}/show', 'ProjectsController@show')->name('settings_project.show');
            Route::get('/{id}/delete', 'ProjectsController@destroy')->name('settings_project.delete');
        }); 
    });
});
