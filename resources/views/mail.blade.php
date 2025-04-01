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
    <p>La razón del envio de este correo es para su aprobación de la participación en la convocatoria.</p>
    {{-- TODO Mostrar convocatoria --}}
    @if ($mailInfo['receptor'] == 'Alumno')
        {{-- <p>Para su aprobación pulse en el siguiente enlace y rellene los campos que se solicitan:
            https://lavanda.iesgrancapitan.org/alumnado/{{ $mailInfo['token'] }}</p> --}}
    @elseif ($mailInfo['receptor'] == 'Empresa')
        <p>Si desea participar en la convocatoria <a
                href="https://lavanda.iesgrancapitan.org/empresa/{{ $mailInfo['token'] }}">pulse aquí.</a></p>
    @endif
</body>

</html>
