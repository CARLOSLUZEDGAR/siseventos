<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    protected $table = 'eventos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'contratante', 
        'ci',
        'celular',
        'predio_id', 
        'tipo_evento_id', 
        'tarifa_id', 
        'fecha_evento',
        'hora_inicio',
        'hora_fin',
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
