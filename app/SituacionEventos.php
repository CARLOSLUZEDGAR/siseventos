<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SituacionEventos extends Model
{
    protected $table = 'situacion_eventos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'evento_id',
        'situacion_id',
        'forma_pago',
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
