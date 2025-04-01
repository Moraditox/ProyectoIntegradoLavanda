<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Convocatoria IES Gran Capitán</title>
</head>

<body>
    <img src="{{ asset('storage/avatar/iesgrancapitan.png') }}" alt="Logo" style="max-width: 100px;">
    <p>Este es un Mensaje auto generado.</p>
    @if ($mailInfo['receptor'] == 'Alumno')
    <p>La razón del envio de este correo es para informarle de que ya puede rellenar el PDF de valoración sobre la empresa durante el periódo de practicas</p>
    @if (isset($mailInfo['pdfFilePath']))
    <p>Descargar PDF</p>
    <a href="{{ asset($mailInfo['pdfFilePath']) }}" download>https://lavanda.iesgrancapitan.org/storage/informes/alumno/InformeTutorLaboral_Rellenable.pdff</a>


    @endif

    @elseif ($mailInfo['receptor'] == 'Empresa')
    <p>La razón del envio de este correo es para informarle de que ya puede rellenar el PDF de valoración sobre el alumnado de practicas en su empresa</p>
    @if (isset($mailInfo['pdfFilePath']))
    <p>Descargar PDF</p>
<a href="{{ asset($mailInfo['pdfFilePath']) }}" download>https://lavanda.iesgrancapitan.org/storage/informes/alumno/InformeAlumnado_Rellenable.pdf</a>


    @endif

    @endif
</body>

</html>
