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
        Route::namespace('User')->group(static function(){
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
        Route::namespace('Settings')->group(static function(){     
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
            Route::prefix('/settings_template')->group(static function(){
                Route::get('/', 'TemplatesController@index')->name('settings_template');
                Route::post('/', 'TemplatesController@store')->name('settings_template.store');
                Route::get('/create', 'TemplatesController@create')->name('settings_template.create');
                Route::get('/{id}/edit', 'TemplatesController@edit')->name('settings_template.edit');
                Route::put('/{id}', 'TemplatesController@update')->name('settings_template.update');
                Route::get('/{id}/show', 'TemplatesController@show')->name('settings_template.show');
                Route::get('/{id}/delete', 'TemplatesController@destroy')->name('settings_template.delete');
            }); 
            Route::prefix('/settings_pdf')->group(static function(){
                Route::get('/', 'PDFSettingsController@index')->name('settings_pdf');
                Route::post('/', 'PDFSettingsController@store')->name('settings_pdf.store');
                Route::get('/create', 'PDFSettingsController@create')->name('settings_pdf.create');
                Route::get('/{id}/edit', 'PDFSettingsController@edit')->name('settings_pdf.edit');
                Route::put('/{id}', 'PDFSettingsController@update')->name('settings_pdf.update');
                Route::get('/{id}/show', 'PDFSettingsController@show')->name('settings_pdf.show');
                Route::get('/{id}/delete', 'PDFSettingsController@destroy')->name('settings_pdf.delete');
            }); 
        });
        Route::namespace('Modules')->group(static function(){
            Route::prefix('/objectives')->group(static function(){
                Route::get('/', 'ObjectivesController@index')->name('objectives');
                Route::post('/', 'ObjectivesController@store')->name('objectives.store');
                Route::get('/create', 'ObjectivesController@create')->name('objectives.create');
                Route::get('/{id}/edit', 'ObjectivesController@edit')->name('objectives.edit');
                Route::put('/{id}', 'ObjectivesController@update')->name('objectives.update');
                Route::get('/{id}/show', 'ObjectivesController@show')->name('objectives.show');
                Route::get('/{id}/delete', 'ObjectivesController@destroy')->name('objectives.delete');            
            });
            Route::prefix('/experiences')->group(static function(){
                Route::get('/', 'ExperiencesController@index')->name('experiences');
                Route::post('/', 'ExperiencesController@store')->name('experiences.store');
                Route::get('/create', 'ExperiencesController@create')->name('experiences.create');
                Route::get('/{id}/edit', 'ExperiencesController@edit')->name('experiences.edit');
                Route::put('/{id}', 'ExperiencesController@update')->name('experiences.update');
                Route::get('/{id}/show', 'ExperiencesController@show')->name('experiences.show');
                Route::get('/{id}/delete', 'ExperiencesController@destroy')->name('experiences.delete');            
            });
            Route::prefix('/projects')->group(static function(){
                Route::get('/', 'ProjectsController@index')->name('projects');
                Route::post('/', 'ProjectsController@store')->name('projects.store');
                Route::get('/create', 'ProjectsController@create')->name('projects.create');
                Route::get('/{id}/edit', 'ProjectsController@edit')->name('projects.edit');
                Route::put('/{id}', 'ProjectsController@update')->name('projects.update');
                Route::get('/{id}/show', 'ProjectsController@show')->name('projects.show');
                Route::get('/{id}/delete', 'ProjectsController@destroy')->name('projects.delete');            
            });
            Route::prefix('/skills')->group(static function(){
                Route::get('/', 'SkillsController@index')->name('skills');
                Route::post('/', 'SkillsController@store')->name('skills.store');
                Route::get('/create', 'SkillsController@create')->name('skills.create');
                Route::get('/{id}/edit', 'SkillsController@edit')->name('skills.edit');
                Route::put('/{id}', 'SkillsController@update')->name('skills.update');
                Route::get('/{id}/show', 'SkillsController@show')->name('skills.show');
                Route::get('/{id}/delete', 'SkillsController@destroy')->name('skills.delete');            
            });
            Route::prefix('/interests')->group(static function(){
                Route::get('/', 'InterestsController@index')->name('interests');
                Route::post('/', 'InterestsController@store')->name('interests.store');
                Route::get('/create', 'InterestsController@create')->name('interests.create');
                Route::get('/{id}/edit', 'InterestsController@edit')->name('interests.edit');
                Route::put('/{id}', 'InterestsController@update')->name('interests.update');
                Route::get('/{id}/show', 'InterestsController@show')->name('interests.show');
                Route::get('/{id}/delete', 'InterestsController@destroy')->name('interests.delete');            
            });
        });
    });
});
