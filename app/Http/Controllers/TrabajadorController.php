<?php

namespace App\Http\Controllers;

use App\Models\Trabajadores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrabajadorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function create()
    {
        $trabajador = new Trabajadores();
        $empresasTabla = DB::table('empresas')->orderBy('nombre')->get();
        $empresas = array();
        foreach ($empresasTabla as $empresa => $columna) {
            $empresas[$columna->id] = $columna->nombre;
        }

        return view('trabajadores.create', compact('trabajador', 'empresas'));
    }

    public function store(Request $request)
    {
        $request->validate(Trabajadores::$rules);

        $trabajador = new Trabajadores($request->all());

        $trabajador->save();

        return redirect()->route('empresas.index')
            ->with('success', 'Trabajador creado correctamente.');
    }
}
