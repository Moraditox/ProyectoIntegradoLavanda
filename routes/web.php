<?php

use App\Http\Controllers\CursosAcademicosController;
use App\Http\Controllers\AsignacionesController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\AlumnadoController;
use App\Http\Controllers\ProfesoradoController;
use App\Http\Controllers\ConvocatoriasController;
use App\Http\Controllers\ActuacionesController;
use App\Http\Controllers\ActuacionesEmpresaController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\CursoAcademicoNewController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\FileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('empresas', EmpresaController::class)->middleware('auth');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/profesorado', [App\Http\Controllers\ProfesoradoController::class, 'index'])->name('profesorado')->middleware('auth');
Route::post('/profesorados/import', [ProfesoradoController::class, 'import'])->name('profesorados.import')->middleware('auth');
Route::post('/profesorados/uploadImages', [ProfesoradoController::class, 'uploadImages'])->name('profesorados.uploadImages')->middleware('auth');
Route::get('/trabajadores', [App\Http\Controllers\TrabajadorController::class, 'create'])->name('trabajadores')->middleware('auth');
Route::post('/trabajadores', [App\Http\Controllers\TrabajadorController::class, 'store'])->name('trabajadores.store')->middleware('auth');
// actuaciones
Route::get('/actuaciones/create', [ActuacionesController::class, 'create'])->name('actuaciones.create')->middleware('auth');
// Ruta para el formulario
Route::match(['get', 'post'], '/actuaciones/storeManual', [ActuacionesController::class, 'storeManual'])->name('actuaciones.storeManual');
Route::match(['get', 'post'], '/actuaciones_empresa/storeManual', [ActuacionesEmpresaController::class, 'storeManual'])->name('actuaciones_empresa.storeManual');
// Resto de las rutas
Route::resource('actuaciones', ActuacionesController::class)->except(['show']);

// buscador fct
Route::get('/matriculas', [MatriculaController::class, 'index'])->name('matriculas.index');

// buscador empresa en convocatoria
Route::get('/empresa', [EmpresaController::class, 'indexConvocatoria'])->name('empresa.indexConvocatoria');

// eliminar alumnos de la convocatoria
//Route::delete('/convocatoria-alumnos/{convocatoria_alumno}', [ConvocatoriasController::class, 'destroyConvocatoriaAlumno'])->name('convocatoria_alumnos.destroy');

Route::get('/empresas-disponibles/{convocatoria}', [App\Http\Controllers\ConvocatoriasController::class, 'empresasDisponibles'])->name('empresasDisponibles');
Route::get('/profesores-disponibles', [App\Http\Controllers\ConvocatoriasController::class, 'getProfesoresDisponibles'])->name('getProfesoresDisponibles');

Route::get('/ver-formulario/{alumnoId}', [App\Http\Controllers\ConvocatoriasController::class, 'verFormularioSeguimiento'])->name('verFormularioSeguimiento');
// Route::get('ver-formulario/{alumnoId}', [App\Http\Controllers\ConvocatoriasController::class, 'verFormularioSeguimiento'])->name('verFormularioSeguimiento');

Route::get('/empresa/participacion/{token}/{convocatoria_id}', [EmpresaController::class, 'mostrarFormularioParticipacion'])
    ->name('empresa.participacion');

Route::post('/procesar-formulario/{token}/{convocatoria_id}', [EmpresaController::class, 'procesarFormularioParticipacion'])
    ->name('empresa.participacion.procesar');


Route::get('/alumnos/{id}/ver-ficha-semanal', [App\Http\Controllers\AlumnadoController::class, 'verFichaSemanal'])
    ->name('ver_ficha_semanal');

Route::get('/alumnos/{id}/ver-informe-alumnado', [App\Http\Controllers\AlumnadoController::class, 'verInformeAlumnado'])
    ->name('ver_informe_alumnado');

Route::get('/alumnos/{id}/ver-informe-profesorado', [App\Http\Controllers\AlumnadoController::class, 'verInformeProfesorado'])
    ->name('ver_informe_profesorado');

