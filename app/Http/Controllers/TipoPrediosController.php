<?php

namespace App\Http\Controllers;

use App\TipoPredios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoPrediosController extends Controller
{
    public function CrearTipoPredio(Request $request)
    {

    }

    public function ListarTipoPredio(Request $request)
    {
        $tipo_predio = DB::table('tipo_predios as tp')
                    ->select('tp.id', 
                            'tp.clasificacion')
                    ->where('tp.estado', 1)
                    ->orderBy('tp.id', 'asc')
                    ->get();

        return ['tipo_predios' => $tipo_predio];
    }

    public function BuscarTipoPredio(Request $request)
    {
        if ($request->buscar == '') {
            $tipo_predio = DB::table('tipo_predios')
                    ->select('id', 
                            'clasificacion')
                    ->where('estado', 1)
                    ->orderBy('id', 'asc')
                    ->paginate(10);
        } else {
            $tipo_predio = DB::table('tipo_predios')
                    ->select('id', 
                            'clasificacion')
                    ->where('estado', 1)
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
