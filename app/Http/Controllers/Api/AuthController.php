<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $usuario = Usuario::where('email',$request->email)->first();

        if(!$usuario || !Hash::check($request->password,$usuario->password))
        {
            return response()->json([
                'message'=>'Credenciales incorrectas'
            ],401);
        }

        $token = $usuario->createToken('api')->plainTextToken;

        return response()->json([
            'token'=>$token,
            'usuario'=>$usuario
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message'=>'Logout correcto'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json(
            $request->user()
        );
    }
}