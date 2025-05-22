@extends('importar')

@section('titulo')
<h1>Importar alumnos</h1>
@endsection

@section('requisitos')
<ul>
    <li><code>apellido1</code></li>
    <li><code>apellido2</code></li>
    <li><code>nombre</code></li>
    <li><code>nie</code></li>
    <li><code>email_corporativo</code></li>
    <li><code>email_personal</code></li>
    <li><code>dni</code></li>
    <li><code>movil</code></li>
</ul>
<li>Descargar ejemplo CSV: <a href="{{ asset('storage/alumnado/EjemploCSV.csv') }}">Descargar</a></li>

@endsection
