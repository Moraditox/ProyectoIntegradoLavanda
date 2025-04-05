<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesores extends Model implements Authenticatable
{
    use HasFactory;

    protected $table = 'profesores';

    // Relación 1:N con la tabla asignaciones
    public function asignaciones()
    {
        return $this->hasMany('App\Models\Asignaciones', 'profesores_id', 'id');
    }


    // Métodos de la interfaz Authenticatable

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    // NUEVO CÓDIGO

    // Relación N:N con cursos academicos
    public function cursosAcademicos()
    {
        return $this->belongsToMany(Curso_Academico::class, 'curso_academico_profesor', 'profesores_id', 'curso_academico_id');
    }
}
