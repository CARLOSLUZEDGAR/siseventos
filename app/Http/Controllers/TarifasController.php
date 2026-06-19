<?php

namespace App\Http\Controllers;

use App\Tarifas;
use Illuminate\Http\Request;

class TarifasController extends Controller
{
    public function CrearTarifa(Request $request)
    {

    }

    public function ListarTarifa(Request $request)
    {
        $tarifa = Tarifas::select('id', 'tarifa', 'porcentaje', 'vigencia', 'estado')
                        ->where('estado', 1)
                        ->orderBy('id', 'asc')
                        ->get();

        return ['tarifas' => $tarifa];
    }
}
