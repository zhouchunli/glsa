<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Redirect;
use Session;

class Authenticate
{

	public function handle($request, Closure $next)
	{
		if(Session::get('user_id')){
			return $next($request);
		}else{
			return Redirect::to("admin/login");
		}



	}
}
