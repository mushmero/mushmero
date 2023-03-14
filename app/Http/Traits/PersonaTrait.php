<?php

namespace App\Http\Traits;

use App\Helpers\Helper;
use App\Models\Personal;
use App\Models\User;

trait PersonaTrait {
    public static function checkPersona()
    {
        $subdomain = Helper::getRouteSubdomain();
        $user_id = User::where('name',str_replace('-',' ',$subdomain))->get()->pluck('id');
        if($subdomain != null){
            if(!empty($user_id)){
                $persona = Personal::where(['status' => 1, 'user_id' => $user_id])->get();
            }else{
                $persona = Personal::where(['status' => 1, 'user_id' => 'no-user'])->get();
            }
        }else{
            $persona = Personal::where(['status' => 1, 'user_id' => 1])->get();
        }
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

    public static function getActivePersonaInformations()
    {
        $subdomain = Helper::getRouteSubdomain();
        $user_id = User::where('name',str_replace('-',' ',$subdomain))->get()->pluck('id');
        if($subdomain != null){
            if(!empty($user_id)){
                $persona = Personal::with(['attachments','educations','experiences','interests','languages','objectives','projects','skills','socials'])
                    ->where(['status' => 1, 'user_id' => $user_id])->first();
            }else{
                $persona = Personal::with(['attachments','educations','experiences','interests','languages','objectives','projects','skills','socials'])
                ->where(['status' => 1, 'user_id' => 'no-user'])->first();
            }
        }else{
            $persona = Personal::with(['attachments','educations','experiences','interests','languages','objectives','projects','skills','socials'])
            ->where(['status' => 1, 'user_id' => 1])->first();
        }
        return $persona;
    }
}