<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        return Usuario::all();
    }

    public function show($id)
    {
        return Usuario::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|string'
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['rol'] = 'cliente';

        return Usuario::create($data);
    }

    public function update(Request $request, $id)
    {
        $user = Usuario::findOrFail($id);

        $user->update($request->only([
            'name',
            'email',
            'telefono'
        ]));

        return response()->json(['message' => 'Usuario actualizado']);
    }

    public function destroy($id)
    {
        Usuario::findOrFail($id)->delete();

        return response()->json(['message' => 'Usuario eliminado']);
    }
}