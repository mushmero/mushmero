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
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|same:retype_password',
            'retype_password' => 'required|min:8',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || $user->temp_pwd != 1) {
            flash('Invalid or expired password reset request.')->error();
            return redirect()->route('login');
        }

        $user->password = UsersTrait::generatePassword($request->password);
        $user->temp_pwd = 0;
        $user->save();

        flash('Successfully updated your password. Please log in with your new password.')->success();
        return redirect()->route('login');
    }
}
