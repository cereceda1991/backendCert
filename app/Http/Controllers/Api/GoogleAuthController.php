<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class GoogleAuthController extends Controller
{
    
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Busca el usuario en la base de datos por el correo electrónico devuelto por Google
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                // Si el usuario no existe, créalo
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                ]);
            }

            // Genera un token JWT para el usuario
            $token = JWTAuth::fromUser($user);

            return response()->success(['token' => $token,'user' => $user ], 'User successfully registered!');

       } catch (\Exception $e) {
            // Manejo de errores en caso de que falle la autenticación con Google
            return response()->json([
                'error' => 'Error al autenticar con Google. Por favor, inténtalo de nuevo.',
            ], 500);
        }
    }
 
    public function logout(Request $request)
    {
        return app('App\Http\Controllers\Api\AuthController')->logout($request);
    }
}
