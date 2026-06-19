<?php

namespace App\Http\Controllers;

use App\Predios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrediosController extends Controller
{
    public function CrearPredio(Request $request)
    {

    }

    public function ListarPredio(Request $request)
    {
        $predio = DB::table('predios as p')
                    ->join('predio_costos as pc', 'p.id', 'pc.predio_id')
                    ->select('p.id', 
                            'p.nombre', 
                            'p.color', 
                            'p.estado',
                            'pc.precio')
                    ->where('p.estado', 1)
                    ->where('pc.estado', 1)
                    ->orderBy('p.id', 'asc')
                    ->get();

        return ['predios' => $predio];
    }
}
