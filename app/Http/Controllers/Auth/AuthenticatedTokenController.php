<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticatedTokenController extends Controller
{
    /**
     * Maneja solicitud de inicio de sesion
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $token = $request->authenticate();    
        $user = auth()->user();
        return response()->json([
            'personal_token'    => $token,
            'user'  => [
                'email' => $user->email,
                'name'  => $user->name,
            ]
        ], 201);

    }

    /**
     * Revoca un personal token
     */
    public function destroy(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
