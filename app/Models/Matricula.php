<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Curso_Academico;
class Matricula extends Model
{
    use HasFactory;

    static $rules = [
        'alumno_id' => 'required',
        'curso_academico_id' => 'required',
        'anno_academico' => 'required',
    ];

    protected $fillable = ['alumno_id', 'curso_academico_id', 'anno_academico'];

    protected $table = 'matricula';

    // Relación 1:N con la tabla curso_academico
    // Matricula model
    public function curso_academico()
    {
        return $this->belongsTo(Curso_Academico::class, 'curso_academico_id');
    }


    // Relación 1:1 con la tabla alumnado
    public function alumnado()
    {
        return $this->belongsTo('App\Models\Alumnado', 'alumno_id', 'id');
    }

    // Relación 1:N con la tabla anno_academico
    public function anno_academico()
    {
        return $this->belongsTo('App\Models\Anno_Academico', 'anno_academico', 'anno');
    }
    public function convocatoriaCursos()
    {
        return $this->hasMany(Convocatoria_Cursos::class, 'curso_academico_id', 'curso_academico_id');
    }
    public function asignaciones()
    {
        return $this->hasMany('App\Models\Asignaciones', 'alumnado_id', 'alumno_id');
    }
}
