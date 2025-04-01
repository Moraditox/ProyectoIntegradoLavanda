<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Empresa
 *
 * @property $id
 * @property $nombre
 * @property $descripcion
 * @property $cif
 * @property $direccion
 * @property $representante_legal
 * @property $email
 * @property $movil
 * @property $nif_representante_legal
 * @property $logo
 * @property $token
 * @property $created_at
 * @property $updated_at
 *
 * @property ConvocatoriaEmpresa[] $convocatoriaEmpresas
 * @property Informe[] $informes
 * @property Trabajadore[] $trabajadores
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Empresa extends Model
{

    static $rules = [
        'nombre' => 'required',
        "persona_contacto"=>"required",
        "correo_contacto"=>"required",
        "telefono_contacto"=>"required",
        'descripcion' => '',
        'cif' => '',
        'direccion' => '',
        'representante_legal' => '',
        'email' => '',
        'movil' => '',
        'nif_representante_legal' => '',
        'logo' => '',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nif_tutor_laboral','web','telefono_contacto','correo_contacto','persona_contacto','nombre', 'descripcion', 'cif', 'direccion', 'representante_legal', 'email', 'movil', 'nif_representante_legal', 'logo', 'localidad'];



    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function convocatoriaEmpresas()
    {
        return $this->hasMany('App\Models\Convocatoria_Empresa', 'empresa_id', 'id');
    }
    public function convocatorias()
    {
        return $this->hasMany('App\Models\Convocatoria_Empresa', 'empresa_id', 'id');
    }
    public function convocatoriaEmpresaPlazas()
    {
        return $this->hasMany(Convocatoria_Empresa_Plaza::class, 'empresa_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function formulario_seguimiento_alumno()
    {
        return $this->hasMany('App\Models\Formulario_Seguimiento_Alumno', 'empresa_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function formulario_seguimiento_empresa()
    {
        return $this->hasMany('App\Models\Formulario_Seguimiento_Empresa', 'empresa_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trabajadores()
    {
        return $this->hasMany('App\Models\Trabajadores', 'empresa_id', 'id');
    }
}
