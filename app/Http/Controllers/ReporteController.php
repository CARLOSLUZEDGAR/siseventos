<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;


class ReporteController extends Controller
{
    public function GenerarContrato(Request $request)
    {
        $evento = DB::table('eventos as e')
                    ->join('predios as p', 'e.predio_id', 'p.id')
                    ->join('tipo_eventos as te', 'e.tipo_evento_id', 'te.id')
                    ->join('tarifas as t', 'e.tarifa_id', 't.id')
                    ->join('tarifa_porcentajes as tpor', 't.id', 'tpor.tarifa_id')
                    ->join('predio_costos as pc', 'e.predio_id', 'pc.predio_id')
                    ->join('tipo_predios as tp', 'e.tipo_predio_id', 'tp.id')
                    ->select('e.id',
                        'e.contratante',
                        'e.ci',
                        'e.celular',
                        'tp.clasificacion',
                        'p.nombre',
                        'te.evento',
                        't.id as idtarifa',
                        't.tarifa',
                        'tpor.porcentaje',
                        'e.fecha_evento',
                        'e.fecha_evento_fin',
                        'e.hora_inicio',
                        'e.hora_fin',
                        'pc.precio')
                    ->where('e.id', $request->idE)
                    ->where('e.estado', 1)
                    ->where('tpor.estado', 1)
                    ->where('tpor.vigencia', 1)
                    ->where('pc.estado', 1)
                    ->where('pc.vigencia', 1)
                    ->first();

        $situacion_evento = DB::table('situacion_eventos as se')
                            ->join('situaciones as s', 'se.situacion_id', 's.id')
                            ->select('s.id',
                                    's.situacion',
                                    'se.monto')
                            ->where('se.estado', 1)
                            ->where('s.estado', 1)
                            ->where('se.id', $request->idSE)
                            ->first();

        $listar_situacion_evento = DB::table('situacion_eventos as se')
                            ->join('situaciones as s', 'se.situacion_id', 's.id')
                            ->select('s.situacion',
                                    'se.monto')
                            ->where('se.estado', 1)
                            ->where('s.estado', 1)
                            ->where('se.evento_id', $request->idE)
                            ->where('se.situacion_id', '!=', 3)
                            ->orderBy('se.situacion_id', 'asc')
                            ->get();

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha_emision_corto= date('d')."/".date('n')."/".date('Y');
        Carbon::setLocale('es');
        $fecha = Carbon::now();
        $fecha_emision = $fecha->format('d') . ' días del mes de ' . $fecha->translatedFormat('F') . ' de ' . $fecha->format('Y');
        $fecha_evento_inicio = mb_strtoupper(Carbon::parse($evento->fecha_evento)->locale('es')->translatedFormat('l, d \d\e F \d\e Y'),'UTF-8');
        $fecha_evento_final = mb_strtoupper(Carbon::parse($evento->fecha_evento_fin)->locale('es')->translatedFormat('l, d \d\e F \d\e Y'),'UTF-8');


        $qr = QrCode::encoding('UTF-8')->size(100)->generate("No. REGISTRO: $evento->id\nCONTRATANTE: $evento->contratante\nSALON: $evento->nombre\nEVENTO: $evento->evento\nFECHA: $evento->fecha_evento");
        $codigo = $qr;

        $pdf = PDF::loadView('reportes.recibo',['evento' => $evento,
                                                'situacion_evento' => $situacion_evento,
                                                'listar_situacion_evento' => $listar_situacion_evento,
                                                'qr' => $codigo,
                                                'fecha_det' => $fecha_emision,
                                                'fecha_evento_inicio' => $fecha_evento_inicio,
                                                'fecha_evento_final' => $fecha_evento_final
                                                ])
        //8.3cm 5cm
        ->setPaper('letter', 'portrait');                                               
        
        return $pdf->stream($evento->id.'_recibo.pdf');
        // return $pdf->download($personal->id_licencia.'-'.$personal->per_ci.'.pdf');
    }

