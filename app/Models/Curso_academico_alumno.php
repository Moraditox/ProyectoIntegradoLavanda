<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso_academico_alumno extends Model
{
    protected $table = 'curso_academico_alumno';

    public $timestamps = false;

    // Relación 1:N con la tabla curso_academico
    public function cursoAcademico()
    {
        return $this->belongsTo('App\Models\Curso_Academico', 'curso_academico_id', 'id');
    }

    // Relación 1:N con la tabla alumno
    public function alumno()
    {
        return $this->belongsTo('App\Models\Alumnado', 'alumno_id', 'id');
    }
}
