<div class="box box-info padding-1">
    <div class="box-body">
        <div class="form-group">
            {{ Form::label('nombre') }}
            {{ Form::text('nombre', $empresa->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
            {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('persona_contacto') }}
            {{ Form::text('persona_contacto', $empresa->persona_contacto, ['class' => 'form-control' . ($errors->has('persona_contacto') ? ' is-invalid' : ''), 'placeholder' => 'Persona contacto']) }}
            {!! $errors->first('persona_contacto', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('correo_contacto') }}
            {{ Form::text('correo_contacto', $empresa->correo_contacto, ['class' => 'form-control' . ($errors->has('correo_contacto') ? ' is-invalid' : ''), 'placeholder' => 'Correo contacto']) }}
            {!! $errors->first('correo_contacto', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('telefono_contacto') }}
            {{ Form::text('telefono_contacto', $empresa->telefono_contacto, ['class' => 'form-control' . ($errors->has('telefono_contacto') ? ' is-invalid' : ''), 'placeholder' => 'Telefono contacto']) }}
            {!! $errors->first('telefono_contacto', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('descripcion') }}
            {{ Form::text('descripcion', $empresa->descripcion, ['class' => 'form-control' . ($errors->has('descripcion') ? ' is-invalid' : ''), 'placeholder' => 'Descripcion']) }}
            {!! $errors->first('descripcion', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('cif') }}
            {{ Form::text('cif', $empresa->cif, ['class' => 'form-control' . ($errors->has('cif') ? ' is-invalid' : ''), 'placeholder' => 'Cif']) }}
            {!! $errors->first('cif', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('direccion') }}
            {{ Form::text('direccion', $empresa->direccion, ['class' => 'form-control' . ($errors->has('direccion') ? ' is-invalid' : ''), 'placeholder' => 'Direccion']) }}
            {!! $errors->first('direccion', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('web') }}
            {{ Form::text('web', $empresa->web, ['class' => 'form-control' . ($errors->has('web') ? ' is-invalid' : ''), 'placeholder' => 'Pagina Web']) }}
            {!! $errors->first('web', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('localidad') }}
            {{ Form::text('localidad', $empresa->localidad, ['class' => 'form-control' . ($errors->has('localidad') ? ' is-invalid' : ''), 'placeholder' => 'Localidad']) }}
            {!! $errors->first('localidad', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        
        <div class="form-group">
            {{ Form::label('representante_legal') }}
            {{ Form::text('representante_legal', $empresa->representante_legal, ['class' => 'form-control' . ($errors->has('representante_legal') ? ' is-invalid' : ''), 'placeholder' => 'Representante Legal']) }}
            {!! $errors->first('representante_legal', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('email') }}
            {{ Form::text('email', $empresa->email, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'Email']) }}
            {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('movil') }}
            {{ Form::text('movil', $empresa->movil, ['class' => 'form-control' . ($errors->has('movil') ? ' is-invalid' : ''), 'placeholder' => 'Movil']) }}
            {!! $errors->first('movil', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        
        <div class="form-group">
            {{ Form::label('nif_representante_legal') }}
            {{ Form::text('nif_representante_legal', $empresa->nif_representante_legal, ['class' => 'form-control' . ($errors->has('nif_representante_legal') ? ' is-invalid' : ''), 'placeholder' => 'Nif Representante Legal']) }}
            {!! $errors->first('nif_representante_legal', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <!--  -->
        <div class="form-group">
            {{ Form::label('logo') }}
            @if ($empresa->logo)
            <br>
            <img src="{{ asset('storage/logos/' . $empresa->logo) }}" alt="{{ $empresa->nombre }}" width="100px">
            @endif
            {{ Form::file('logo', ['class' => 'form-control' . ($errors->has('logo') ? ' is-invalid' : ''), 'placeholder' => 'Logo']) }}
            {!! $errors->first('logo', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="box-footer mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Enviar') }}</button>
        <a href="{{ route('empresas.index') }}" class="btn btn-danger">{{ __('Cancelar') }}</a>
    </div>
</div>
