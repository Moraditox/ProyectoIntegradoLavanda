<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convocatoria_Cursos extends Model
{
    use HasFactory;

    protected $table = 'convocatoria_cursos';

    static $rules = [
        'convocatoria_id' => 'required',
        'curso_academico_id' => 'required',
    ];

    protected $fillable = ['convocatoria_id', 'curso_academico_id'];

    // Relación 1:N con la tabla convocatorias
    public function convocatorias()
    {
        return $this->belongsTo('App\Models\Convocatorias', 'convocatoria_id', 'id');
    }

    // Relación 1:N con la tabla curso_academico
    public function curso_academico()
    {
        return $this->belongsTo('App\Models\Curso_Academico', 'curso_academico_id', 'id');
    }
    

}
