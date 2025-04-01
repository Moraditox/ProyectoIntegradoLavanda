<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso_Academico extends Model
{
    use HasFactory;

    protected $table = 'curso_academico';

    public function ciclos()
    {
        return $this->belongsTo('App\Models\Ciclos', 'ciclo', 'ciclo');
    }

    public function matricula()
    {
        return $this->hasMany('App\Models\Matricula', 'curso_academico_id', 'id');
    }
    public function profesor()
    {
        return $this->belongsTo('App\Models\Profesor', 'profesor_id', 'id');
    }
    public function ciclo()
    {
        return $this->belongsTo('App\Models\Ciclos', 'ciclo', 'ciclo');
    }

}
