<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'nombres', 'ap_paterno', 'ap_materno', 'email', 'estado', 'sysuser'

    ];
}
