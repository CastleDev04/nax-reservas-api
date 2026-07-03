<?php

namespace App\Models;
use App\Models\Negocio;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $fillable = [
        'negocio_id',
        'usuario_id',
        'nombre',
        'activo'
    ];

    protected $casts = [
    'activo' => 'boolean'
];

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }


    public function usuario()
{
    return $this->belongsTo(Usuario::class);
}

    public function servicios()
{
    return $this->belongsToMany(
        Servicio::class,
        'empleado_servicio',
        'empleado_id',
        'servicio_id'
    );
}

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function horarios()
    {
        return $this->hasMany(HorarioEmpleado::class);
    }

    public function bloqueos()
    {
        return $this->hasMany(BloqueoEmpleado::class);
    }
}