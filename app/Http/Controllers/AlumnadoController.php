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
use App\Models\Curso_academico_alumno;
use App\Models\curso_academico_new;
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

    public function index()
    {
        $actionImportar = route('alumnados.import');
        $actionImagenes = route('alumnados.uploadImages');

        // Recuperamos todos los ciclos disponibles
        $cursos = DB::table('ciclos_disponibles')->get();

        // Recuperamos la convocatoria en estado de preparación
        $convocatoria = Convocatorias::where('estado', 'Preparación')->first();

        // Recuperamos el curso academico más reciente
        $annoAcademico = curso_academico_new::orderBy('created_at', 'desc')->first();

        return view('alumnado.importarAlumnos', compact('actionImportar', 'actionImagenes', 'cursos', 'convocatoria', 'annoAcademico'));
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
            'anno_academico' => 'required'
        ]);
        
        $convocatoriaId = $request->input('convocatoria');

        $archivo = $request->file('archivo');
        $file_path = $archivo->getPathName();

        $csv_data = array_map('str_getcsv', file($file_path));

        // El primer elemento del array es el encabezado, se usa como claves para cada fila
        $encabezado = array_shift($csv_data);

        $filas = array();
        foreach ($csv_data as $fila) {
            $filas[] = array_combine($encabezado, $fila);
        }

        // Procesar y guardar los datos en la base de datos
        $alumnosDuplicados = [];

        foreach ($filas as $fila) {
            // Verifica si el alumno ya está matriculado en la misma convocatoria
            // $alumnoExistente = Matricula::whereHas('alumnado', function ($query) use ($fila) {
            //     $query->where('nie', $fila['nie']);
            // })->whereHas('asignaciones', function ($query) use ($convocatoriaId) {
            //     $query->where('convocatoria_id', $convocatoriaId);
            // })->exists();

            // Comprobamos si el alumno ya existe en la base de datos usando el DNI, no lo volvemos a insertar
            $alumnoExistente = Alumnado::where('nie', $fila['nie'])->exists();

            // En caso de que no exista, creamos un nuevo registro en alumnado
            if (!$alumnoExistente) {
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
            } else {
                // Si el alumno ya existe, lo buscamos
                $alumno = Alumnado::where('nie', $fila['nie'])->first();
            }

            // Vamos a comprobar si el alumno ya  está asignado a este curso academico con un ciclo
            $alumnoExiste = Curso_academico_alumno::where('alumno_id', $alumno->id)
                ->where('curso_academico_id', $request->anno_academico)
                ->exists();

            // Si el alumno ya está asignado a este curso académico, lo añadimos a la lista de duplicados
            if ($alumnoExiste) {
                // Agrega el ID del alumno a la lista de duplicados
                $alumnosDuplicados[] = $fila['nie'];

                continue;
            }
            
            // Matricular al alumno en el curso actual
            // $matricula = new Matricula();
            // $matricula->alumno_id = $alumno->id;
            // $matricula->curso_academico_id = $request->curso;
            // $matricula->anno_academico = $anno_actual;
            // $matricula->save();

            // Creamos la relación entre el alumno y el curso académico
            $cursoAcademico = new Curso_academico_alumno();
            $cursoAcademico->curso_academico_id = $request->anno_academico;
            $cursoAcademico->alumno_id = $alumno->id;
            $cursoAcademico->ciclo_nombre = $request->curso;
            $cursoAcademico->save();

            if ($convocatoriaId) {
                $asignacion = new Asignaciones();
                $asignacion->convocatoria_id = $convocatoriaId;
                $asignacion->alumnado_id = $alumno->id;
                $asignacion->save();
            }
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
