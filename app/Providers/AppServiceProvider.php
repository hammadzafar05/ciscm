<?php

namespace App\Providers;

use App\HeaderMenu;
use App\Template;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
	    $menus = HeaderMenu::where([
		    ['name', '=', 'custom'],
		    ['url', '!=', 'verify-certificate'],
	    ])->orderBy('sort_order')->get();
	    \View::share('shomvabona_custom_website_pages', $menus);
	    
        Paginator::useBootstrap();
        if(class_exists('\App\Lib\Helpers') && method_exists(new \App\Lib\Helpers(),'bootProviders')){
            \App\Lib\Helpers::bootProviders();
        }

	    //$admin_role = getAdminRole(Auth::user()->id);
		//dd(Auth::user());
	
	    //compose all the views....
	   
	    view()->composer('*', function ($view) {
			if (Auth::user()) {
				if (Auth::user()->role_id == '1') {
					$view_admin_role = getAdminRole(Auth::user()->id);
				}else{
					$view_admin_role = '';
				}
				//dd($view_admin_role);
			}else {
				$view_admin_role = '';
			}
		    View::share('view_admin_role', $view_admin_role);
			
		    $partner_sidebar_logo = '';
		    $partner = User::find(Auth::id());
			if ($partner) {
				if ($partner->logo) {
					$partner_sidebar_logo = asset('cdn/temp/' . $partner->logo);
				}
			}
		    View::share('partner_sidebar_logo', $partner_sidebar_logo);
	    });

		
    }
}
