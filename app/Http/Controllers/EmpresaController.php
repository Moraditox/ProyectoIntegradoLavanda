<?php

namespace App\Http\Controllers;

use App\Models\Actuaciones;
use App\Models\Alumnado;
use App\Models\Anno_Academico;
use App\Models\Asignaciones;
use App\Models\Ciclos;
use App\Models\Empresa;
use App\Models\Convocatoria_Empresa;
use App\Models\Convocatoria_Empresa_Plaza;
use App\Models\Convocatorias;
use App\Models\InformesAlumnado;
use App\Models\InformesProfesorado;
use App\Models\Formulario_Seguimiento_Empresa;
use App\Models\OfertaPlaza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class EmpresaController
 * @package App\Http\Controllers
 */
class EmpresaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $empresas = Empresa::where(function ($query) use ($search) {
            $query->where('nombre', 'like', "%$search%")
                ->orWhere('cif', 'like', "%$search%")
                ->orWhere('representante_legal', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('movil', 'like', "%$search%");
        })->paginate();

        return view('empresa.index', compact('empresas'))
            ->with('i', ($request->input('page', 1) - 1) * $empresas->perPage());
    }

    public function indexConvocatoria(Request $request)
    {
        $search = $request->input('search');

        // Consulta para obtener las empresas que coinciden con la búsqueda
        $empresas = Empresa::where('nombre', 'like', "%$search%")->get();

        return view('empresa.indexConvocatoria', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresa = new Empresa();
        return view('empresa.create', compact('empresa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    request()->validate(Empresa::$rules);

    if ($request->hasFile('logo')) {
        $filename = $request->file('logo')->getClientOriginalName();
        $path = $request->file('logo')->storeAs('logos', $filename, 'public');
        info('Ruta del archivo almacenado: ' . $path);
    } else {
        $filename = null;
    }

    $empresa = new Empresa($request->all());
    $empresa->logo = $filename;
    $empresa->token = DB::raw('MD5(UUID())');
    $empresa->save();

    return redirect()->route('empresas.index')
        ->with('success', 'La empresa ha sido añadida correctamente.');
}


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empresa = Empresa::find($id);
        $empleados = $empresa->trabajadores()->paginate();
        $empresaId = $empresa->id;
        $alumnosFct = Alumnado::with(['profesor', 'matricula.curso_academico', 'asignacionesDetalles.convocatorias'])
            ->whereHas('asignacionesDetalles', function ($query) use ($empresaId) {
                $query->where('empresa_id', $empresaId);
            })
            ->get();

        $informesAlumnado = InformesAlumnado::where('empresa_id', $empresaId)->get();
        $informesProfesorado = InformesProfesorado::where('empresa_id', $empresaId)->get();
        // Media Alumnos
        $mediaPosibilidadesFormativasAlumnado = round($informesAlumnado->avg('posibilidades_formativas'), 1);
        $mediaCumplimientoProgramaAlumnado = round($informesAlumnado->avg('cumplimiento_programa'), 1);
        $mediaSeguimientoTutorTrabajoAlumnado = round($informesAlumnado->avg('seguimiento_tutor_trabajo'), 1);
        $mediaSeguimientoProfesorAlumnado = round($informesAlumnado->avg('seguimiento_profesor'), 1);
        $mediaPosibilidadesLaboralesAlumnado = round($informesAlumnado->avg('posibilidades_laborales'), 1);
        $mediaAdecuacionFormacionAlumnado = round($informesAlumnado->avg('adecuacion_formacion'), 1);
        $mediaNivelSatisfaccionAlumnado = round($informesAlumnado->avg('nivel_satisfaccion'), 1);
        $mediaValoracionGeneralAlumnado = round($informesAlumnado->avg('valoracion_general'), 1);
        // Media profesores
        $mediaPosibilidadesFormativasProfesorado = round($informesProfesorado->avg('posibilidades_formativas'), 1);
        $mediaCumplimientoProgramaProfesorado = round($informesProfesorado->avg('cumplimiento_programa'), 1);
        $mediaSeguimientoTutorTrabajoProfesorado = round($informesProfesorado->avg('seguimiento_tutor_trabajo'), 1);
        $mediaSeguimientoProfesorProfesorado = round($informesProfesorado->avg('seguimiento_profesor'), 1);
        $mediaPosibilidadesLaboralesProfesorado = round($informesProfesorado->avg('posibilidades_laborales'), 1);
        $mediaAdecuacionFormacionProfesorado = round($informesProfesorado->avg('adecuacion_formacion'), 1);
        $mediaNivelSatisfaccionProfesorado = round($informesProfesorado->avg('nivel_satisfaccion'), 1);
        $mediaValoracionGeneralProfesorado = round($informesProfesorado->avg('valoracion_general'), 1);
        // Calcular la media de los informes
        $mediaInformesAlumnado = round($informesAlumnado->avg('valoracion_general'));
        $mediaInformesProfesorado = round($informesProfesorado->avg('valoracion_general'));


        foreach ($alumnosFct as $alumno) {
            $profesor = $alumno->profesor;
            $curso = $alumno->matricula->cursoAcademico->curso ?? 'N/A';
            $ciclo = $alumno->matricula->cursoAcademico->ciclo ?? 'N/A';

            $alumno->curso = $curso;
            $alumno->ciclo = $ciclo;
            $alumno->nombre_profesor = optional($profesor)->nombre ?? 'N/A';
        }

        // Pasar todos los datos necesarios a la vista
        return view('empresa.show', compact(
            'empresa',
            'empleados',
            'alumnosFct',
            'informesAlumnado',
            'informesProfesorado',
            'mediaInformesAlumnado',
            'mediaInformesProfesorado',
            'mediaPosibilidadesFormativasAlumnado',
            'mediaCumplimientoProgramaAlumnado',
            'mediaSeguimientoTutorTrabajoAlumnado',
            'mediaSeguimientoProfesorAlumnado',
            'mediaPosibilidadesLaboralesAlumnado',
            'mediaAdecuacionFormacionAlumnado',
            'mediaNivelSatisfaccionAlumnado',
            'mediaValoracionGeneralAlumnado',
            'mediaPosibilidadesFormativasProfesorado',
            'mediaCumplimientoProgramaProfesorado',
            'mediaSeguimientoTutorTrabajoProfesorado',
            'mediaSeguimientoProfesorProfesorado',
            'mediaPosibilidadesLaboralesProfesorado',
            'mediaAdecuacionFormacionProfesorado',
            'mediaNivelSatisfaccionProfesorado',
            'mediaValoracionGeneralProfesorado'
        ));

    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empresa = Empresa::find($id);

        return view('empresa.edit', compact('empresa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Empresa $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empresa $empresa)
{
    request()->validate(Empresa::$rules);

    $empresa->update($request->all());

    if ($request->hasFile('logo')) {
        $filename = $request->file('logo')->getClientOriginalName();
        $path = $request->file('logo')->storeAs('logos', $filename, 'public');
        info('Ruta del archivo almacenado: ' . $path);
        $empresa->logo = $filename;
    }

    $empresa->save();

    return redirect()->route('empresas.index')
        ->with('success', 'La empresa ha sido actualizada correctamente.');
}

    

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $empresa = Empresa::findOrFail($id);
            // Elimina la instancia de la empresa
            $empresa->delete();

            // Elimina la imagen del logo si existe
            if (Storage::exists('public/logos/' . $empresa->logo)) {
                Storage::delete('public/logos/' . $empresa->logo);
            }

            return redirect()->route('empresas.index')
                ->with('success', 'La empresa ha sido eliminada correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'No se puede eliminar esta empresa porque tiene dependencias en la base de datos.');
        }
    }

    /**
     * Listado de empresas
     */
    public function listadoEmpresas(Request $request)
    {
        if ($request->input('search') == null) {
            $empresas = Empresa::all();
            return view('empresa.listadoEmpresas', compact('empresas'));
        } else {
            $search = $request->input('search');
            $empresas = Empresa::where('nombre', 'like', "%$search%")->paginate();

            return view('empresa.listadoEmpresas', compact('empresas'))
                ->with('i', (request()->input('page', 1) - 1) * $empresas->perPage());
        }
    }

    /**
     * Convocatorias de una empresa
     */
    public function convocatorias($nombre)
    {
        $empresa = Empresa::where('nombre', $nombre)->first();
        $convocatoria_empresa = Convocatoria_Empresa::where('empresa_id', $empresa->id)->get();
        return view('empresa.listadoConvocatorias', compact('empresa', 'convocatoria_empresa'));
    }

    /**
     * Muestra el formulario para unirse a una convocatoria
     */
    public function mail()
    {
        $token = request()->route('token');
        $empresa = Empresa::where('token', $token)->first();
        if (!$empresa) {
            abort(404);
        }
        $participa = false;
        try {
            $convocatoria = DB::table('convocatoria_empresa')->where('empresa_id', $empresa->id)->latest()->first()->convocatoria_id;
            $condicion = DB::table('convocatorias')->where('id', $convocatoria)->where('fecha_fin', '>', date('Y-m-d'))->first();
        } catch (\Throwable $th) {
            $condicion = null;
        }
        if ($condicion) {
            $participa = true;
        }
        return view('alumnadoVistaMail', compact('empresa', 'participa'));
    }

    public function mailSeguimiento()
    {
        $token = request()->route('token');
        $empresa = Empresa::where('token', $token)->first();
        if (!$empresa) {
            abort(404);
        }
        $participa = false;
        try {
            $convocatoria = DB::table('convocatoria_empresa')->where('empresa_id', $empresa->id)->latest()->first()->convocatoria_id;
            $condicion = DB::table('convocatorias')->where('id', $convocatoria)->where('fecha_fin', '>', date('Y-m-d'))->first();
        } catch (\Throwable $th) {
            $condicion = null;
        }
        if ($condicion) {
            $participa = true;
        }
        return view('empresaVistaMailSeguimiento', compact('empresa', 'participa'));
    }

    public function mailInforme()
    {
        $token = request()->route('token');
        $empresa = Empresa::where('token', $token)->first();
        if (!$empresa) {
            abort(404);
        }
        $participa = false;
        try {
            $convocatoria = DB::table('convocatoria_empresa')->where('empresa_id', $empresa->id)->latest()->first()->convocatoria_id;
            $condicion = DB::table('convocatorias')->where('id', $convocatoria)->where('fecha_fin', '>', date('Y-m-d'))->first();
        } catch (\Throwable $th) {
            $condicion = null;
        }
        if ($condicion) {
            $participa = true;
        }
        return view('empresaVistaMailInforme', compact('empresa', 'participa'));
    }

    /**
     * Añade la empresa a la convocatoria
     */
    public function unirseConvocatoria(Request $request)
    {
        $token = request()->route('token');
        $empresa = Empresa::where('token', $token)->first();
        $convocatoria_empresa = new Convocatoria_Empresa();
        $convocatoria_empresa->convocatoria_id = DB::table('convocatorias')->where('fecha_fin', '>', date('Y-m-d'))->first()->id;
        $convocatoria_empresa->empresa_id = $empresa->id;
        $convocatoria_empresa->numero_alumnos = $request->input('numero_alumnos');
        $convocatoria_empresa->tareas_a_realizar = $request->input('tareas_a_realizar');
        $convocatoria_empresa->perfil_requerido = $request->input('perfil_requerido');
        $convocatoria_empresa->save();
        return redirect()->route('empresa.mail', ["token" => $token])
            ->with('success', 'La empresa ha sido añadida correctamente a la convocatoria.');
    }

    public function mostrarAlumnos(Request $request)
    {
        $token = $request->route('token');
        $empresa = Empresa::where('token', $token)->first();

        if (!$empresa) {
            abort(404);
        }

        // Cargar la relación 'alumnado' y 'convocatoria' utilizando Eager Loading
        $asignaciones = Asignaciones::with(['alumnado', 'convocatorias'])
            ->where('empresa_id', $empresa->id)
            ->get();

        $alumnos = [];

        foreach ($asignaciones as $asignacion) {
            if ($asignacion->convocatorias->fecha_fin > date('Y-m-d')) {
                $alumnos[] = $asignacion->alumnado;
            }
        }

        return view('empresaVistaAlumnos', compact('alumnos', 'empresa'));
    }


    /**
     * Muestra el formulario de seguimiento de un alumno
     */
    public function informeEmpresaAlumno(Request $request)
    {
        $token = $request->route('token');
        $empresa = Empresa::where('token', $token)->first();
        if (!$empresa) {
            abort(404);
        }
        $alumno = Alumnado::find($request->route('alumno'));
        $ciclo = Ciclos::where('ciclo', $alumno->matricula->curso_academico->ciclo)->first()->ciclo;
        return view('empresaVistaInformeEmpresaAlumno', compact('alumno', 'empresa', 'ciclo'));
    }

    public function guardarInformeEmpresaAlumno(Request $request)
    {
        $token = $request->route('token');
        $alumno = Alumnado::find($request->alumnado_id);
        $asignacion = Asignaciones::where('alumnado_id', $alumno->id)->latest()->first();
        $empresa = Empresa::where('token', $token)->first();

        if (!$asignacion) {
            return redirect()->route('alumnosEmpresa', ['token' => $token])
                ->with('error', 'La asignación del alumno no existe.');
        }

        request()->validate(Formulario_Seguimiento_Empresa::$rules);

        $formulario = new Formulario_Seguimiento_Empresa($request->all());

        $formulario->id_convocatoria = $asignacion->convocatoria_id;

        $formulario->save();

        $actuacion = new Actuaciones([
            'emisor' => 'Tutor Laboral',
            'tipo' => 'Automatico',
            'observaciones' => 'La empresa ' . $empresa->nombre . ' ha hecho un seguimiento del alumno ' .
                $alumno->nombre . ' ' . $alumno->apellido1 . ' ' . $alumno->apellido2,
            'informe_empresa_id' => $formulario->id,
            'asignacion_id' => $asignacion->id
        ]);

        $actuacion->save();

        return redirect()->route('empresa.mail', ['token' => $token])
            ->with('success', 'El informe de la empresa se ha añadido correctamente.');
    }
    public function formularioSeguimientoAlumno(Request $request)
    {
        $token = $request->route('token');
        $alumnoId = $request->route('alumno');

        $empresa = Empresa::where('token', $token)->first();
        $alumno = Alumnado::find($alumnoId);

        if (!$empresa || !$alumno) {
            abort(404);
        }

        $ciclo = Ciclos::where('ciclo', $alumno->matricula->curso_academico->ciclo)->first()->ciclo;
        return view('formularioSeguimientoAlumno', compact('alumno', 'empresa', 'ciclo'));
    }

    public function mostrarFormularioParticipacion($token,$convocatoria_id)
    {
        $empresa = Empresa::where('token', $token)->first();
        if (!$empresa) {
            abort(404, 'Empresa no encontrada');
        }
        $convocatoria = Convocatorias::find($convocatoria_id);
       
        return view('empresaParticipacionFormulario', compact('empresa','convocatoria'));
    }
    public function procesarFormularioParticipacion(Request $request, $token, $convocatoria_id)
    {
        $request->validate([
            'observaciones' => 'nullable|string',
            'ciclo.*' => 'nullable|exists:ciclos,id', 
            'numero_plazas.*' => 'nullable|integer', 
            'perfil.*' => 'required|string', 
            'tareas.*' => 'required|string', 
        ]);

        $empresa = Empresa::where('token', $token)->first();
        if (!$empresa) {
            return redirect()->back()->with('error', 'Empresa no encontrada.');
        }

        $convocatoriaId = $request->input('convocatoria_id');

        if ($request->filled('ciclo') && $request->filled('numero_plazas')) {
            $ciclos = $request->input('ciclo');
            $numerosPlazas = $request->input('numero_plazas');
            $perfiles = $request->input('perfil');
            $tareas = $request->input('tareas');

            foreach ($ciclos as $index => $cicloId) {
                Convocatoria_Empresa_Plaza::create([
                    'empresa_id' => $empresa->id,
                    'convocatoria_id' => $convocatoriaId,
                    'ciclos_id' => $cicloId,
                    'numero_plazas' => $numerosPlazas[$index],
                    'perfil' => $perfiles[$index],
                    'tareas' => $tareas[$index],
                    'observaciones' => $request->observaciones,
                ]);
            }
        }

        $actuacion = new Actuaciones([
            'emisor' => 'Empresa',
            'tipo' => 'Automatico',
            'observaciones' => 'La empresa ' . $empresa->nombre . ' ha rellenado el formulario de participación.',
            'convocatoria_id' => $convocatoriaId,
        ]);
        $actuacion->save();

        return view('alumnadoVistaMail');
    }

    // Método que devuelve el formulario para unir una empresa a una convocatoria
    public function unirseConvocatoriaForm($id)
    {
        /**
         * Propuesta
         * Deshabilitar las convocatorias a las que la empresa ya pertenece
         */
        $empresa = Empresa::find($id);
        if (!$empresa) {
            abort(404);
        }

        // Recuperamos todas las convcocatorias del curso actual
        // *** SIN IMPLEMENTAR ***
        $convocatorias = Convocatorias::all();

        // Recuperamos a los profesores y alumnos relacionados al año academico actual
        // *** SIN IMPLEMENTAR ***
        $alumnos = Alumnado::all();
        $profesores = DB::table('profesores')->get();

        return view('empresa.unirseConvocatoriaForm', compact('empresa', 'convocatorias', 'alumnos', 'profesores'));
    }

    // Método que recoge los datos del formulario para unir una empresa a una convocatoria
    // Este método de unirse a la convocatoria se llama desde la pestaña de empresas
    // Además asigna plazas a la empresa en la convocatoria
    public function unirseConvocatoriaBoton(Request $request, $id)
    {
        $empresa = Empresa::find($id);
        if (!$empresa) {
            abort(404);
        }

        // Validar los datos del formulario
        $request->validate([
            'convocatoria_id' => 'required|exists:convocatorias,id',
            'alumno_id' => 'nullable|exists:alumnado,id',
            'profesor_id' => 'nullable|exists:profesores,id',
            'observaciones' => 'nullable|string'
        ]);

        // Guardar la relación entre la empresa y la convocatoria
        $convocatoria_empresa = new Convocatoria_Empresa();
        $convocatoria_empresa->convocatoria_id = $request->input('convocatoria_id');
        $convocatoria_empresa->empresa_id = $empresa->id;
        $convocatoria_empresa->alumno_referencia_id = $request->input('alumno_referencia_id');
        $convocatoria_empresa->profesor_referencia_id = $request->input('profesor_referencia_id');
        $convocatoria_empresa->observaciones = $request->input('observaciones');
        $convocatoria_empresa->save();

        // Asignamos las plazas a la empresa en la convocatoria
        // Recorremos el apartado de especialidades del request y guardamos cada una de ellas
        $especialidades = $request->input('especialidades');
        foreach ($especialidades as $especialidad) {
            OfertaPlaza::create([
                'relacion_convocatoria_empresa_id' => $convocatoria_empresa->id,
                'especialidad' => $especialidad['nombre'],
                'plazas' => $especialidad['plazas'],
                'observaciones' => $especialidad['observaciones']
            ]);
        }

        return redirect()->route('empresas.index')
            ->with('success', 'La empresa ha sido añadida correctamente a la convocatoria.');
    }
}
