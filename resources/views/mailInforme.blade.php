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
    <p>La razón del envio de este correo es para informarle de que ya puede subir el informe de los alumnos asignados a su empresa.</p>
    <p>Porfavor rellena el siguiente formulario para valorar su experiencia en la empresa <a href="https://lavanda.iesgrancapitan.org/alumnado/informe/{{ $mailInfo['token'] }}">pulse aquí.</a>
</p>
    @elseif ($mailInfo['receptor'] == 'Empresa')
    <p>La razón del envio de este correo es para informarle de que ya puede acceder al seguimiento su FCT.</p>
    <p>Para acceder <a href="https://lavanda.iesgrancapitan.org/empresa/informe/{{ $mailInfo['token'] }}">pulse aquí.</a></p>

    @endif
</body>

</html>
