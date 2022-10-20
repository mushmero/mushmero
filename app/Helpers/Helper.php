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

    public static function formatDate($data, $timezone = 'Asia/Kuala_Lumpur')
    {
        return Carbon::parse($data)->setTimezone($timezone);
    }
}