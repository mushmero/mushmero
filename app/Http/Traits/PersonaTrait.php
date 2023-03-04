<?php

namespace App\Http\Traits;
use App\Http\Controllers\Modules\PersonalController;

trait PersonaTrait {
    public static function checkPersona()
    {
        $persona_status = (new PersonalController)->countstatus();
        if($persona_status == 1){
            return true;
        }else{
            return false;
        }
    }
}