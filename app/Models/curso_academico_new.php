<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class curso_academico_new extends Model
{
    use HasFactory;

    // usa la tabla 'cursos_academicos' en lugar de 'curso_academico_new'
    protected $table = 'cursos_academicos';

    // Relación N:N con la tabla profesores
    public function profesores()
    {
        return $this->belongsToMany(Profesores::class, 'curso_academico_profesor', 'curso_academico_id', 'profesor_id');
    }
}
