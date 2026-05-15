<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IndiceExport implements FromView,  ShouldAutoSize, WithStyles, WithProperties
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $año = Carbon::now();

        $data = DB::table('personals as p')
            ->join('personal_escalafones as pe','p.per_codigo','pe.per_codigo')
            ->join('grados as g','pe.gra_cod','g.id')
            ->join('personal_estudios as pes','p.per_codigo','pes.per_codigo')
            ->join('estudios as e','pes.est_cod','e.id')
            ->join('personal_especialidades as pesp','p.per_codigo','pesp.per_codigo')
            ->join('especialidades as es','pesp.espe_cod','es.id')
            ->join('subespecialidades as ses','pesp.subespe_cod','ses.id')
            ->join('personal_cargos as pc','p.per_codigo','pc.per_codigo')
            ->join('cargos as c','pc.car_cod','c.id')
            ->join('personal_situaciones as ps','p.per_codigo','ps.per_codigo')
            ->join('personal_destinos as pd','p.per_codigo','pd.per_codigo')
            ->join('nivel1_destinos as d1','pd.d1_cod','d1.id')
            ->join('nivel2_destinos as d2','pd.d2_cod','d2.id')
            ->join('nivel3_destinos as d3','pd.d3_cod','d3.id')
            ->join('nivel4_destinos as d4','pd.d4_cod','d4.id')
            ->select('p.per_codigo','p.per_cm as cm', 'p.per_nombre as nombre','p.per_paterno as paterno','p.per_materno as materno','p.per_sexo as genero','p.per_ci as ci','p.per_expedido as exp','p.per_fecha_nacimiento as fecha_nac','g.abreviatura as grado','e.abreviatura as complemento','es.nombre as espe','ses.nombre as subesp','c.descripcion as cargo','d1.descripcion as dest1','d2.descripcion as dest2','d3.descripcion as dest3','d4.descripcion as dest4')
            ->whereIn('ps.subsit_cod',[1,2,4,5,8,9,11,13,14,16,17,29,30,31,32,33])
            ->where('pe.estado',1)
            ->where('pes.estado',1)
            ->where('pesp.estado',1)
            ->where('pc.estado',1)
            ->where('pc.nivel_cargo',1)
            ->where('ps.estado',1)
            ->where('pd.estado',1)
            ->where('g.fuerza','LIKE','FAB')            
            ->orderBy('pd.d1_cod')
            ->orderBy('d2.prioridad')
            ->orderBy('d3.d2_cod')
            ->orderBy('d3.orden')
            ->orderBy('pe.esca_cod')
            ->orderBy('pe.subesc_cod')
            ->orderBy('g.orden')
            ->orderBy('p.per_cm')
            ->get();
            
            ini_set('max_execution_time', 300);
            return view('Excel.indiceGenDest', [
                'data' => $data,
            ]);
            
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getAlignment()->setHorizontal('center');
    }

    public function properties(): array
    {
        $año = strval(Carbon::now()->format('Y'));
        return [
            'creator'        => 'DIGETIC',
            'lastModifiedBy' => Auth::user()->id,
            'title'          => 'Indice general de Destinos '.$año,
            'category'       => 'Reporte',
            'manager'        => 'DIGETIC',
            'company'        => 'FUERZ AEREA BOLIVIANA',
        ];
    }
}