    public function GenerarCarnet(Request $request)
    {
        $personal = DB::table('personals as p')
                    ->join('personal_licencias as pl','p.id','pl.id_personal')
                    ->join('nacionalidads as n','p.id_nacionalidad','n.id')
                    ->join('entidads as e','pl.id_entidad','e.id')
                    ->join('grados as g','pl.id_grado','g.id')
                    ->join('licencias as l','pl.id_licencia','l.id')
                    // ->join('habilitacions as h','pl.id_habilitacion','h.id')
                    ->join('competencia_linguisticas as cl','pl.id_comp_linguistica','cl.id')
                    ->select('p.id as id_personal',
                        'n.nacionalidad',
                        'n.pais',
                        'p.per_foto',
                        'p.per_nombre',
                        'p.per_paterno',
                        'p.per_materno',
                        'p.per_ci',
                        'p.per_sexo',
                        'p.per_fecha_nacimiento',
                        'p.per_direccion',
                        'pl.id as id_licencia',
                        'pl.id_entidad',
                        'e.entidad',
                        'g.abreviatura',
                        'l.licencia',
                        'l.traduccion',
                        'pl.id_habilitacion',
                        // 'h.habilitacion',
                        // 'h.traduccion as htraduccion',
                        'cl.nivel',
                        'cl.traduccion as cltraduccion',
                        'pl.observacion',
                        'pl.fecha_emision',
                        'pl.fecha_expiracion'
                        )
                    ->where('p.id',$request->id_p)
                    ->where('pl.estado',1)
                    ->first();

        $arrayIdHabilitaciones = json_decode($personal->id_habilitacion, true);
        $cantidad = count($arrayIdHabilitaciones);

        $arrayHabilitaciones = DB::table('habilitacions')
                            ->select('id', 'habilitacion', 'traduccion')
                            ->whereIn('id', $arrayIdHabilitaciones)
                            ->get();

        // $url_verificacion = '127.0.0.1:8000/verificarPersonal/'.$personal->id_licencia;
        // $url_verificacion = '192.168.0.15:8000/verificarPersonal/'.$personal->id_licencia;
        $url_verificacion = 'siscardgae2.onrender.com/verificarPersonal/'.$personal->id_licencia;


        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha_emision = date('d')."/".date('n')."/".date('Y');

        $qr = QrCode::encoding('UTF-8')->size(100)->generate($url_verificacion);
        $codigo = $qr;
        
        // $qr = QrCode::encoding('UTF-8')->size(100)->generate( "No. LICENCIA: $personal->per_ci\nGRADO: $personal->abreviatura\nAPELLIDO(S): $personal->per_paterno $personal->per_materno\nNOMBRE(S): $personal->per_nombre\nTITULO: $personal->licencia\nHABILITACION(ES): $arrayHabilitaciones\nFECHA: $fecha_emision");
        // $codigo = $qr;

        $pdf = PDF::loadView('reportes.carnet',['personal'=>$personal,
                                                'arrayHabilitaciones' => $arrayHabilitaciones,
                                                'qr'=>$codigo
                                                ])
        //8.3cm 5cm
        ->setPaper(array(0, 0, 235.276, 141.732), 'portrait');                                               
        
        return $pdf->stream($personal->id_licencia.'-'.$personal->per_ci.'.pdf');
        // return $pdf->download($personal->id_licencia.'-'.$personal->per_ci.'.pdf');

    }

