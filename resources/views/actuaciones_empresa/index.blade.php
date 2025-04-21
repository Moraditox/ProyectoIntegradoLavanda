@extends('layouts.app')

@section('template_title')
Actuaciones
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">
                            {{ __('Actuaciones') }}
                        </span>

                        <div class="float-right w-50">
                            <form action="{{ route('actuaciones.index') }}" method="GET"
                                class="form-inline my-2 my-lg-0">
                                <div class="input-group w-100">
                                    <input class="form-control mr-sm-2" type="search" name="search"
                                        value="{{ request('search') }}" placeholder="{{ __('Buscar') }}"
                                        aria-label="{{ __('Buscar') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">
                                            {{ __('Buscar') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="float-right">
                            <a href="{{ route('actuaciones.create') }}" class="btn btn-primary btn-sm float-right"
                                data-placement="left">
                                {{ __('Añadir') }}
                            </a>
                        </div>
                    </div>
                </div>
                @if ($message = Session::get('success'))
                <div class="alert alert-success m-2">
                    <p>{{ $message }}</p>
                </div>
                @endif
                @if ($message = Session::get('error'))
                <div class="alert alert-danger m-2">
                    <p>{{ $message }}</p>
                </div>
                @endif

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>
                                    <th>Emisor</th>
                                    <th>Tipo</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($actuaciones as $actuacion)
                                <tr>
                                    <td>{{ $actuacion->emisor }}</td>
                                    <td>{{ $actuacion->tipo }}</td>
                                    <td>{{ $actuacion->observaciones }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">No se encontraron actuaciones.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Preguntar a José si quitar esto -->
            {!! $actuaciones->links() !!}
        </div>
    </div>
</div>
@endsection