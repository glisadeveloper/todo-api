<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Helpers\ReturnResponse;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * Get the informations when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */
    protected function unauthenticated($request, array $guards)
    {
         abort(response()->json(['todo-api' => ['error' => 'You are not logged in or your session has expired, please login !']], 401));         
    }
    
}
