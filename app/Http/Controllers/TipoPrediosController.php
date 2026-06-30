<?php

namespace App\Http\Controllers;

use App\TipoPredios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TipoPrediosController extends Controller
{
    public function CrearTipoPredio(Request $request)
    {
        try {
            // VALIDACIONES
            $request->validate([
                'clasificacion'      => 'required'
            ]);
            // VERIFICAR SI EXISTE
            $existe = DB::table('tipo_predios')
                ->where('clasificacion', $request->clasificacion)
                ->where('estado', 1)
                ->exists();

            if ($existe) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'La clasificación ya se encuentra registrada'
                ], 200);
            }
            
            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            // REGISTRAR CLASIFICACION
            $clasificacion = TipoPredios::create([
                'clasificacion'     => $request->clasificacion,
                'observacion'       => $request->observacion,
                'estado'            => 1,
                'sysuser'           => Auth::user()->id
            ]);
            // CONFIRMAR
            DB::commit();

            return response()->json([
                'success'       => true,
                'mensaje'       => 'Clasificación registrada correctamente',
                'clasificacion' => $clasificacion,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al registrar la clasificación',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function EditarTipoPredio(Request $request)
    {
        try {
            // VALIDACIONES
            $request->validate([
                'id_clasificacion'  => 'required',
                'clasificacion'     => 'required',
            ]);
            // VERIFICAR SI EXISTE
            // $existe = DB::table('tipo_predios')
            //     ->where('clasificacion', $request->clasificacion)
            //     ->where('estado', 1)
            //     ->exists();

            // if ($existe) {
            //     return response()->json([
            //         'success' => false,
            //         'mensaje' => 'La clasificación ya se encuentra registrada'
            //     ], 200);
            // }
            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            // EDITAR CLASIFICACION
            DB::table('tipo_predios')
                ->where('id', $request->id_clasificacion)
                ->update([
                    'clasificacion'    => $request->clasificacion,
                    'observacion'      => $request->observacion,
                    'sysuser'          => Auth::user()->id,
                    'updated_at'       => now()
                ]);
            // CONFIRMAR
            DB::commit();

            return response()->json([
                'success' => true,
                'mensaje' => 'Clasificación editado correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al editar la clasificación',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function CambiarEstadoTipoPredio(Request $request)
    {
        try {
            // VALIDACIONES
            $request->validate([
                'id_clasificacion'  => 'required',
                'estado'            => 'required',
            ]);
            // VERIFICAR SI EXISTE
            // $existe = DB::table('tipo_predios')
            //     ->where('clasificacion', $request->clasificacion)
            //     ->where('estado', 1)
            //     ->exists();

            // if ($existe) {
            //     return response()->json([
            //         'success' => false,
            //         'mensaje' => 'La clasificación ya se encuentra registrada'
            //     ], 200);
            // }
            $estado = 1 - $request->estado;
            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            // EDITAR CLASIFICACION
            DB::table('tipo_predios')
                ->where('id', $request->id_clasificacion)
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
                    'mensaje' => 'Clasificación deshabilitado correctamente'
                ], 200);
            }else {
                return response()->json([
                    'success' => true,
                    'mensaje' => 'Clasificación habilitado correctamente'
                ], 200);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al cambiar de estado la clasificación',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function ListarTipoPredio(Request $request)
    {
        $tipo_predio = TipoPredios::select('id', 
                            'clasificacion',
                            'estado')
                    ->where('estado', 1)
                    ->orderBy('id', 'asc')
                    ->get();

        return ['tipo_predios' => $tipo_predio];
    }

    public function BuscarTipoPredio(Request $request)
    {
        if ($request->buscar == '') {
            $tipo_predio = DB::table('tipo_predios')
                    ->select('id', 
                            'clasificacion',
                            'observacion',
                            'estado')
                    ->orderBy('id', 'asc')
                    ->paginate(10);
        } else {
            $tipo_predio = DB::table('tipo_predios')
                    ->select('id', 
                            'clasificacion',
                            'observacion',
                            'estado')
                    ->where('clasificacion','LIKE','%'.$request->buscar.'%')
                    ->orderBy('id', 'asc')
                    ->paginate(10);
        }
        
        return response()->json([
            'pagination' => [
                'total'         => $tipo_predio->total(),
                'current_page'  => $tipo_predio->currentPage(),
                'per_page'      => $tipo_predio->perPage(),
                'last_page'     => $tipo_predio->lastPage(),
                'from'          => $tipo_predio->firstItem(),
                'to'            => $tipo_predio->lastItem(),
            
            ],
            'tipo_predios' => $tipo_predio
        ]); 
    }

    public function MostrarTipoPredio(Request $request)
    {
        $tipo_predios = TipoPredios::select('id', 'clasificacion', 'observacion')
                        ->where('id', $request->id)
                        ->first();

        return ['tipo_predios' => $tipo_predios];
    }
}
