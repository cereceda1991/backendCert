<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }
    
        $user = Auth::user();
        $user->makeHidden('password'); // Ocultar la contraseña en la respuesta
        $token = JWTAuth::fromUser($user);
    
        return response()->json(['user' => $user, 'token' => $token], Response::HTTP_OK);
    }
    
    public function logout(Request $request)
    {
        // Revocar el token de acceso del usuario actual
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
    }
}
