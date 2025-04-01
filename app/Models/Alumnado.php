<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumnado extends Model
{
    use HasFactory;

    protected $table = 'alumnado';

    // Relacion 1:1 con la tabla matricula
    public function matricula()
    {
        return $this->hasOne('App\Models\Matricula', 'alumno_id', 'id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'alumno_id');
    }
    
    // Relacion 1:1 con la tabla asignaciones
    public function asignaciones()
    {
        return $this->hasOne('App\Models\Asignaciones', 'alumnado_id', 'id');
    }

    // Relacion 1:1 con la tabla formulario_seguimiento_alumno
    public function formulario_seguimiento_alumno()
    {
        return $this->hasOne('App\Models\Formulario_Seguimiento_Alumno', 'alumnado_id', 'id');
    }

    // Relacion 1:1 con la tabla formulario_seguimiento_empresa
    public function formulario_seguimiento_empresa()
    {
        return $this->hasOne('App\Models\Formulario_Seguimiento_Empresa', 'alumnado_id', 'id');
    }
    public function profesor()
    {
        return $this->hasOneThrough(
            Profesores::class,
            Asignaciones::class,
            'alumnado_id', // Foreign key on Asignaciones table
            'id',           // Local key on Alumnado table
            'id',           // Local key on Profesores table
            'profesores_id' // Foreign key on Asignaciones table
        );
    }

    public function curso_academico()
    {
        return $this->belongsTo(Curso_Academico::class);
    }

    public function convocatoria()
    {
        return $this->belongsTo(Convocatorias::class);
    }
    public function asignacionesDetalles()
    {
        return $this->hasMany(Asignaciones::class, 'alumnado_id', 'id');
    }

}
