<?php

namespace App\Http\Controllers;

use App\TipoEventos;
use Illuminate\Http\Request;

class TipoEventosController extends Controller
{
    public function CrearTipoEvento(Request $request)
    {

    }

    public function ListarTipoEvento(Request $request)
    {
        $tipo_evento = TipoEventos::select('id', 'evento', 'estado')
                        ->where('estado', 1)
                        ->orderBy('id', 'asc')
                        ->get();

        return ['tipo_eventos' => $tipo_evento];
    }
}
