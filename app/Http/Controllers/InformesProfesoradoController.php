<?php

namespace App\Http\Controllers;
include_once (dirname(__DIR__).'/../../vendor/autoload.php');
use App\Models\InformesProfesorado;
use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;

class InformesProfesoradoController extends Controller
{
    //
    public function index() {
        $informes = json_decode(InformesProfesorado::all());
        return view('/informesProfesorado',  ['data' =>  $informes]);
     }
  
     public function store(Request $request) {
  
        if($file = $request->file){
           $request->validate([ 'file' => 'required|mimes:pdf' ]);
           $pdf = Pdf::getText($file->path()); 
           $arr = explode("\n", $pdf);
   
           $atributos = [ "alumno" => trim(explode(":", $arr[1])[1]),
                          "curso" => trim(explode(":", $arr[3])[1]),
                          "trimestre" => trim(explode(":", $arr[7])[1]),
                          "ciclo_formativo" => trim(explode(":", $arr[5])[1]),
                          "centro_trabajo" => trim(explode(":", $arr[10])[1]),
                          "tutor_laboral" => trim(explode(":", $arr[11])[1]),
                          "profesor_seguimiento" => trim(explode(":", $arr[12])[1]),
                          "posibilidades_formativas" => trim(explode(":", $arr[13])[1]),
                          "cumplimiento_programa" => trim(explode(":", $arr[14])[1]),
                          "seguimiento_alumno" => trim(explode(":", $arr[15])[1]),
                          "apoyo_profesor" => trim(explode(":", $arr[16])[1]),
                          "posibilidades_laborales" => trim(explode(":", $arr[18])[1]),
                          "calidad_informes_tutor_trabajo" => trim(explode(":", $arr[19])[1]),
                          "nivel_satisfaccion" => trim(explode(":", $arr[20])[1]),
                          "valoracion_general" => trim(explode(":", $arr[21])[1]),
                          "aspectos_mejorar" => trim($arr[23]),
                          "aspectos_destacar" => trim($arr[26]) ];
  
           $file = new InformesProfesorado($atributos);
           $file->save();
        }
        return $this->index();
     }
}
