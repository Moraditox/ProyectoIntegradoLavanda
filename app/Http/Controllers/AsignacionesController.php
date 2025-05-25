<?php

namespace App\Http\Controllers;

use App\Models\Alumnado;
use App\Models\Asignaciones;
use App\Models\Convocatoria_Empresa;
use App\Models\Convocatorias;
use App\Models\Curso_Academico;
use App\Models\Profesores;
use App\Models\Matricula;
use App\Models\Trabajadores;
use Illuminate\Http\Request;

/**
 * Class AsignacionesController
 * @package App\Http\Controllers
 */
class AsignacionesController extends Controller
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
     * Display the specified resource.
     *
     * @param  int $convocatoria_id
     * @return \Illuminate\Http\Response
     */
    public function show($convocatoria_id, $curso_id)
    {
        $convocatoria = Convocatorias::find($convocatoria_id);
        $curso = Curso_Academico::find($curso_id);
        $asignaciones_consulta = new Asignaciones();
        $asignaciones = array();
        foreach ($asignaciones_consulta->get() as $asignacion) {
            if ($asignacion->convocatoria_id == $convocatoria_id && $asignacion->alumnado->matricula->curso_academico_id == $curso_id) {
                $asignaciones[] = $asignacion;
            }
        }
        $alumnos = array();
        foreach (Alumnado::all() as $alumno) {
            $asignado = false;
            foreach ($asignaciones as $asignacion) {
                if ($asignacion->alumnado_id == $alumno->id) {
                    $asignado = true;
                }
            }
            if (!$asignado && $alumno->matricula->curso_academico_id == $curso_id && $alumno->matricula->anno_academico == $convocatoria->anno_academico) {
                $alumnos[] = $alumno;
            }
        }
        $profesores = Profesores::all();
        $trabajadores = Trabajadores::all()->groupBy('empresa_id');
        return view('asignaciones.show', compact('convocatoria', 'curso', 'asignaciones', 'alumnos', 'profesores', 'trabajadores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $convocatoria_id)
    {
        $alumnos = $request->input('alumnos');
        if (!filled($alumnos)) {
            return redirect()->back()->with('error', 'No se han seleccionado alumnos.');
        }

        $profesores_id = $request->input('profesores');
        $trabajador_id = $request->input('trabajador_id');

        foreach ($alumnos as $alumno_id) {
            $asignacion = new Asignaciones();
            $asignacion->convocatoria_id = $convocatoria_id;
            $asignacion->alumnado_id = $alumno_id;
            $asignacion->profesores_id = $profesores_id;  // Asignar profesor

            $trabajador = Trabajadores::find($trabajador_id);

            if ($trabajador) {
                $asignacion->trabajadores_id = $trabajador->id;
                $asignacion->empresa_id = $trabajador->empresa_id;
                $asignacion->save();
            } else {
                return redirect()->back()->with('error', 'No se encontró el trabajador.');
            }
        }
        $convocatoria_empresa = new Convocatoria_Empresa();
        $convocatoria_empresa_consulta = Convocatoria_Empresa::where('convocatoria_id', $convocatoria_id)->get();
        $trabajador = Trabajadores::find($request->input('trabajador_id'));
        $convocatoria_empresa->empresa_id = $trabajador->empresa_id;
        if (!$convocatoria_empresa_consulta->contains('empresa_id', $trabajador->empresa_id)) {
            $convocatoria_empresa->convocatoria_id = $convocatoria_id;
            $convocatoria_empresa->save();
        }
        return redirect()->route('asignaciones.show', [$convocatoria_id, $asignacion->alumnado->matricula()->first()->curso_academico_id])
            ->with('success', 'Asignaciones creadas correctamente.');
    }
    public function asignarEmpresa(Request $request)
    {
        $matriculaId = $request->input('matriculaId');
        $selectedEmpresaId = $request->input('selectedEmpresaId');

        $matricula = Matricula::find($matriculaId);

        $matricula->alumnado->asignaciones()->update(['empresa_id' => $selectedEmpresaId]);

        return redirect()->back()->with('success', 'Asignación de empresa exitosa.');
    }
    public function asignarProfesor(Request $request)
    {
        $matriculaId = $request->input('matriculaId');
        $selectedProfesorId = $request->input('selectedProfesorId');

        $matricula = Matricula::find($matriculaId);

        $matricula->alumnado->asignaciones()->update(['profesores_id' => $selectedProfesorId]);

        return redirect()->back()->with('success', 'Asignación de profesor exitosa.');
    }
}
