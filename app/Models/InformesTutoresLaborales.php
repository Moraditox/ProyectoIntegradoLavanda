<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformesTutoresLaborales extends Model
{
    use HasFactory;

    protected $fillable = ["alumno",
        "curso",
        "trimestre",
        "ciclo_formativo",
        "centro_trabajo",
        "tutor_laboral",
        "profesor_seguimiento",
        "competencias_profesionales",
        "competencias_organizativas",
        "competencias_relacionales",
        "respuesta_contingencias",
        "aspecto_1",
        "aspecto_2",
        "aspecto_3",
        "aspectos",
        "areas_puestos_actividades",
        "modificaciones",
        "empresa_id"];

    protected $table = "informes_tutores_laborales";

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
