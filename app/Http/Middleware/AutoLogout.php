<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Redirect;
use Session;
use Carbon\Carbon;

class AutoLogout
{
    protected $session;
    protected $timeout;

    public function __construct(Store $session)
    {
        $this->session = $session;
        // Session lifetime in minutes, converted to seconds
        $this->timeout = (int) config('session.lifetime', 120) * 60;
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
        if (auth()->check()) {
            $lastActive = $this->session->get('last_active');
            $now = time();

            if ($lastActive && ($now - (int) $lastActive) > $this->timeout) {
                $this->session->forget('last_active');
                $this->session->flush();
                auth()->logout();

                flash('Due to inactivity you have been logged out')->warning();
                return redirect()->guest('login');
            }

            if ($request->path() !== 'logout') {
                $this->session->put('last_active', $now);
            } else {
                $this->session->forget('last_active');
            }
        }

        return $next($request);
    }
}
