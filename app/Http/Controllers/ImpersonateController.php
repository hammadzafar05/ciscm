<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
		// $this->middleware('can:impersonate');
	}
	
	/**
	 * Impersonate the given user.
	 *
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function impersonate(User $user)
	{
		if ($user->id !== ($original = Auth::user()->id)) {
			session()->put('original_user', $original);
			
			auth()->login($user);
		}
		
		$url =  route('student.dashboard');
		return redirect($url);
	}
	
	/**
	 * Revert to the original user.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function revert()
	{
		auth()->loginUsingId(session()->get('original_user'));
		
		session()->forget('original_user');
		
		$url = route('admin.dashboard');
		$url = route(('admin.student.index'));
		return redirect($url);
	}
}