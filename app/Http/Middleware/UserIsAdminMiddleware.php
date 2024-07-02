<?php

namespace App\Http\Middleware;

use App\Http\ApiResponse;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserIsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user() ?? null;

        if (isset($user) && ($user->is_admin)) {
            return $next($request);
        }

        return ApiResponse::send(false, Response::HTTP_UNAUTHORIZED, "Unauthorized");
    }
}
