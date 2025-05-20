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
</ul>
<li>Descargar ejemplo CSV: <a href="{{ asset('storage/profesorado/EjemploCSV.csv') }}">Descargar</a></li>
@endsection

@section('action')
    {{ route('profesorados.import') }}
@endsection
