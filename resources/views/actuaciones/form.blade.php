@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('actuaciones.storeManual') }}" method="POST">
        @csrf

        <div class="box box-info padding-1">
            <div class="box-body">
                <!-- Campo Emisor -->
                <div class="form-group">
                    {{ Form::label('emisor', 'Emisor') }}
                    {{ Form::text('emisor', old('emisor'), ['class' => 'form-control' . ($errors->has('emisor') ? '
                    is-invalid' : ''), 'placeholder' => 'Emisor']) }}
                    {!! $errors->first('emisor', '<div class="invalid-feedback">:message</div>') !!}
                </div>

                <!-- Campo Tipo -->
                <!-- <div class="form-group">
                    {{ Form::label('tipo', 'Tipo') }}
                    {{ Form::text('tipo', old('tipo'), ['class' => 'form-control' . ($errors->has('tipo') ? '
                    is-invalid' : ''), 'placeholder' => 'Tipo']) }}
                    {!! $errors->first('tipo', '<div class="invalid-feedback">:message</div>') !!}
                </div> -->
                <!-- Campo Tipo -->
                <div class="form-group">
                    {{ Form::label('tipo', 'Tipo') }}
                    {{ Form::text('tipo', 'Manual', ['class' => 'form-control' . ($errors->has('tipo') ? ' is-invalid' : ''),
                    'placeholder' => 'Tipo', 'readonly' => true]) }}
                    {!! $errors->first('tipo', '<div class="invalid-feedback">:message</div>') !!}
                </div>

                <!-- Campo Observaciones -->
                <div class="form-group">
                    {{ Form::label('observaciones', 'Observaciones') }}
                    {{ Form::text('observaciones', old('observaciones'), ['class' => 'form-control' .
                    ($errors->has('observaciones') ? ' is-invalid' : ''), 'placeholder' => 'Observaciones']) }}
                    {!! $errors->first('observaciones', '<div class="invalid-feedback">:message</div>') !!}
                </div>

                <!-- Campo Informe Alumno ID -->
                <div class="form-group">
                    {{ Form::label('informe_alumno_id', 'Informe Alumno ID') }}
                    {{ Form::text('informe_alumno_id', old('informe_alumno_id'), ['class' => 'form-control' .
                    ($errors->has('informe_alumno_id') ? ' is-invalid' : ''), 'placeholder' => 'Informe Alumno ID']) }}
                    {!! $errors->first('informe_alumno_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>

                <!-- Campo Informe Empresa ID -->
                <div class="form-group">
                    {{ Form::label('informe_empresa_id', 'Informe Empresa ID') }}
                    {{ Form::text('informe_empresa_id', old('informe_empresa_id'), ['class' => 'form-control' .
                    ($errors->has('informe_empresa_id') ? ' is-invalid' : ''), 'placeholder' => 'Informe Empresa ID'])
                    }}
                    {!! $errors->first('informe_empresa_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>

                <!-- Campo Asignación ID -->
                <div class="form-group">
                    {{ Form::label('asignacion_id', 'Asignación ID') }}
                    {{ Form::text('asignacion_id', old('asignacion_id'), ['class' => 'form-control' .
                    ($errors->has('asignacion_id') ? ' is-invalid' : ''), 'placeholder' => 'Asignación ID']) }}
                    {!! $errors->first('asignacion_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>

            </div>

            <div class="box-footer mt-2">
                <button type="submit" class="btn btn-primary">{{ __('Enviar') }}</button>
                <a href="{{ route('convocatorias.index') }}" class="btn btn-danger">{{ __('Cancelar') }}</a>
            </div>
        </div>
    </form>
</div>
@endsection