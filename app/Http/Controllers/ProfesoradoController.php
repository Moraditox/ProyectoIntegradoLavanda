<?php

namespace App\Http\Controllers;

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
        return view('profesorado.importarProfesores', compact('actionImportar', 'actionImagenes'));
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
        foreach ($filas as $fila) {
            $profesor = new Profesores();
            $profesor->apellido1 = $fila['apellido1'];
            $profesor->apellido2 = $fila['apellido2'];
            $profesor->nombre = $fila['nombre'];
            $profesor->email = $fila['email'];
            $profesor->movil = $fila['movil'];
            $profesor->imagen = $fila['email'] . '.jpg';
            $profesor->horas_segundo = $fila['horas_segundo'];
            $profesor->save();

            // Añadir a la tabla users
            User::create([
                'name' => $fila['nombre'] . ' ' . $fila['apellido1'] . ' ' . $fila['apellido2'],
                'email' => $fila['email'],
            ]);
        }

        return redirect()->route('profesorado')->with('success', 'Los profesores se han importado correctamente.');
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
