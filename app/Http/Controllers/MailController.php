<?php

namespace App\Http\Controllers;

use App\Mail\mailLavanda;
use App\Models\Alumnado;
use App\Models\Empresa;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function EnviarCorreoPrueba($token, $receptor, $email)
    {
        $empresa = Empresa::where('token', $token)->first();
        // $convocatoria_empresa = DB::table('convocatoria_empresa')->where('empresa_id', $empresa->id)->latest()->first();
        $convocatoria_empresa = DB::table('convocatorias')->where('fecha_fin', '>', date('Y-m-d'))->first();
        if ($convocatoria_empresa == null) {
            return redirect()->route('empresas.index')
                ->with('error', 'No hay convocatoria activa.');
        }
        $parametros = [
            "token" => $token,
            "receptor" => $receptor,
            "view" => "mail"
        ];
        Mail::to($email)->send(new mailLavanda($parametros));
        return redirect()->back()
            ->with('success', 'Correo enviado correctamente.');
    }

    public function EnviarCorreoSeguimiento($token, $receptor, $email)
    {
        try {

            if ($receptor == 'Empresa') {
                $empresa = Empresa::where('token', $token)->first();
                $convocatoria_empresa = DB::table('convocatoria_empresa')->where('empresa_id', $empresa->id)->latest()->first();
                if ($convocatoria_empresa == null) {
                    return redirect()->route('empresas.index')
                        ->with('error', 'No hay convocatoria activa.');
                }
            } else {
                $convocatoria = DB::table('convocatorias')->where('fecha_fin', '>', date('Y-m-d'))->first();
                if ($convocatoria == null) {
                    return redirect()->route('alumnado.index')
                        ->with('error', 'No hay convocatoria activa.');
                }
            }
            $parametros = [
                "token" => $token,
                "receptor" => $receptor,
                "view" => "mailSeguimiento"
            ];
            Mail::to($email)->send(new mailLavanda($parametros));
            return redirect()->back()
                ->with('success', 'Correo enviado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with("error", "Error al enviar el correo:" . $e->getMessage());
        }
    }

    public function EnviarCorreoInforme($token, $receptor, $email)
    {
        if ($receptor == 'Empresa') {
            $empresa = Empresa::where('token', $token)->first();
            $convocatoria_empresa = DB::table('convocatoria_empresa')->where('empresa_id', $empresa->id)->latest()->first();
            if ($convocatoria_empresa == null) {
                return redirect()->route('empresas.index')
                    ->with('error', 'No hay convocatoria activa.');
            }
        } else {
            $convocatoria = DB::table('convocatorias')->where('fecha_fin', '>', date('Y-m-d'))->first();
            if ($convocatoria == null) {
                return redirect()->route('alumnado.index')
                    ->with('error', 'No hay convocatoria activa.');
            }
        }
        $parametros = [
            "token" => $token,
            "receptor" => $receptor,
            "view" => "mailInforme"
        ];
        Mail::to($email)->send(new mailLavanda($parametros));
        return redirect()->back()
            ->with('success', 'Correo enviado correctamente.');
    }
    public function enviarCorreoAlumno($matriculaId)
    {
        $matricula = Matricula::with('alumnado')->find($matriculaId);
        if ($matricula) {
            $alumno = $matricula->alumnado;
            $mailInfo = [
                'token' => $alumno->token, 
                'receptor' => 'Alumno',
                'view' => 'mailSeguimiento', 
            ];
            Mail::to($alumno->email_corporativo)->send(new MailLavanda($mailInfo));

            return redirect()->back()->with('success', 'Correo enviado al alumno correctamente.');
        } else {
            return redirect()->back()->with('error', 'Matrícula no encontrada.');
        }
    }

    public function enviarCorreoEmpresa($empresaId)
    {
        $empresa = Empresa::find($empresaId);
        if (!$empresa) {
            return redirect()->back()->with('error', 'Empresa no encontrada.');
        }
        $correoDestino = $empresa->correo_contacto;

        $parametros = [
            'token' => $empresa->token,
            'receptor' => 'Empresa',
            'view' => 'mailSeguimiento',
        ];

        Mail::to($correoDestino)->send(new mailLavanda($parametros));

        return redirect()->back()->with('success', 'Correo enviado a la empresa correctamente.');
    }

    // para mandar el correo
    public function enviarCorreoParticipar($empresaId,$convocatoriaId)
    {
        $empresa = Empresa::find($empresaId);
        if (!$empresa) {
            return redirect()->back()->with('error', 'Empresa no encontrada.');
        }
        $parametros = [
            'token' => $empresa->token,
            'convocatoria_id'=> $convocatoriaId,
            'receptor' => 'Empresa',
            'view' => 'mailParticipacion',
        ];
        Mail::to($empresa->correo_contacto)->send(new mailLavanda($parametros));

        return redirect()->back()->with('success', 'Correo enviado a la empresa correctamente.');
    }

    public function handleGetRequestParticipar($empresaId, $convocatoriaId)
    {
        $empresa = Empresa::find($empresaId);

        if (!$empresa) {
            return redirect()->back()->with('error', 'Empresa no encontrada.');
        }
        $parametros = [
            'token' => $empresa->token,
            'convocatoria_id' => $convocatoriaId,
            'receptor' => 'Empresa',
            'view' => 'mailParticipacion',
        ];
        Mail::to($empresa->correo_contacto)->send(new mailLavanda($parametros));

        return redirect()->back()->with('success', 'Correo enviado a la empresa correctamente.');
    }
    public function enviarCorreoPdfEmpresa($empresaId)
    {
        $empresa = Empresa::find($empresaId);
        if (!$empresa) {
            return redirect()->back()->with('error', 'Empresa no encontrada.');
        }

        $pdfFileName = 'InformeTutorLaboral_Rellenable.pdf';
        $pdfFilePath = 'storage/informeRellenable/empresa/' . $pdfFileName;
        $parametros = [
            'token' => $empresa->token,
            'receptor' => 'Empresa',
            'view' => 'mailPdfEmpresa',
            'pdfFilePath' => $pdfFilePath,
        ];

        Mail::to($empresa->correo_contacto)->send(new mailLavanda($parametros));

        return redirect()->back()->with('success', 'Correo con PDF enviado a la empresa correctamente.');
    }

    public function enviarCorreoPdfAlumno($alumnoId)
    {
        $alumnado = Alumnado::find($alumnoId);
        if (!$alumnado) {
            return redirect()->back()->with('error', 'Alumno no encontrado.');
        }

        $pdfFileName = 'InformeAlumnado_Rellenable.pdf';
        $pdfFilePath = 'storage/informeRellenable/alumno/' . $pdfFileName;

        if (!file_exists(($pdfFilePath))) {
            return redirect()->back()->with('error', 'El archivo PDF no existe.');
        }

        $parametros = [
            'token' => $alumnado->token,
            'receptor' => 'Alumno',
            'view' => 'mailPdfEmpresa',
            'pdfFilePath' => $pdfFilePath,
        ];

        Mail::to($alumnado->email_corporativo)->send(new mailLavanda($parametros));

        return redirect()->back()->with('success', 'Correo con PDF enviado al alumno correctamente.');
    }



}
