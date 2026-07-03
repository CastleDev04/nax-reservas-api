<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoCita extends Model
{
    protected $table = 'pagos_citas';

    protected $fillable = [
        'cita_id',
        'monto',
        'metodo',
        'estado',
        'fecha_pago',
        'nota',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }
}
