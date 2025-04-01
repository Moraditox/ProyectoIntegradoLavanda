<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invitación a Convocatoria</title>
</head>

<body>
    <img src="{{ asset('storage/avatar/iesgrancapitan.png') }}" alt="Logo" style="max-width: 100px;">
    <p>Este es un mensaje generado automáticamente.</p>
    <p>Le invitamos a participar en nuestra nueva convocatoria del IES Gran Capitán.</p>
    <p>Por favor, acceda al siguiente enlace y rellene el formulario con los datos sobre lo que ofrece su empresa:</p>
    <p><a
        href="https://lavanda.iesgrancapitan.org/empresa/participacion/{{ $mailInfo['token'] }}/{{ $mailInfo['convocatoria_id'] }}">https://lavanda.iesgrancapitan.org/empresa/participacion</a>

    </p>
    <p>IES Gran Capitán</p>
</body>

</html>