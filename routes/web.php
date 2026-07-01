<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/**
 * Rutas de funciones estandarizadas
 */

// RUTAS DGAE //

//**************************RUTAS DE ACCESO AL SISTEMA******************************* */


/**
 * Rutas para personal
 */
Route::post('/datPer','PersonalController@DatosPersonalesAcceso');

/**
 * RUTAS PARA CREACION DE USUARIOS
 */
Route::post('/crearUsuario','UsuarioController@CrearUsuario'); //BASE
Route::post('/listarUsuarios','UsuarioController@ListarUsuarios');
Route::post('/datosUsuarios','UsuarioController@DatosUsuarios'); 
Route::put('/editarUsuarios','UsuarioController@EditarUsuario'); //BASE
Route::put('/cambiarEstadoUsuario','UsuarioController@CambiarEstadoUsuario');
Route::get('/datosUsuario','UsuarioController@DatosUsuario');
Route::post('/editContrasena','UsuarioController@EditContrasena');

Route::post('/authenticate/ingreso','Auth\LoginController@login');
Route::get('/authenticate/salir','Auth\LoginController@logout')->name('logout');
Route::get('/listarPermisos','Auth\LoginController@ListarPermisos');
Route::get('/login','Auth\LoginController@VistaLogin')->name('login');

/**
 * rutas para la admnitraciond e datos
 */
Route::post('/listarol','RoleController@ListarRole');
Route::post('/listarol2','RoleController@ListarRole2'); //Roles qu no tiene aasignado el usuario
Route::post('/listarolus','RoleController@ListarRoleUsuario');
Route::post('/listarPermisos','RoleController@ListarPermisos');
Route::post('/listaRolPermiso','RoleController@ListaRolPermiso');
Route::post('/guardarRol','RoleController@GuardarRol');
Route::post('/editarRol','RoleController@EditarRol');
Route::get('/listarRoles','RoleController@ListarRoles'); //BASE
//rutas que permiten adicionar y quitar roles a los usuarios
Route::post('/agregarRol','RoleController@AgregarRol');
Route::post('/quitarRol','RoleController@QuitarRol');

/**
 * Rutas para listar permisos
 */
Route::post('/listapermisos','PermisoController@ListarPermisos');
Route::post('/guadarPermiso','PermisoController@GuardarPermisos');
Route::post('/editarPermiso','PermisoController@EditarPermisos');
Route::post('/datosPermiso','PermisoController@DatosPermiso');
Route::get('/listarModulos','PermisoController@ListarModulos');

//NOMBRE DEL USUARIO
Route::get('/datosP','DatosController@datosP');
Route::post('/crearPersonal','PersonalController@CrearPersonal');
Route::post('/listarPersonal','PersonalController@ListarPersonal');
Route::post('/renovarPersonal','PersonalController@RenovarPersonal');
Route::post('/eliminarPersonal','PersonalController@EliminarPersonal');
Route::get('/verificarPersonal/{id_per_lic}','PersonalController@VerificarPersonal');

Route::post('/datosPersonal','PersonalController@DatosPersonal');



/****************************** INICIO FUNCIONES *******************************************/
Route::post('/registrarEvento','EventosController@CrearEvento');
Route::post('/listarPredio','PrediosController@ListarPredio');
Route::post('/listarTipoEvento','TipoEventosController@ListarTipoEvento');
Route::post('/listarTarifa','TarifasController@ListarTarifa');
Route::post('/listarSituacion','SituacionesController@ListarSituacion');
Route::post('/listarEvento','EventosController@ListarEvento');
Route::post('/mostrarEvento','EventosController@MostrarEvento');
Route::post('/mostrarClasificacion','TipoPrediosController@MostrarTipoPredio');
Route::post('/editarEvento','EventosController@EditarEvento');
Route::post('/pagarSaldoEvento','EventosController@PagarSaldoEvento');
Route::post('/buscarPredio','PrediosController@BuscarPredio');
Route::post('/buscarClasificacion','TipoPrediosController@BuscarTipoPredio');
Route::post('/listarTipoPredio','TipoPrediosController@ListarTipoPredio');
Route::post('/registrarClasificacion','TipoPrediosController@CrearTipoPredio');
Route::post('/editarClasificacion','TipoPrediosController@EditarTipoPredio');
Route::post('/cambiarEstadoClasificacion','TipoPrediosController@CambiarEstadoTipoPredio');
Route::post('/registrarPredio','PrediosController@CrearPredio');
Route::post('/mostrarPredio','PrediosController@MostrarPredio');
Route::post('/editarPredio','PrediosController@EditarPredio');
Route::post('/cambiarEstadoPredio','PrediosController@CambiarEstadoPredio');
Route::post('/buscarTipoEvento','TipoEventosController@BuscarTipoEvento');
Route::post('/registrarTipoEvento','TipoEventosController@CrearTipoEvento');
Route::post('/mostrarTipoEvento','TipoEventosController@MostrarTipoEvento');
Route::post('/editarTipoEvento','TipoEventosController@EditarTipoEvento');
Route::post('/cambiarEstadoTipoEvento','TipoEventosController@CambiarEstadoTipoEvento');
Route::post('/buscarTarifa','TarifasController@BuscarTarifa');
Route::post('/registrarTarifa','TarifasController@CrearTarifa');
Route::post('/mostrarTarifa','TarifasController@MostrarTarifa');
Route::post('/editarTarifa','TarifasController@EditarTarifa');
Route::post('/cambiarEstadoTarifa','TarifasController@CambiarEstadoTarifa');





















Route::get('/contrato','ReporteController@GenerarContrato');


















/****************************** FIN FUNCIONES *******************************************/




/****************************** FUNCIONES DE EXPORTACION DE EXCEL *******************************************/

Route::get('/indGenDest','Excel\IndiceGenDestController@export');

Route::get('/{optional?}', function () {
    return view('app');
})->name('basepath')
   ->where('optional','.*');

