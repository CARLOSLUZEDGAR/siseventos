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
        'salon_id', 
        'tipo_evento_id', 
        'tarifa_id', 
        'fecha_evento',
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
