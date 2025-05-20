<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convocatoria_Empresa extends Model
{
    use HasFactory;

    protected $table = 'convocatoria_empresa';

    static $rules = [
        'convocatoria_id' => 'required',
        'empresa_id' => 'required',
        'alumno_referencia_id' => 'nullable',
        'profesor_referencia_id' => 'nullable',
        'observaciones' => 'nullable'

    ];

    protected $fillable = ['convocatoria_id', 'empresa_id', 'alumno_referencia_id', 'profesor_referencia_id', 'observaciones'];

    // Relación 1:N con la tabla convocatorias
    public function convocatorias()
    {
        return $this->belongsTo('App\Models\Convocatorias', 'convocatoria_id', 'id');
    }

    // Relación 1:N con la tabla empresas
    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa', 'empresa_id', 'id');
    }

    // Relación 1:N con oferta_plazas
    public function ofertaPlazas()
    {
        return $this->hasMany('App\Models\OfertaPlaza', 'relacion_convocatoria_empresa_id', 'id');
    }

}
