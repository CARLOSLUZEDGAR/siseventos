<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoPrediosController extends Controller
{
    public function CrearTipoPredio(Request $request)
    {

    }

    public function ListarTipoPredio(Request $request)
    {
        $tipo_predio = DB::table('tipo_predios as tp')
                    ->select('tp.id', 
                            'tp.clasificacion')
                    ->where('tp.estado', 1)
                    ->orderBy('tp.id', 'asc')
                    ->get();

        return ['tipo_predios' => $tipo_predio];
    }
}