Route::post('/editar-asignacion-empresa', [App\Http\Controllers\ConvocatoriasController::class, 'editarAsignacionEmpresa'])
    ->name('editar-asignacion-empresa');
Route::post('/informes', [App\Http\Controllers\InformesAlumnadoController::class, 'store'])->name('informes.store');
// subida informes
Route::post('/informes-alumnado/store', [App\Http\Controllers\InformesAlumnadoController::class, 'store'])->name('informes-alumnado.store');
Route::post('/convocatorias/update/{convocatoria}', [App\Http\Controllers\ConvocatoriasController::class, 'update'])->name('convocatoria.update')->middleware('auth');
Route::get('/asignaciones/{convocatoria_id}/{curso_id}', [App\Http\Controllers\AsignacionesController::class, 'show'])->name('asignaciones.show')->middleware('auth');
;
Route::post('/asignaciones/{convocatoria_id}', [App\Http\Controllers\AsignacionesController::class, 'store'])->name('asignaciones.store')->middleware('auth');
;
Route::post("/asignar-empresa", [App\Http\Controllers\AsignacionesController::class, 'asignarEmpresa'])
    ->name('asignar-empresa');
Route::post("/asignar-profesor", [App\Http\Controllers\AsignacionesController::class, 'asignarProfesor'])
    ->name('asignar-profesor');
Route::get('/login-google', function () {
    return Socialite::driver('google')->redirect();
});
Route::get('/google-callback', function () {
    $user = Socialite::driver('google')->user();

    $userExists = User::where('email', $user->email)->first();
    if ($userExists) {
        Auth::login($userExists);
        return redirect()->route('home');
    } else {
        return redirect()->route('loginError');
    }
});

Route::get('/loginError', function () {
    return view('auth.loginError');
})->name('loginError');

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::delete('/actuaciones/{actuacion}', [ConvocatoriasController::class, 'destroyActuacion'])->name('actuaciones.destroy');
Route::get('/alumnos', [App\Http\Controllers\AlumnadoController::class, 'listadoCursos'])->name('alumnos')->middleware('auth');
Route::get('/alumnos/{anno}/{curso}', [App\Http\Controllers\AlumnadoController::class, 'infoCurso'])->name('alumnos.infoCurso')->middleware('auth');
Route::get('/alumnos/{alumno}', [App\Http\Controllers\AlumnadoController::class, 'infoAlumno'])->name('alumnos.infoAlumno')->middleware('auth');
Route::get('/alumnosBuscar', [App\Http\Controllers\AlumnadoController::class, 'buscar'])->name('alumnos.buscar')->middleware('auth');
Route::post('/guardar-informe-alumno-empresa', [AlumnadoController::class, 'guardarInformeAlumnoEmpresa'])->name('guardarInformeAlumnoEmpresa');
Route::get('/listadoEmpresas', [App\Http\Controllers\EmpresaController::class, 'listadoEmpresas'])->name('empresa.listadoEmpresas')->middleware('auth');
Route::get('/empresa/{nombre}/convocatorias', [App\Http\Controllers\EmpresaController::class, 'convocatorias'])->name('empresa.convocatorias')->middleware('auth');
Route::post('/procesar-formulario', [AlumnadoController::class, 'procesarFormulario'])->name('ruta_para_procesar_formulario');
Route::delete('/convocatoria-empresas/{convocatoria_empresa}', [ConvocatoriasController::class, 'destroyConvocatoriaEmpresa'])->name('convocatoria_empresas.destroy');


Route::get('/empresa/{token}', [App\Http\Controllers\EmpresaController::class, 'mail'])->name('empresa.mail');

// No sé si esta ruta se usa
Route::get('/empresa/{token}/unirse-convocatoria/', [\App\Http\Controllers\EmpresaController::class, 'unirseConvocatoria'])->name('unirseConvocatoria');

Route::get('/empresa/seguimiento/{token}', [App\Http\Controllers\EmpresaController::class, 'mailSeguimiento'])->name('empresa.mail_seguimiento');

Route::get('/empresa/informe/{token}', [App\Http\Controllers\EmpresaController::class, 'mailInforme'])->name('empresa.mail_informe');

