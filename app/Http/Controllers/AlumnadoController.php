<?php

namespace App\Http\Controllers;

use App\Models\Actuaciones;
use App\Models\Alumnado;
use App\Models\Anno_Academico;
use App\Models\Asignaciones;
use App\Models\Ciclos;
use App\Models\Convocatoria_Cursos;
use App\Models\Convocatorias;
use App\Models\Curso_Academico;
use App\Models\Empresa;
use App\Models\Formulario_Seguimiento_Alumno;
use App\Models\Formulario_Seguimiento_Empresa;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
class AlumnadoController extends Controller
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
     * Show the index page.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $actionImportar = route('alumnados.import');
    //     $actionImagenes = route('alumnados.uploadImages');
    //     $cursos = Curso_Academico::all();
    //     $convocatorias = Convocatorias::all();

    //     // Eager load the curso_academico relationship
    //     $matriculas = Matricula::with('curso_academico')->get();

    //     return view('alumnado.importarAlumnos', compact('actionImportar', 'actionImagenes', 'cursos', 'convocatorias', 'matriculas'));
    // }

    // public function index()
    // {
    //     $actionImportar = route('alumnados.import');
    //     $actionImagenes = route('alumnados.uploadImages');
    //     $cursos = Curso_Academico::all();
    //     $convocatorias = Convocatorias::all();

    //     // Obtener las matrículas ordenadas alfabéticamente por apellido
    //     $matriculas = Matricula::with(['alumnado', 'curso_academico'])
    //         ->join('alumnado', 'matricula.alumno_id', '=', 'alumnado.id')
    //         ->orderBy('alumnado.apellido1')
    //         ->orderBy('alumnado.apellido2')
    //         ->orderBy('alumnado.nombre')
    //         ->get();

    //     return view('alumnado.importarAlumnos', compact('actionImportar', 'actionImagenes', 'cursos', 'convocatorias', 'matriculas'));
    // }

    public function index()
    {
        $actionImportar = route('alumnados.import');
        $actionImagenes = route('alumnados.uploadImages');
        $cursos = Curso_Academico::all();
        $convocatorias = Convocatorias::all();

        // Obtener las matrículas ordenadas alfabéticamente por apellido
        $alumnos = Alumnado::orderBy('apellido1')
            ->orderBy('apellido2')
            ->orderBy('nombre')
            ->get();

        return view('alumnado.importarAlumnos', compact('actionImportar', 'actionImagenes', 'cursos', 'convocatorias', 'alumnos'));
    }



    /**
     * Importar alumnos.
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:csv,txt',
            'curso' => 'required',
            'convocatoria' => 'required',
        ]);

        $convocatoriaId = $request->input('convocatoria');
        $cursoId = $request->input('curso');
        $annoActual = now()->year . '-' . (now()->year + 1);

        $archivo = $request->file('archivo');
        $file_path = $archivo->getPathName();

        $csv_data = array_map('str_getcsv', file($file_path));

        // El primer elemento del array es el encabezado, se usa como claves para cada fila
        $encabezado = array_shift($csv_data);

        $filas = array();
        foreach ($csv_data as $fila) {
            $filas[] = array_combine($encabezado, $fila);
        }

        // Añadir un nuevo anno academico si no existe
        $anno_actual = now()->year;
        $anno_actual = $anno_actual . '-' . ($anno_actual + 1);
        $annos = Anno_Academico::get()->pluck('anno')->toArray();
        $anno_existe = false;
        foreach ($annos as $anno) {
            if ($anno == $anno_actual) {
                $anno_existe = true;
            }
        }
        if (!$anno_existe) {
            $anno = new Anno_Academico();
            $anno->anno = $anno_actual;
            $anno->save();
        }

        // Procesar y guardar los datos en la base de datos
        $alumnosDuplicados = [];

        foreach ($filas as $fila) {
            // Verifica si el alumno ya está matriculado en la misma convocatoria
            $alumnoExistente = Matricula::whereHas('alumnado', function ($query) use ($fila) {
                $query->where('nie', $fila['nie']);
            })->whereHas('asignaciones', function ($query) use ($convocatoriaId) {
                $query->where('convocatoria_id', $convocatoriaId);
            })->exists();



            if ($alumnoExistente) {
                // Agrega el ID del alumno a la lista de duplicados
                $alumnosDuplicados[] = $fila['nie'];

                continue;
            }

            // Resto de tu lógica para insertar al nuevo alumno
            $alumno = new Alumnado();
            $alumno->apellido1 = $fila['apellido1'];
            $alumno->apellido2 = $fila['apellido2'];
            $alumno->nombre = $fila['nombre'];
            $alumno->nie = $fila['nie'];
            $alumno->email_corporativo = $fila['email_corporativo'];
            $alumno->email_personal = $fila['email_personal'];
            $alumno->dni = $fila['dni'];
            $alumno->movil = $fila['movil'];
            $alumno->imagen = $fila['nie'] . '.jpg';
            $alumno->token = DB::raw('MD5(UUID())');
            $alumno->save();

            // Matricular al alumno en el curso actual
            $matricula = new Matricula();
            $matricula->alumno_id = $alumno->id;
            $matricula->curso_academico_id = $request->curso;
            $matricula->anno_academico = $anno_actual;
            $matricula->save();

            $asignacion = new Asignaciones();
            $asignacion->convocatoria_id = $convocatoriaId;
            $asignacion->alumnado_id = $alumno->id;
            $asignacion->save();
        }

        // Puedes retornar un mensaje con los IDs de alumnos duplicados
        return redirect()->route('alumnado')->with([
            'success' => 'Los alumnos se han importado correctamente.',
            'duplicados' => $alumnosDuplicados,
        ]);

    }



    /**
     * Upload images.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadImages(Request $request)
    {
        $archivos = $request->validate([
            'archivo.*' => 'image'
        ]);

        foreach ($archivos['archivo'] as $archivo) {
            $ruta = $archivo->storeAs('public/alumnado/perfil', $archivo->getClientOriginalName());
        }

        return redirect()->route('alumnado')
            ->with('success', 'Los archivos se han subido correctamente.');
    }

    /**
     * Listado de cursos page.
     */
    public function listadoCursos()
    {
        $cursos = Curso_Academico::all()->groupBy('ciclo');
        $annos = DB::table('anno_academico')->orderBy('anno', 'desc')->get();
        $matriculas = Matricula::all();
        return view('alumnado.listadoCursos', compact('cursos', 'annos', 'matriculas'));
    }

    /**
     * Info curso page.
     */
    public function infoCurso($anno, $curso)
    {
        $anno = Anno_Academico::where('anno', $anno)->first();
        $curso = Curso_Academico::where('id', $curso)->first();
        $matriculas = Matricula::where('curso_academico_id', $curso->id)->where('anno_academico', $anno->anno)->get();
        $alumnos = array();
        foreach ($matriculas as $matricula) {
            $alumnos[] = Alumnado::where('id', $matricula->alumno_id)->first();
        }
        
        return view('alumnado.infoCurso', compact('alumnos', 'curso', 'anno'));
    }

    /**
     * Info alumno page.
     */
    public function infoAlumno($id)
    {
        $alumno = Alumnado::where('id', $id)->first();
        $matriculas = Matricula::where('alumno_id', $alumno->id)->orderBy('anno_academico', 'desc')->get();
        $asignaciones = Asignaciones::where('alumnado_id', $alumno->id)->orderBy('convocatoria_id')->get();
        return view('alumnado.infoAlumno', compact('alumno', 'matriculas', 'asignaciones'));
    }

    /**
     * Buscar alumnos.
     */
    public function buscar(Request $request)
    {
        $nombre = $request->input('nombre');
        $apellido1 = $request->input('apellido1');
        $apellido2 = $request->input('apellido2');

        $query = Alumnado::query();

        if ($nombre) {
            $query->where('nombre', 'like', "%$nombre%");
        }
        if ($apellido1) {
            $query->where('apellido1', 'like', "%$apellido1%");
        }
        if ($apellido2) {
            $query->where('apellido2', 'like', "%$apellido2%");
        }

        $query->orderBy('id', 'desc');
        $alumnos = $query->paginate();

        return view('alumnado.listadoAlumnos', compact('alumnos'))
            ->with('i', ($alumnos->currentPage() - 1) * $alumnos->perPage());
    }

    public function mail()
    {
        
        return view('alumnadoVistaMail');
    }


    public function mailSeguimiento()
    {
        $token = request()->route('token');
        $alumno = Alumnado::where('token', $token)->first();

        if (!$alumno) {
            abort(404);
        }

        // Obtiene la última asignación del alumno
        $asignacion = $alumno->asignaciones()->latest()->first();
        $matricula = $alumno->matriculas()->latest()->first();
        if (!$asignacion) {
            // Manejar el caso en que el alumno no tenga asignaciones
            abort(404, 'Asignación no encontrada');
        }

        // Obtiene la empresa asociada a la asignación
        $empresa = $asignacion->empresa;
        if (!$empresa) {
            // Manejar el caso en que no se encuentra la empresa
            abort(404, 'Empresa no encontrada');
        }
        if (!$matricula) {
            // Manejar el caso en que el alumno no tenga matrículas
            abort(404, 'Matrícula no encontrada');
        }
        $ciclo = $matricula->curso_academico->ciclo;
        return view('alumnadoVistaMailSeguimiento', compact('alumno', 'empresa', 'ciclo'));
    }



    public function procesarFormulario(Request $request)
    {
        $alumnoId = $request->input('alumno_id');
        $alumno = Alumnado::find($alumnoId);

        // Aquí procesas los datos del formulario

        return redirect()->route('home')->with('success', 'Formulario enviado correctamente');
    }


    public function informeAlumnoEmpresa(Request $request)
    {
        $token = $request->route('token');
        $alumno = Alumnado::where('token', $token)->first();
        if (!$alumno) {
            abort(404);
        }
        $empresa = Empresa::find($request->route('empresa'));
        $ciclo = Ciclos::where('ciclo', $alumno->matricula->curso_academico->ciclo)->first()->ciclo;
        return view('alumnadoVistaInformeAlumnoEmpresa', compact('alumno', 'empresa', 'ciclo'));
    }

    public function guardarInformeAlumnoEmpresa(Request $request)
    {
        $token = $request->route('token');
        $alumno = Alumnado::where('token', $token)->first();
        $asignacion = Asignaciones::where('alumnado_id', $alumno->id)->latest()->first();
        request()->validate(Formulario_Seguimiento_Alumno::$rules);

        // Create the form data with all required fields
        $formData = $request->all();
        $formData['alumnado_id'] = $alumno->id; // Ensure alumnado_id is set

        $formulario = new Formulario_Seguimiento_Alumno($formData);
        $formulario->id_convocatoria = $asignacion->convocatoria_id;
        $formulario->save();
        $actuacion =  new Actuaciones(['emisor' => 'Alumno', 'tipo' => 'Automatico',
        'observaciones' => 'El alumno '. $alumno->nombre . ' ' . $alumno->apellido1 . ' ' . $alumno->apellido2 . ' ha hecho un seguimiento.',
        'informe_alumno_id'=> $formulario->id, 'asignacion_id' => $asignacion->id]);
        $actuacion->save();

        return redirect()->route('alumno.mail', ['token' => $token])
            ->with('success', 'El informe se ha añadido correctamente.');
    }


    public function verFichaSemanal($id)
    {
        $nombreArchivo = "ficha_semanal_{$id}.pdf";  
        if (Storage::disk('public')->exists("informes/fichas_semanales/{$nombreArchivo}")) {
            $contenidoArchivo = Storage::disk('public')->get("informes/fichas_semanales/{$nombreArchivo}");
            return Response::make($contenidoArchivo, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $nombreArchivo . '"',
            ]);
        } else {
            return abort(404);
        }
    }
    public function verInformeAlumnado($id)
    {
        $nombreArchivo = "informe_alumnado_{$id}.pdf";
        if (Storage::disk('public')->exists("informes/alumnado/{$nombreArchivo}")) {
            $contenidoArchivo = Storage::disk('public')->get("informes/alumnado/{$nombreArchivo}");
            return Response::make($contenidoArchivo, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $nombreArchivo . '"',
            ]);
        } else {
            return abort(404);
        }
    }
    public function verInformeProfesorado($id)
    {
        $nombreArchivo = "informe_profesorado_{$id}.pdf";
        if (Storage::disk('public')->exists("informes/profesorado/{$nombreArchivo}")) {
            $contenidoArchivo = Storage::disk('public')->get("informes/profesorado/{$nombreArchivo}");
            return Response::make($contenidoArchivo, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $nombreArchivo . '"',
            ]);
        } else {
            return abort(404);
        }
    }
}
