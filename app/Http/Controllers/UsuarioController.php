<?php

namespace App\Http\Controllers;

use App\Mail\AccesoUsuarios;
use App\User;
use App\Usuario; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;

class UsuarioController extends Controller
{
    public function DatosUsuario()
    {
        $usuario = DB::table('users as u')
                ->join('usuarios as us','u.id_usuario','us.id')
                ->select('u.id','u.nick','u.estado','u.email','us.nombres','us.ap_paterno','us.ap_materno')
                ->where('us.estado',1)
                ->where('u.id',Auth::user()->id)
                ->first();
        
        return response()->json($usuario);
    }

    public function CrearUsuario(Request $request) //BASE
    {
        // Generador de Contraseña aleatoria
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '12345';
        // for ($i=0; $i < 10; $i++) {
        //     $randomString .= $characters[rand(0,$charactersLength - 1)];
        // }
        $usuario = Usuario::create([
            'nombres' =>  mb_strtoupper($request->nombres),
            'ap_paterno' => mb_strtoupper($request->ap_paterno),
            'ap_materno' => mb_strtoupper($request->ap_materno),
            'email' => $request->email,
            'estado' => '1',
            'sysuser' => Auth::user()->id
        ]);

        $user = User::create([
            'id_usuario' => $usuario->id,
            'nick' => mb_strtoupper($request->nombres).$usuario->id,
            'email' => $request->email,
            'password' => Hash::make($randomString),
            'estado' => '1',
            'seccion' => '4',
            'session' => '1'
        ]);

        $user->assignRole($request->rol);

        
        // $verificacion = User::where('email',$request->email)->exists();
        // if ($verificacion) {
        //     return response()->json(['code' => $verificacion, 'mensaje' => 'El correo ya fue registrado revise sus datos.','tipo'=>'error', 'titulo'=>'Error']);
        // } else {
        //     try {
        //         DB::beginTransaction();
        //         $user = User::create([
        //             'percod' => $request->percodigo,
        //             'nick' => $request->nick,
        //             'email' => $request->email,
        //             'password' => Hash::make($randomString),
        //             'seccion' => 4
        //         ]);

        //         $user->assignRole($request->rol);
        //         DB::commit();

        //         Mail::to($request->email)
        //         ->send(new AccesoUsuarios($request, $randomString));
        //     } catch (\Exception $e) {
        //         DB::rollBack();
        //         return response()->json(['code' => $verificacion, 'mensaje' => 'Ocurrio un error al momento de registra al usuario.','tipo' => 'error', 'titulo'=>'Error']);
        //     }
        //     return response()->json(['code' => $verificacion, 'mensaje' => 'Usuario creado correctamente.','tipo' => 'success', 'titulo'=>'Aceptado']);
        // }
    }
    public function ListarUsuarios(Request $request)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            $usuarios = DB::table('users as u')
                    ->join('usuarios as us','u.id_usuario','us.id')
                    // ->join('model_has_roles as mr', 'u.id','mr.model_id')
                    // ->join('roles as r','mr.role_id','r.id')
                    // ->select('us.id as usuario_id','u.id as user_id','u.nick','u.estado','us.nombres','us.ap_paterno','us.ap_materno','r.name as role')
                    ->select('us.id as usuario_id','u.id as user_id','u.nick','u.estado','us.nombres','us.ap_paterno','us.ap_materno')
                    ->where('us.estado',1)
                    ->orderBy('u.id','desc')
                    ->paginate(10);
        } else {
            $usuarios = DB::table('users as u')
                    ->join('usuarios as us','u.id_usuario','us.id')
                    // ->join('model_has_roles as mr', 'u.id','mr.model_id')
                    // ->join('roles as r','mr.role_id','r.id')
                    // ->select('us.id as usuario_id','u.id as user_id','u.nick','u.estado','us.nombres','us.ap_paterno','us.ap_materno','r.name as role')
                    ->select('us.id as usuario_id','u.id as user_id','u.nick','u.estado','us.nombres','us.ap_paterno','us.ap_materno')
                    ->where(function($q) use ($buscar){
                            $q->where('us.ap_paterno','LIKE','%'.$buscar.'%')
                            ->orWhere('us.ap_materno','LIKE','%'.$buscar.'%')
                            ->orWhere('us.nombres','LIKE','%'.$buscar.'%');
                        }) 
                    ->where('us.estado',1)
                    ->orderBy('u.id','desc')
                    ->paginate(10);
        }
        return response()->json([
            'pagination' => [
                'total'         => $usuarios->total(),
                'current_page'  => $usuarios->currentPage(),
                'per_page'      => $usuarios->perPage(),
                'last_page'     => $usuarios->lastPage(),
                'from'          => $usuarios->firstItem(),
                'to'            => $usuarios->lastItem(),
            
            ],
            'usuarios' => $usuarios
        ]);       
    }

    public function DatosUsuarios(Request $request)
    {
        $user_id = $request->user_id;
        
        $usuarios = DB::table('users as u')
            ->join('usuarios as us','u.id_usuario','us.id')
            ->join('model_has_roles as mr', 'u.id','mr.model_id')
            ->join('roles as r','mr.role_id','r.id')
            ->select('us.id','u.nick','u.estado','us.nombres','us.ap_paterno','us.ap_materno','us.email','r.name as role')
            ->where('us.estado',1)
            ->where('u.id',$user_id)
            ->first();

        $rol = DB::table('model_has_roles as mr')
            ->join('roles as r','mr.role_id','r.id')
            ->select('r.id','r.name')
            ->where('mr.model_id',$user_id)
            ->first();

        return response()->json(['usuarios'=>$usuarios,'role'=>$rol]);
    }

    public function EditarUsuario(Request $request)
    {
        //id de user
        $usuario_id = $request->usuario_id;
        // DB::table('users')
        //     ->where('id',$id)
        //     ->update([
        //         'seccion' => $request->seccion
        //     ]);
        $usuario = Usuario::where('id',$usuario_id)
                ->first();
                $usuario->update([
                    'nombres' => mb_strtoupper($request->nombre),
                    'ap_paterno' => mb_strtoupper($request->ap_paterno),
                    'ap_materno' => mb_strtoupper($request->ap_materno),
                    'email' => $request->email,
                ]);
        $user = User::where('id_usuario',$usuario_id)
                ->first();
                $user->update([
                    'email' => $request->email,
                ]);
        return response()->json($request);
    }

    public function ListarPermisos(Request $request)
    {
        $user = User::find($request->id);
        $roles = $user->getAllPermissions();
        return response()->json($roles);
    }

    public function EditContrasena(Request $request)
    {
        $u = DB::table('users')
            ->select('password')
            ->where('id',Auth::user()->id)
            ->first();
        if (Hash::check($request->actpassword, $u->password)) {
            DB::table('users')
                ->where('id',Auth::user()->id)
                ->update([
                    'password' => Hash::make($request->newpassword),
                    'session' => 1
                ]);
            Auth::logout();
            $code = 200;
            return response()->json($code);
        } else {
            $code = 400;
            return response()->json($code);
        }
    }

    public function CambiarEstadoUsuario(Request $request)
    {
        $estado = 1 - $request->estado;
        $usuario = User::find($request->user_id);
        $usuario->estado = $estado;
        $usuario->save();

         return response()->json($estado);
    }
}
