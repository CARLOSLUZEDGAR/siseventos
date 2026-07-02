<?php

namespace App\Http\Controllers;

use App\TarifaPorcentajes;
use App\Tarifas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TarifasController extends Controller
{
    public function CrearTarifa(Request $request)
    {
         {
            try {
                // VALIDACIONES
                $request->validate([
                    'tarifa'       => 'required',
                    'porcentaje'   => 'required'
                ]);
                // VERIFICAR SI LA TARIFA YA EXISTE
                $existeTarifa = DB::table('tarifas')
                    ->where('tarifa', $request->tarifa)
                    // ->where('estado', 1)
                    ->exists();

                if ($existeTarifa) {
                    return response()->json([
                        'success' => false,
                        'mensaje' => 'La tarifa ya se encuentra registrada.'
                    ], 200);
                }

                // INICIAR TRANSACCIÓN
                DB::beginTransaction();
                // REGISTRAR TARIFA         
                $tarifa = Tarifas::create([
                    'tarifa'        => $request->tarifa,
                    'observacion'   => $request->observacion,
                    'estado'        => 1,
                    'sysuser'       => Auth::user()->id
                ]);

                TarifaPorcentajes::create([
                    'tarifa_id'     => $tarifa->id,
                    'porcentaje'    => $request->porcentaje,
                    'vigencia'      => 1,
                    'observacion'   => $request->observacion.'(PORCENTAJE INICIAL)',
                    'estado'        => 1,
                    'sysuser'       => Auth::user()->id
                ]);

                // CONFIRMAR
                DB::commit();

                return response()->json([
                    'success'       => true,
                    'mensaje'       => 'Tarifa registrada correctamente',
                    'tarifa'        => $tarifa
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'mensaje' => 'Ocurrió un error al registrar la tarifa',
                    'error'   => $e->getMessage()
                ], 500);
            }
        }
    }

    public function EditarTarifa(Request $request)
    {
        try {

            // VALIDACIONES
            $request->validate([
                'id_tarifa'   => 'required',
                'tarifa'      => 'required',
                'porcentaje'  => 'required'
            ]);

            // VERIFICAR SI LA TARIFA YA EXISTE
            $existeNombre = DB::table('tarifas')
                ->where('tarifa', $request->tarifa)
                ->where('id', '!=', $request->id_tarifa)
                ->exists();

            if ($existeNombre) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'La tarifa ya se encuentra registrada.'
                ], 200);
            }

            // OBTENER EL REGISTRO ACTUAL
            $tarifaActual = DB::table('tarifas as t')
                            ->join('tarifa_porcentajes as tp', 't.id', 'tp.tarifa_id')
                            ->select('t.id as idtarifa',
                                    't.tarifa',
                                    't.estado',
                                    'tp.id as idtarifaporcentajes',
                                    'tp.tarifa_id',
                                    'tp.porcentaje',
                                    'tp.estado',
                                    'tp.vigencia')
                            ->where('t.id', $request->id_tarifa)
                            ->where('t.estado', 1)
                            ->where('tp.estado', 1)
                            ->where('tp.vigencia', 1)
                            ->first();

            // INICIAR TRANSACCIÓN
            DB::beginTransaction();

                DB::table('tarifas')
                    ->where('id', $request->id_tarifa)
                    ->update([
                    'tarifa'      => $request->tarifa,
                    'observacion' => $request->observacion,
                    'sysuser'     => Auth::user()->id,
                    'updated_at'  => now()
                ]);

            if ($tarifaActual->porcentaje != $request->porcentaje) {
                DB::table('tarifa_porcentajes')
                    ->where('id', $tarifaActual->idtarifaporcentajes)
                    ->update([
                    'vigencia'    => 0,
                    'sysuser'     => Auth::user()->id,
                ]);

                TarifaPorcentajes::create([
                    'tarifa_id'     => $request->id_tarifa,
                    'porcentaje'    => $request->porcentaje,
                    'vigencia'      => 1,
                    'observacion'   => $request->observacion,
                    'estado'        => 1,
                    'sysuser'       => Auth::user()->id
                ]);
            }

            // // SI CAMBIO SOLO EL NOMBRE SE REGISTRA EN TARIFA Y TARIFAPORCENTAJES
            // if ($tarifaActual->tarifa != $request->tarifa) {
            //     // REGISTRAR TARIFA         
            //     $tarifa = Tarifas::create([
            //         'tarifa'        => $request->tarifa,
            //         'observacion'   => $request->observacion,
            //         'estado'        => 1,
            //         'sysuser'       => Auth::user()->id
            //     ]);

            //     TarifaPorcentajes::create([
            //         'tarifa_id'     => $tarifa->id,
            //         'porcentaje'    => $request->porcentaje,
            //         'vigencia'      => 1,
            //         'observacion'   => $request->observacion,
            //         'estado'        => 1,
            //         'sysuser'       => Auth::user()->id
            //     ]);

            // // SI SOLO CAMBIO PORCENTAJE SE ACTUALIZA TRIFAS Y SE REGISTRA EN TARIFAPORCENTAJES
            // } elseif ($tarifaActual->tarifa == $request->tarifa && $tarifaActual->porcentaje != $request->porcentaje) {

            //     DB::table('tarifas')
            //         ->where('id', $request->id_tarifa)
            //         ->update([
            //         'observacion' => $request->observacion,
            //         'sysuser'     => Auth::user()->id,
            //         'updated_at'  => now()
            //     ]);

            //     DB::table('tarifa_porcentajes')
            //         ->where('id', $tarifaActual->idtarifaporcentajes)
            //         ->update([
            //         'vigencia'    => 0,
            //         'observacion' => 'CAMBIO DE VIGENCIA',
            //         'sysuser'     => Auth::user()->id,
            //         'updated_at'  => now()
            //     ]);

            //     TarifaPorcentajes::create([
            //         'tarifa_id'     => $request->id_tarifa,
            //         'porcentaje'    => $request->porcentaje,
            //         'vigencia'      => 1,
            //         'observacion'   => $request->observacion,
            //         'estado'        => 1,
            //         'sysuser'       => Auth::user()->id
            //     ]);

            // // SI CAMBIARON NOMBRE Y PORCENTAJE REGISTRO EN TARIFAS Y TARIFAPORCENTAJES
            // } elseif ($tarifaActual->tarifa != $request->tarifa && $tarifaActual->porcentaje != $request->porcentaje) {
            //     $tarifa = Tarifas::create([
            //         'tarifa'        => $request->tarifa,
            //         'observacion'   => $request->observacion,
            //         'estado'        => 1,
            //         'sysuser'       => Auth::user()->id
            //     ]);

            //     TarifaPorcentajes::create([
            //         'tarifa_id'     => $tarifa->id,
            //         'porcentaje'    => $request->porcentaje,
            //         'vigencia'      => 1,
            //         'observacion'   => $request->observacion,
            //         'estado'        => 1,
            //         'sysuser'       => Auth::user()->id
            //     ]);
            // }

            // CONFIRMAR TRANSACCIÓN
            DB::commit();

            return response()->json([
                'success' => true,
                'mensaje' => 'Tarifa editada correctamente.'
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al editar la tarifa.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function CambiarEstadoTarifa(Request $request)
    {
        try {
            // VALIDACIONES
            $request->validate([
                'id_tarifa'  => 'required',
                'estado'     => 'required',
            ]);
            
            $estado = 1 - $request->estado;
            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            // EDITAR EVENTO
            DB::table('tarifas')
                ->where('id', $request->id_tarifa)
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
                    'mensaje' => 'Tarifa deshabilitada correctamente'
                ], 200);
            }else {
                return response()->json([
                    'success' => true,
                    'mensaje' => 'Tarifa habilitada correctamente'
                ], 200);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al cambiar de estado la tarifa',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function ListarTarifa(Request $request)
    {
        $tarifa = DB::table('tarifas as t')
                    ->join('tarifa_porcentajes as tp', 't.id', 'tp.tarifa_id')
                    ->select('t.id',
                            't.tarifa',
                            'tp.porcentaje',
                            'tp.vigencia',
                            't.estado')
                    ->where('t.estado', 1)
                    ->where('tp.estado', 1)
                    ->where('tp.vigencia', 1)
                    ->orderBy('t.id', 'asc')
                    ->get();

        return ['tarifas' => $tarifa];
    }

    public function BuscarTarifa(Request $request)
    {
        if ($request->buscar == '') {
            $tarifa = DB::table('tarifas as t')
                        ->join('tarifa_porcentajes as tp', 't.id', 'tp.tarifa_id')  
                        ->select('t.id', 
                                't.tarifa',  
                                't.estado',
                                'tp.porcentaje',
                                't.observacion')
                        ->where('tp.estado', 1)
                        ->where('tp.vigencia', 1)
                        ->orderBy('t.id', 'asc')
                        ->paginate(10);
        } else {
            $tarifa = DB::table('tarifas as t')
                        ->join('tarifa_porcentajes as tp', 't.id', 'tp.tarifa_id')  
                        ->select('t.id', 
                                't.tarifa',  
                                't.estado',
                                'tp.porcentaje',
                                't.observacion')
                        ->where('t.tarifa','LIKE','%'.$request->buscar.'%')
                        ->where('tp.estado', 1)
                        ->where('tp.vigencia', 1)
                        ->orderBy('t.id', 'asc')
                        ->paginate(10);
        }
        
        return response()->json([
            'pagination' => [
                'total'         => $tarifa->total(),
                'current_page'  => $tarifa->currentPage(),
                'per_page'      => $tarifa->perPage(),
                'last_page'     => $tarifa->lastPage(),
                'from'          => $tarifa->firstItem(),
                'to'            => $tarifa->lastItem(),
            
            ],
            'tarifas' => $tarifa
        ]); 
    }

    public function MostrarTarifa(Request $request)
    {
        // $tarifa = DB::table('tarifas')
        //             ->select('id', 
        //                     'tarifa',
        //                     'porcentaje',
        //                     'observacion')
        //             ->where('id', $request->id)
        //             ->first();
        $tarifa = DB::table('tarifas as t')
                    ->join('tarifa_porcentajes as tp', 't.id', 'tp.tarifa_id')  
                    ->select('t.id', 
                            't.tarifa',
                            'tp.porcentaje',
                            't.observacion')
                    ->where('t.id', $request->id)
                    ->where('tp.estado', 1)
                    ->where('tp.vigencia', 1)
                    ->first();

        return ['tarifas' => $tarifa];
    }
}
