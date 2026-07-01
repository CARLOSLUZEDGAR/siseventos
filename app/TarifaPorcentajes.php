<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TarifaPorcentajes extends Model
{
    protected $table = 'tarifa_porcentajes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'tarifa_id',
        'porcentaje',
        'vigencia',
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
