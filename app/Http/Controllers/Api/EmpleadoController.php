<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index()
    {
        return Empleado::with('usuario', 'servicios')->get();
    }

    public function show($id)
    {
        return Empleado::with('usuario', 'servicios', 'horarios', 'bloqueos')
            ->findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'negocio_id' => 'required|exists:negocios,id',
            'usuario_id' => 'nullable|exists:usuarios,id',
            'nombre' => 'required|string|max:255',
        ]);

        $empleado = Empleado::create([
            'negocio_id' => $data['negocio_id'],
            'usuario_id' => $data['usuario_id'] ?? null,
            'nombre' => $data['nombre'],
            'activo' => true
        ]);

        return response()->json($empleado, 201);
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $empleado->update(
            $request->only([
                'nombre',
                'activo'
            ])
        );

        return response()->json([
            'message' => 'Empleado actualizado'
        ]);
    }

    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();

        return response()->json([
            'message' => 'Empleado eliminado'
        ]);
    }
}