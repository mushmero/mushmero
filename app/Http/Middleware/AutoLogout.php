<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use wadeshuler\FlashMessages\Facades\FlashMessages;
use Redirect;

class AutoLogout
{
    protected $session;
    protected $timeout;

    public function __construct(Store $session)
    {
        $this->session = $session;
        $this->timeout = env('SESSION_LIFETIME', 1200);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $is_logged_in = $request->path() != 'logout';
        if(!session('last_active')) {
            $this->session->put('last_active', time());
        } elseif(time() - $this->session->get('last_active') > $this->timeout) {
            
            $this->session->forget('last_active');
            
            $cookie = cookie('intend', $is_logged_in ? url()->current() : 'home');
            
            auth()->logout();
            $is_logged_in ? $this->session->put('last_active', time()) : $this->session->forget('last_active');
            FlashMessages::addFlashMessage(
                'error',
                'Due to inactivity you have been logged out',
            );
            return Redirect::To('login');
        }

        $is_logged_in ? $this->session->put('last_active', time()) : $this->session->forget('last_active');

        return $next($request);
    }
}
