<?php

namespace App\Http\Controllers;

use App\Predios;
use Illuminate\Http\Request;

class PrediosController extends Controller
{
    public function CrearPredio(Request $request)
    {

    }

    public function ListarPredio(Request $request)
    {
        $predio = Predios::select('id', 'nombre', 'color', 'estado')
                        ->where('estado', 1)
                        ->orderBy('id', 'asc')
                        ->get();

        return ['predios' => $predio];
    }
}
