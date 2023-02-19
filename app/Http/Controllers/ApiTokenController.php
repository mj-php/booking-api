<?php

namespace App\Http\Controllers;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    /**
     * Update the authenticated user's API token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getToken(Request $request): JsonResponse
    {
        $apiKey = env('JWT_SECRET');

        $issuedAt = new DateTimeImmutable();
        $expire = $issuedAt->modify('+1 days')->getTimestamp();      // Add 1 days

        $user = Auth::user();

        $data = [
            'iat' => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
            'iss' => $request->getHttpHost(),                       // Issuer
            'nbf' => $issuedAt->getTimestamp(),         // Not before
            'exp' => $expire,                           // Expire
            'userName' => $user->email,                     // User name
        ];

        return response()->json(['token' => JWT::encode(
            $data,
            $apiKey,
            'HS512'
        )]);
    }
}
