<?php

namespace App\Http\Controllers;

use App\Predios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrediosController extends Controller
{
    public function CrearPredio(Request $request)
    {

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
                            'pc.precio',
                            'tp.clasificacion')
                    ->where('p.estado', 1)
                    ->where('pc.estado', 1)
                    ->where('tp.estado', 1)
                    ->orderBy('tc.id', 'asc')
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
                            'pc.precio',
                            'tp.clasificacion')
                    ->where('p.estado', 1)
                    ->where('pc.estado', 1)
                    ->where('tp.estado', 1)
                    ->where('nombre','LIKE','%'.$request->buscar.'%')
                    ->orderBy('tc.id', 'asc')
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
}
