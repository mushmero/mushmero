<?php

namespace App\Http\Traits;
use App\Http\Controllers\Modules\PersonalController;
use App\Models\Personal;

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

    public static function getActivePersonaByAuthUser()
    {
        $persona = Personal::where(['status' => 1, 'user_id' => auth()->user()->id])->first();
        return $persona;
    }
}