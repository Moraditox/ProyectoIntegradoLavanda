<?php

namespace App\Http\Controllers;
use App\Models\Anno_Academico;
use Illuminate\Http\Request;

class AnnoAcademicoNewController extends Controller
{
    /**
     * Store a new record in the anno_academico table.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'anno' => 'required|string|max:255',
        ]);

        // Create a new record
        $annoAcademico = new Anno_Academico();
        $annoAcademico->anno = $request->input('anno');
        $annoAcademico->save();

        // Return a response
        return redirect('/cursos')->with('success', 'Año Academico created successfully');
    }
}