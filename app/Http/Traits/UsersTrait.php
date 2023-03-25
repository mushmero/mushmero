<?php

namespace App\Http\Traits;

use App\Helpers\Helper;
use App\Mail\VerificationMail;
use App\Models\User;
use Hash;
use Mail;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

trait UsersTrait 
{

    public static function userRole()
    {
        return 'Users';
    }
    public static function systemModules()
    {
        $systemModules = Helper::getSystemModules();

        $system = [];

        foreach($systemModules as $systemModule){
            $system['system'][] = $systemModule;
        }

        return $system;

    }
    public static function customModules()
    {
        $allModules = Helper::getModulesFromRoute();
        $systemModules = Helper::getSystemModules();

        $structuredModules = [];

        foreach(array_diff($allModules, $systemModules) as $module){
            if(strpos($module,'.')){
                $explode = explode('.', $module);
                $structuredModules[$explode[0]][] = $explode[1];
            }else{
                $structuredModules[$module][] = $module;
            }
        }

        return $structuredModules;
    }

    public static function getSelectedPermissionsId($permissions)
    {
        $permissionID = [];
        foreach($permissions as $permission){
            $explodePermission = explode('.', $permission);
            if(count($explodePermission) == 2){
                if($explodePermission[0] == $explodePermission[1]){
                    $permissionID[] = Permission::findByName($explodePermission[1]);
                }else{
                    $permissionID[] = Permission::findByName($permission);
                }
            }else{
                $permissionID[] = Permission::findByName($permission);
            }
        }
        return $permissionID;
    }
    private static function userPermission()
    {
        $systemModules = self::systemModules();
        $customModules = self::customModules();
        $userPermitted = [];
        $permittedModules = ['home','users','settings_social','settings_education','settings_language','settings_project','settings_template','settings_pdf','objectives','experiences','projects','skills','interests','languages','educations','socials','personal'];
        $standardModules = ['objectives','experiences','projects','skills','interests','languages','educations','socials','personal'];
        $permittedAction = ['store','create','edit','update','delete','show'];
        foreach($systemModules as $systems){
            foreach($systems as $system){
                array_push($userPermitted, $system);
            }
        }
        foreach($permittedModules as $module){
            if(in_array($module, array_keys($customModules))){
                if(in_array($module, $customModules[$module])){
                    if($module == 'home'){
                        array_push($userPermitted, $module.'.'.$module);
                    }
                    if($module == 'users'){
                        array_push($userPermitted, $module.'.'.$module);
                        array_push($userPermitted, $module.'.edit');
                        array_push($userPermitted, $module.'.update');
                        array_push($userPermitted, $module.'.show');             
                    }
                    if($module == 'settings_social' || $module == 'settings_education' || $module == 'settings_language' || $module == 'settings_project' || $module == 'settings_template' || $module == 'settings_pdf'){
                        array_push($userPermitted, $module.'.'.$module);
                    }
                    if(in_array($module, $standardModules)){
                        array_push($userPermitted, $module.'.'.$module);
                        foreach($permittedAction as $action){
                            array_push($userPermitted, $module.'.'.$action);
                        }
                    }
                }
            }
        }
        return $userPermitted;
    }

    public static function generatePassword($string)
    {
        return Hash::make($string);
    }

    private static function createRoleIfNotExists($role)
    {
        $allRoles = Role::all()->pluck('name')->toArray();
        if(in_array($role, $allRoles)){
            $exist = Role::findByName($role);
            return $exist;
        }else{
            $newRole = Role::create(['name' => $role]);
            return $newRole;
        }
    }

    public static function createuser($lead)
    {
        $random_password = Helper::generateRandomString(8);
        $token = Helper::generateRandomString(64);
        $userdata = [
            'name' => $lead->name,
            'email' => $lead->email,
            'password' => self::generatePassword($random_password),
            'temp_pwd' => 1,
            'email_token' => $token,
            'created_at' => Carbon::now(),
        ];
        $role = self::createRoleIfNotExists(self::userRole());
        $permissions = self::getSelectedPermissionsId(self::userPermission());
        $user = User::create($userdata);
        $user->assignRole($role->name);
        $role->syncPermissions($permissions);

        if(!is_null($user)){
            $info = [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $random_password,
                'token' => $token,
                'login_url' => 'https://'.$lead->subdomain.'.'.Helper::getDomain(),
                'inquiry' => env('MAIL_INQUIRY_ADDRESS'),
            ];    
            self::sendWelcomeMail($info);
        }

        return true;
    }

    public static function findUserByEmail($email)
    {
        $exist = User::where(['email' => $email])->first();
        return $exist;
    }

    public static function findUserById($id)
    {
        $exist = User::where(['id' => $id])->first();
        return $exist;
    }

    public static function sendWelcomeMail($userdata)
    {
        Mail::to($userdata['email'])->send(new VerificationMail($userdata));
        return true;
    }

    public static function verifyNewUser($email)
    {
        $user = User::where('email',$email)->first();

        if($user->temp_pwd == 1){
            return true;
        }else{
            return false;
        }
    }
    
}