<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';

    protected $fillable = [
        'negocio_id',
        'cliente_id',
        'empleado_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'notas'
    ];

    protected $casts = [
    'fecha' => 'date'
];

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'cliente_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function detalles()
{
    return $this->hasMany(
        DetalleCitaServicio::class,
        'cita_id'
    );
}
}