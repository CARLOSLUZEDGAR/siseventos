<?php

namespace App\Http\Controllers;

use App\Situaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SituacionesController extends Controller
{
    public function CrearSituacion(Request $request)
    {
        {
            try {
                // VALIDACIONES
                $request->validate([
                    'situacion'       => 'required',
                ]);
                // VERIFICAR SI LA SITUACION YA EXISTE
                $existeSituacion = DB::table('situaciones')
                    ->where('situacion', $request->situacion)
                    // ->where('estado', 1)
                    ->exists();

                if ($existeSituacion) {
                    return response()->json([
                        'success' => false,
                        'mensaje' => 'La situación ya se encuentra registrada.'
                    ], 200);
                }
                
                // INICIAR TRANSACCIÓN
                DB::beginTransaction();
                // REGISTRAR SITUACION         
                $situacion = Situaciones::create([
                    'situacion'     => $request->situacion,
                    'observacion'   => $request->observacion,
                    'estado'        => 1,
                    'sysuser'       => Auth::user()->id
                ]);

                // CONFIRMAR
                DB::commit();

                return response()->json([
                    'success'       => true,
                    'mensaje'       => 'Situación registrada correctamente',
                    'situacion'        => $situacion
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'mensaje' => 'Ocurrió un error al registrar la situacion',
                    'error'   => $e->getMessage()
                ], 500);
            }
        }
    }

    public function EditarSituacion(Request $request)
    {
        try {

            // VALIDACIONES
            $request->validate([
                'id_situacion'   => 'required',
                'situacion'      => 'required',
            ]);

            // VERIFICAR SI LA SITUACION YA EXISTE
            $existeNombre = DB::table('situaciones')
                ->where('situacion', $request->situacion)
                ->where('id', '!=', $request->id_situacion)
                // ->where('estado', 1)
                ->exists();

            if ($existeNombre) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'La situación ya se encuentra registrada.'
                ], 200);
            }

            // INICIAR TRANSACCIÓN
            DB::beginTransaction();

            // EDITAR SITUACION
                DB::table('situaciones')
                    ->where('id', $request->id_situacion)
                    ->update([
                    'situacion'   => $request->situacion,
                    'observacion' => $request->observacion,
                    'sysuser'     => Auth::user()->id,
                    'updated_at'  => now()
                ]);

            // CONFIRMAR TRANSACCIÓN
            DB::commit();

            return response()->json([
                'success' => true,
                'mensaje' => 'Situación editada correctamente.'
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al editar la situación.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function CambiarEstadoSituacion(Request $request)
    {
        try {
            // VALIDACIONES
            $request->validate([
                'id_situacion'  => 'required',
                'estado'     => 'required',
            ]);
        
            $estado = 1 - $request->estado;
            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            // EDITAR EVENTO
            DB::table('situaciones')
                ->where('id', $request->id_situacion)
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
                    'mensaje' => 'Situación deshabilitada correctamente'
                ], 200);
            }else {
                return response()->json([
                    'success' => true,
                    'mensaje' => 'Situación habilitada correctamente'
                ], 200);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al cambiar de estado la situación',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function ListarSituacion(Request $request)
    {
        $situacion = Situaciones::select('id', 'situacion', 'estado')
                        ->where('estado', 1)
                        ->orderBy('id', 'asc')
                        ->get();

        return ['situaciones' => $situacion];
    }

    public function ListarSituacionEvento(Request $request)
    {
        $idevento = $request->idevento;
        $idtarifa = $request->idtarifa;
        // Extraer todas las situciones de un evento
        $situacion_evento = DB::table('situacion_eventos')
                        ->where('evento_id', $idevento)
                        ->orderBy('situacion_id', 'asc')
                        ->pluck('situacion_id');

        // Si existe la situación id = 2, también excluir la situacion id = 1
        if ($situacion_evento->contains(2)) {
            $situacion_evento->push(1);
        }

        if ($idtarifa != 1) {
            // Obtener las todo los tipos de situaciones excluyendo las situaciones que ya tiene un evento
            $situacion = Situaciones::select('id', 'situacion', 'estado')
                            ->where('estado', 1)
                            ->whereNotIn('id', $situacion_evento)
                            ->orderBy('id', 'asc')
                            ->get();

            $cantidad = $situacion->count();
        } else {
            $situacion = (object) [
                'id' => null,
                'situacion' => null,
                'estado' => null
            ];

            $cantidad = 0;
        }
        

        return ['situaciones' => $situacion, 'cantidadsituacion' => $cantidad];
    }

    public function BuscarSituacion(Request $request)
    {
        if ($request->buscar == '') {
            $situacion = DB::table('situaciones')
                        ->select('id', 
                                'situacion',  
                                'estado',
                                'observacion')
                        ->orderBy('id', 'asc')
                        ->paginate(10);
        } else {
            $situacion = DB::table('situaciones')
                        ->select('id', 
                                'situacion',  
                                'estado',
                                'observacion')
                        ->where('situacion','LIKE','%'.$request->buscar.'%')
                        ->orderBy('id', 'asc')
                        ->paginate(10);
        }
        
        return response()->json([
            'pagination' => [
                'total'         => $situacion->total(),
                'current_page'  => $situacion->currentPage(),
                'per_page'      => $situacion->perPage(),
                'last_page'     => $situacion->lastPage(),
                'from'          => $situacion->firstItem(),
                'to'            => $situacion->lastItem(),
            
            ],
            'situaciones' => $situacion
        ]); 
    }

    public function MostrarSituacion(Request $request)
    {
        $situacion = DB::table('situaciones')
                    ->select('id', 
                            'situacion',
                            'observacion')
                    ->where('id', $request->id)
                    ->where('estado', 1)
                    ->first();

        return ['situaciones' => $situacion];
    }
}
