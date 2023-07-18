<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
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
        $user = Auth::user();
        if($user->role_id != 1){
            return redirect()->route('login');
        }

        if(Auth::user() && Auth::user()->can('access','global_resource_access'))
        {
            @define('GLOBAL_ACCESS',true);
        }
        else{
            @define('GLOBAL_ACCESS',false);
        }

        if(Auth::user() && Auth::user()->role_id==1 && Auth::user()->admin){

            if(!defined('ADMIN_ID'))define('ADMIN_ID',Auth::user()->admin->id);
        }

        //check that user can access route
        $list = include '../app/Lib/permissions.php';
        $routeName = $request->route()->getName();
         if(isset($list[$routeName])){
             $permission = $list[$routeName];
           //  $permission = 'view_students'; //remove this later
             if(!empty($permission) && !Auth::user()->can('access',$permission)){
                 abort(403);
             }
         }

        return $next($request);
    }
}
