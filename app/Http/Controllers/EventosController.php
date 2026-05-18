<?php

namespace App\Http\Controllers;

use App\Eventos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventosController extends Controller
{
    public function CrearEvento(Request $request)
    {
        $responsable = $request->reponsable;
        $salon_id = $request->salon_id;
        $tipo_evento_id = $request->tipo_evento_id;
        $tarifa_id = $request->tarifa_id;
        $fecha_evento = $request->fecha_evento;
        $observacion = $request->observacion;

        $evento = Eventos::create([
            'contratante' => $responsable, 
            'salon_id' => $salon_id,
            'tipo_evento_id' => $tipo_evento_id,
            'tarifa_id' => $tarifa_id,
            'fecha_evento' => $fecha_evento,
            'observacion' => $observacion,
            'estado' => 1,
            'sysuser' => Auth::user()->id
        ]);
    }
}
