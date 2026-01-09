<?php

namespace App\Helpers;

use App\Models\AppsVersion;
use App\Models\Config\Countries;
use File;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Str;
use MatthiasMullie\Minify;

class Helper
{    
	public static function appVer()
	{
		$appsVersion = AppsVersion::first();
		if(!empty($appsVersion)){
			$version = $appsVersion->major.'.'.$appsVersion->minor.'.'.$appsVersion->build.'_'.$appsVersion->date.' '.$appsVersion->uuid;
		}else{
			$version = '1.0.0';
		}
		return $version;
	}
	public static function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[random_int(0, $charactersLength - 1)];
		}
		return $randomString;
	}
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

    // public static function getModulesFromRoute()
    // {
    //     $modules = array();
    //     $routes = Route::getRoutes()->getRoutes();

    //     foreach($routes as $route){
	// 		if(is_array($route->getAction()['middleware'])){
	// 			if ($route->getName() != '' && $route->uri() != '/' && in_array('permission', $route->getAction()['middleware']) && !in_array('verify.subdomain', $route->getAction()['middleware'])) {
	// 				$modules[] = $route->getName();
	// 			}
	// 		}
    //     }
    //     return $modules;
    // }

	// public static function getSystemModules()
	// {
	// 	$systemModules = [
	// 		'adminlte.darkmode.toggle',
	// 		'sanctum.csrf-cookie',
	// 		'login',
	// 		'logout',
	// 		'register',
	// 		'password.request',
	// 		'password.email',
	// 		'password.reset',
	// 		'password.update',
	// 		'password.confirm',
	// 	];
	// 	return $systemModules;
	// }

    public static function formatDate($date, $timezone = 'Asia/Kuala_Lumpur', $format = 'd-m-Y H:i:s')
    {
		if(is_null($date)) return 'Empty Date';
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

	public static function createDirectory($dir)
	{
		if(!File::exists($dir)){
			mkdir($dir, 0755,true);
		}
	}
	
	public static function getVisitor($ip = NULL, $deepdetect = TRUE){
		if(filter_var($ip,FILTER_VALIDATE_IP) === FALSE){
			$ip = $_SERVER['REMOTE_ADDR'];
			if($deepdetect){
				if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)){
                	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				}
	            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)){
	                $ip = $_SERVER['HTTP_CLIENT_IP'];
	            }
			}
		}
		if(isset($_SERVER['HTTP_REFERER'])){
			$data = array(
				'ip'	=>	$ip,
				'referrer'	=> $_SERVER['HTTP_REFERER'],
				'useragent'	=>	$_SERVER['HTTP_USER_AGENT'],
				'timestamp'	=> Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s e'),
			);
		}else{
			$data = array(
				'ip'	=>	$ip,
				'referrer'	=> '',
				'useragent'	=>	$_SERVER['HTTP_USER_AGENT'],
				'timestamp'	=> Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s e'),
			);
		}
		
		return $data;
	}

	public static function ipLocation($ip){
		
		$geoip = json_decode(file_get_contents('https://ipinfo.io/'.$ip), true);
		if (in_array($geoip['ip'], ['127.0.0.1', '::1'])) {
			$location = array(
				'userip' => $geoip['ip'],
				'city' => null,
				'country' => null,
				'countryCode' => null,
				'continent'	=> null,
				'continentCode' => null,
				'timezone'	=> null,
				'latitude' => null,
				'longitude' => null,
			);
		} else {
			$countryInfo = Helper::countryData($geoip['country']);
			$latlong = explode(',', $geoip['loc']);
			$location = array(
				'userip' => $geoip['ip'],
				'city' => $geoip['city'],
				'country' => $countryInfo['name'],
				'countryCode' => $geoip['country'],
				'continent'	=> $countryInfo['continent_name'],
				'continentCode' => $countryInfo['continent_code'],
				'timezone'	=> $geoip['timezone'],
				'latitude' => $latlong[0],
				'longitude' => $latlong[1],
			);
		}
		return $location;
	}
	public static function visitor(){
		$geoip = [];
		$visitor = Helper::getVisitor($_SERVER['REMOTE_ADDR']);
		$geoip = Helper::ipLocation($visitor['ip']);
		$data = array_merge($visitor,$geoip);

		$data = [
            'userip' => $data['ip'] ?? null,
            'referrer' => $data['referrer'] ?? null,
            'useragent' => $data['useragent'] ?? null,
            'datetime' => $data['timestamp'] ?? null,
            'city' => $data['city'] ?? null,
            'country' => $data['country'] ?? null,
            'countryCode' => $data['countryCode'] ?? null,
            'continent' => $data['continent'] ?? null,
            'continentCode' => $data['continentCode'] ?? null,
            'timezone' => $data['timezone'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
        ];
		return $data;
	}

	public static function transformArray($old_array)
	{
		if(empty($old_array)) return;
		$new_array = array();
		foreach($old_array as $key => $value){
			foreach($value as $k => $v){
				$new_array[$k] = $v;
			}
			unset($old_array[$key]);
		}
		return $new_array;
	}

	// public static function getRouteSubdomain()
	// {
	// 	$route = Route::getCurrentRoute();
	// 	return $route->parameter('subdomain');
	// }

	public static function getDomain()
	{
		$domain = 'localhost';		
        if(Str::startsWith(config('app.url'), array('http://','https://'))){
            $domain = Str::replace(['http://','https://'], '', config('app.url'));
        }
		return $domain;
	}

	// public static function urlHasPermission($url = null)
	// {
	// 	$authUser = auth()->user();
	// 	$allRoutes = Route::getRoutes()->getRoutesByName();

	// 	foreach($allRoutes as $key => $route){
	// 		if(is_array($route->getAction()['middleware'])){
	// 			if ($route->getName() != '' && $route->uri() != '/' && in_array('permission', $route->getAction()['middleware']) && !in_array('verify.subdomain', $route->getAction()['middleware'])) {
	// 				if($route->getPrefix() != ''){
	// 					if(explode('/',$route->getPrefix())[1] == $url){
	// 						if($authUser->hasPermissionTo($route->getAction()['as'])){
	// 							return true;
	// 						}else{
	// 							return false;
	// 						}
	// 					}
	// 				}
	// 			}
	// 		}
	// 	}
	// 	return false;
	// }

	public static function minifyCSSJS($source, $type)
	{
		if($type == null) return false;
		if($type == 'css'){
			$minifier = new Minify\CSS();
			$minifier->add($source);
			return $minifier->minify();
		}
		if($type == 'js'){
			$minifier = new Minify\JS();
			$minifier->add($source);
			return $minifier->minify();
		}
	}

	public static function countryData($countryCode)
	{
		$country = Countries::where('code', $countryCode)->first();
		return $country;
	}
}