<div class="box box-info padding-1">
    <div class="box-body">

        <div class="form-group">
            {{ Form::label('Primer apellido') }}
            {{ Form::text('apellido1', $trabajador->apellido1, ['class' => 'form-control' . ($errors->has('apellido1') ? ' is-invalid' : ''), 'placeholder' => 'Primer apellido']) }}
            {!! $errors->first('apellido1', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Segundo apellido') }}
            {{ Form::text('apellido2', $trabajador->apellido2, ['class' => 'form-control' . ($errors->has('apellido2') ? ' is-invalid' : ''), 'placeholder' => 'Segundo apellido']) }}
            {!! $errors->first('apellido2', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Nombre') }}
            {{ Form::text('nombre', $trabajador->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
            {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('NIF') }}
            {{ Form::text('nif', $trabajador->nif, ['class' => 'form-control' . ($errors->has('nif') ? ' is-invalid' : ''), 'placeholder' => 'NIf']) }}
            {!! $errors->first('nif', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Email') }}
            {{ Form::text('email', $trabajador->email, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'Email']) }}
            {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Móvil') }}
            {{ Form::text('movil', $trabajador->movil, ['class' => 'form-control' . ($errors->has('movil') ? ' is-invalid' : ''), 'placeholder' => 'Móvil']) }}
            {!! $errors->first('movil', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Rol') }}
            {{ Form::text('rol', $trabajador->rol, ['class' => 'form-control' . ($errors->has('rol') ? ' is-invalid' : ''), 'placeholder' => 'Rol']) }}
            {!! $errors->first('rol', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        {{-- <div class="form-group">
            {{ Form::label('Empresa') }}
            {{ Form::select('empresa_id', $empresas, ) }}
            {!! $errors->first('empresa_id', '<div class="invalid-feedback">:message</div>') !!}
        </div> --}}
        <div class="form-group">
            {{ Form::label('Empresa') }}
            <select class="form-control" name="empresa_id" id="empresa_id">
                <option value="" disabled selected>Selecciona una empresa</option>
                @foreach ($empresas as $empresaId => $empresaNombre)
                    <option value="{{ $empresaId }}">{{ $empresaNombre }}</option>
                @endforeach
            </select>
            {!! $errors->first('empresa_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="box-footer mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Enviar') }}</button>
        <a href="{{ route('convocatorias.index') }}" class="btn btn-danger">{{ __('Cancelar') }}</a>
    </div>
</div>

{{-- Select2 --}}
<script>
    $(document).ready(function() {
        $('#empresa_id').select2({
            language: {
                noResults: function() {
                    return 'No se encontraron resultados';
                }
            }
        });
    });
</script>
