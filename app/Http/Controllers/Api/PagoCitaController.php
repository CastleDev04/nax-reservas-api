<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PagoCita;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PagoCitaController extends Controller
{
    public function index($citaId)
    {
        return PagoCita::where('cita_id', $citaId)->get();
    }

    public function store(Request $request, $citaId)
    {
        $data = $request->validate([
            'monto' => 'required|numeric|min:0',
            'metodo' => 'nullable|string',
            'nota' => 'nullable|string',
        ]);

        $pago = PagoCita::create([
            'cita_id' => $citaId,
            'monto' => $data['monto'],
            'metodo' => $data['metodo'] ?? 'efectivo',
            'estado' => 'pagado',
            'fecha_pago' => Carbon::now(),
            'nota' => $data['nota'] ?? null,
        ]);

        return response()->json($pago, 201);
    }

    public function update(Request $request, $id)
    {
        $pago = PagoCita::findOrFail($id);

        $pago->update($request->only([
            'monto',
            'metodo',
            'estado',
            'nota'
        ]));

        return response()->json([
            'message' => 'Pago actualizado',
            'pago' => $pago
        ]);
    }

    public function destroy($id)
    {
        PagoCita::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Pago eliminado'
        ]);
    }
}