    public function GenerarReportePersonal(Request $request)
    {
        $personal = DB::table('personals as p')
                    ->join('personal_licencias as pl','p.id','pl.id_personal')
                    ->join('nacionalidads as n','p.id_nacionalidad','n.id')
                    ->join('entidads as e','pl.id_entidad','e.id')
                    ->join('grados as g','pl.id_grado','g.id')
                    ->join('licencias as l','pl.id_licencia','l.id')
                    // ->join('habilitacions as h','pl.id_habilitacion','h.id')
                    ->join('competencia_linguisticas as cl','pl.id_comp_linguistica','cl.id')
                    ->select('p.id as id_personal',
                        'n.nacionalidad',
                        'n.pais',
                        'p.per_foto',
                        'p.per_nombre',
                        'p.per_paterno',
                        'p.per_materno',
                        'p.per_ci',
                        'p.per_sexo',
                        'p.per_fecha_nacimiento',
                        'pl.id as id_licencia',
                        'pl.id_entidad',
                        'e.entidad',
                        'g.abreviatura',
                        'l.licencia',
                        'l.traduccion',
                        'pl.id_habilitacion',
                        // 'h.habilitacion',
                        // 'h.traduccion as htraduccion',
                        'cl.nivel',
                        'cl.traduccion as cltraduccion',
                        'pl.observacion',
                        'pl.fecha_emision',
                        'pl.fecha_expiracion'
                        )
                    ->where('p.id',$request->id_p)
                    ->where('pl.estado',1)
                    ->first();

        $arrayIdHabilitacionAct = json_decode($personal->id_habilitacion, true);
        $cantidad = count($arrayIdHabilitacionAct);

        $arrayHabilitacionesAct = DB::table('habilitacions')
                            ->select('id', 'habilitacion', 'traduccion')
                            ->whereIn('id', $arrayIdHabilitacionAct)
                            ->get();

        $personal_licencias = DB::table('personal_licencias as pl')
                            ->join('categorias as c','pl.id_categoria','c.id')
                            ->join('entidads as e','pl.id_entidad','e.id')
                            ->join('grados as g','pl.id_grado','g.id')
                            ->join('licencias as l','pl.id_licencia','l.id')
                            // ->join('habilitacions as h','pl.id_habilitacion','h.id')
                            ->join('competencia_linguisticas as cl','pl.id_comp_linguistica','cl.id')
                            ->select('pl.id',
                                    'c.categoria',
                                    'e.entidad',
                                    'g.abreviatura',
                                    'l.licencia',
                                    'pl.id_habilitacion',
                                    // 'h.habilitacion',
                                    'cl.nivel',
                                    'pl.fecha_emision')
                            ->where('pl.id_personal',$request->id_p)
                            ->orderBy('pl.fecha_emision','asc')
                            ->get();
        
        // Recorremos los resultados
        foreach ($personal_licencias as $pl) {
            // Decodificamos el JSON a array
            $arrayIdHabilitaciones = json_decode($pl->id_habilitacion, true);

            if (is_array($arrayIdHabilitaciones) && count($arrayIdHabilitaciones) > 0) {
                // Obtenemos las habilitaciones de esos IDs
                $arrayHabilitaciones = DB::table('habilitacions')
                    ->select('id', 'habilitacion', 'traduccion')
                    ->whereIn('id', $arrayIdHabilitaciones)
                    ->get();

                // Le agregamos la lista al objeto actual
                $pl->habilitaciones = $arrayHabilitaciones;
            } else {
                $pl->habilitaciones = collect(); // colección vacía
            }
        }

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha_emision = date('d')."/".date('n')."/".date('Y');
        // $qr = QrCode::encoding('UTF-8')->size(100)->generate("No. LICENCIA: $personal->per_ci\nGRADO: $personal->abreviatura\nAPELLIDO(S): $personal->per_paterno $personal->per_materno\nNOMBRE(S): $personal->per_nombre\nTITULO: $personal->licencia\nHABILITACION: $personal->habilitacion\nFECHA: $fecha_emision");
        $qr = QrCode::encoding('UTF-8')->size(100)->generate("No. LICENCIA: $personal->per_ci\nGRADO: $personal->abreviatura\nAPELLIDO(S): $personal->per_paterno $personal->per_materno\nNOMBRE(S): $personal->per_nombre\nTITULO: $personal->licencia\nFECHA: $fecha_emision");
        $codigo = $qr;

        $pdf = PDF::loadView('reportes.dat_personal',['personal'=>$personal,
                                                    'arrayHabilitaciones' => $arrayHabilitaciones,
                                                    'qr'=>$codigo,
                                                    'licencias'=>$personal_licencias
                                                    ])
        //8.3cm 5cm
        ->setPaper('letter', 'portrait');                                               
        
        return $pdf->stream($personal->per_ci.'PERSONAL_DAT.pdf');
        // return $pdf->download($personal->id_licencia.'-'.$personal->per_ci.'.pdf');
    }

