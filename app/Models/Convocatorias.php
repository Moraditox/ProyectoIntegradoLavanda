<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convocatorias extends Model
{
    use HasFactory;

    static $rules = [
        'periodo' => 'required',
        'fecha_inicio' => 'required',
        'fecha_fin' => 'required',
        'observaciones' => 'required',
        'anno_academico' => 'required',
        'curso_academico' => 'required',
        'empresas' => 'nullable',
    ];

    protected $fillable = ['periodo', 'fecha_inicio', 'fecha_fin', 'observaciones', 'anno_academico', 'curso_academico', 'empresas'];

    protected $table = 'convocatorias';

    // Relación 1:N con la tabla anno_academico
    public function anno_academico()
    {
        return $this->belongsTo('App\Models\Anno_Academico', 'anno_academico_id', 'id');
    }

    // Relación N:N con la tabla convocatoria_empresas
    public function convocatoria_empresas()
    {
        return $this->hasMany('App\Models\Convocatoria_Empresa', 'convocatoria_id', 'id');
    }

    // Relación 1:N con la tabla asignaciones
    public function asignaciones()
    {
        return $this->hasMany('App\Models\Asignaciones', 'convocatoria_id', 'id');
    }

    // Relación 1:N con la tabla convocatoria_cursos
    public function convocatoria_cursos()
    {
        return $this->hasMany('App\Models\Convocatoria_Cursos', 'convocatoria_id', 'id');
    }

    // Definir una relación para obtener los cursos académicos asociados
    public function cursosAcademicos()
    {
        return $this->hasManyThrough('App\Models\Curso_Academico', 'App\Models\Convocatoria_Cursos', 'convocatoria_id', 'id', 'id', 'curso_academico_id');
    }
    public function empresa() {
        return $this->belongsTo('App\Models\Empresa', 'empresa_id', 'id');
    }

}
