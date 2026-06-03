<?php

namespace App\Http\Controllers;

use App\Eventos;
use App\SituacionEventos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventosController extends Controller
{
    public function CrearEvento(Request $request)
    {
        $fecha_evento = $request->fecha_evento;
        $predio_id = $request->predio;
        $responsable = $request->responsable;
        $tipo_evento_id = $request->tipo_evento;
        $tarifa_id = $request->tarifa;
        $situacion_id = $request->situacion;
        $forma_pago = $request->forma_pago;
        $observacion = $request->observacion;

        $evento = Eventos::create([
            'contratante' => $responsable, 
            'predio_id' => $predio_id,
            'tipo_evento_id' => $tipo_evento_id,
            'tarifa_id' => $tarifa_id,
            'fecha_evento' => $fecha_evento,
            'observacion' => $observacion,
            'estado' => 1,
            'sysuser' => Auth::user()->id
        ]);

        $situacion_evento = SituacionEventos::create([
            'evento_id' => $evento->id,
            'situacion_id' => $situacion_id,
            'forma_pago' => $forma_pago,
            'observacion' => $observacion,
            'estado' => 1,
            'sysuser' =>Auth::user()->id
        ]);
    }

    public function EditarEvento(Request $request)
    {
        try {

            // VALIDACIONES
            $request->validate([
                'id'             => 'required',
                'fecha_evento'   => 'required',
                'predio_id'      => 'required',
                'responsable'    => 'required',
            ]);

            // VERIFICAR SI EL SALÓN YA ESTÁ OCUPADO
            $existe = DB::table('eventos')
                        ->whereDate('fecha_evento', $request->fecha_evento)
                        ->where('predio_id', $request->predio_id)
                        ->where('id', '!=', $request->id) // IGNORA EL MISMO EVENTO
                        ->where('estado', 1)
                        ->exists();

            // SI EXISTE OTRO EVENTO
            if($existe){

                return response()->json([
                    'success' => false,
                    'mensaje' => 'El salón ya se encuentra ocupado en la fecha seleccionada'
                ], 200);

            }

            // INICIAR TRANSACCIÓN
            DB::beginTransaction();

            // EDITAR EVENTO
            DB::table('eventos')
                ->where('id', $request->id)
                ->update([
                    'fecha_evento' => $request->fecha_evento,
                    'predio_id'    => $request->predio_id,
                    'contratante'  => $request->responsable,
                    'updated_at'   => now()
                ]);

            // CONFIRMAR
            DB::commit();

            return response()->json([
                'success' => true,
                'mensaje' => 'Evento editado correctamente'
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al editar el evento',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function ListarEvento(Request $request)
    {
        $evento = DB::table('eventos as e')
                    ->join('predios as p', 'e.predio_id', 'p.id')
                    ->join('tipo_eventos as te', 'e.tipo_evento_id', 'te.id')
                    ->join('tarifas as t', 'e.tarifa_id', 't.id')
                    ->select('e.id',
                            'e.predio_id',
                            'e.contratante',
                            'p.nombre',
                            'p.color',
                            'te.evento',
                            't.tarifa',
                            'e.fecha_evento')
                    ->where('e.estado', 1)
                    ->where('p.estado', 1)
                    ->where('te.estado', 1)
                    ->where('t.estado', 1)
                    ->whereYear('e.fecha_evento', $request->anio)
                    ->whereMonth('e.fecha_evento', $request->mes)
                    ->orderBy('e.fecha_evento', 'asc')
                    ->get();

        return ['eventos' => $evento];
    }

    public function MostrarEvento(Request $request)
    {
        $evento = DB::table('eventos as e')
                    ->join('predios as p', 'e.predio_id', 'p.id')
                    ->join('tipo_eventos as te', 'e.tipo_evento_id', 'te.id')
                    ->join('tarifas as t', 'e.tarifa_id', 't.id')
                    ->select('e.id',
                            'e.predio_id',
                            'p.nombre',
                            'e.contratante',
                            'p.nombre',
                            'p.color',
                            'e.tipo_evento_id',
                            'te.evento',
                            'e.tarifa_id',
                            't.tarifa',
                            'e.fecha_evento',
                            'e.observacion')
                    ->where('e.estado', 1)
                    ->where('p.estado', 1)
                    ->where('te.estado', 1)
                    ->where('t.estado', 1)
                    ->where('e.id', $request->evento_id)
                    // ->whereYear('e.fecha_evento', $request->anio)
                    // ->whereMonth('e.fecha_evento', $request->mes)
                    // ->orderBy('e.fecha_evento', 'asc')
                    ->first();

        return ['eventos' => $evento];
    }
}
