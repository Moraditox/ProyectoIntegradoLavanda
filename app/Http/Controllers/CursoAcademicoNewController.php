<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curso_Academico_new;

class CursoAcademicoNewController extends Controller
{
    public function index()
    {
        // Obtener todos los cursos académicos
        $courses = Curso_Academico_new::all();

        return view('curso_academico.index', compact('courses'));
    }

    /**
     * Mostrar los profesores para asignar a un curso académico específico.
     */
    public function addTeachersToCourse($courseId)
    {
        // Recuperamos todos los profesores de la base de datos
        $teachers = \App\Models\Profesores::all();

        // Recuperamos el curso académico específico
        $course = Curso_Academico_new::findOrFail($courseId);

        if (!$course) {
            return redirect()->route('cursos.index')->with('error', 'Curso académico no encontrado');
        }

        // Enviamos la información a la vista
        return view('curso_academico.addTeachers', compact('teachers', 'course'));
    }

    /**
     * Almacenar la asignación de profesores a un curso académico.
     */
    public function storeTeachersToCourse(Request $request, $courseId)
    {
        // Validamos que se haya seleccionado al menos un profesor
        $request->validate([
            'profesores' => 'required|array|min:1',  // Al menos un profesor debe ser seleccionado
            'profesores.*' => 'exists:profesores,id',  // Los IDs deben existir en la tabla de profesores
        ]);

        // Recuperamos el curso académico
        $course = Curso_Academico_new::findOrFail($courseId);

        // Asignamos los profesores seleccionados al curso
        // Esto se sincroniza la relación muchos a muchos entre cursos y profesores
        // Elimina las relaciones existentes y añade las nuevas
        $course->profesores()->sync($request->profesores); 

        // Redirigimos con un mensaje de éxito
        return redirect()->route('cursos.index')->with('success', 'Profesores asignados correctamente');
    }


}
