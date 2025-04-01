<?php

namespace App\Http\Controllers;
include_once (dirname(__DIR__).'/../../vendor/autoload.php');
use App\Models\InformesTutoresLaborales;
use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;

class InformesTutoresLaboralesController extends Controller
{
    public function index() {
        $informes = json_decode(InformesTutoresLaborales::all());
        return view('/informesTutoresLaborales',  ['data' =>  $informes]);
     }
  
     public function store(Request $request) {
  
        if($file = $request->file){
           $request->validate([ 'file' => 'required|mimes:pdf' ]);
           $pdf = Pdf::getText($file->path()); 
           $arr = explode("\n", $pdf);

           $atributos = [ "alumno" => trim(explode(":", $arr[1])[1]),
                          "curso" => trim(explode(":", $arr[5])[1]),
                          "trimestre" => trim($arr[4]),
                          "ciclo_formativo" => trim(explode(":", $arr[2])[1]),
                          "centro_trabajo" => trim(explode(":", $arr[9])[1]),
                          "tutor_laboral" => trim(explode(":", $arr[10])[1]),
                          "profesor_seguimiento" => trim(explode(":", $arr[11])[1]),
                          "competencias_profesionales" => trim(explode(":", $arr[12])[1]),
                          "competencias_organizativas" => trim(explode(":", $arr[13])[1]),
                          "competencias_relacionales" => trim(explode(":", $arr[14])[1]),
                          "respuesta_contingencias" => trim(explode(":", $arr[15])[1]),
                          "aspecto_1" => trim(explode(":", $arr[16])[1]),
                          "aspecto_2" => trim(explode(":", $arr[17])[1]),
                          "aspecto_3" => trim(explode(":", $arr[18])[1]),
                          "aspectos" => trim($arr[20])." ".trim($arr[21])." ".trim($arr[22]),
                          "areas_puestos_actividades" => trim($arr[25]),
                          "modificaciones" => trim($arr[28]) ];
  
           $file = new InformesTutoresLaborales($atributos);
           $file->save();
        }
        return $this->index();
     }
}