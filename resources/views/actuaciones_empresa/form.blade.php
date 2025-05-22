@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 text-center">{{ __('Crear Actuación') }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('actuaciones_empresa.storeManual') }}" method="POST">
                @csrf

                <!-- Campo Nombre del tutor -->
                <div class="mb-3">
                    <label for="id_profesor" class="form-label">{{ __('Profesor') }}</label>
                    <select name="id_profesor" id="id_profesor" class="form-select{{ $errors->has('id_profesor') ? ' is-invalid' : '' }}">
                        <option value="">{{ __('Seleccione un profesor') }}</option>
                        @foreach($profesores as $profesor)
                            <option value="{{ $profesor->id }}" {{ old('id_profesor') == $profesor->id ? 'selected' : '' }}>
                                {{ $profesor->nombre }}
                            </option>
                        @endforeach
                    </select>
                    {!! $errors->first('id_profesor', '<div class="invalid-feedback">:message</div>') !!}
                </div>

                <input type="hidden" name="id_empresa" value="{{ $empresaId }}">

                <!-- Campo Descripción -->
                <div class="mb-3">
                    <label for="descripcion" class="form-label">{{ __('Descripción') }}</label>
                    <input type="text" name="descripcion" id="descripcion" class="form-control{{ $errors->has('descripcion') ? ' is-invalid' : '' }}" value="{{ old('descripcion') }}">
                    {!! $errors->first('descripcion', '<div class="invalid-feedback">:message</div>') !!}
                </div>

                <!-- Campo Contacto -->
                <div class="mb-3">
                    <label for="contacto" class="form-label">{{ __('Contacto') }}</label>
                    <input type="text" name="contacto" id="contacto" class="form-control{{ $errors->has('contacto') ? ' is-invalid' : '' }}" value="{{ old('contacto') }}">
                    {!! $errors->first('contacto', '<div class="invalid-feedback">:message</div>') !!}
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">{{ __('Enviar') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection