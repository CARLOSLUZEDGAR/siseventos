<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoEventos extends Model
{
    protected $table = 'tipo_eventos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'evento',
        'clasificacion',
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
