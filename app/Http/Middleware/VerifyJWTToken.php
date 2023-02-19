<?php

namespace App\Http\Middleware;

use Closure;
use DateTimeImmutable;
use DomainException;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$this->isTokenValid($token,$request)) {
            return response()->json('Unauthorized', 401);
        }

        return $next($request);
    }

    function isTokenValid($authorizationToken,$request): bool
    {
        if (!$authorizationToken) {
            // No token was able to be extracted from the authorization header
            return false;
        }

        $apiKey = env('JWT_SECRET');

        try {
            $token = JWT::decode($authorizationToken, new Key($apiKey, 'HS512'));
        } catch (ExpiredException|DomainException|Exception $exception) {
            return false;
        }

        $now = new DateTimeImmutable();

        if ($token->iss !== $request->getHttpHost() ||
            $token->nbf > $now->getTimestamp() ||
            $token->exp < $now->getTimestamp()) {
            return false;
        }

        return true;
    }
}
