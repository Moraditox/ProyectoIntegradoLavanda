<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ciclosDisponibles extends Model
{
    protected $table = 'ciclos_disponibles';

    // Relación N:N con la tabla convocatorias
    public function convocatorias()
    {
        return $this->belongsToMany('App\Models\Convocatorias', 'convocatoria_ciclo', 'ciclo_nombre', 'convocatoria_id');
    }
}
