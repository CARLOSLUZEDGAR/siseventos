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

                // VERIFICAR SI EL PORCENTAJE YA EXISTE
                // $existePorcentaje = DB::table('tarifas')
                //     ->where('porcentaje', $request->porcentaje)
                //     ->where('estado', 1)
                //     ->exists();

                // if ($existePorcentaje) {
                //     return response()->json([
                //         'success' => false,
                //         'mensaje' => 'El porcentaje seleccionado ya está asignado a otra tarifa. Seleccione un porcentaje diferente.'
                //     ], 200);
                // }
                
                // INICIAR TRANSACCIÓN
                DB::beginTransaction();
                // REGISTRAR TARIFA
                // $tarifa = Tarifas::create([
                //     'tarifa'        => $request->tarifa,
                //     'porcentaje'    => $request->porcentaje,
                //     'vigencia'      => 1,
                //     'observacion'   => $request->observacion,
                //     'estado'        => 1,
                //     'sysuser'       => Auth::user()->id
                // ]);

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
                    'observacion'   => $request->observacion,
                    'estado'        => 1,
                    'sysuser'       => Auth::user()->id
                ]);

                // CONFIRMAR
                DB::commit();

                return response()->json([
                    'success'       => true,
                    'mensaje'       => 'Tarifa registrada correctamente.',
                    'tarifa'   => $tarifa
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'mensaje' => 'Ocurrió un error al registrar la tarifa.',
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
                ->where('estado', 1)
                ->exists();

            if ($existeNombre) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'La tarifa ya se encuentra registrada.'
                ], 200);
            }

            // VERIFICAR SI EL PORCENTAJE YA EXISTE
            $existePorcentaje = DB::table('tarifas')
                ->where('porcentaje', $request->porcentaje)
                ->where('id', '!=', $request->id_tarifa)
                ->where('estado', 1)
                ->exists();

            if ($existePorcentaje) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'El porcentaje seleccionado ya está asignado a otra tarifa. Seleccione un porcentaje diferente.'
                ], 200);
            }

            // OBTENER EL REGISTRO ACTUAL
            $tarifaActual = Tarifas::findOrFail($request->id_tarifa);

            // INICIAR TRANSACCIÓN
            DB::beginTransaction();

            // SI EL PORCENTAJE NO CAMBIÓ SOLO SE ACTUALIZA EL REGISTRO
            if ($tarifaActual->porcentaje == $request->porcentaje) {

                $tarifaActual->update([
                    'tarifa'      => $request->tarifa,
                    'observacion' => $request->observacion,
                    'sysuser'     => Auth::user()->id,
                    'updated_at'  => now()
                ]);

            } else {

                // CAMBIAR VIGENCIA DEL REGISTRO ACTUAL
                $tarifaActual->update([
                    'vigencia'    => 0,
                    'observacion' => 'CAMBIO DE VIGENCIA',
                    'sysuser'     => Auth::user()->id,
                    'updated_at'  => now()
                ]);

                // CREAR NUEVA TARIFA
                Tarifas::create([
                    'tarifa'      => $request->tarifa,
                    'porcentaje'  => $request->porcentaje,
                    'vigencia'    => 1,
                    'observacion' => $request->observacion,
                    'estado'      => 1,
                    'sysuser'     => Auth::user()->id
                ]);
            }

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

    public function ListarTarifa(Request $request)
    {
        // $tarifa = Tarifas::select('id', 'tarifa', 'porcentaje', 'vigencia', 'estado')
        //                 ->where('estado', 1)
        //                 ->orderBy('id', 'asc')
        //                 ->get();
        $tarifa = DB::table('tarifas as t')
                    ->join('tarifas_porcentajes as tp', 't.id', 'tp.tarifa_id')
                    ->select('t.id',
                            't.tarifa',
                            'tp.porcentaje',
                            'tp.vogencia',
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
            // $tarifa = DB::table('tarifas')
            //         ->select('id', 
            //                 'tarifa',  
            //                 'estado',
            //                 'porcentaje',
            //                 'observacion')
            //         ->orderBy('id', 'asc')
            $tarifa = DB::table('tarifas as t')
                        ->join('tarifas_porcentajes as tp', 't.id', 'tp.tarifa_id')  
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
                        ->join('tarifas_porcentajes as tp', 't.id', 'tp.tarifa_id')  
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
                    ->join('tarifas_porcentajes as tp', 't.id', 'tp.tarifa_id')  
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
