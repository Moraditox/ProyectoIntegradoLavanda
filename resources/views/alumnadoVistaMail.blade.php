<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Completado</title>

    <style>
        body {
            display: flex;
            align-items: flex-start;
            /* Centrar en la parte superior */
            justify-content: center;
            min-height: 100vh;
            /* Mínimo 100% del alto de la ventana */
            margin: 0;
        }

        div {
            text-align: center;
            margin-top: 10vh;
            /* Ajusta según sea necesario */
        }
    </style>
</head>

<body>
    <div>
        <a href="https://lavanda.iesgrancapitan.org/home"><img src="{{ asset('storage/avatar/iesgrancapitan.png') }}"
                alt="Logo proyecto" width="200"></a>
        
        <h1>Lavanda</h1>
        <h2>¡Gracias por completar el formulario de seguimiento!</h2>
        <p>Ya puede cerrar esta ventana.</p>
        <button onclick="cerrarVentana()">Cerrar Ventana</button>

        <script>
            function cerrarVentana() {
                // Cierra la ventana actual
                window.close();
            }
        </script>
    </div>
</body>

</html>