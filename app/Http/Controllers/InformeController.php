<?php

namespace App\Http\Controllers;

use App\Models\Actuaciones;
use App\Models\Asignaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InformeController extends Controller
{
    /**
     * Upload files to storage
     */
    public function upload(Request $request, $token)
    {
        $archivos = $request->file('informe');

        $empresa = DB::table('empresas')->where('token', $token)->first();
        $i = count(Storage::files('public/informeRellenable/empresas/' . $empresa->cif));
        // $nombre = str_replace(' ', '_', $empresa->nombre);
        foreach ($archivos as $archivo) {
            $archivo->storeAs('public/informeRellenable/empresas/' . $empresa->cif, $i++ . '.pdf');

            $actuacion = new Actuaciones([
                'emisor' => 'Tutor Laboral',
                'tipo' => 'Automatico',
                'observaciones' => 'La empresa ' . $empresa->nombre . ' ha subido un documento,
                puedes encontrarlo en storage/app/public/informeRellenable/empresas/' . $empresa->cif . '/' . ($i - 1) . '.pdf',
            ]);
            $actuacion->save();
        }

        return redirect()->route('empresa.mail', ['token' => $empresa->token])
            ->with('success', 'Los archivos se han subido correctamente.');
    }

    public function uploadAlumno(Request $request, $token)
    {
        $archivos = $request->file('informe');

        $alumno = DB::table('alumnado')->where('token', $token)->first();
        $i = count(Storage::files('public/informeRellenable/alumnos/' . $alumno->nie));
        // $nombre = str_replace(' ', '_', $alumno->nombre);
        $asignacion = Asignaciones::where('alumnado_id', $alumno->id)->latest()->first();
        foreach ($archivos as $archivo) {
            $archivo->storeAs('public/informeRellenable/alumnos/' . $alumno->nie, $i++ . '.pdf');

            $actuacion = new Actuaciones([
                'emisor' => 'Alumno',
                'tipo' => 'Automatico',
                'observaciones' => 'El alumno ' . $alumno->nombre . ' ' . $alumno->apellido1 . ' ' . $alumno->apellido2 . ' ha subido un documento,
                puedes encontrarlo en storage/app/public/informeRellenable/alumnos/' . $alumno->nie . '/' . ($i - 1) . '.pdf',
                'alumnado_id' => $alumno->id,
                'asignacion_id' => $asignacion->id
            ]);
            $actuacion->save();
        }

        return redirect()->route('alumno.mail', ['token' => $alumno->token])
            ->with('success', 'Los archivos se han subido correctamente.');
    }
}
