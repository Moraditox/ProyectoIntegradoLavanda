@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-center">
                    <div class="card-header">
                        <strong>Error al iniciar sesión</strong>
                    </div>
                    <div class="card-body">
                        <p class="alert alert-danger">La cuenta seleccionada no tiene permisos para acceder a la aplicación.</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ url('login-google') }}">Iniciar sesión con otra cuenta</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
