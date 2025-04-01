@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Trabajadores
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header" style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                        <span class="card-title">{{ __('Añadir') }} trabajador</span>
                        
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('convocatorias.index') }}"> {{ __('Volver') }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('trabajadores.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('trabajadores.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
