<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfertaPlaza extends Model
{
    use HasFactory;

    protected $table = 'oferta_plazas';

    protected $fillable = [
        'id',
        'relacion_convocatoria_empresa_id',
        'especialidad',
        'plazas',
        'observaciones',
        'perfil',
        'tareas'
    ];

    // Relación 1:N con la tabla convocatoria_empresas
    public function convocatoriaEmpresa()
    {
        return $this->belongsTo('App\Models\Convocatoria_Empresa', 'relacion_convocatoria_empresa_id', 'id');
    }
}
