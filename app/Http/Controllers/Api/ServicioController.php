<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index(Request $request)
    {
        return Servicio::where('negocio_id', $request->negocio_id)
            ->where('activo', true)
            ->get();
    }

    public function show($id)
    {
        return Servicio::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'negocio_id' => 'required|exists:negocios,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'duracion_minutos' => 'required|integer|min:1',
        ]);

        $servicio = Servicio::create([
            'negocio_id' => $data['negocio_id'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'precio' => $data['precio'],
            'duracion_minutos' => $data['duracion_minutos'],
            'activo' => true
        ]);

        return response()->json([
            'message' => 'Servicio creado correctamente',
            'servicio' => $servicio
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $servicio->update(
            $request->only([
                'nombre',
                'descripcion',
                'precio',
                'duracion_minutos',
                'activo'
            ])
        );

        return response()->json([
            'message' => 'Servicio actualizado'
        ]);
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();

        return response()->json([
            'message' => 'Servicio eliminado'
        ]);
    }
}