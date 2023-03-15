<?php

namespace App\Http\Middleware;

use App\Models\Leads;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Route;
use Str;

class VerifySubdomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $subdomain = Route::getCurrentRoute()->parameter('subdomain');
        $subdomain_dbvalue = Str::replace('-',' ',$subdomain);
        $exist = Leads::where(['name' => $subdomain_dbvalue])->first();
        if($exist === null){            
            flash(
                'Unable to verify account. Please contact admin',
            )->error();
            return redirect()->route('welcome');
        }else{
            if(User::where(['email' => $exist->email])->first() === null){            
                flash(
                    'Unable to verify account. Please contact admin',
                )->error();
                return redirect()->route('welcome');
            }
        }

        return $next($request);
    }
}
