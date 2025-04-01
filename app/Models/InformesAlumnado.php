<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformesAlumnado extends Model
{
    use HasFactory;

    protected $fillable = [ "alumno",
                            "curso",
                            "trimestre",
                            "ciclo_formativo",
                            "centro_trabajo",
                            "tutor_trabajo",
                            "profesor_seguimiento",
                            "posibilidades_formativas",
                            "cumplimiento_programa",
                            "seguimiento_tutor_trabajo",
                            "seguimiento_profesor",
                            "posibilidades_laborales",
                            "adecuacion_formacion",
                            "nivel_satisfaccion",
                            "valoracion_general",
                            "aspectos_mejorar",
                            "aspectos_destacar",
                            "empresa_id" ];
    
    protected $table = "informes_alumnado";

    public function __construct(array $attributes = []){
        parent::__construct($attributes);       
    }

}