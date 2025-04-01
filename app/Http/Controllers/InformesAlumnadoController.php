<?php
namespace App\Http\Controllers;

use App\Models\InformesAlumnado;
use App\Models\InformesProfesorado;
use App\Models\InformesTutoresLaborales;
use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;
use App\Models\Empresa;
use App\Models\Actuaciones;
use Illuminate\Support\Facades\Storage;
use App\Models\Alumnado;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Exception;
use Google_Service_Exception;

class InformesAlumnadoController extends Controller
{
   private $informes_alumnado = [];

   public function index()
   {
      $informes = json_decode(InformesAlumnado::all());
      return view('/file', ['data' => $informes]);
   }
   public function store(Request $request)
   {
      $request->validate([
         'file' => 'required|mimes:pdf',
         'tipo_informe' => 'required|in:alumnado,profesorado,ficha_semanal',
         'empresa_id' => 'required|exists:empresas,id',
      ]);
      $file = $request->file('file');
      $pdfContent = Pdf::getText($file->path());
      $arr = explode("\n", $pdfContent);


      Storage::putFile('storage/app/informes', $file);
      $nombreArchivoLocal = null;
      $atributosProfesorado = [];
      $atributosAlumnado = [];
      $atributos = [];
      $entity = null;
      $file = $request->file('file');
      $nombreArchivoOriginal = $file->getClientOriginalName();
      switch ($request->input('tipo_informe')) {
         case 'alumnado':
            $driveFolderId = '18Y4v8SCvU2M40vdPFzuqJrcv64jGX7w5';
            $alumnoId = $request->input('alumno_id');
            $nombreArchivoLocal = "informe_alumnado_{$alumnoId}.pdf";
            $folder = 'informes/alumnado';
            $atributos = [
               "alumno" => trim(explode(":", $arr[1])[1]),
               "curso" => trim(explode(":", $arr[3])[1]),
               "trimestre" => trim(explode(":", $arr[7])[1]),
               "ciclo_formativo" => trim(explode(":", $arr[5])[1]),
               "centro_trabajo" => trim(explode(":", $arr[10])[1]),
               "tutor_trabajo" => trim(explode(":", $arr[11])[1]),
               "profesor_seguimiento" => trim(explode(":", $arr[12])[1]),
               "posibilidades_formativas" => trim(explode(":", $arr[13])[1]),
               "cumplimiento_programa" => trim(explode(":", $arr[14])[1]),
               "seguimiento_tutor_trabajo" => trim(explode(":", $arr[15])[1]),
               "seguimiento_profesor" => trim(explode(":", $arr[16])[1]),
               "posibilidades_laborales" => trim(explode(":", $arr[18])[1]),
               "adecuacion_formacion" => trim(explode(":", $arr[19])[1]),
               "nivel_satisfaccion" => trim(explode(":", $arr[20])[1]),
               "valoracion_general" => trim(explode(":", $arr[21])[1]),
               "aspectos_mejorar" => trim($arr[23]),
               "aspectos_destacar" => trim($arr[26]),
               "empresa_id" => $request->input('empresa_id'),
            ];
            $entity = InformesAlumnado::class;
            break;
         case 'profesorado':
            $folder = 'informes/profesorado';
            $driveFolderId = '1dR7UU9ykZc6ddaKoYzYBndgoe8MazkgP';
            $alumnoId = $request->input('alumno_id');
            $nombreArchivoLocal = "informe_profesorado_{$alumnoId}.pdf";
            $atributos = ["alumno" => trim(explode(":", $arr[1])[1]),
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
               "aspectos_destacar" => trim($arr[26]),
               "empresa_id" => $request->input('empresa_id')];
            $entity = InformesProfesorado::class;
            break;
         case 'empresa':
            break;
         case 'ficha_semanal':
            $folder = 'informes/fichas_semanales';
            if (!Storage::exists($folder)) {
               Storage::makeDirectory($folder);
            }

            $alumnoId = $request->input('alumno_id');
            $alumno = Alumnado::find($alumnoId);
            $apellidoAlumno = $alumno->apellido1;
            $driveFolderId = "1y1RJ-M8PTwiqoWwFSi07kWHMfXGkQbUg";
            $nombreAlumno = $alumno->nombre;
            $fileExtension = $file->getClientOriginalExtension();
            $nombreArchivoLocal = "ficha_semanal_{$alumnoId}." . $fileExtension;
            $file->storeAs($folder, $nombreArchivoLocal, 'public');

            $nombreArchivoGoogleDrive = "{$apellidoAlumno}{$nombreAlumno}FichaSemanal.pdf";

            $this->subirAGoogleDrive($nombreArchivoLocal, $nombreArchivoGoogleDrive, $folder, $driveFolderId);
            break;
         default:
            $folder = null;
            $driveFolderId = null;
            $atributos = [];
            break;
      }

      if (!is_null($folder) && !is_null($driveFolderId) && !is_null($entity)) {
         // Handle storage and Google Drive upload
         $storagePath = "{$folder}";
         $filePath = $file->storeAs($storagePath, $nombreArchivoLocal, 'public');

         if ($driveFolderId) {
            $nombreArchivoGoogleDrive = $nombreArchivoLocal;
            $this->subirAGoogleDrive($nombreArchivoLocal, $nombreArchivoGoogleDrive, $storagePath, $driveFolderId);
         }

         // Merge all attributes for database insertion
         $attributes = [
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'folder' => $folder,
         ] + $atributos;

         $entity::create($attributes);
      }
      $empresaId = $request->input('empresa_id');
      $empresa = Empresa::find($empresaId);

      if ($empresa) {
         $nombreEmpresa = $empresa->nombre;

         Actuaciones::create([
            'emisor' => 'Docente',
            'tipo' => 'Automático',
            'observaciones' => 'Se ha subido un PDF de evaluación a la empresa ' . $nombreEmpresa,
         ]);
      } else {
         return redirect()->back()->with('error', 'Empresa no encontrada.');
      }

      return redirect()->back()->with('success', 'Informe PDF subido correctamente.');
   }
   private function subirAGoogleDrive($nombreArchivoLocal, $nombreArchivoGoogleDrive, $storagePath, $idCarpetaDestino)
   {
      $cliente = new Google_Client();
      $cliente->setAuthConfig("/var/www/html/lavanda/credenciales.json");
      $cliente->addScope(Google_Service_Drive::DRIVE);
      $drive = new Google_Service_Drive($cliente);

      $archivo = new Google_Service_Drive_DriveFile([
         'name' => $nombreArchivoGoogleDrive,
         'parents' => [$idCarpetaDestino],
      ]);

      $rutaArchivoLocal = storage_path("app/public/{$storagePath}/{$nombreArchivoLocal}");

      if (!file_exists($rutaArchivoLocal) || !is_file($rutaArchivoLocal)) {

         throw new Exception("El archivo no existe o no es un archivo válido en la ruta especificada: " . $rutaArchivoLocal);
      }

      try {
         $archivoSubido = $drive->files->create($archivo, [
            'data' => file_get_contents($rutaArchivoLocal),
            'mimeType' => 'application/pdf',
            'uploadType' => 'multipart',
         ]);
      } catch (Google_Service_Exception $e) {
         \Log::error($e->getMessage());
         throw $e;
      }
   }


}