<?php

namespace App\Http\Controllers;

use App\TipoEventos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TipoEventosController extends Controller
{
    public function CrearTipoEvento(Request $request)
    {
        {
            try {
                // VALIDACIONES
                $request->validate([
                    'tipo_evento'   => 'required',
                    'tipo_predio'   => 'required'
                ]);
                // VERIFICAR SI EL NOMBRE YA EXISTE
                $existeNombre = DB::table('tipo_eventos')
                    ->where('evento', $request->tipo_evento)
                    ->exists();

                if ($existeNombre) {
                    return response()->json([
                        'success' => false,
                        'mensaje' => 'El nombre del evento ya se encuentra registrado.'
                    ], 200);
                }
                
                // INICIAR TRANSACCIÓN
                DB::beginTransaction();
                // REGISTRAR TIPO EVENTO
                $tipo_evento = TipoEventos::create([
                    'evento'        => $request->tipo_evento,
                    'clasificacion' => $request->tipo_predio,
                    'observacion'   => $request->observacion,
                    'estado'        => 1,
                    'sysuser'       => Auth::user()->id
                ]);

                // CONFIRMAR
                DB::commit();

                return response()->json([
                    'success'       => true,
                    'mensaje'       => 'Evento registrado correctamente',
                    'tipo_evento'   => $tipo_evento
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
    }

    public function EditarTipoEvento(Request $request)
    {
        try {
            // VALIDACIONES
            $request->validate([
                'id_tipo_evento'  => 'required',
                'tipo_evento'     => 'required',
            ]);
            // VERIFICAR SI EL NOMBRE YA EXISTE
            $existeNombre = DB::table('tipo_eventos')
                ->where('evento', $request->tipo_evento)
                ->where('id', '!=', $request->id_tipo_evento)
                ->exists();

            if ($existeNombre) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'El nombre del evento ya se encuentra registrado.'
                ], 200);
            }

            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            // EDITAR EVENTO
            DB::table('tipo_eventos')
                ->where('id', $request->id_tipo_evento)
                ->update([
                    'evento'        => $request->tipo_evento,
                    'observacion'   => $request->observacion,
                    'sysuser'       => Auth::user()->id,
                    'updated_at'    => now()
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

    public function CambiarEstadoTipoEvento(Request $request)
    {
        try {
            // VALIDACIONES
            $request->validate([
                'id_tipo_evento'  => 'required',
                'estado'          => 'required',
            ]);
            
            $estado = 1 - $request->estado;
            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            // EDITAR EVENTO
            DB::table('tipo_eventos')
                ->where('id', $request->id_tipo_evento)
                ->update([
                    'estado'        => $estado,
                    'sysuser'       => Auth::user()->id,
                    'updated_at'    => now()
                ]);
            // CONFIRMAR
            DB::commit();

            if($request->estado == 1) {
                return response()->json([
                    'success' => true,
                    'mensaje' => 'Evento deshabilitado correctamente'
                ], 200);
            }else {
                return response()->json([
                    'success' => true,
                    'mensaje' => 'Evento habilitado correctamente'
                ], 200);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al cambiar de estado al evento',
                'error'   => $e->getMessage()
            ], 500);
        }
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

    public function BuscarTipoEvento(Request $request)
    {
        if ($request->buscar == '') {
            $tipo_evento = DB::table('tipo_eventos as te')
                    ->join('tipo_predios as tp', 'te.clasificacion', 'tp.id')
                    ->select('te.id', 
                            'te.evento',  
                            'te.estado',
                            'tp.id as id_tipo_predio',
                            'tp.clasificacion',
                            'te.observacion')
                    ->where('tp.estado', 1)
                    ->orderBy('tp.id', 'asc')
                    ->orderBy('te.id', 'asc')
                    ->paginate(10);
        } else {
            $tipo_evento = DB::table('tipo_eventos as te')
                    ->join('tipo_predios as tp', 'te.clasificacion', 'tp.id')
                    ->select('te.id', 
                            'te.evento',  
                            'te.estado',
                            'tp.id as id_tipo_predio',
                            'tp.clasificacion',
                            'te.observacion')
                    ->where('tp.estado', 1)
                    ->where('te.evento','LIKE','%'.$request->buscar.'%')
                    ->orderBy('tp.id', 'asc')
                    ->orderBy('te.id', 'asc')
                    ->paginate(10);
        }
        
        return response()->json([
            'pagination' => [
                'total'         => $tipo_evento->total(),
                'current_page'  => $tipo_evento->currentPage(),
                'per_page'      => $tipo_evento->perPage(),
                'last_page'     => $tipo_evento->lastPage(),
                'from'          => $tipo_evento->firstItem(),
                'to'            => $tipo_evento->lastItem(),
            
            ],
            'tipo_eventos' => $tipo_evento
        ]); 
    }

    public function MostrarTipoEvento(Request $request)
    {
        $tipo_evento = DB::table('tipo_eventos as te')
                        ->join('tipo_predios as tp', 'te.clasificacion', 'tp.id')
                        ->select('te.id', 
                                'te.evento',
                                'tp.clasificacion',
                                'te.observacion')
                        ->where('te.id', $request->id)
                        ->first();

        return ['tipo_eventos' => $tipo_evento];
    }
}
