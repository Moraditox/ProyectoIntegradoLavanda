<?php

namespace App\Http\Controllers;

use App\Models\curso_academico_new;
use App\Models\Profesores;
use App\Models\User;
use Illuminate\Http\Request;

class ProfesoradoController extends Controller
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
    public function index()
    {
        $actionImportar = route('profesorados.import');
        $actionImagenes = route('profesorados.uploadImages');

        // Recuperamos el curso academico más reciente
        $annoAcademico = curso_academico_new::orderBy('created_at', 'desc')->first();

        return view('profesorado.importarProfesores', compact('actionImportar', 'actionImagenes', 'annoAcademico'));
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
        ]);

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
        $hayDuplicados = false;

        foreach ($filas as $fila) {
            if (
            Profesores::where('email', $fila['email'])->exists() ||
            User::where('email', $fila['email'])->exists()
            ) {
            $hayDuplicados = true;
            continue;
            }
            $profesor = new Profesores();
            $profesor->apellido1 = $fila['apellido1'];
            $profesor->apellido2 = $fila['apellido2'];
            $profesor->nombre = $fila['nombre'];
            $profesor->email = $fila['email'];
            $profesor->save();

            // Añadir a la tabla users
            User::create([
            'name' => $fila['nombre'] . ' ' . $fila['apellido1'] . ' ' . $fila['apellido2'],
            'email' => $fila['email'],
            ]);
        }

        $mensaje = $hayDuplicados
            ? 'Los profesores se han importado correctamente y los duplicados se han ignorado.'
            : 'Los profesores se han importado correctamente.';

        return redirect()->route('profesorado')->with('success', $mensaje);
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
            $ruta = $archivo->storeAs('public/profesorado/perfil', $archivo->getClientOriginalName());
        }

        return redirect()->route('profesorado')
            ->with('success', 'Los archivos se han subido correctamente.');
    }
}
