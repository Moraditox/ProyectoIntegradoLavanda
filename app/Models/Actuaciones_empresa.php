<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actuaciones_Empresa extends Model
{

    protected $table = 'actuaciones_empresa';

    protected $fillable = [
        'id_empresa',
        'id_profesor',
        'descripcion',
        'contacto',
    ];

    static $rules = [
        'id_empresa' => 'required',
        'id_profesor' => 'required',
        'descripcion' => 'required',
        'contacto' => 'required',
    ];

    // Relación 1:N con la tabla empresas
    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa', 'id_empresa', 'id');
    }

    // Relación 1:N con la tabla profesores
    public function profesor()
    {
        return $this->belongsTo('App\Models\Profesores', 'id_profesor', 'id');
    }
}