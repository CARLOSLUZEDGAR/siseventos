<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPredios extends Model
{
    protected $table = 'tipo_predios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'clasificacion',
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
