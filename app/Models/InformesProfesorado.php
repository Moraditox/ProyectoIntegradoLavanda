<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformesProfesorado extends Model
{
    use HasFactory;

    protected $fillable = ["alumno",
        "curso",
        "trimestre",
        "ciclo_formativo",
        "centro_trabajo",
        "tutor_laboral",
        "profesor_seguimiento",
        "posibilidades_formativas",
        "cumplimiento_programa",
        "seguimiento_alumno",
        "apoyo_profesor",
        "posibilidades_laborales",
        "calidad_informes_tutor_trabajo",
        "nivel_satisfaccion",
        "valoracion_general",
        "aspectos_mejorar",
        "aspectos_destacar",
        "empresa_id"];

    protected $table = "informes_profesorado";

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
