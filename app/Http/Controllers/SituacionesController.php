<?php

namespace App\Http\Controllers;

use App\Situaciones;
use Illuminate\Http\Request;

class SituacionesController extends Controller
{
    public function CrearSituacion(Request $request)
    {

    }

    public function ListarSituacion(Request $request)
    {
        $situacion = Situaciones::select('id', 'situacion', 'estado')
                        ->where('estado', 1)
                        ->orderBy('id', 'asc')
                        ->get();

        return ['situaciones' => $situacion];
    }
}
