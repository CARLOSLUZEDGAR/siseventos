<?php

namespace App\Http\Controllers;

use App\PredioCostos;
use App\Predios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrediosController extends Controller
{
    public function CrearPredio(Request $request)
    {
        try {
            // VALIDACIONES
            $request->validate([
                'predio'      => 'required',
                'tipo_predio' => 'required',
                'color'       => 'required',
                'precio'      => 'required'
            ]);
            // VERIFICAR SI EL NOMBRE YA EXISTE
            $existeNombre = DB::table('predios')
                ->where('nombre', $request->predio)
                ->where('estado', 1)
                ->exists();

            if ($existeNombre) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'El nombre del predio ya se encuentra registrado.'
                ], 200);
            }

            // VERIFICAR SI EL COLOR YA EXISTE
            $existeColor = DB::table('predios')
                ->where('color', $request->color)
                ->where('estado', 1)
                ->exists();

            if ($existeColor) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'El color seleccionado ya está asignado a otro predio. Seleccione un color diferente.'
                ], 200);
            }
            
            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            // REGISTRAR PREDIO
            $predio = Predios::create([
                'nombre'        => $request->predio,
                'abreviatura'   => $request->abreviatura,
                'clasificacion' => $request->tipo_predio,
                'color'         => $request->color,
                'observacion'   => $request->observacion,
                'estado'        => 1,
                'sysuser'       => Auth::user()->id
            ]);

            $predio_costos = PredioCostos::create([
                'predio_id'      => $predio->id,
                'precio'         => $request->precio,
                'vigencia'       => 1,
                'observacion'    => 'ALQUILER INICIAL',
                'estado'         => 1,
                'sysuser'        => Auth::user()->id
            ]);
            // CONFIRMAR
            DB::commit();

            return response()->json([
                'success'       => true,
                'mensaje'       => 'Predio registrado correctamente',
                'predio'        => $predio
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al registrar el predio',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function EditarPredio(Request $request)
    {
        try {
            // VALIDACIONES
            $request->validate([
                'id_predio'  => 'required',
                'predio'     => 'required',
                'color'      => 'required',
                'precio'     => 'required',
            ]);
            // VERIFICAR SI EL NOMBRE YA EXISTE
            $existeNombre = DB::table('predios')
                ->where('nombre', $request->predio)
                ->where('id', '!=', $request->id_predio)
                ->where('estado', 1)
                ->exists();

            if ($existeNombre) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'El nombre del predio ya se encuentra registrado.'
                ], 200);
            }

            // VERIFICAR SI EL COLOR YA EXISTE
            $existeColor = DB::table('predios')
                ->where('color', $request->color)
                ->where('id', '!=', $request->id_predio)
                ->where('estado', 1)
                ->exists();

            if ($existeColor) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'El color seleccionado ya está asignado a otro predio. Seleccione un color diferente.'
                ], 200);
            }

            $predio_costo_ultimo = PredioCostos::select('precio')
                                    ->where('predio_id', $request->id_predio)
                                    ->where('vigencia', 1)
                                    ->first();

            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            // EDITAR CLASIFICACION
            DB::table('predios')
                ->where('id', $request->id_predio)
                ->update([
                    'nombre'        => $request->predio,
                    'abreviatura'   => $request->abreviatura,
                    'color'         => $request->color,
                    'observacion'   => $request->observacion,
                    'sysuser'       => Auth::user()->id,
                    'updated_at'    => now()
                ]);

            if ($predio_costo_ultimo->precio != $request->precio) {
                DB::table('predio_costos')
                    ->where('predio_id', $request->id_predio)
                    ->update([
                        'vigencia'      => 0,
                        'observacion'   => 'CAMBIO DE VIGENCIA',
                        'sysuser'       => Auth::user()->id,
                        // 'updated_at'    => now()
                    ]);

                $predio_costos = PredioCostos::create([
                    'predio_id'      => $request->id_predio,
                    'precio'         => $request->precio,
                    'vigencia'       => 1,
                    'observacion'    => $request->observacion,
                    'estado'         => 1,
                    'sysuser'        => Auth::user()->id
                ]);
            }
            // CONFIRMAR
            DB::commit();

            return response()->json([
                'success' => true,
                'mensaje' => 'Predio editado correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al editar el predio',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function CambiarEstadoPredio(Request $request)
    {
        try {
            // VALIDACIONES
            $request->validate([
                'id_predio'  => 'required',
                'estado'     => 'required',
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
            DB::table('predios')
                ->where('id', $request->id_predio)
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
                    'mensaje' => 'Predio deshabilitado correctamente'
                ], 200);
            }else {
                return response()->json([
                    'success' => true,
                    'mensaje' => 'Predio habilitado correctamente'
                ], 200);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al cambiar de estado el predio',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function ListarPredio(Request $request)
    {
        $predio = DB::table('predios as p')
                    ->join('predio_costos as pc', 'p.id', 'pc.predio_id')
                    ->join('tipo_predios as tp', 'p.clasificacion', 'tp.id')
                    ->select('p.id', 
                            'p.nombre', 
                            'p.color', 
                            'p.estado',
                            'pc.precio',
                            'tp.id as id_tipo_predio',
                            'tp.clasificacion')
                    ->where('p.estado', 1)
                    ->where('pc.estado', 1)
                    ->where('tp.estado', 1)
                    ->where('p.clasificacion', $request->tipo_predio_id)
                    ->orderBy('p.id', 'asc')
                    ->get();

        return ['predios' => $predio];
    }

    public function BuscarPredio(Request $request)
    {
        if ($request->buscar == '') {
            $predio = DB::table('predios as p')
                    ->join('predio_costos as pc', 'p.id', 'pc.predio_id')
                    ->join('tipo_predios as tp', 'p.clasificacion', 'tp.id')
                    ->select('p.id', 
                            'p.nombre', 
                            'p.color', 
                            'p.estado',
                            'pc.id as id_predio_costo',
                            'pc.precio',
                            'tp.id as id_tipo_predio',
                            'tp.clasificacion')
                    ->where('pc.vigencia', 1)
                    ->where('pc.estado', 1)
                    ->where('tp.estado', 1)
                    ->orderBy('tp.id', 'asc')
                    ->orderBy('p.id', 'asc')
                    ->paginate(10);
        } else {
            $predio = DB::table('predios as p')
                    ->join('predio_costos as pc', 'p.id', 'pc.predio_id')
                    ->join('tipo_predios as tp', 'p.clasificacion', 'tp.id')
                    ->select('p.id', 
                            'p.nombre', 
                            'p.color', 
                            'p.estado',
                            'pc.id as id_predio_costo',
                            'pc.precio',
                            'tp.id as id_tipo_predio',
                            'tp.clasificacion')
                    ->where('pc.vigencia', 1)
                    ->where('pc.estado', 1)
                    ->where('tp.estado', 1)
                    ->where('p.nombre','LIKE','%'.$request->buscar.'%')
                    ->orderBy('tp.id', 'asc')
                    ->orderBy('p.id', 'asc')
                    ->paginate(10);
        }
        
        return response()->json([
            'pagination' => [
                'total'         => $predio->total(),
                'current_page'  => $predio->currentPage(),
                'per_page'      => $predio->perPage(),
                'last_page'     => $predio->lastPage(),
                'from'          => $predio->firstItem(),
                'to'            => $predio->lastItem(),
            
            ],
            'predios' => $predio
        ]); 
    }

    public function MostrarPredio(Request $request)
    {
        $predios = DB::table('predios as p')
                        ->join('tipo_predios as tp', 'p.clasificacion', 'tp.id')
                        ->join('predio_costos as pc', 'p.id', 'pc.predio_id')
                        ->select('p.id', 
                                'p.nombre',
                                'p.abreviatura',
                                'tp.clasificacion',
                                'p.color',
                                'p.observacion',
                                'pc.precio')
                        ->where('p.id', $request->id)
                        ->where('pc.vigencia', 1)
                        ->first();

        return ['predios' => $predios];
    }
}
