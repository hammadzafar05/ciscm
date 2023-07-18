<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Closure;

class StartSession extends \Illuminate\Session\Middleware\StartSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return tap($this->manager->driver(), static function ($session) use ($request) {

            $sessionCookieName = config('session.cookie');

            if ($request->has($sessionCookieName)) {
                $sessionId = $request->input($sessionCookieName);
            } else {
                $sessionId = $request->cookies->get($session->getName());
            }

            $session->setId($sessionId);
        });
    }


}
