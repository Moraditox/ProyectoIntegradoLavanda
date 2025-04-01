@extends('layouts.app')
@if (Session::has('success'))
    <success>
        {{ session()->get('success') }}
    </success>
@endif
@if (Session::has('error'))
    <error>
        {{ session()->get('error') }}
    </error>
@endif
@section('content')
    <h1>Informes relativos las prácticas en empresas</h1>
    <div class="container">
        <div class="row">
            <ul>
                <li><a href="{{route('file')}}">Informes del alumnado</a></li>
                <li><a href="{{route('informesProfesorado')}}">Informes de los profesores</a></li>
                <li><a href="{{route('file')}}">Informes de los tutores laborales</a></li>
                <li><a href="{{route('file')}}">Fichas semanales</a></li>
            </ul>        
        </div>
    </div>
@endsection