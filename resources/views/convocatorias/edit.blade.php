@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Convocatoria
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header" style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                        <span class="card-title">{{ __('Modificar') }} convocatoria</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('convocatoria.update', $convocatoria->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('POST') }}
                            @csrf

                            @include('convocatorias.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
