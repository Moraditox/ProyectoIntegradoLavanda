<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignaciones extends Model
{
    use HasFactory;

    protected $table = 'asignaciones';
    protected $fillable = [
        'empresa_id',
        'profesores_id',
        // Otros campos aquí
    ];

    // Relación 1:N con la tabla convocatorias
    public function convocatorias()
    {
        return $this->belongsTo('App\Models\Convocatorias', 'convocatoria_id', 'id');
    }

    // Relación 1:N con la tabla alumnado
    public function alumnado()
    {
        return $this->belongsTo('App\Models\Alumnado', 'alumnado_id', 'id');
    }

    // Relación 1:N con la tabla profesores
// En el modelo Asignaciones.php
    public function profesor()
    {
        return $this->belongsTo('App\Models\Profesores', 'profesores_id', 'id');
    }



    // Relación 1:N con la tabla trabajadores
    public function trabajadores()
    {
        return $this->belongsTo('App\Models\Trabajadores', 'trabajadores_id', 'id');
    }

    // Relación 1:N con la tabla actuaciones
    public function actuaciones()
    {
        return $this->hasMany('App\Models\Actuaciones', 'asignacion_id', 'id');
    }
    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa', 'empresa_id', 'id');
    }

}
