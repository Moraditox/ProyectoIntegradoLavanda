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
use App\Models\OfertaPlaza;
use Illuminate\Support\Facades\Mail;

class ConvocatoriasController extends Controller
{

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
            ->with(['empresa', 'empresa.convocatorias', 'ofertaPlazas'])
            ->get();

        // Inicializa $empresaId con un valor predeterminado
        $empresaId = null;

        if (!$convocatoria_empresas->isEmpty()) {
            // Asigna $empresaId solo si $convocatoria_empresas no está vacío
            $empresaId = $convocatoria_empresas->first()->empresa_id;
        }

        $convocatoriaEmpresaPlazas = Convocatoria_Empresa_Plaza::where('convocatoria_id', $convocatoria->id)->get();
        $actuaciones = Actuaciones::where('id', $convocatoria->id)->get();
        $alumnosIds = $convocatoria->asignaciones()->pluck('alumnado_id');
        $actuaciones = Actuaciones::all();
       
        // Obtener las matrículas de los alumnos asociados a la convocatoria
        $matriculas = Matricula::with(['alumnado', 'alumnado.asignaciones.empresa'])
            ->join('alumnado', 'matricula.alumno_id', '=', 'alumnado.id')
             ->join('curso_academico', 'matricula.curso_academico_id', '=', 'curso_academico.id')
             ->join('ciclos', 'curso_academico.ciclo', '=', 'ciclos.ciclo')->whereIn('alumno_id', $alumnosIds)
             ->orderBy('ciclos.ciclo')
             ->orderBy('alumnado.apellido1')->get();

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
        $annos_consulta = DB::table('anno_academico')->orderByDesc('anno')->get();
        $annos = array();
        foreach ($annos_consulta as $anno) {
            $annos[$anno->anno] = $anno->anno;
        }
        $cursos_consulta = DB::table('curso_academico')->get();
        $cursos = array();
        foreach ($cursos_consulta as $curso) {
            $cursos[$curso->id] = $curso->curso . 'º ' . $curso->grupo . ' ' . $curso->ciclo . ' ' . $curso->turno;
        }
        $empresas_consulta = DB::table('empresas')->get();
        $empresas = array();
        foreach ($empresas_consulta as $empresa) {
            $empresas[$empresa->id] = $empresa->nombre . ' (CIF: ' . $empresa->cif . ')';
        }
        return view('convocatorias.create', compact('convocatoria', 'annos', 'cursos', 'empresas'));
    }

    /**
     * Store a newly created convocatoria in storage.
     *
     * This method validates the incoming request data using the rules defined
     * in the Convocatorias model. It creates a new convocatoria record, associates
     * it with the selected academic courses and companies, and saves the relationships
     * in the database.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request containing
     *                                          convocatoria data, selected academic courses,
     *                                          and optionally associated companies.
     *
     * @return \Illuminate\Http\RedirectResponse Redirects to the index route of convocatorias
     *                                            with a success message upon successful creation.
     *
     * @throws \Illuminate\Validation\ValidationException If the validation of the request data fails.
     */
    public function store(Request $request)
    {
        $request->validate(Convocatorias::$rules);

        // Creamos la convocatoria
        $valoresConvocatoria = $request->except('curso_academico', 'empresas');
        $convocatoria = new Convocatorias($valoresConvocatoria);
        $convocatoria->save();

        $cursos = $request->input('curso_academico');
        $convocatoria_cursos = array();
        foreach ($cursos as $curso) {
            $convocatoria_cursos[] = new Convocatoria_Cursos(['convocatoria_id' => $convocatoria->id, 'curso_academico_id' => $curso]);
        }
        $convocatoria->convocatoria_cursos()->saveMany($convocatoria_cursos);

        if (isset($request->empresas)) {

            $empresas = $request->input('empresas');
            $convocatoria_empresas = array();
            foreach ($empresas as $empresa) {
                $convocatoria_empresa = new Convocatoria_Empresa();
                $convocatoria_empresa->convocatoria_id = $convocatoria->id;
                $convocatoria_empresa->empresa_id = $empresa;
                $convocatoria_empresa->tareas_a_realizar = null;
                $convocatoria_empresa->perfil_requerido = null;
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
        $annos_consulta = DB::table('anno_academico')->orderByDesc('anno')->get();
        $annos = array();
        foreach ($annos_consulta as $anno) {
            $annos[$anno->anno] = $anno->anno;
        }
        $cursos_consulta = DB::table('curso_academico')->get();
        $cursos = array();
        foreach ($cursos_consulta as $curso) {
            $cursos[$curso->id] = $curso->curso . 'º ' . $curso->grupo . ' ' . $curso->ciclo . ' ' . $curso->turno;
        }
        $cursosSeleccionados = array();
        foreach ($convocatoria->convocatoria_cursos as $curso) {
            $cursosSeleccionados[] = $curso->curso_academico_id;
        }
        $empresas_consulta = DB::table('empresas')->get();
        $empresas = array();
        foreach ($empresas_consulta as $empresa) {
            $empresas[$empresa->id] = $empresa->nombre . ' (CIF: ' . $empresa->cif . ')';
        }
        $empresasSeleccionadas = array();
        foreach ($convocatoria->convocatoria_empresas as $empresa) {
            $empresasSeleccionadas[] = $empresa->empresa_id;
        }
        
        return view('convocatorias.edit', compact('convocatoria', 'annos', 'cursos', 'empresas', 'cursosSeleccionados', 'empresasSeleccionadas'));
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
        foreach ($cursos as $curso) {
            $convocatoria_cursos[] = new Convocatoria_Cursos(['convocatoria_id' => $convocatoria->id, 'curso_academico_id' => $curso]);
        }
        $convocatoria->convocatoria_cursos()->delete();
        $convocatoria->convocatoria_cursos()->saveMany($convocatoria_cursos);

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

        return redirect()->route('convocatorias.index')
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
        $convocatorias[] = Convocatorias::find($convocatoriaId);
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

        return view('empresa.editarEmpresaConvocatoriaForm', compact('empresa', 'convocatoria', 'convocatorias', 'alumnos', 'profesores', 'especialidades', 'plazas'));
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
        foreach ($especialidades as $especialidad) {
            if (in_array($especialidad['nombre'], $especialidadesUnicas)) {
                return redirect()->back()->withInput()->with('error', 'Las especialidades no pueden repetirse.');
            }
            $especialidadesUnicas[] = $especialidad['nombre'];
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

        return redirect()->route('convocatoria.show', ['convocatoria' => $convocatoriaId])
            ->with('success', 'La información de la empresa en la convocatoria ha sido actualizada correctamente.');
    }
}
