<?php

namespace App\Http\Middleware;

use App\Values\RoleValue;
use App\Values\StatusValue;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if(!$user->hasRole([RoleValue::SUPER_ADMIN_NAME, RoleValue::STAFF_ADMIN_NAME])){
            return response('You have no permission' , StatusValue::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
