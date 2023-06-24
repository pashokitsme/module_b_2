<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Authenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        $bearer = $request->bearerToken();
        if(!$bearer || !$user = User::where('token', $bearer)->first())
            throw new UnauthorizedHttpException("auth");

        return $next($request->merge(['user' => $user]));
    }
}
