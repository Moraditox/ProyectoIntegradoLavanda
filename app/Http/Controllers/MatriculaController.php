<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matricula;
use App\Models\Convocatorias;

class MatriculaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $ciclo = $request->input('ciclo');

        // Obtener las matrículas y ordenar por ciclo y luego por apellido1 del alumno
        $matriculas = Matricula::join('alumnado', 'matricula.alumno_id', '=', 'alumnado.id')
            ->join('curso_academico', 'matricula.curso_academico_id', '=', 'curso_academico.id')
            ->join('ciclos', 'curso_academico.ciclo', '=', 'ciclos.ciclo')
            ->orderBy('ciclos.ciclo')
            ->orderBy('alumnado.apellido1');

        // Filtrar por búsqueda si existe
        if ($search) {
            $matriculas->where(function ($query) use ($search) {
                $query->where('alumnado.nombre', 'like', "%$search%")
                    ->orWhere('alumnado.apellido1', 'like', "%$search%")
                    ->orWhere('alumnado.apellido2', 'like', "%$search%");
            });
        }

        // Filtrar por ciclo si se selecciona
        if ($ciclo) {
            $matriculas->where('curso_academico.ciclo', $ciclo);
        }

        // Paginar los resultados
        $matriculas = $matriculas->paginate();

        // Obtener la convocatoria actual
        $convocatoria = Convocatorias::first(); // Aquí puedes ajustar tu lógica para obtener la convocatoria que necesites

        return view('matriculas.index', compact('matriculas', 'convocatoria'))
            ->with('i', ($request->input('page', 1) - 1) * $matriculas->perPage());
    }
}
