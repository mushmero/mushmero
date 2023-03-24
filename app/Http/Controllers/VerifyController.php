<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function VerifyEmail($token = null)
    {
        if($token == null){
            flash(
                'Invalid login attempt',
            )->error();
            return redirect('login');
        }

        $user = User::where('email_token', $token)->first();
        if(is_null($user)){
            flash(
                'Invalid login attempt',
            )->error();
            return redirect('login');
        }
        
        $user->update([
            'email_verify' => 1,
            'email_verified_at' => Carbon::now(),
            'email_token' => '',
        ]);

        flash(
            'Account activated successfully',
        )->success();

        return redirect('login');

    }
}
