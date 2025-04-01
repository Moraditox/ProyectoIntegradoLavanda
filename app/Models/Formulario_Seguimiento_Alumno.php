<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulario_Seguimiento_Alumno extends Model
{
    use HasFactory;

    static $rules = [
        'correo' => 'required',
        'empresa_id' => 'required',
        'ciclo' => 'required',
        'alumnado_id' => 'required',
        'actividades_formativas_y_tareas_que_realiza_la_empresa' => 'required',
        'actividades_y_tareas_que_estas_realizando_en_la_empresa' => 'required',
        'posibilidades_formativas_que_ofrece_la_empresa' => 'required',
        'cumplimiento_del_programa_formativo_por_parte_de_la_empresa' => 'required',
        'seguimiento_realizado_por_el_tutor_del_centro_de_trabajo' => 'required',
        'seguimiento_hecho_por_tu_profesor' => 'required',
        'adecuacion__formacion_recibida_en_centro_docente_con_practicas' => 'required',
        'integracion_en_el_entorno_laboral' => 'required',
        'observaciones' => 'required',
        'sugerencias_de_mejora' => 'required',
        'valoracion_general_de_las_practicas' => 'required'
    ];

    protected $fillable = [
        'correo', 'empresa_id', 'ciclo', 'alumnado_id', 'actividades_formativas_y_tareas_que_realiza_la_empresa',
        'actividades_y_tareas_que_estas_realizando_en_la_empresa', 'posibilidades_formativas_que_ofrece_la_empresa',
        'cumplimiento_del_programa_formativo_por_parte_de_la_empresa', 'seguimiento_realizado_por_el_tutor_del_centro_de_trabajo',
        'seguimiento_hecho_por_tu_profesor', 'adecuacion__formacion_recibida_en_centro_docente_con_practicas',
        'integracion_en_el_entorno_laboral', 'observaciones', 'sugerencias_de_mejora', 'valoracion_general_de_las_practicas'
    ];

    protected $table = 'formulario_seguimiento_alumno';

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
