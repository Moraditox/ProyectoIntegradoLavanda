<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anno_Academico extends Model
{
    use HasFactory;

    protected $table = 'anno_academico';

    // Relación 1:N con la tabla anno_academico
    public function matricula()
    {
        return $this->hasMany('App\Models\Matricula', 'anno_academico', 'anno');
    }

    // Relación 1:N con la tabla convocatorias
    public function convocatorias()
    {
        return $this->hasMany('App\Models\Convocatorias', 'anno_academico', 'anno');
    }
}
