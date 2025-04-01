<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Curso_Academico;
class Convocatoria_Empresa_Plaza extends Model
{
    protected $table = 'convocatoria_empresa_plazas';
    protected $fillable = ['empresa_id', 'convocatoria_id', 'ciclos_id', 'numero_plazas', 'perfil', 'tareas', 'observaciones'];

    // Definir la relación con la tabla 'empresas'
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
    public function cicloAcademico()
    {
        return $this->belongsTo('App\Models\Curso_Academico', 'ciclos_id', 'id');
    }
    public function ciclo()
    {
        return $this->belongsTo('App\Models\Ciclos', 'ciclos_id');
    }



}