Route::get('/empresa/{token}/alumnos/', [\App\Http\Controllers\EmpresaController::class, 'mostrarAlumnos'])->name('alumnosEmpresa');
Route::get('/empresa/{empresa_id}/detalle', [App\Http\Controllers\EmpresaController::class, 'detalle'])->name('empresa.detalle');

Route::get('/empresa/{token}/alumnos/{alumno}', [\App\Http\Controllers\EmpresaController::class, 'informeEmpresaAlumno'])->name('informeEmpresaAlumno');

Route::post('/empresa/{token}/alumnos/{alumno}/form/', [\App\Http\Controllers\EmpresaController::class, 'guardarInformeEmpresaAlumno'])->name('guardarInformeEmpresaAlumno');
Route::get('/convocatorias/get-empresa-details/{empresaId}', [ConvocatoriasController::class, 'getConvocatoriaEmpresaDetails']);

Route::post('/informeUpload/{token}', [\App\Http\Controllers\InformeController::class, 'upload'])->name('informe.upload');

Route::post('/informeUpload/alumno/{token}', [\App\Http\Controllers\InformeController::class, 'uploadAlumno'])->name('informeAlumno.upload');

Route::get('/alumnado/informe/{token}', [App\Http\Controllers\AlumnadoController::class, 'mail'])->name('alumno.mail');
Route::get('/empresa/informe/{token}', [App\Http\Controllers\EmpresaController::class, 'mail'])->name('empresa.mail');
Route::get('/alumnado/seguimiento/{token}', [App\Http\Controllers\AlumnadoController::class, 'mailSeguimiento'])->name('alumno.mail_seguimiento');

Route::get('/alumnado/{token}/empresa/{empresa}/form/', [\App\Http\Controllers\AlumnadoController::class, 'informeAlumnoEmpresa'])->name('informeAlumnoEmpresa');

Route::post('/alumnado/{token}/empresa/{empresa}/form/', [\App\Http\Controllers\AlumnadoController::class, 'guardarInformeAlumnoEmpresa'])->name('guardarInformeAlumnoEmpresa');
Route::get('/actuaciones-empresa/create', [App\Http\Controllers\ActuacionesEmpresaController::class, 'create'])
    ->name('actuaciones-empresa.create')->middleware('auth');
Route::post('/actuaciones/store', [ActuacionesEmpresaController::class, 'store'])->name('actuaciones_empresa.store')->middleware('auth');
// Route::get('/alumnado/{token}/informe/{')->name('mailInforme');


// para el menú de informes sobre las prácticas
Route::view('/menuInformes', 'menuInformes');

// para el manejo de informes PDF del alumnado
Route::get('/file', [FileController::class, 'index'])->name('file');
Route::post('/file', [FileController::class, 'store'])->name('file.store');

// para el manejo de informes PDF del profesorado
use App\Http\Controllers\InformesProfesoradoController;

Route::get('/informesProfesorado', [InformesProfesoradoController::class, 'index'])->name('informesProfesorado');
Route::post('/informesProfesorado', [InformesProfesoradoController::class, 'store'])->name('informesProfesorado.store');

// para el manejo de informes PDF de los tutores laborales
use App\Http\Controllers\InformesTutoresLaboralesController;

Route::get('/informesTutoresLaborales', [InformesTutoresLaboralesController::class, 'index'])->name('informesTutoresLaborales');
Route::post('/informesTutoresLaborales', [InformesTutoresLaboralesController::class, 'store'])->name('informesTutoresLaborales.store');

// NUEVAS RUTAS

// CURSOS
// Ruta para enseñar los cursos académicos
Route::get('/cursos', [CursoAcademicoNewController::class, 'index'])->name('cursos.index')->middleware('auth');
// Ruta para añadir profesores a un curso académico
Route::get('/cursos/{courseId}/assign-professors', [CursoAcademicoNewController::class, 'addTeachersToCourse'])->name('cursos.assingTeachers')->middleware('auth');
// Ruta que recoge el formulario para añadir profesores a un curso académico
Route::post('/curso_academico/{courseId}/storeTeachers', [CursoAcademicoNewController::class, 'storeTeachersToCourse'])->name('curso_academico.storeTeachers')->middleware('auth');

