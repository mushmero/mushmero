<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Traits\UsersTrait;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $input = $request->all();
     
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if(!UsersTrait::verifyNewUser($input['email'])){

            if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password'])))
            {
                return redirect()->route('home');
            }else{
                flash(
                    'Invalid Email or Password. Please try again',
                )->error();
                return redirect()->route('login');
            }

        }else{
            return redirect()->route('change-password',['email' => $input['email']]);
        }
    }

    public function changePassword(Request $request)
    {
        $email = $request->input('email');
        return view('templates.users.content.changePassword', compact('email'));
    }

    public function storePassword(Request $request)
    {
        $this->validate($request, [
            'password'=>'required|min:6|same:retype_password',
            'retype_password' => 'required|min:6',
        ]);
        $data = $request->all();
        $userdata = [
            'password' => UsersTrait::generatePassword($data['password']),
            'temp_pwd' => 0,
        ];
        $update = User::where(['email' => $data['email']])->update($userdata);

        if($update){
            flash(
                'Successfully update new password',
            )->success();
            return redirect()->route('login');
        }else{
            flash(
                'Unable to update password. Please contact admin.',
            )->error();
            return redirect()->route('login');
        }
    }
}
