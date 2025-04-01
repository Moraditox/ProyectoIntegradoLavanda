<?php

namespace App\Http\Controllers;

use App\Models\Actuaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ActuacionesController extends Controller
{
    /**
     * Muestra un listado de actuaciones con opción de búsqueda.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $actuaciones = Actuaciones::where(function ($query) use ($search) {
            $query->where('emisor', 'like', "%$search%")
                ->orWhere('tipo', 'like', "%$search%")
                ->orWhere('observaciones', 'like', "%$search%");
        })->paginate();

        return view('actuaciones.index', compact('actuaciones'))
            ->with('i', ($request->input('page', 1) - 1) * $actuaciones->perPage());
    }

    /**
     * Muestra el formulario para crear una nueva actuación.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $actuacion = new Actuaciones();
        return view('actuaciones.create', compact('actuacion'));
    }

    /**
     * Almacena una nueva actuación en el almacenamiento.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validación de los campos del formulario
        $validator = Validator::make($request->all(), Actuaciones::$rules);

        if ($validator->fails()) {
            return redirect()->route('actuaciones.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Crear una nueva instancia de Actuaciones con los datos del formulario
        $actuacion = new Actuaciones([
            'emisor' => $request->input('emisor'),
            'tipo' => $request->input('tipo'),
            'observaciones' => $request->input('observaciones'),
            'informe_alumno_id' => $request->input('informe_alumno_id'),
            'informe_empresa_id' => $request->input('informe_empresa_id'),
            'asignacion_id' => $request->input('asignacion_id'),
        ]);

        // Guardar la actuación en la base de datos
        $actuacion->save();

        // Redireccionar a la vista de índice o a donde sea apropiado
        return redirect()->route('convocatorias.index')
            ->with('success', 'Actuación creada exitosamente.');
    }

    /**
     * Almacena una nueva actuación en el almacenamiento de forma manual.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function storeManual(Request $request)
    {
        // Validación de los campos del formulario
        $validator = Validator::make($request->all(), Actuaciones::$rules);

        if ($validator->fails()) {
            return redirect()->route('actuaciones.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Crear una nueva instancia de Actuaciones con los datos del formulario
        $actuacion = new Actuaciones([
            'emisor' => $request->input('emisor'),
            'tipo' => $request->input('tipo'),
            'observaciones' => $request->input('observaciones'),
            'informe_alumno_id' => $request->input('informe_alumno_id'),
            'informe_empresa_id' => $request->input('informe_empresa_id'),
            'asignacion_id' => $request->input('asignacion_id'),
        ]);

        // Guardar la actuación en la base de datos
        $actuacion->save();

        // Redireccionar al índice de convocatorias
        return redirect()->route('convocatorias.index')
            ->with('success', 'Actuación creada manualmente exitosamente.');
    }

    // Otros métodos
}
