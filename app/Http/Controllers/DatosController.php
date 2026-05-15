<?php

namespace App\Http\Controllers;

use App\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatosController extends Controller
{
    public function ListarModulos()
    {
        $modulos = Modulo::all();

        return response()->json($modulos);
    }

    public function ListarRoles()
    {
        $role = Role::all();

        return response()->json($role);
    }

    /**
     * ACCEDE A LOS DATOS DEL USUARIO LOGUEADO
     */
    public function datosP()
    {
        $datos = DB::table('usuarios as us')
            ->join('users as u','u.id_usuario','us.id')
            ->join('model_has_roles as mr', 'u.id','mr.model_id')
            ->join('roles as r','mr.role_id','r.id')
            ->select('us.nombres','us.ap_paterno','us.ap_materno','r.id as id_rol','r.name as name_rol')
            ->where('us.id',Auth::user()->id_usuario)
            ->where('u.estado',1)
            ->first();
    
        return response()->json($datos);
    }
}
