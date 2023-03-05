<?php

namespace App\Http\Traits;
use App\Http\Controllers\Modules\PersonalController;
use App\Models\Personal;

trait PersonaTrait {
    public static function checkPersona()
    {
        $persona = Personal::where(['status' => 1])->get();
        return $persona;
    }

    public static function getActivePersonaByAuthUser()
    {
        $persona = Personal::where(['status' => 1, 'user_id' => auth()->user()->id])->first();
        return $persona;
    }

    public static function countActiveStatusByUser($id)
    {
        if($id == '') return false;
        $result = Personal::where(['status' => 1, 'user_id' => $id])->get();
        return $result->count();
    }
}