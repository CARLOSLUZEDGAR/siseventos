<?php

namespace App\Http\Controllers;

use App\PredioCostos;
use Illuminate\Http\Request;

class PredioCostosController extends Controller
{
    public function CrearPredioCosto(Request $request)
    {

    }

    public function ListarPredioCosto(Request $request)
    {
        $predio_costo = PredioCostos::select('id', 'predio_id', 'precio', 'vigencia', 'estado')
                        ->where('estado', 1)
                        ->where('vigencia', 1)
                        ->orderBy('id', 'asc')
                        ->get();

        return ['predio_costo' => $predio_costo];
    }
}
