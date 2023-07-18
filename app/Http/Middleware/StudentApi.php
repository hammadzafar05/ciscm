<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Carbon;

class StudentApi
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
        $authToken = trim($request->header('Authorization'));

        //get user
        $now = Carbon::now()->toDateTimeString();
        $student = \App\Student::where('api_token',$authToken)->where('token_expires','>',$now)->first();
        if(!$student){
            return jsonResponse(['status'=>false,'error'=>'Unauthorized request: '.$authToken]);
        }


        return $next($request);
    }
}
