<?php

namespace App\Helpers;

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
}