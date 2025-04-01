@extends('importar')

@section('titulo')
<h1>Importar profesores</h1>
@endsection

@section('requisitos')
<ul>
    <li><code>apellido1</code></li>
    <li><code>apellido2</code></li>
    <li><code>nombre</code></li>
    <li><code>email</code></li>
    <li><code>movil</code></li>
    <li><code>horas_segundo</code></li>
</ul>
<li>Recuerda que la <code>imagen</code> debe de tener el <code>email</code> como nombre de la imagen que se encuentra en la carpeta <code>public/storage/profesorado</code>.</li>
<li><code>La imagen tiene que tener el formato .jpg</code></li>
<li>Descargar ejemplo CSV: <a href="{{ asset('storage/profesorado/EjemploCSV.csv') }}">Descargar</a></li>
@endsection

@section('action')
    {{ route('profesorados.import') }}
@endsection
