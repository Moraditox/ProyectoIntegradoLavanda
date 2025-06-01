<?php

namespace App\Http\Controllers;

use App\Models\Convocatorias;
use App\Models\Convocatoria_Cursos;
use App\Models\Convocatoria_Empresa;
use App\Models\Actuaciones;
use App\Models\Formulario_Seguimiento_Alumno;
use App\Models\Formulario_Seguimiento_Empresa;
use App\Models\Asignaciones;
use App\Models\Matricula;
use App\Models\Profesores;
use Illuminate\Http\Request;
use App\Models\Convocatoria_Empresa_Plaza;
use Illuminate\Support\Facades\DB;
use App\Models\Empresa;
use App\Mail\mailLavanda;
use App\Models\Alumnado;
use App\Models\curso_academico_new;
use App\Models\OfertaPlaza;
use Illuminate\Support\Facades\Mail;

class ConvocatoriasController extends Controller
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
     * Index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('home');
    }

    /**
     * Show
     *
     * @param  \App\Models\Convocatorias $convocatoria
     * @return \Illuminate\Http\Response
     */

    public function show(Convocatorias $convocatoria)
    {
        // ****
        // $matriculas = Matricula::join('alumnado', 'matricula.alumno_id', '=', 'alumnado.id')
        //     ->join('curso_academico', 'matricula.curso_academico_id', '=', 'curso_academico.id')
        //     ->join('ciclos', 'curso_academico.ciclo', '=', 'ciclos.ciclo')
        //     ->orderBy('ciclos.ciclo')
        //     ->orderBy('alumnado.apellido1');
            // ****

        $convocatoria_cursos = Convocatoria_Cursos::where('convocatoria_id', $convocatoria->id)
            ->with('curso_academico')
            ->get();

        $cursosUnicos = $convocatoria_cursos->groupBy('curso_academico.curso')->map(function ($cursoGroup) {
            return [
                'curso' => $cursoGroup->first()->curso_academico->curso,
                'ciclos' => $cursoGroup->pluck('curso_academico.ciclo')->implode(', ')
            ];
        });

        $empresasDisponibles = Empresa::whereDoesntHave('convocatorias', function ($query) use ($convocatoria) {
            $query->where('convocatoria_id', $convocatoria->id);
        })->get();

        // $convocatoria_empresas = Convocatoria_Empresa::where('convocatoria_id', $convocatoria->id)->get();
        $convocatoria_empresas = Convocatoria_Empresa::where('convocatoria_id', $convocatoria->id)
            ->with([
            'empresa',
            'empresa.convocatorias',
            'ofertaPlazas',
            'alumnoReferencia', // relación con Alumnado
            'profesorReferencia' // relación con Profesores
            ])
            ->get();

        // Inicializa $empresaId con un valor predeterminado
        $empresaId = null;

        if (!$convocatoria_empresas->isEmpty()) {
            // Asigna $empresaId solo si $convocatoria_empresas no está vacío
            $empresaId = $convocatoria_empresas->first()->empresa_id;
        }

        $convocatoriaEmpresaPlazas = Convocatoria_Empresa_Plaza::where('convocatoria_id', $convocatoria->id)->get();
        $actuaciones = Actuaciones::where('id', $convocatoria->id)->get();
        // $alumnosIds = $convocatoria->asignaciones()->pluck('alumnado_id');
        $actuaciones = Actuaciones::all();

        // Recuperamos todas las asignaciones de alumnos a esta convocatoria
        $matriculas = Asignaciones::with(['alumnado', 'empresa', 'profesor'])
            // ->whereIn('alumnado_id', $alumnosIds)
            ->where('convocatoria_id', $convocatoria->id)
            ->get();

        // Recorremos cada matricula y recuperamos el ciclo del alumno
        foreach ($matriculas as $matricula) {
            $cursoAcademico = DB::table('curso_academico_alumno')
                ->where('alumno_id', $matricula->alumnado_id)
                ->where('curso_academico_id', $convocatoria->anno_academico)
                ->select('ciclo_nombre')
                ->first();

            if ($cursoAcademico) {
                $matricula->ciclo = $cursoAcademico->ciclo_nombre;
            } else {
                $matricula->ciclo = 'No asignado';
            }
        }
    
        // Obtener los registros de curso_academico_alumno para los alumnos asociados a la convocatoria
        // $matriculas = \App\Models\Asignaciones::with(['alumnado', 'empresa', 'profesor'])
        //     ->whereIn('alumnado_id', $alumnosIds)
        //     ->get();
        
        $profesores = Profesores::all();

        return view('convocatorias.show', compact('convocatoriaEmpresaPlazas', 'empresasDisponibles', 'convocatoria', 'convocatoria_cursos', 'convocatoria_empresas', 'actuaciones', 'matriculas', 'cursosUnicos', 'profesores', 'empresaId'));
    }

    // public function empresasDisponibles(Convocatorias $convocatoria)
    // {
    //     // Obtener las empresas asociadas a la convocatoria
    //     $empresas = Convocatoria_Empresa::where('convocatoria_id', $convocatoria->id)->with('empresa')->get();

    //     return response()->json($empresas);
    // }
    public function empresasDisponibles(Convocatorias $convocatoria)
    {
        // Obtener las empresas asociadas a la convocatoria con el número de plazas
        $empresas = Convocatoria_Empresa_Plaza::where('convocatoria_id', $convocatoria->id)
            ->with('empresa')
            ->get();

        return response()->json($empresas);
    }

    public function destroyActuacion($id)
    {
        $actuacion = Actuaciones::findOrFail($id);
        $actuacion->delete();

        return redirect()->back()->with('success', 'Actuación eliminada con éxito.');
    }
    public function destroyConvocatoriaEmpresa($id)
    {
        $convocatoriaEmpresa = Convocatoria_Empresa::findOrFail($id);
        $convocatoriaEmpresa->delete();

        return redirect()->back()->with('success', 'Empresa eliminada de la convocatoria con éxito.');
    }

    // public function destroyConvocatoriaAlumno($id)
    // {
    //     $convocatoriaAlumno = Convocatoria_Alumno::findOrFail($id);
    //     $convocatoriaAlumno->delete();

    //     return redirect()->back()->with('success', 'Alumno eliminado de la convocatoria con éxito.');
    // }


    public function getProfesoresDisponibles(Request $request)
    {
        try {
            // Obtén todos los profesores disponibles
            $profesores = Profesores::all();

            return response()->json(['profesores' => $profesores]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener profesores.']);
        }
    }

    public function editarAsignacionEmpresa(Request $request)
    {
        $alumnoId = $request->alumnadoId;
        $empresaId = $request->empresaId;
        $profesorId = $request->profesorId;

        // Actualiza la asignación de la empresa y el profesor para el alumno
        Asignaciones::updateOrCreate(
            ['alumnado_id' => $alumnoId],
            ['empresa_id' => $empresaId, 'profesores_id' => $profesorId]
        );

        return response()->json(['success' => 'Datos actualizados correctamente.']);
    }




    /**
     * Create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Comprobamos que no haya ya una convocatoria en estado preparacion
        $convocatoriaPreparacion = Convocatorias::where('estado', 'Preparacion')->first();
        if ($convocatoriaPreparacion) {
            return redirect()->back()->with('error', 'Solo puede haber una convocatoria en estado de preparación.');
        }
        $convocatoria = new Convocatorias();

        $empresas_consulta = DB::table('empresas')->get();
        $empresas = array();
        foreach ($empresas_consulta as $empresa) {
            $empresas[$empresa->id] = $empresa->nombre . ' (CIF: ' . $empresa->cif . ')';
        }

        // Recuperamos el año académico actual más reciente
        $annoAcademico = curso_academico_new::orderBy('years', 'desc')->first();

        // Recuperamos todos los cursos académicos relacionados con el año académico actual
        $cursos = DB::table('curso_academico_alumno')
            ->where('curso_academico_id', $annoAcademico->id)
            ->pluck('ciclo_nombre')
            ->unique()
            ->values();

        return view('convocatorias.create', compact('convocatoria', 'annoAcademico', 'cursos', 'empresas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Convocatorias::$rules);

        $valoresConvocatoria = $request->except('curso_academico', 'empresas');

        $convocatoria = new Convocatorias($valoresConvocatoria);
        $convocatoria->save();

        $cursos = $request->input('curso_academico');
    
        // Guardamos los cursos asignados 
        $convocatoria->ciclosDisponibles()->attach($cursos);

        // Recorremos los cursos seleccionadmos y creamos una asignación para cada alumno
        if (!empty($cursos)) {
            foreach ($cursos as $curso) {
                // Recuperamos los alumnos que pertenecen a este curso
                $alumnos = DB::table('curso_academico_alumno')
                    ->where('ciclo_nombre', $curso)
                    ->pluck('alumno_id');
                // Recorremos los alumnos y creamos una asignación para cada uno
                foreach ($alumnos as $alumno) {
                    $asignacion = new Asignaciones();
                    $asignacion->convocatoria_id = $convocatoria->id;
                    $asignacion->alumnado_id = $alumno;
                    $asignacion->save();
                }
            }  
        }

        if (isset($request->empresas)) {

            $empresas = $request->input('empresas');
            $convocatoria_empresas = array();
            foreach ($empresas as $empresa) {
                $convocatoria_empresa = new Convocatoria_Empresa();
                $convocatoria_empresa->convocatoria_id = $convocatoria->id;
                $convocatoria_empresa->empresa_id = $empresa;
                $convocatoria_empresa->save();

                $convocatoria_empresas[] = $convocatoria_empresa;
            }

            $convocatoria->convocatoria_empresas()->saveMany($convocatoria_empresas);
        }

        return redirect()->route('convocatorias.index')
            ->with('success', 'La convocatoria ha sido añadida correctamente.');
    }

    /**
     * Edit
     *
     * @param  \App\Models\Convocatorias $convocatoria
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $convocatoria = Convocatorias::find($id);
        
        // Recuperamos el año académico asociado a la convocatoria
        $annoAcademico = curso_academico_new::find($convocatoria->anno_academico);
        
        // Recuperamos los cursos de este año académico
        $cursos = DB::table('curso_academico_alumno')
            ->where('curso_academico_id', $annoAcademico->id)
            ->pluck('ciclo_nombre')
            ->unique()
            ->values();
    
        // Recuperamos los cursos seleccionados en la convocatoria
        $cursosSeleccionados = DB::table('convocatoria_ciclo')
            ->where('convocatoria_id', $convocatoria->id)
            ->pluck('ciclo_nombre')
            ->toArray();

        $empresas_consulta = DB::table('empresas')->get();
        $empresas = array();
        foreach ($empresas_consulta as $empresa) {
            $empresas[$empresa->id] = $empresa->nombre . ' (CIF: ' . $empresa->cif . ')';
        }
        $empresasSeleccionadas = array();
        foreach ($convocatoria->convocatoria_empresas as $empresa) {
            $empresasSeleccionadas[] = $empresa->empresa_id;
        }
        
        return view('convocatorias.edit', compact('convocatoria', 'annoAcademico', 'cursos', 'empresas', 'cursosSeleccionados', 'empresasSeleccionadas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Convocatorias $convocatoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Convocatorias $convocatoria)
    {
        $request->validate(Convocatorias::$rules);

        $valoresConvocatoria = $request->except('curso_academico', 'empresas');
        $convocatoria->update($valoresConvocatoria);

        $cursos = $request->input('curso_academico');
        $convocatoria_cursos = [];
        // foreach ($cursos as $curso) {
        //     $convocatoria_cursos[] = new Convocatoria_Cursos(['convocatoria_id' => $convocatoria->id, 'curso_academico_id' => $curso]);
        // }
        
        // Guardamos los cursos asignados 
        if (empty($cursos)) {
            $convocatoria->ciclosDisponibles()->detach();
            Asignaciones::where('convocatoria_id', $convocatoria->id)->delete();
        } else {
            $convocatoria->ciclosDisponibles()->sync($cursos);

            // Obtener todos los alumnos que pertenecen a los cursos seleccionados
            $alumnosSeleccionados = collect();
            foreach ($cursos as $curso) {
                $alumnos = DB::table('curso_academico_alumno')
                    ->where('ciclo_nombre', $curso)
                    ->pluck('alumno_id');
                $alumnosSeleccionados = $alumnosSeleccionados->merge($alumnos);
            }
            $alumnosSeleccionados = $alumnosSeleccionados->unique();

            // Eliminar asignaciones de alumnos cuyos cursos ya no estén asociados a la convocatoria
            Asignaciones::where('convocatoria_id', $convocatoria->id)
                ->whereNotIn('alumnado_id', $alumnosSeleccionados)
                ->delete();

            // Recorremos los cursos seleccionados y creamos una asignación para cada alumno solo si no existe
            foreach ($cursos as $curso) {
                // Recuperamos los alumnos que pertenecen a este curso
                $alumnos = DB::table('curso_academico_alumno')
                    ->where('ciclo_nombre', $curso)
                    ->pluck('alumno_id');
                // Recorremos los alumnos y creamos una asignación solo si no existe
                foreach ($alumnos as $alumno) {
                    $existe = Asignaciones::where('convocatoria_id', $convocatoria->id)
                        ->where('alumnado_id', $alumno)
                        ->exists();
                    if (!$existe) {
                        Asignaciones::create([
                            'convocatoria_id' => $convocatoria->id,
                            'alumnado_id' => $alumno,
                            'empresa_id' => null,
                            'profesores_id' => null
                        ]);
                    }
                }
            }
        }

        $empresas = $request->input('empresas');
        // $convocatoria->convocatoria_empresas()->delete();
        $convocatoria_empresas = [];

        if ($empresas != null) {
            foreach ($empresas as $empresa) {
                $convocatoria_empresa = new Convocatoria_Empresa();
                $convocatoria_empresa->convocatoria_id = $convocatoria->id;
                $convocatoria_empresa->empresa_id = $empresa;
                $convocatoria_empresas[] = $convocatoria_empresa;
            }
        }

        // $convocatoria->convocatoria_empresas()->saveMany($convocatoria_empresas);
        // Modifica el estado de la convocatoria según el input
        $convocatoria->estado = $request->input('estado');
        $convocatoria->save();

        return redirect()->route('convocatoria.show', ['convocatoria' => $convocatoria->id])
            ->with('success', 'La convocatoria ha sido actualizada correctamente.');
        }

    public function verFormularioSeguimiento($alumnoId)
    {
        $formularioDataAlumno = Formulario_Seguimiento_Alumno::where('alumnado_id', $alumnoId)->first();
        $formularioDataEmpresa = Formulario_Seguimiento_Empresa::where('alumnado_id', $alumnoId)->first();

        // Verifica si ambos formularios están vacíos
        if (!$formularioDataAlumno && !$formularioDataEmpresa) {
            return response()->json(['error' => 'No hay formularios de seguimiento de este alumno.']);
        }

        return view('verFormulario', compact('formularioDataAlumno', 'formularioDataEmpresa'));
    }

    // Método para editar la empresa de una convocatoria
    public function editEmpresa(Request $request)
    {
        // Recupera los parámetros desde la ruta en lugar del request body
        $convocatoriaId = $request->route('convocatoria');
        $empresaId = $request->route('empresa');

        // Busca la relación Convocatoria_Empresa por convocatoria y empresa
        $convocatoriaEmpresa = Convocatoria_Empresa::where('convocatoria_id', $convocatoriaId)
            ->where('empresa_id', $empresaId)
            ->first();

        if (!$convocatoriaEmpresa) {
            return response()->json(['error' => 'No se encontró la empresa en la convocatoria.'], 404);
        }

        // Recuperamos la empresa
        $empresa = Empresa::find($empresaId);
        if (!$empresa) {
            return response()->json(['error' => 'No se encontró la empresa.'], 404);
        }

        $empresa->profesorId = $convocatoriaEmpresa->profesor_referencia_id;
        $empresa->alumnoId = $convocatoriaEmpresa->alumno_referencia_id;
        $empresa->observaciones = $convocatoriaEmpresa->observaciones;

        // Recuperamos la convocatoria
        $convocatoria = Convocatorias::find($convocatoriaId);


        // Recuperamos las especialidades de la empresa
        $especialidades = DB::table('ciclos_disponibles')
            ->pluck('especialidad')
            ->unique()
            ->values()
            ->all();

        // Recuperamos los alumnos y profesores asociados a la convocatoria
        // SIN IMPLEMENTAR, AHORA MISMO SE SELECCIONAN TODOS 
        $alumnos = Alumnado::all();
        $profesores = DB::table('profesores')->get();

        // Recuperamos las plazas asociadas a la empresa en esta convocatoria si es que tiene
        $plazas = OfertaPlaza::where('relacion_convocatoria_empresa_id', $convocatoriaEmpresa->id)
            ->with('convocatoriaEmpresa')
            ->get();

        return view('empresa.editarEmpresaConvocatoriaForm', compact('empresa', 'convocatoria', 'alumnos', 'profesores', 'especialidades', 'plazas'));
    }

    // Método que recibe la request del formulario y actualiza la info de empresa en la convocatoria
    public function updateEmpresa(Request $request)
    {
        $convocatoriaId = $request->route('convocatoria');
        $empresaId = $request->route('empresa');

        // Validar los datos del formulario
        $request->validate([
            'convocatoria_id' => 'required|exists:convocatorias,id',
            'alumno_referencia_id' => 'nullable|exists:alumnado,id',
            'profesor_referencia_id' => 'nullable|exists:profesores,id',
            'observaciones' => 'nullable|string',
        ]);

        // Comprobar que las especialidades no se repiten
        $especialidades = $request->input('especialidades');
        $especialidadesUnicas = [];
        if (isset($especialidades)) {
            foreach ($especialidades as $especialidad) {
                if (in_array($especialidad['nombre'], $especialidadesUnicas)) {
                    return redirect()->back()->withInput()->with('error', 'Las especialidades no pueden repetirse.');
                }
                $especialidadesUnicas[] = $especialidad['nombre'];
            }
        }

        // Buscar la relación Convocatoria_Empresa
        $convocatoriaEmpresa = Convocatoria_Empresa::where('convocatoria_id', $convocatoriaId)
            ->where('empresa_id', $empresaId)
            ->first();

        if (!$convocatoriaEmpresa) {
            return redirect()->back()->with('error', 'No se encontró la relación empresa-convocatoria.');
        }

        // Actualizar los campos de la relación
        $convocatoriaEmpresa->alumno_referencia_id = $request->input('alumno_referencia_id');
        $convocatoriaEmpresa->profesor_referencia_id = $request->input('profesor_referencia_id');
        $convocatoriaEmpresa->observaciones = $request->input('observaciones');
        $convocatoriaEmpresa->save();

        // Eliminar las plazas anteriores asociadas a esta relación
        OfertaPlaza::where('relacion_convocatoria_empresa_id', $convocatoriaEmpresa->id)->delete();

        // Crear las nuevas plazas
        if (isset($especialidades)) {
            foreach ($especialidades as $especialidad) {
                OfertaPlaza::create([
                    'relacion_convocatoria_empresa_id' => $convocatoriaEmpresa->id,
                    'especialidad' => $especialidad['nombre'],
                    'plazas' => $especialidad['plazas'],
                    'observaciones' => $especialidad['observaciones'],
                    'perfil' => $especialidad['perfil'],
                    'tareas' => $especialidad['tareas']
                ]);
            }
        }

        return redirect()->route('convocatoria.show', ['convocatoria' => $convocatoriaId])
            ->with('success', 'La información de la empresa en la convocatoria ha sido actualizada correctamente.');
    }
}
