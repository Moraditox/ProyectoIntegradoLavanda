<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajadores extends Model
{
    use HasFactory;

    protected $table = 'trabajadores';

    static $rules = [
        'apellido1' => 'required',
        'apellido2' => 'required',
        'nombre' => 'required',
        'nif' => 'required',
        'email' => 'required',
        'movil' => 'required',
        'rol' => 'required',
        'empresa_id' => 'required',
    ];

    protected $fillable = ['apellido1', 'apellido2', 'nombre', 'nif', 'email', 'movil', 'rol', 'empresa_id'];

    // Relación 1:N con la tabla empresas
    public function empresas()
    {
        return $this->belongsTo('App\Models\Empresa', 'empresa_id', 'id');
    }

    // Relación 1:N con la tabla asignaciones
    public function asignaciones()
    {
        return $this->hasMany('App\Models\Asignaciones', 'trabajadores_id', 'id');
    }
}
