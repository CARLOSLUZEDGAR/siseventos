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
                'fecha_evento'      => 'required',
                'fecha_evento_fin'  => 'required',
                'hora_inicio'       => 'required',
                'hora_fin'          => 'required',
                'tipo_predio_id'    => 'required',
                'predio_id'         => 'required',
                'responsable'       => 'required',
                'ci'                => 'required',
                'celular'           => 'required',
                'tipo_evento_id'    => 'required',
                'tarifa_id'         => 'required',
                'situacion_id'      => 'required',
                'forma_pago'        => 'required',
                'monto'             => 'required'
            ]);
            // VERIFICAR SI EL SALÓN YA ESTÁ OCUPADO
            $inicioNuevo = $request->fecha_evento . ' ' . $request->hora_inicio;
            $finNuevo    = $request->fecha_evento_fin . ' ' . $request->hora_fin;
            $existe = DB::table('eventos')
                ->where('predio_id', $request->predio_id)
                ->where('estado', 1)
                ->where(function ($query) use ($inicioNuevo, $finNuevo) {
                    $query->whereRaw(
                        "(fecha_evento || ' ' || hora_inicio)::timestamp < ?",
                        [$finNuevo]
                    )
                    ->whereRaw(
                        "(fecha_evento_fin || ' ' || hora_fin)::timestamp > ?",
                        [$inicioNuevo]
                    );
                })
                ->exists();

            if ($existe) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'El salón ya se encuentra ocupado en el rango de fecha y hora seleccionado'
                ], 200);
            }
            
            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            // REGISTRAR EVENTO
            $evento = Eventos::create([
                'contratante'       => $request->responsable,
                'ci'                => $request->ci,
                'celular'           => $request->celular,
                'tipo_predio_id'    => $request->tipo_predio_id,
                'predio_id'         => $request->predio_id,
                'tipo_evento_id'    => $request->tipo_evento_id,
                'tarifa_id'         => $request->tarifa_id,
                'fecha_evento'      => $request->fecha_evento,
                'hora_inicio'       => $request->hora_inicio,
                'fecha_evento_fin'  => $request->fecha_evento_fin,
                'hora_fin'          => $request->hora_fin,
                'observacion'       => $request->observacion,
                'estado'            => 1,
                'sysuser'           => Auth::user()->id
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
                'id_evento'         => 'required',
                'predio_id'         => 'required',
                'fecha_evento'      => 'required',
                'hora_inicio'       => 'required',
                'fecha_evento_fin'  => 'required',
                'hora_fin'          => 'required'
                // 'observacion'    => 'required',
            ]);
            // VERIFICAR SI EL SALÓN YA ESTÁ OCUPADO
            $inicioNuevo = $request->fecha_evento . ' ' . $request->hora_inicio;
            $finNuevo    = $request->fecha_evento_fin . ' ' . $request->hora_fin;
            $existe = DB::table('eventos')
                ->where('predio_id', $request->predio_id)
                ->where('id', '!=', $request->id_evento)
                ->where('estado', 1)
                ->where(function ($query) use ($inicioNuevo, $finNuevo) {
                    $query->whereRaw(
                        "(fecha_evento || ' ' || hora_inicio)::timestamp < ?",
                        [$finNuevo]
                    )
                    ->whereRaw(
                        "(fecha_evento_fin || ' ' || hora_fin)::timestamp > ?",
                        [$inicioNuevo]
                    );
                })
                ->exists();

            if ($existe) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'El salón ya se encuentra ocupado en el rango de fecha y hora seleccionado'
                ], 200);
            }
            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            // EDITAR EVENTO
            DB::table('eventos')
                ->where('id', $request->id_evento)
                ->update([
                    // 'predio_id'        => $request->predio_id,
                    'fecha_evento'     => $request->fecha_evento,
                    'hora_inicio'      => $request->hora_inicio,
                    'fecha_evento_fin' => $request->fecha_evento_fin,
                    'hora_fin'         => $request->hora_fin,
                    'observacion'      => $request->observacion,
                    'sysuser'          => Auth::user()->id,
                    'updated_at'       => now()
                ]);

            DB::table('situacion_eventos')
                ->where('evento_id', $request->id_evento)
                ->update([
                    'observacion'      => $request->observacion,
                    'sysuser'          => Auth::user()->id,
                    'updated_at'       => now()
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
                'forma_pago'    => 'required'
                // 'responsable'    => 'required',
            ]);
            $pago_saldo = $request->precio - $request->adelanto;
            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            $situacion_evento = SituacionEventos::create([
                'evento_id'    => $request->id_evento,
                'situacion_id' => 2,
                'forma_pago'   => $request->forma_pago,
                'monto'        => $pago_saldo,
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
                            'e.fecha_evento',
                            'e.hora_inicio',
                            'e.hora_fin')
                    ->where('e.estado', 1)
                    // ->where('p.estado', 1)
                    // ->where('te.estado', 1)
                    // ->where('t.estado', 1)
                    ->whereYear('e.fecha_evento', $request->anio)
                    ->whereMonth('e.fecha_evento', $request->mes)
                    ->orderBy('e.fecha_evento', 'asc')
                    ->orderBy('e.hora_inicio', 'asc')
                    ->get();

        return ['eventos' => $evento];
    }

    public function MostrarEvento(Request $request)
    {
        $evento = DB::table('eventos as e')
                    ->join('predios as p', 'e.predio_id', 'p.id')
                    ->join('tipo_eventos as te', 'e.tipo_evento_id', 'te.id')
                    ->join('tarifas as t', 'e.tarifa_id', 't.id')
                    ->join('tarifa_porcentajes as tpor', 't.id', 'tpor.tarifa_id')
                    ->join('situacion_eventos as se', 'e.id', 'se.evento_id')
                    ->join('situaciones as s', 'se.situacion_id', 's.id')
                    ->join('predio_costos as pc', 'e.predio_id', 'pc.predio_id')
                    ->join('tipo_predios as tp', 'e.tipo_predio_id', 'tp.id')
                    ->select('e.id',
                            'e.predio_id',
                            'p.nombre',
                            'e.contratante',
                            'e.ci',
                            'e.celular',
                            'tp.clasificacion',
                            'p.nombre',
                            'p.color',
                            'e.tipo_evento_id',
                            'te.evento',
                            'e.tarifa_id',
                            't.tarifa',
                            'tpor.porcentaje',
                            'e.fecha_evento',
                            'e.fecha_evento_fin',
                            'e.hora_inicio',
                            'e.hora_fin',
                            'e.observacion',
                            's.id as situacion_id',
                            's.situacion',
                            'se.monto',
                            'pc.precio')
                    ->where('e.estado', 1)
                    // ->where('p.estado', 1)
                    // ->where('te.estado', 1)
                    // ->where('t.estado', 1)
                    ->where('tpor.estado', 1)
                    ->where('tpor.vigencia', 1)
                    ->where('se.estado', 1)
                    // ->where('s.estado', 1)
                    ->where('pc.estado', 1)
                    ->where('pc.vigencia', 1)
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
