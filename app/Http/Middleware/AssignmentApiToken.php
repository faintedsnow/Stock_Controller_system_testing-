<?php

namespace App\Http\Middleware;

use App\Support\SimpleApiToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AssignmentApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = SimpleApiToken::userFromToken($request->bearerToken());

        if (! $user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        Auth::setUser($user);
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
