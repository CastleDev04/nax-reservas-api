<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Negocio;
use Illuminate\Http\Request;

class DetectarNegocio
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();

        $negocio = Negocio::where('dominio', $host)
            ->where('activo', true)
            ->first();

        if (!$negocio) {
            abort(404, 'Negocio no encontrado');
        }

        app()->instance('negocio_actual', $negocio);

        return $next($request);
    }
}