<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulario_Seguimiento_Empresa extends Model
{
    use HasFactory;

    protected $table = 'formulario_seguimiento_empresa';

    static $rules = [
        'correo' => 'required',
        'empresa_id' => 'required',
        'ciclo' => 'required',
        'alumnado_id' => 'required',
        'competencias_profesionales' => 'required',
        'competencias_organizativas' => 'required',
        'competencias_relacionales' => 'required',
        'capacidad_de_respuesta_a_las_contingencias' => 'required',
        'capacidad_de_aprendizaje' => 'required',
        'cumplimiento_de_las_normas' => 'required',
        'actividades_formativas_y_tareas_realizadas_en_la_empresa' => 'required',
        'observaciones_sobre_competencias_profesionales' => 'required',
        'observaciones_sobre_competencias_personales_y_sociales' => 'required',
        'sugerencias_generales_de_mejora' => 'required',
    ];

    protected $fillable = [
        'correo', 'empresa_id', 'ciclo', 'alumnado_id', 'competencias_profesionales',
        'competencias_organizativas', 'competencias_relacionales', 'capacidad_de_respuesta_a_las_contingencias',
        'capacidad_de_aprendizaje', 'cumplimiento_de_las_normas', 'actividades_formativas_y_tareas_realizadas_en_la_empresa',
        'observaciones_sobre_competencias_profesionales', 'observaciones_sobre_competencias_personales_y_sociales',
        'sugerencias_generales_de_mejora'
    ];

    // Relación 1:N con la tabla alumnado
    public function alumnado()
    {
        return $this->belongsTo('App\Models\Alumnado', 'alumnado_id', 'id');
    }

    // Relación 1:N con la tabla empresas
    public function empresas()
    {
        return $this->belongsTo('App\Models\Empresas', 'empresa_id', 'id');
    }

    // Relación 1:1 con la tabla actuaciones
    public function actuaciones()
    {
        return $this->hasOne('App\Models\Actuaciones', 'informe_id', 'id');
    }
}
