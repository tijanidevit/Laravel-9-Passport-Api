<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserEmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if ($user->verification_code != '') {
            return response([
                'status' => false,
                'message' => 'Email account not yet verified'
            ], 200);
        }
        return $next($request);
    }
}
