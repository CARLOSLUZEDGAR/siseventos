<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Situaciones extends Model
{
    protected $table = 'situaciones';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'situacion', 
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
