<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

class Helper
{    
    public static function getModulesFromMenu()
    {
        $menu = config('adminlte.menu');

        foreach($menu as $m){
            if(isset($m['text']) && isset($m['url'])){
                $modules[] = $m['text'];
            }
            if(isset($m['submenu'])){
                foreach($m['submenu'] as $sub){
                    if(isset($sub['text']) && isset($sub['url'])){
                        $modules[] = $sub['text'];
                    }
                }
            }
        }
        
        return $modules;
    }

    public static function getCurrentRoutePrefix()
    {
        $prefix = explode('/',Request()->route()->getPrefix())[1];
        
        return $prefix;
    }

    public static function getModulesFromRoute()
    {
        $modules = array();
        $routes = Route::getRoutes()->getRoutes();

        foreach($routes as $route){
            if ($route->getName() != '' && $route->getAction()['middleware']['0'] == 'web') {
                $modules[] = $route->getName();
            }
        }
        return $modules;
    }

	public static function getSystemModules()
	{
		$systemModules = [
			'adminlte.darkmode.toggle',
			'sanctum.csrf-cookie',
			'login',
			'logout',
			'register',
			'password.request',
			'password.email',
			'password.reset',
			'password.update',
			'password.confirm',
		];
		return $systemModules;
	}

    public static function formatDate($date, $timezone = 'Asia/Kuala_Lumpur', $format = 'd-m-Y H:i:s')
    {
        return Carbon::parse($date)->setTimezone($timezone)->format($format);
    }

    public static function bgcolor()
    {
		$color = array(
			'bg-navy' => 'bg-navy',
			'bg-olive' => 'bg-olive',
			'bg-lime' => 'bg-lime',
			'bg-fuchsia' => 'bg-fuchsia',
			'bg-maroon' => 'bg-maroon',
			'bg-blue' => 'bg-blue',
			'bg-indigo' => 'bg-indigo',
			'bg-purple' => 'bg-purple',
			'bg-pink' => 'bg-pink',
			'bg-red' => 'bg-red',
			'bg-orange' => 'bg-orange',
			'bg-yellow' => 'bg-yellow',
			'bg-green' => 'bg-green',
			'bg-teal' => 'bg-teal',
			'bg-cyan' => 'bg-cyan',
			'bg-white' => 'bg-white',
			'bg-gray' => 'bg-gray',
			'bg-gray-dark' => 'bg-gray-dark',
			'bg-gray-light' => 'bg-gray-light',
			'bg-black' => 'bg-black',
			'bg-success' => 'bg-success',
			'bg-danger' => 'bg-danger',
			'bg-info' => 'bg-info',
			'bg-warning' => 'bg-warning',
			'bg-light' => 'bg-light',
			'bg-dark' => 'bg-dark',
			'bg-primary' => 'bg-primary',
			'bg-secondary' => 'bg-secondary',
		);
		return $color;
    }
    
	public static function textcolor(){
		$textcolor = array(
			'text-white' => 'text-white',
			'text-primary' => 'text-primary',
			'text-secondary' => 'text-secondary',
			'text-success' => 'text-success',
			'text-info' => 'text-info',
			'text-warning' => 'text-warning',
			'text-danger' => 'text-danger',
			'text-light' => 'text-light',
			'text-dark' => 'text-dark',
			'text-body' => 'text-body',
			'text-muted' => 'text-muted',
			'text-black-50' => 'text-black-50',
			'text-white-50' => 'text-white-50',
		);
		return $textcolor;
	}

	public static function gettextcolor($color){
        $lightColor = ['bg-lime','bg-orange','bg-yellow','bg-white','bg-gray-light','bg-warning','bg-light'];

        if(in_array($color, $lightColor)){
				$textcolor = 'text-dark';
		}else{
			$textcolor = 'text-white';
		}	
		return $textcolor;
	}
    
	public static function templatefields(){
		$fields = array(
			'profile-image' => 'profile-image',
			'profile-name' => 'profile-name',
			'profile-tagline' => 'profile-tagline',
			'profile-email' => 'profile-email',
			'profile-phone' => 'profile-phone',
			'profile-website' => 'profile-website',
			'social-info' => 'social-info',
			'education-info' => 'education-info',
			'language-info' => 'language-info',
			'interest-info' => 'interest-info',
			'objective-info' => 'objective-info',
			'experience-info' => 'experience-info',
			'project-info' => 'project-info',
			'skill-info' => 'skill-info',
			'main-color' => 'main-color',
			'accent-color' => 'accent-color',
			'text-color-primary' => 'text-color-primary',
			'text-color-secondary' => 'text-color-secondary',
		);
		return $fields;
	}
}