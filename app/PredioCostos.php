<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PredioCostos extends Model
{
    protected $table = 'predio_costos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'predio_id', 
        'precio',
        'vigencia',
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
