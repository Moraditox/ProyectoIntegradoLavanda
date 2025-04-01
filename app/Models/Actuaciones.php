<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actuaciones extends Model
{
    use HasFactory;

    protected $table = 'actuaciones';

    protected $fillable = [
        'emisor',
        'tipo',
        'observaciones',
        'informe_alumno_id',
        'informe_empresa_id',
        'asignacion_id',
    ];

    static $rules = [
        'emisor'=>'required',
        'tipo'=>'required',
        'observaciones'=>'nullable',
        'informe_alumno_id'=>'nullable',
        'informe_empresa_id'=>'nullable',
        'asignacion_id'=>'nullable',
    ];

    // Relación 1:N con la tabla asignaciones
    public function asignaciones()
    {
        return $this->belongsTo('App\Models\Asignaciones', 'asignacion_id', 'id');
    }

    // Relación 1:1 con la tabla formulario_seguimiento_alumno
    public function formulario_seguimiento_alumno()
    {
        return $this->belongsTo('App\Models\Formulario_Seguimiento_Alumno', 'informe_id', 'id');
    }
    public function asignacion()
    {
        return $this->belongsTo('App\Models\Asignaciones', 'asignacion_id');
    }
    // Relación 1:1 con la tabla formulario_seguimiento_empresa
    public function formulario_seguimiento_empresa()
    {
        return $this->belongsTo('App\Models\Formulario_Seguimiento_Empresa', 'informe_id', 'id');
    }
    public function profesor()
    {
        return $this->belongsTo('App\Models\Profesores', 'profesores_id');
    }
}
