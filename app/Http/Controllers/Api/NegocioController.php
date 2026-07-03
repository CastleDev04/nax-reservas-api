<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use Illuminate\Http\Request;

class NegocioController extends Controller
{
    public function index()
    {
        return Negocio::all();
    }

    public function show($id)
    {
        return Negocio::findOrFail($id);
    }

    public function store(Request $request)
    {
        return Negocio::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $negocio = Negocio::findOrFail($id);
        $negocio->update($request->all());

        return response()->json(['message' => 'Actualizado']);
    }

    public function destroy($id)
    {
        Negocio::findOrFail($id)->delete();

        return response()->json(['message' => 'Eliminado']);
    }
}