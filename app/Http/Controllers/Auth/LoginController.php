<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Desactivar la caché para todas las rutas en este controlador
            $response = $next($request);
            return $response->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                            ->header('Pragma', 'no-cache')
                            ->header('Expires', '0');
        });
    }
    
    public function login(Request $request)
    {   
        $email = $request->email;
        $password = $request->password;
        // $respuesta = Auth::attempt(['email' => $email, 'password' => $password, 'estado'=>1]);
        if ($respuesta = Auth::attempt(['email' => $email, 'password' => $password, 'estado'=> 1, 'session' => 1])) {
            // User::where('id',Auth::user()->id)
            //     ->update([
            //         'session' => 0
            //     ]);
            Auth::logoutOtherDevices($password);
            return response()->json([
                'authUser' => Auth::user(),
                'code' => 200
            ]);
        } elseif ($respuesta = Auth::attempt(['email' => $email, 'password' => $password, 'estado'=> 1, 'session' => 0])) {  
            Auth::logoutOtherDevices($password);
            return response()->json([
                'authUser' => Auth::user(),
                'code' => 300
            ]);           
        } else {
            // $session = User::where('email',$email)->select('session')->first();
            // if ($session->session == 0) {
            //     return response()->json([
            //         'code' => 400
            //     ]);
            // } else {
                return response()->json([
                    'code' => 401
                ]);
            // }
            
        }
    }

    public function logout(Request $request)
    {   
        // User::where('id',Auth::user()->id)
        //     ->update([
        //         'session' => 1
        //     ]);

        Auth::logout();
        // return redirect("/");
        
        // $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // return redirect('/');
        return redirect('/')->with('message', 'Sesión cerrada correctamente.');

    }
    public function VistaLogin(Request $request)
    {   
        return redirect("/");
    }

    public function ver()
    {
        return response()->json([
            'authUser' => Auth::user()->per_codigo,
            'code' => 200
        ]);
    }
}