    public function GenerarReporteLicencias(Request $request)
    {
        
        $fechade = date_create($request->de);
        $de = date_format($fechade,"d/m/Y");

        $fechade = date_create($request->hasta);
        $hasta = date_format($fechade,"d/m/Y");
                                    
        // $de = $request->de;
        // $hasta = $request->hasta;

        $personal_licencias = DB::table('personal_licencias as pl')
                            ->join('categorias as c','pl.id_categoria','c.id')
                            ->join('entidads as e','pl.id_entidad','e.id')
                            ->join('grados as g','pl.id_grado','g.id')
                            ->join('licencias as l','pl.id_licencia','l.id')
                            // ->join('habilitacions as h','pl.id_habilitacion','h.id')
                            ->join('competencia_linguisticas as cl','pl.id_comp_linguistica','cl.id')
                            ->join('personals as p','pl.id_personal','p.id')
                            ->select('pl.id',
                                    'c.categoria',
                                    'e.entidad',
                                    'g.abreviatura',
                                    'l.licencia',
                                    'pl.id_habilitacion',
                                    // 'h.habilitacion',
                                    'cl.nivel',
                                    'pl.fecha_emision',
                                    'p.per_nombre',
                                    'p.per_paterno',
                                    'p.per_materno')
                            // ->where('pl.fecha_emision','2024-04-06')
                            ->whereBetween('pl.fecha_emision',[$de,$hasta])
                            ->orderBy('pl.fecha_emision','asc')
                            ->get();

        // Recorremos los resultados
        foreach ($personal_licencias as $pl) {
            // Decodificamos el JSON a array
            $arrayIdHabilitaciones = json_decode($pl->id_habilitacion, true);

            if (is_array($arrayIdHabilitaciones) && count($arrayIdHabilitaciones) > 0) {
                // Obtenemos las habilitaciones de esos IDs
                $arrayHabilitaciones = DB::table('habilitacions')
                    ->select('id', 'habilitacion', 'traduccion')
                    ->whereIn('id', $arrayIdHabilitaciones)
                    ->get();

                // Le agregamos la lista al objeto actual
                $pl->habilitaciones = $arrayHabilitaciones;
            } else {
                $pl->habilitaciones = collect(); // colección vacía
            }
        }

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha_emision = date('d')."/".date('n')."/".date('Y');
        $qr = QrCode::encoding('UTF-8')->size(100)->generate("FECHA: $fecha_emision\nDE: $de\nHASTA: $hasta");
        $codigo = $qr;

        $pdf = PDF::loadView('reportes.list_licencias',['qr'=>$codigo,
                                                'licencias'=>$personal_licencias,
                                                'de'=>$de,
                                                'hasta'=>$hasta
                                                ])
        //8.3cm 5cm
        ->setPaper('letter', 'landscape');                                               
        
        return $pdf->stream($de.'-'.$hasta.'pdf');
        // return $pdf->download($personal->id_licencia.'-'.$personal->per_ci.'.pdf');
    }

    public function DownloadPDF(Request $request)
    {
        $filePath = storage_path("../document/personal/".$request->documento);
        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File not found.'], 404);
        }
        return response()->download($filePath);
    }
}