// Anno Academico
Route::post('/curso_academico', [CursosAcademicosController::class, 'store'])->name('curso_academico.store');

// Rutas de envío de correos
Route::get('/mailejemplo/{token}/{receptor}/{email}', [\App\Http\Controllers\MailController::class, 'EnviarCorreoPrueba'])->name('mail.ejemplo')->middleware('auth');
Route::get('/mailSeguimieto/{token}/{receptor}/{email}', [\App\Http\Controllers\MailController::class, 'EnviarCorreoSeguimiento'])->name('mail.enviarSeguimiento')->middleware('auth');
Route::get('/mailInforme/{token}/{receptor}/{email}', [\App\Http\Controllers\MailController::class, 'EnviarCorreoInforme'])->name('mail.enviarInforme')->middleware('auth');
Route::get('/enviar-correo-empresa/{empresaId}', [MailController::class, 'enviarCorreoEmpresa'])->name('enviar-correo-empresa');
Route::get('/enviar-correo-alumno/{matriculaId}', [App\Http\Controllers\MailController::class, 'enviarCorreoAlumno'])->name('enviar-correo-alumno');
Route::post('/enviar-correo-participar/{empresa}/{convocatoria}', [MailController::class, 'enviarCorreoParticipar'])
    ->name('enviar-correo-participar')->middleware('auth');
Route::get('/enviar-correo-participar/{empresa}/{convocatoria}', [MailController::class, 'handleGetRequestParticipar'])
    ->name('enviar-correo-participar.get');
Route::get('/enviar-correo-pdf-empresa/{empresaId}', [MailController::class, 'enviarCorreoPdfEmpresa'])
    ->name('enviar-correo-pdf-empresa');
Route::get('/enviar-correo-pdf-alumno/{alumnoId}', [MailController::class, 'enviarCorreoPdfAlumno'])
    ->name('enviar-correo-pdf-alumno');

// Rutas de convocatorias
Route::resource('convocatorias', ConvocatoriasController::class)->middleware('auth');
Route::get('/convocatorias/create', [App\Http\Controllers\ConvocatoriasController::class, 'create'])->name('convocatoria.create')->middleware('auth');
Route::post('/convocatorias', [App\Http\Controllers\ConvocatoriasController::class, 'store'])->name('convocatoria.store')->middleware('auth');
Route::get('/convocatorias/{convocatoria}', [App\Http\Controllers\ConvocatoriasController::class, 'show'])->name('convocatoria.show')->middleware('auth');
Route::get('/convocatorias/edit/{convocatoria}', [App\Http\Controllers\ConvocatoriasController::class, 'edit'])->name('convocatoria.edit')->middleware('auth');
Route::get('/convocatorias/editEmpresa/{convocatoria}/{empresa}', [App\Http\Controllers\ConvocatoriasController::class, 'editEmpresa'])->name('convocatoria.editEmpresa')->middleware('auth');
Route::post('/convocatorias/editEmpresa/{convocatoria}/{empresa}', [App\Http\Controllers\ConvocatoriasController::class, 'updateEmpresa'])->name('convocatoria.updateEmpresa')->middleware('auth');

// Rutas de alumnado
Route::get('/alumnado', [App\Http\Controllers\AlumnadoController::class, 'index'])->name('alumnado')->middleware('auth');
Route::post('/alumnados/import', [AlumnadoController::class, 'import'])->name('alumnados.import')->middleware('auth');
Route::post('/alumnados/uploadImages', [AlumnadoController::class, 'uploadImages'])->name('alumnados.uploadImages')->middleware('auth');

// Rutas de empresa
Route::get('/empresas/addToConvocatoria/{empresa}', [App\Http\Controllers\EmpresaController::class, 'unirseConvocatoriaForm'])->name('empresas.addConvocatoria')->middleware('auth');
Route::post('/empresas/addToConvocatoria/{empresa}', [App\Http\Controllers\EmpresaController::class, 'unirseConvocatoriaBoton'])->name('empresa.unirseConvocatoria')->middleware('auth');