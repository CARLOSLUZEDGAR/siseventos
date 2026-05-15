<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = 'personals';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'id_nacionalidad', 'per_paterno', 'per_materno', 'per_nombre', 'per_fecha_nacimiento',
        'per_sexo', 'per_ci', 'per_celular', 'per_mail', 'per_cm', 'per_foto', 'per_direccion', 'estado', 'sysuser'
    ];


    // public function subescalafon()
    // {
    //     return $this->belongsTo('App\Escalafon', 'id');
    // }

    // //DatosFamiliares
    // public function datos_familiares()
    // {
    //     return $this->hasMany('App\Datos_familiares'); // se hace la referencia a la entidad fuerte para utilizar sus atributos
    // }

    // //Datos_fisicos
    // public function datos_fisicos()
    // {
    //     return $this->hasMany('App\Datos_fisicos'); // se hace la referencia a la entidad fuerte para utilizar sus atributos
    // }
}
