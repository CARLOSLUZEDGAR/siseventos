<?php

namespace App\Http\Controllers;

use App\TarifaPorcentajes;
use Illuminate\Http\Request;

class TarifaPorcentajesController extends Controller
{
    public function CrearTarifaPorcentaje(Request $request)
    {

    }

    public function ListarTarifaPorcentaje(Request $request)
    {
        $tarifa_porcentaje = TarifaPorcentajes::select('id', 'tarifa_id', 'porcentaje', 'vigencia', 'estado')
                        ->where('estado', 1)
                        ->where('vigencia', 1)
                        ->orderBy('id', 'asc')
                        ->get();

        return ['tarifa_porcentaje' => $tarifa_porcentaje];
    }
}
