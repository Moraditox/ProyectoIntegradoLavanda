@extends('layouts.app')

@section('template_title')
Empresas
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<span id="card_title">
							{{ __('Empresas') }}
						</span>
						<div class="tab-pane fade" id="empresas">
			
							<form action="{{ route('empresa.indexConvocatoria') }}" method="GET">
								<div class="form-group form-inline">
									<input type="text" name="search" id="search" class="form-control mr-2"
										placeholder="Buscar empresas">
									<button type="submit" class="btn btn-primary">Buscar</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover">
							<thead class="thead">
								<tr>
									<th>Empresa</th>
									<th>Contacto</th>
									<th>Teléfono</th>
									<th>Email</th>
								</tr>
							</thead>
							<tbody>
								@foreach($empresas as $empresa)
								<tr>
									<td>{{ $empresa->nombre }}</td>
									<td>{{ $empresa->persona_contacto }}</td>
									<td>{{ $empresa->telefono_contacto }}</td>
									<td>{{ $empresa->correo_contacto }}</td>
									
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection