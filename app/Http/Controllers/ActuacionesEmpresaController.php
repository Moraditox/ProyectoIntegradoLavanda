<?php

namespace App\Http\Controllers;

use App\Models\Actuaciones_Empresa;
use App\Models\Profesores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActuacionesEmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $actuaciones = Actuaciones_Empresa::all();
        return response()->json($actuaciones);
    }

    public function create(Request $request)
    {
        $actuacion = new Actuaciones_Empresa();
        $profesores = Profesores::all();
        $empresaId = $request->query('empresa_id');
        return view('actuaciones_empresa.create', compact('actuacion', 'profesores', 'empresaId'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_profesor' => 'required|integer|exists:profesores,id',
            'id_empresa' => 'required|integer|exists:empresas,id',
            'descripcion' => 'nullable|string',
            'contacto' => 'required|string|max:255',
        ]);

        $actuacion = Actuaciones_Empresa::create($validatedData);
        return response()->json($actuacion, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $actuacion = Actuaciones_Empresa::findOrFail($id);
        return response()->json($actuacion);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
        ]);

        $actuacion = Actuaciones_Empresa::findOrFail($id);
        $actuacion->update($validatedData);

        return response()->json($actuacion);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $actuacion = Actuaciones_Empresa::findOrFail($id);
        $actuacion->delete();

        return response()->json(['message' => 'Actuación eliminada correctamente.']);
    }

    public function storeManual(Request $request)
    {
        // Validación de los campos del formulario
        echo "Iniciando validación de los campos del formulario...\n";
        $validator = Validator::make($request->all(), Actuaciones_Empresa::$rules);

        if ($validator->fails()) {
            echo "La validación ha fallado.\n";
            return redirect()->route('actuaciones-empresa.create', ['empresa_id' => $request->input('id_empresa')])
            ->withErrors($validator)
            ->withInput();
        }
        echo "Validación completada con éxito.\n";

        // Crear una nueva instancia de Actuaciones_Empresa con los datos del formulario
        echo "Creando una nueva instancia de Actuaciones_Empresa...\n";
        $actuacion = new Actuaciones_Empresa([
            'id_profesor' => $request->input('id_profesor'),
            'id_empresa' => $request->input('id_empresa'),
            'descripcion' => $request->input('descripcion'),
            'contacto' => $request->input('contacto'),
        ]);

        // Guardar la actuación en la base de datos
        echo "Guardando la actuación en la base de datos...\n";
        $actuacion->save();
        echo "Actuación guardada exitosamente.\n";

        // Redireccionar al índice de actuaciones_empresa
        echo "Redireccionando al índice de actuaciones_empresa...\n";
        return redirect()->route('empresas.show', ['empresa' => $request->input('id_empresa')])
            ->with('success', 'Actuación creada manualmente exitosamente.');
        }
}