<?php

namespace App\Http\Controllers;

use App\Models\curso_academico_new;
use Illuminate\Http\Request;

class CursosAcademicosController extends Controller
{
    /**
     * Store a new record in the anno_academico table.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validar la entrada
        $request->validate([
            'year' => 'required|string|max:10',
        ]);

        // Verificar si ya existe un año académico con ese valor
        $existe = curso_academico_new::where('years', $request->input('year'))->exists();

        if ($existe) {
            // Si ya existe, redireccionar con un mensaje de error
            return redirect('/cursos')->with('error', 'El Año Académico ya existe.');
        }

        // Crear un nuevo registro
        $annoAcademico = new curso_academico_new();
        $annoAcademico->years = $request->input('year');
        $annoAcademico->save();

        // Redireccionar con mensaje de éxito
        return redirect('/cursos')->with('success', 'Año Académico creado exitosamente.');
    }
}
