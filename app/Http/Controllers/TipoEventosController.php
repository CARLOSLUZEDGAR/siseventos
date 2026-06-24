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
        $tipo_evento = TipoEventos::select('id', 'evento', 'clasificacion', 'estado')
                        ->where('estado', 1)
                        ->where('clasificacion', $request->tipo_predio_id)
                        ->orderBy('id', 'asc')
                        ->get();

        return ['tipo_eventos' => $tipo_evento];
    }
}
