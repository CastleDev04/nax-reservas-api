<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloqueoEmpleado extends Model
{
    protected $table = 'bloqueos_empleados';

    protected $fillable = [
        'empleado_id',
        'fecha_inicio',
        'fecha_fin',
        'motivo'
    ];

    protected $casts = [
    'fecha_inicio' => 'datetime',
    'fecha_fin' => 'datetime'
];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
