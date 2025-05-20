@extends('layouts.app')

{{-- Este formulario permite a una empresa unirse a una convocatoria --}}
@section('content')
    <div class="card">
        <div class="card-header">
            <h2>{{ __('Editar empresa en convocatoria') }}</h2>
            <h3>{{ $empresa->nombre }}</h3>
            @if ($message = Session::get('error'))
                <div class="alert alert-danger m-2">
                    <p>{{ $message }}</p>
                </div>
            @endif
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('convocatoria.updateEmpresa', [$convocatoria->id, $empresa->id]) }}" role="form" enctype="multipart/form-data">
                @csrf

                @include('empresa.formularioConvocatoria')

                <button type="submit" class="btn btn-primary">{{ __('Guardar cambios') }}</button>
                <a href="/convocatorias/{{ $convocatoria->id }}" class="btn btn-danger">{{ __('Cancelar') }}</a>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        const especialidadesCount = @json(count($especialidades));
        const especialidades = @json($especialidades);
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/empresa/empresaConvocatoriaForm.js') }}"></script>
@endsection