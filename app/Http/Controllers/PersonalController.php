<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Personal;
use App\PersonalDocumento;
use App\PersonalLicencia;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\SupabaseStorageService;
use Illuminate\Support\Facades\Log;

class PersonalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['VerificarPersonal']);
    }

    public function CrearPersonal(Request $request)
    {
        DB::beginTransaction();

        try {

            $storage = new \App\Services\SupabaseStorageService();

            // =========================
            // 📸 FOTO
            // =========================
            $urlFoto = null;

            if ($request->filled('foto') || $request->hasFile('foto')) {

                try {

                    $ci = preg_replace('/[^A-Za-z0-9]/', '', $request->ci);

                    $customName = $ci . '_' . \Illuminate\Support\Str::uuid();

                    // =====================================
                    // 📌 CASO 1: BASE64
                    // =====================================
                    if (
                        is_string($request->foto) &&
                        str_contains($request->foto, 'base64')
                    ) {

                        // 🔥 subida directa del base64
                        $urlFoto = $storage->upload(
                            $request->foto,
                            'img/personas',
                            $customName
                        );
                    }

                    // =====================================
                    // 📌 CASO 2: ARCHIVO NORMAL
                    // =====================================
                    elseif ($request->hasFile('foto')) {

                        $file = $request->file('foto');

                        $urlFoto = $storage->upload(
                            $file,
                            'img/personas',
                            $customName
                        );
                    }

                    // =====================================
                    // 📌 FORMATO INVÁLIDO
                    // =====================================
                    else {
                        throw new \Exception('Formato de foto no reconocido');
                    }

                } catch (\Exception $e) {

                    logger()->error('ERROR FOTO', [
            'mensaje' => $e->getMessage()
        ]);

        throw new \Exception($e->getMessage());
                }
            }

            // =========================
            // 👤 REGISTRO PERSONAL
            // =========================
            $personal = Personal::create([
                'per_foto' => $urlFoto,
                'per_ci' => $request->ci,
                'per_nombre' => mb_strtoupper($request->nombre),
                'per_paterno' => mb_strtoupper($request->ap_paterno),
                'per_materno' => mb_strtoupper($request->ap_materno),
                'per_sexo' => $request->sexo,
                'per_celular' => $request->celular,
                'per_mail' => $request->email,
                'per_fecha_nacimiento' => $request->fech_nac,
                'per_direccion' => mb_strtoupper($request->direccion),
                'estado' => '1',
                'sysuser' => auth()->id()
            ]);
     
            // =========================
            // 📂 DOCUMENTOS
            // =========================
           
            DB::commit();

            return response()->json([
                'success' => true,
                'personal' => $personal
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            logger()->error('Error en CrearPersonal', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Error al registrar personal',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * FUNCION PARA EL LISTADOR DE PERSONAL EN TIEMPO REALs
     */

    public function ListarPersonal(Request $request) //DGAE
    {
        $buscar = $request->buscar;
        if ($request->buscar == '') {
            $personal = DB::table('personals as p')
                   ->select('p.id',
                            'p.per_foto',
                            'p.per_ci',
                            'p.per_nombre',
                            'p.per_paterno',
                            'p.per_materno',
                            'p.per_sexo',
                            'p.per_celular',
                            'p.per_mail',
                            'p.per_fecha_nacimiento',
                            'p.per_direccion')
                    ->where('p.estado',1)
                    ->orderBy('p.id', 'desc')
                    ->paginate(10);
        } else {
            $personal = DB::table('personals as p')
                    ->select('p.id',   
                            'p.per_foto',
                            'p.per_ci',
                            'p.per_nombre',
                            'p.per_paterno',
                            'p.per_materno',
                            'p.per_sexo',
                            'p.per_celular',
                            'p.per_mail',
                            'p.per_fecha_nacimiento',
                            'p.per_direccion')
                    ->where(function($q) use ($buscar){
                        $q->where('p.per_ci','LIKE','%'.$buscar.'%')
                        ->orWhere('p.per_paterno','LIKE','%'.$buscar.'%')
                        ->orWhere('p.per_materno','LIKE','%'.$buscar.'%')
                        ->orWhere('p.per_nombre','LIKE','%'.$buscar.'%');
                    }) 
                    ->where('p.estado',1)
                    ->orderBy('p.id', 'desc')
                    ->paginate(10);
        }
        return response()->json([
            'pagination' => [
                'total'         => $personal->total(),
                'current_page'  => $personal->currentPage(),
                'per_page'      => $personal->perPage(),
                'last_page'     => $personal->lastPage(),
                'from'          => $personal->firstItem(),
                'to'            => $personal->lastItem(),
            ],
            'personal' => $personal
        ]); 
    }

    public function DatosPersonal(Request $request)//DGAE
    {
        $personal = DB::table('personals as p')
            ->select(
                'p.id as id_personal',
                'p.per_foto',
                'p.per_nombre',
                'p.per_paterno',
                'p.per_materno',
                'p.per_ci',
                'p.per_sexo',
                'p.per_fecha_nacimiento',
                'p.per_celular',
                'p.per_direccion',
                'p.per_mail'
            )
            ->where('p.id',$request->personal_id)
            ->where('p.estado',1)
            ->first();

        return [
            'personal' => $personal
        ];
    }

    public function RenovarPersonal(Request $request)//DGAE
    {
        DB::beginTransaction();

        try {

            $storage = new \App\Services\SupabaseStorageService();

            // =========================
            // 🔍 OBTENER PERSONAL
            // =========================
            $personal = Personal::where('id', $request->id_personal)
                ->firstOrFail();

            // =========================
            // 📸 FOTO
            // =========================
            $urlFoto = $personal->per_foto;

            if ($request->filled('foto')) {

                // ==================================================
                // SI LA FOTO ES LA MISMA URL NO SUBE NUEVAMENTE
                // ==================================================
                if ($request->foto !== $personal->per_foto) {

                    try {

                        $ci = preg_replace(
                            '/[^A-Za-z0-9]/',
                            '',
                            $request->ci
                        );

                        $customName = $ci . '_' .
                            \Illuminate\Support\Str::uuid();

                        // =====================================
                        // 📌 CASO 1: BASE64
                        // =====================================
                        if (
                            is_string($request->foto) &&
                            str_contains($request->foto, 'base64')
                        ) {

                            $urlFoto = $storage->upload(
                                $request->foto,
                                'img/personas',
                                $customName
                            );
                        }

                        // =====================================
                        // 📌 FORMATO INVÁLIDO
                        // =====================================
                        else {

                            throw new \Exception(
                                'Formato de foto no reconocido'
                            );
                        }

                    } catch (\Exception $e) {

                        logger()->error('ERROR FOTO RENOVAR', [
                            'mensaje' => $e->getMessage()
                        ]);

                        throw new \Exception(
                            'Error al subir fotografía'
                        );
                    }
                }
            }

            // =========================
            // 👤 ACTUALIZAR PERSONAL
            // =========================
            $personal->update([
                'per_foto' => $urlFoto,
                'per_ci' => $request->ci,
                'per_nombre' => mb_strtoupper($request->nombre),
                'per_paterno' => mb_strtoupper($request->ap_paterno),
                'per_materno' => mb_strtoupper($request->ap_materno),
                'per_sexo' => $request->sexo,
                'per_celular' => $request->celular,
                'per_mail' => $request->email,
                'per_direccion' => mb_strtoupper($request->direccion),
                'estado' => '1',
                'sysuser' => auth()->id()
            ]);                   

            DB::commit();

            return response()->json([
                'success' => true,
                'personal' => $personal
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            logger()->error('Error en RenovarPersonal', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Error al renovar personal',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function EliminarPersonal(Request $request)
    {
        $personal= Personal::where('id', $request->persona_id)
                        ->update([
                            'estado' => 0
                        ]);
        return response()->json($request->persona_id);
    }

    //////////////////////////////////////////////////////////////

    /***************FUNCIONES PARA ACCESO DE SISTEMA********************* */
    
    public function DatosPersonalesAcceso(Request $request)
    {
        $percodigo = $request->percodigo;
        $datos = DB::table('personal_escalafones as ep')
            ->join('personals as p','ep.per_codigo','p.per_codigo')
            ->join('grados as g','ep.gra_cod','g.id')
            ->join('personal_estudios as epe','p.per_codigo','epe.per_codigo')
            ->join('estudios as e','epe.est_cod','e.id')
            ->join('personal_situaciones as ps','p.per_codigo','ps.per_codigo')
            ->join('subsituaciones as ss','ps.subsit_cod','ss.id')
            ->join('personal_destinos as pd','p.per_codigo','pd.per_codigo')
            ->join('nivel3_destinos as nd3','pd.d3_cod','nd3.id')
            ->join('nivel2_destinos as nd2','pd.d2_cod','nd2.id')
            ->select('p.per_codigo as percodigo','p.per_foto as foto','p.per_nombre as nombre','p.per_paterno as paterno','p.per_materno as materno','p.per_cm as cm',
                        'p.per_ci as ci', 'p.per_expedido as expedido', 'p.per_mail as email','g.abreviatura as grado','e.abreviatura as complemento',
                        'ss.nombre as situacion','nd2.descripcion as des2','nd3.descripcion as des3')
            ->where('ep.per_codigo',$percodigo)
            ->where('ps.estado',1)
            ->where('ep.estado',1)
            ->where('epe.estado',1)
            ->where('pd.estado',1)
            ->first();

            return response()->json($datos);
    }

    public function VerificarPersonal($id_per_lic) {
        $id_per_licen = $id_per_lic;

        $personal = DB::table('personals as p')
                    ->join('personal_licencias as pl','p.id','pl.id_personal')
                    ->join('nacionalidads as n','p.id_nacionalidad','n.id')
                    ->join('entidads as e','pl.id_entidad','e.id')
                    ->join('grados as g','pl.id_grado','g.id')
                    ->join('licencias as l','pl.id_licencia','l.id')
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
                        'cl.nivel',
                        'cl.traduccion as cltraduccion',
                        'pl.observacion',
                        'pl.fecha_emision',
                        'pl.fecha_expiracion'
                        )
                    ->where('pl.id',$id_per_licen)
                    ->where('pl.estado',1)
                    ->first();

        $fecha_actual = Carbon::now();

        $fecha_expiracion = Carbon::parse($personal->fecha_expiracion);

        $estado = $fecha_actual->gt($fecha_expiracion) ? 'NO VIGENTE' : 'VIGENTE';    

        return view('verificacion.verificar', compact('personal', 'estado'));
    }
}
