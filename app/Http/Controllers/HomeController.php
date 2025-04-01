<?php

namespace App\Http\Controllers;

use App\Models\Convocatorias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Agrupar las convocatorias por año académico
        $convocatorias = DB::table('convocatorias')->orderByDesc('anno_academico')->get()->groupBy('anno_academico');
        return view('home', compact('convocatorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Convocatorias::$rules);
        $convocatoria = new Convocatorias($request->all());
        $convocatoria->save();

        return redirect()->route('convocatorias.index')
            ->with('success', 'La convocatoria ha sido añadida correctamente.');
    }
}
