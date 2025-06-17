<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CursosAcademicos extends Model
{
    protected $table = 'cursos_academicos';

    protected $fillable = [
        "id",
        "years",
        "created_at",
        "updated_at",
    ];
}