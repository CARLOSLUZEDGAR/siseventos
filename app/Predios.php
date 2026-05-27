<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Predios extends Model
{
    protected $table = 'predios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'nombre', 
        'abreviatura',
        'color',
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
