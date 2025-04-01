<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciclos extends Model
{
    use HasFactory;

    // Relación 1:N con la tabla curso_academico
    public function curso_academico()
    {
        return $this->hasMany('App\Models\Curso_Academico', 'ciclo', 'ciclo');
    }
}
