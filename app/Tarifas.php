<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tarifas extends Model
{
    protected $table = 'tarifas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'tarifa', 
        'precio',
        'vigencia',
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
