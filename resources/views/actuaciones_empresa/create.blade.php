@extends('layouts.app')

@section('template_title')
{{ __('Añadir Actuación') }}
@endsection

@section('content')
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">

            @includeif('partials.errors')

            <div class="card card-default">
                <div class="card-header"
                    style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                    <span class="card-title">{{ __('Añadir Actuación') }}</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('actuaciones_empresa.store') }}" role="form">
                        @csrf

                        @include('actuaciones_empresa.form')

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection