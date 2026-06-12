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
        try {
            // VALIDACIONES
            $request->validate([
                'fecha_evento'  => 'required',
                'predio_id'     => 'required',
                'responsable'   => 'required',
                'ci'            => 'required',
                'celular'       => 'required',
                'tipo_evento_id'=> 'required',
                'tarifa_id'     => 'required',
                'situacion_id'  => 'required',
                'forma_pago'    => 'required',
                'monto'         => 'required'
            ]);
            // VERIFICAR SI EL SALÓN YA ESTÁ OCUPADO
            $existe = DB::table('eventos')
                        ->whereDate('fecha_evento', $request->fecha_evento)
                        ->where('predio_id', $request->predio_id)
                        ->where('estado', 1)
                        ->exists();
            if ($existe) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'El salón ya se encuentra ocupado en la fecha seleccionada'
                ], 200);
            }
            
            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            // REGISTRAR EVENTO
            $evento = Eventos::create([
                'contratante'    => $request->responsable,
                'ci'             => $request->ci,
                'celular'        => $request->celular,
                'predio_id'      => $request->predio_id,
                'tipo_evento_id' => $request->tipo_evento_id,
                'tarifa_id'      => $request->tarifa_id,
                'fecha_evento'   => $request->fecha_evento,
                'observacion'    => $request->observacion,
                'estado'         => 1,
                'sysuser'        => Auth::user()->id
            ]);
            // REGISTRAR SITUACIÓN
            $situacion_evento = SituacionEventos::create([
                'evento_id'    => $evento->id,
                'situacion_id' => $request->situacion_id,
                'forma_pago'   => $request->forma_pago,
                'monto'        => $request->monto,
                'observacion'  => $request->observacion,
                'estado'       => 1,
                'sysuser'      => Auth::user()->id
            ]);
            // CONFIRMAR
            DB::commit();

            return response()->json([
                'success' => true,
                'mensaje' => 'Evento registrado correctamente',
                'evento' => $evento,
                'situacion_evento' => $situacion_evento
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al registrar el evento',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function EditarEvento(Request $request)
    {
        try {
            // VALIDACIONES
            $request->validate([
                'id_evento'      => 'required',
                'fecha_evento'   => 'required',
                'predio_id'      => 'required',
                // 'responsable'    => 'required',
            ]);
            // VERIFICAR SI EL SALÓN YA ESTÁ OCUPADO
            $existe = DB::table('eventos')
                        ->whereDate('fecha_evento', $request->fecha_evento)
                        ->where('predio_id', $request->predio_id)
                        ->where('id', '!=', $request->id_evento) // IGNORA EL MISMO EVENTO
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
                ->where('id', $request->id_evento)
                ->update([
                    'fecha_evento' => $request->fecha_evento,
                    'predio_id'    => $request->predio_id,
                    // 'contratante'  => $request->responsable,
                    'observacion'  => $request->observacion,
                    'sysuser'      => Auth::user()->id,
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

    public function PagarSaldoEvento(Request $request)
    {
        try {
            // VALIDACIONES
            $request->validate([
                'id_evento'     => 'required',
                'precio'        => 'required',
                'adelanto'      => 'required',
                // 'responsable'    => 'required',
            ]);
            $pago_saldo = $request->precio - $request->adelanto;
            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            $situacion_evento = SituacionEventos::create([
                'evento_id'    => $request->id_evento,
                'situacion_id' => 2,
                'forma_pago'   => 'TRANSFERENCIA',
                'monto'        =>  $pago_saldo,
                'observacion'  => 'PAGO TOTAL',
                'estado'       => 1,
                'sysuser'      => Auth::user()->id
            ]);
            // CONFIRMAR
            DB::commit();

            return response()->json([
                'success' => true,
                'mensaje' => 'Evento pagado correctamente',
                'situacion_evento' => $situacion_evento
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al efectuar el pago del evento',
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
                    ->join('situacion_eventos as se', 'e.id', 'se.evento_id')
                    ->join('situaciones as s', 'se.situacion_id', 's.id')
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
                            't.precio',
                            'e.fecha_evento',
                            'e.observacion',
                            's.id as situacion_id',
                            's.situacion',
                            'se.monto')
                    ->where('e.estado', 1)
                    ->where('p.estado', 1)
                    ->where('te.estado', 1)
                    ->where('t.estado', 1)
                    ->where('se.estado', 1)
                    ->where('s.estado', 1)
                    ->where('e.id', $request->evento_id)
                    ->orderBy('se.created_at', 'desc')
                    ->first();

        $situacion_evento = DB::table('situacion_eventos as se')
                            ->join('situaciones as s', 'se.situacion_id', 's.id')
                            ->select('s.situacion',
                                    'se.monto')
                            ->where('se.estado', 1)
                            ->where('s.estado', 1)
                            ->where('se.evento_id', $request->evento_id)
                            ->orderBy('se.situacion_id', 'asc')
                            ->get();

        return ['eventos' => $evento, 'situacion_evento' => $situacion_evento];
    }
}
