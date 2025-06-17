@extends('layouts.app') @section('template_title') {{ $convocatoria->observaciones ?? __('Show Convocatoria') }} @endsection

<style>
	.enlace-alumno {
		        color: black;
		        text-decoration: none;
		    }

	.alumnoDisabledRow td{
		background-color: #bbbbbb !important;
	}
		
	
</style>

@section('content')
	<p hidden id="convocatoriaId">{{$convocatoria->id}}</p>	
	<section class="content container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header" style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
						<div class="float-left">

							<span class="card-title">{{ __('Información de la') }} convocatoria {{ $convocatoria->periodo }}</span>
						</div>
						@if(session('success'))
							<div class="alert alert-success">
								{{ session('success') }}
							</div>
						@endif
						@if(session('error'))
							<div class="alert alert-danger">
								{{ session('error') }}
							</div>
						@endif
						<div class="float-right">
							<a class="btn btn-success" href="{{ route('convocatoria.edit', $convocatoria->id) }}"> {{ __('Modificar') }}</a>
							<a class="btn btn-primary" href="{{ route('convocatorias.index') }}"> {{ __('Volver') }}</a>
						</div>
					</div>
					<div class="card-body">
						@foreach($cursosUnicos as $cursoUnico)
						<div class="form-group">
							<strong>Curso académico</strong> {{ $convocatoria->anno_academico }}
							<br>
						</div>
						<div class="form-group">
							<strong>Convocatoria:</strong> {{ $convocatoria->periodo }}
						</div>
						<div class="form-group">
							<strong>Cursos:</strong> @php $ciclosArray = explode(', ', $cursoUnico['ciclos']);
	$totalCiclos = count($ciclosArray); @endphp @foreach($ciclosArray as $index => $ciclo) {{ $cursoUnico['curso'] }} {{ $ciclo }} @if (
			$index
			< $totalCiclos - 1
		) , @endif @endforeach </div>
								@endforeach
						</div>

						<div style="margin:20px" class="card mt-4">
							<ul style="gap:20px" class="nav nav-tabs">
								<li class="nav-item">
									<a class="nav-link active" id="fct-tab" data-toggle="tab" href="#fct">FP DUAL</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="actuaciones-tab" data-toggle="tab" href="#actuaciones">Actuaciones</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="empresas-tab" data-toggle="tab" href="#empresas">Empresas</a>
								</li>
								{{-- <li class="nav-item">
									<a class="nav-link" id="plazas-tab" data-toggle="tab" href="#plazas">Plazas</a>
								</li> --}}
							</ul>

								<div class="tab-content">
								<div class="tab-pane fade show active" id="fct">
									<!-- <br>
									<form action="{{ route('matriculas.index') }}" method="GET" class="form-inline">
										<div class="input-group w-100">
											<input type="text" class="form-control mr-2" name="search" id="search" placeholder="Buscar alumnos">

											<select class="form-control mr-2" name="ciclo" id="ciclo">
												<option value="">Seleccionar Ciclo</option>
												<option value="ASIR">ASIR</option>
												<option value="DAW">DAW</option>
												<option value="DAM">DAM</option>
											</select>

											<div class="input-group-append">
												<button class="btn btn-outline-success" type="submit">Buscar</button>
											</div>
										</div>
									</form> -->

									<table class="table mt-4">
										<thead>
											<tr>
												<th>Alumno</th>
												<th>Grupo</th>
												<th>Empresa</th>
												<th>Profesor</th>
												<th>Observaciones</th>
												<th>Acciones</th>
											</tr>
										</thead>
										<tbody>
											@if($matriculas->isEmpty())
												<tr>
													<td colspan="5" class="text-center">No hay alumnos asociados a esta convocatoria.</td>
												</tr>
											@else
												@foreach($matriculas as $matricula) 
													@php 
														$alumno = $matricula->alumnado;
		$rutaAlumno = route('alumnos.infoAlumno', $alumno->id);
		$alumnoHabilitado = $matricula->enabled ? true : false;
													@endphp
													<tr class="matricula-row 
														{{ empty(optional($matricula->alumnado->asignaciones)->empresa) ? 'sin-empresa' : '' }} 
														{{ empty(optional($matricula->alumnado->asignaciones)->profesor) ? 'sin-profesor' : '' }} 
														{{ !$alumnoHabilitado ? 'alumnoDisabledRow' : '' }}"
														data-matricula-id="{{$matricula->id}}">
														<td style="width:250px">
															<a href="{{ $rutaAlumno }}" class="enlace-alumno {{ (empty(optional($matricula->alumnado->asignaciones)->empresa) || empty(optional($matricula->alumnado->asignaciones)->profesor)) ? 'text-danger' : '' }}">{{ $alumno->apellido1 }} {{ $alumno->apellido2 }} {{ $alumno->nombre }}</a>
														</td>
														<td>{{ $matricula->ciclo }}</td>
														<td data-empresa-id="{{ $matricula->empresa_id ?? '' }}">
															@if(isset($matricula->empresa_id)) 
																@php
			$convEmpresa = $convocatoria_empresas->firstWhere('empresa_id', $matricula->empresa_id);
			$empresa = $convEmpresa && $convEmpresa->empresa ? $convEmpresa->empresa : null;
																@endphp
																{{ $empresa ? $empresa->nombre : 'Sin asignación de empresa' }}
															@else 
																Sin asignación de empresa 
															@endif
														</td>
														<td data-profesor-id="{{ $matricula->profesores_id ?? '' }}">
															@if (isset($matricula->profesores_id)) 
																@php
			$profesor = $profesores->firstWhere('id', $matricula->profesores_id);
																@endphp
																{{ $profesor ? $profesor->nombre : 'Sin profesor asignado' }}
															@else 
																Sin profesor asignado
															@endif
														</td>
														{{-- Observaciones --}}												
														<td>
															@if(isset($matricula->observaciones)) 
																{{ $matricula->observaciones }} 
															@else 
																Sin observaciones 
															@endif
														</td>
														<td>
															<button class="btn btn-success btn-sm editarEmpresaBtn" data-alumnado-id="{{ $matricula->alumnado->id }}" data-especialidad="{{ $matricula->especialidad }}" data-toggle="modal" data-target="#editarEmpresaModal">
																Asignar/Editar
															</button>
															<button class="btn btn-primary btn-sm informesBtn" data-alumno-id="{{ $alumno->id }}" data-toggle="modal" data-target="#informesModal">
																Informes
															</button>
														</td>
													</tr>
												@endforeach
											@endif
										</tbody>
									</table>
								</div>

								<div class="tab-pane fade" id="actuaciones"><br>

									<div class="row">
										<div class="col-md-6 mb-3">
											<a class="btn btn-success" href="{{ route('actuaciones.create') }}">Añadir Actuación</a>
										</div>
										<div class="col-md-6 mb-3">
											<!-- Formulario de búsqueda -->
											<form action="{{ route('actuaciones.index') }}" method="GET" class="form-inline">
												<div class="input-group w-100">
													<input type="text" class="form-control" name="search" placeholder="Buscar actuaciones">
													<div class="input-group-append">
														<button class="btn btn-outline-success" type="submit">Buscar</button>
													</div>
												</div>
											</form>
										</div>
									</div>

									<table class="table mt-4">
										<thead>
											<tr>
												<th>Fecha y hora</th>
												<th>Tipo</th>
												<th>Descripción</th>
											</tr>
										</thead>
										<tbody>
											@foreach($actuaciones as $actuacion)
											<tr>
												<td>{{ $actuacion->created_at }}</td>
												<td>{{ $actuacion->tipo }}</td>
												<td>{{ $actuacion->observaciones }}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>

								<div class="tab-pane fade" id="empresas">
									@if($convocatoria_empresas->isEmpty())
										<div class="text-center mt-4">
											<a href="{{ url('empresas') }}" class="btn btn-primary">Añadir Empresa</a>
										</div>
									@else
									<br>

									<form action="{{ route('empresa.indexConvocatoria') }}" method="GET" class="form-inline">
										<div class="input-group w-100">
											<input type="text" class="form-control mr-2" name="search" id="search" placeholder="Buscar empresas">
											<div class="input-group-append">
												<button type="submit" class="btn btn-outline-success">Buscar</button>
											</div>
										</div>
									</form>

									<table class="table mt-4">
										<thead>
											<tr>
												<th>Empresa</th>
												<th>Contacto</th>
												<th>Alumno de contacto</th>
												<th>Profesor de contacto</th>
												<th>Teléfono</th>
												<th>Email</th>
												<th>Observaciones</th>
												<th>Participación</th>
												<th>Acciones</th>
											</tr>
										</thead>
										<tbody>
											@foreach($convocatoria_empresas as $convocatoria_empresa)
												@php 
													$empresa = $convocatoria_empresa->empresa;
		$plazas = $convocatoria_empresa->ofertaPlazas;
												@endphp
												<tr>
													<td>{{ $empresa->nombre }}</td>
													<td>{{ $empresa->persona_contacto }}</td>
													<td>
														{{ $convocatoria_empresa->alumnoReferencia
			? $convocatoria_empresa->alumnoReferencia->apellido1 . ' ' . $convocatoria_empresa->alumnoReferencia->apellido2 . ' ' . $convocatoria_empresa->alumnoReferencia->nombre
			: 'No asignado' }}
													</td>
													<td>
														{{ $convocatoria_empresa->profesorReferencia
			? $convocatoria_empresa->profesorReferencia->nombre . ' ' . $convocatoria_empresa->profesorReferencia->apellido1
			: 'No asignado' }}
													</td>
													<td>{{ $empresa->telefono_contacto }}</td>
													<td>{{ $empresa->correo_contacto }}</td>
													<td>{{ $convocatoria_empresa->observaciones }}</td>
													<td>
														<form action="{{ route('enviar-correo-participar', ['empresa' => $empresa->id, 'convocatoria' => $convocatoria->id]) }}" method="POST" id="participarForm-{{ $empresa->id }}">
															@csrf
															<button type="button" class="btn btn-primary btn-sm" onclick="showConfirmationModalParticipar('{{ $empresa->nombre ?? '' }}', {{ $empresa->id }})">Participar</button>
														</form>
													</td>
													<td>
														<button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#plazasCollapse{{ $empresa->id }}">
															<i class="fa fa-eye" title="Ver plazas"></i> 
														</button>
														<a class="btn btn-sm btn-success" title="Editar" href="{{ route('convocatoria.editEmpresa', [$convocatoria->id, $empresa->id]) }}">
															<i class="fa fa-fw fa-edit"></i>
														</a>
														<button type="button" class="btn btn-sm btn-danger" title="Eliminar de la convocatoria" data-toggle="modal" data-target="#confirmModal{{ $convocatoria_empresa->id }}">
															<i class="fa fa-fw fa-trash"></i>
														</button>
														<!-- Modal de confirmación de eliminación -->
														<div class="modal fade" id="confirmModal{{ $convocatoria_empresa->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel{{ $convocatoria_empresa->id }}" aria-hidden="true">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																	<div class="modal-header">
																		<h5 class="modal-title" id="confirmModalLabel{{ $convocatoria_empresa->id }}">Confirmar Eliminación</h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	<div class="modal-body">
																		¿Estás seguro de que quieres eliminar la empresa de la convocatoria?
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
																		<form action="{{ route('convocatoria_empresas.destroy', $convocatoria_empresa->id) }}" method="POST">
																			@csrf @method('DELETE')
																			<button type="submit" class="btn btn-danger">Eliminar de Convocatoria</button>
																		</form>
																	</div>
																</div>
															</div>
														</div>
													</td>
												</tr>
												<tr class="collapse" id="plazasCollapse{{ $empresa->id }}">
													<td colspan="9">
														<div class="card card-body">
															<table class="table table-sm mb-0">
																<thead>
																	<tr>
																		<th>Especialidad</th>
																		<th>Plazas</th>
																		<th>Perfil</th>
																		<th>Tareas</th>
																		<th>Observaciones</th>
																	</tr>
																</thead>
																<tbody>
																	@if($plazas->isEmpty())
																		<tr>
																			<td colspan="5" class="text-center">No hay plazas asignadas</td>
																		</tr>
																	@else
																		@foreach($plazas as $plaza)
																			<tr>
																				<td>{{ $plaza->especialidad }}</td>
																				<td>{{ $plaza->plazas }}</td>
																				<td>{{ $plaza->perfil ?? 'No asignado' }}</td>
																				<td>{{ $plaza->tareas ?? 'No asignado' }}</td>
																				<td>{{ $plaza->observaciones ?? 'No asignado' }}</td>
																			</tr>
																		@endforeach
																	@endif
																</tbody>
															</table>
														</div>
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
									@endif
								</div>

						</div>
					</div>
				</div>
	</section>

	<!-- Modal de Informes -->
	<div class="modal fade" id="informesModal" tabindex="-1" role="dialog" aria-labelledby="informesModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="informesModalLabel">Informes</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
				</div>
				<div class="modal-body">
					<div id="informesModalContent"></div>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function () {
							$('.btn-ver-detalles').on('click', function () {
								var empresaId = $(this).data('empresa-id');
								var nombreEmpresa = $(this).data('nombre-empresa');
								$('#modalPlazas' + empresaId).modal('show');
								$('#modalPlazas' + empresaId + ' .modal-body p.nombre-empresa').text('Nombre empresa: ' + nombreEmpresa);
							});
						});



	</script>

	<script>
		function showConfirmationModalEmpresa(url, tipo, nombre) {
						let mensaje = "¿Estás seguro de que quieres enviar el correo a la empresa " + nombre + "?";
						document.getElementById('modalMessage').innerText = mensaje;
						document.getElementById('confirmButton').href = url;
						$('#confirmationModal').modal('show');
					}

					function showConfirmationModalAlumno(url, tipo, nombre) {
						let mensaje = "¿Estás seguro de que quieres enviar el correo al alumno " + nombre + "?";
						document.getElementById('modalMessage').innerText = mensaje;
						document.getElementById('confirmButton').href = url;
						$('#confirmationModal').modal('show');
					}



	</script>
	<script>
		const empresasConPlazas = @json($convocatoria_empresas->toArray());
		console.log(empresasConPlazas);
		const matriculas = Object.values(@json($matriculas->toArray()));
	</script>
	<script>
		$(document).ready(function () {
			$('.editarEmpresaBtn').on('click', function () {
				var alumnadoId = $(this).data('alumnado-id');
				$('#empresaSelect').data('alumnado-id', alumnadoId); 

				// Recuperamos la especialidad del alumnado
				var especialidad = $(this).data('especialidad');
				var empresaAsignada = $(this).closest('tr').find('td:nth-child(3)').data('empresa-id');

				// Filtramos las empresas con plazas para la especialidad
				var empresasFiltradas = empresasConPlazas.filter(function(convEmpresa) {
					if (!convEmpresa.oferta_plazas) return false;
					return convEmpresa.oferta_plazas.some(function(plaza) {
						return plaza.especialidad === especialidad;
					});
				});
				
				// Rellenamos el select
				var $empresaSelect = $('#empresaSelect');
				$empresaSelect.empty();
				$empresaSelect.append('<option value="">Sin empresa</option>');
				empresasFiltradas.forEach(function(convEmpresa) {
					// Calculamos si la empresa tiene plazas disponibles
					var plazasDisponibles = convEmpresa.oferta_plazas
						.filter(function(plaza) {
							return plaza.especialidad === especialidad;
						})
						.reduce(function(total, plaza) {
							return total + plaza.plazas;
						}, 0);

					// Recorremos todos los campos de las columnas de la tabla de alumnos que coincidan con la especialidad
					// Y restamos las plazas ocupadas
					matriculas.forEach(function(matricula) {
						if (matricula.especialidad === especialidad && matricula.empresa_id === convEmpresa.empresa_id) {
							plazasDisponibles--;
						}
					});

					// Comprobamos si la empresa está asignada al alumnado
					var isEmpresaAsignada = (convEmpresa.empresa_id === empresaAsignada);

					if (convEmpresa.empresa) {

						let textoOpcion = convEmpresa.empresa.nombre;
						if (plazasDisponibles > 0) {
							textoOpcion += " | " + plazasDisponibles + " Plaza/s disponibles";
							$empresaSelect.append(
								$('<option>', {
									value: convEmpresa.empresa.id,
									text: textoOpcion
								})
							);
						} else {
							textoOpcion += " | Sin plazas disponibles";
							$empresaSelect.append(
								$('<option>', {
									value: convEmpresa.empresa.id,
									text: textoOpcion,
									disabled: !isEmpresaAsignada,
									selected: isEmpresaAsignada
								})
							);
						}
					}
				});

				// Asignamos la empresa al select
				$empresaSelect.val(empresaAsignada);

				// Ponemos los valores de observaciones y habilitación del alumno en el modal
				var asignacion = $(this).closest('tr').find('td:nth-child(5)').text().trim(); // Observaciones
				var habilitado = !$(this).closest('tr').hasClass('alumnoDisabledRow'); // Verifica si la fila tiene la clase 'alumnoDisabledRow'
				$('#observacionesInput').val(asignacion);
				$('#habilitarAlumnoCheck').prop('checked', habilitado);
				var profesorIdAsignado = $(this).closest('tr').find('td:nth-child(4)').data('profesor-id');

				if (profesorIdAsignado) {
					$('#profesorSelect').val(profesorIdAsignado);
				} else {
					$('#profesorSelect').prop('selectedIndex', 0);
				}
			});

			$('#guardarCambiosBtn').on('click', function () {
				var alumnadoId = $('#empresaSelect').data('alumnado-id');
				var selectedEmpresaId = $('#empresaSelect').val();
				var selectedProfesorId = $('#profesorSelect').val(); 
				var convocatoriaId = $('#convocatoriaId').text(); // Obtener el ID de la convocatoria desde el elemento oculto
				var observaciones = $('#observacionesInput').val();
				var habilitarAlumno = $('#habilitarAlumnoCheck').is(':checked') ? 1 : 0; // Obtener el estado del checkbox
				console.log(selectedEmpresaId);
				$.ajax({
					type: 'POST',
					url: '{{ route("editar-asignacion-empresa") }}',
					data: {
						_token: '{{ csrf_token() }}',
						alumnadoId: alumnadoId,
						empresaId: selectedEmpresaId,
						profesorId: selectedProfesorId,
						convocatoriaId: convocatoriaId, // Enviar la URL/id de la convocatoria
						observaciones: observaciones,
						habilitarAlumno: habilitarAlumno // Enviar el estado del checkbox
					},
					success: function (response) {
						location.reload();
					},
					error: function () {
						alert('Error al actualizar los datos. Intente nuevamente.');
					}
				});
			});

			$(".asignar-btn").on("click", function () {
				var matriculaId = $(this).closest("tr.matricula-row").data("matricula-id");
				var selectedEmpresaId = $("#dropdown-" + matriculaId + " select[name='empresa_id']").val();
				var selectedProfesorId = $("#dropdown-profesor-" + matriculaId + " select[name='profesores_id']").val();
				var alumnoNombre = $("#dropdown-" + matriculaId + " select[name='empresa_id'] option:selected").data("alumno-nombre");
				var alumnoApellido = $("#dropdown-" + matriculaId + " select[name='empresa_id'] option:selected").data("alumno-apellido");

				if (selectedEmpresaId) {
					asignarEmpresa(matriculaId, selectedEmpresaId);
				}

				if (selectedProfesorId) {
					asignarProfesor(matriculaId, selectedProfesorId);
				}
			});

			function asignarEmpresa(matriculaId, empresaId) {
				$.ajax({
					type: "POST",
					url: "{{ route('asignar-empresa') }}",
					data: {
						_token: "{{ csrf_token() }}",
						matriculaId: matriculaId,
						selectedEmpresaId: empresaId
					},
					success: function (response) {
						location.reload(); 
					},
					error: function (error) {
						console.error(error);
						alert("Ocurrió un error al asignar la empresa. Por favor, inténtalo de nuevo.");
					}
				});
			}

			function asignarProfesor(matriculaId, profesorId) {
				$.ajax({
					type: "POST",
					url: "{{ route('asignar-profesor') }}", 
					data: {
						_token: "{{ csrf_token() }}",
						matriculaId: matriculaId,
						selectedProfesorId: profesorId
					},
					success: function (response) {
						location.reload(); 
					},
					error: function (error) {
						console.error(error);
						alert("Ocurrió un error al asignar el profesor. Por favor, inténtalo de nuevo.");
					}
				});
			}
		});
	</script>
	<script>
		function showConfirmationModalParticipar(nombreEmpresa, empresaId) {
							let mensaje = "¿Estás seguro de que quieres enviar el correo de participación a la empresa " + nombreEmpresa + "?";
							document.getElementById('modalMessageParticipar').innerText = mensaje;
							$('#confirmationModalParticipar').modal('show');

							document.getElementById('confirmButton2').onclick = function () {
							document.getElementById('participarForm-' + empresaId).submit();
							$('#confirmationModalParticipar').modal('hide');
						};
						}



	</script>
	<script>
		function showFormularioModal(route, alumnoId) {
						$.ajax({
							url: route,
							type: 'GET',
							success: function (data) {
								$('#modalTitle').text('Detalles del Formulario');
								$('#modalMessage').html(data);
								// $('#confirmButton').hide(); 
								$('#confirmationModal').modal('show');
							},
							error: function (xhr, status, error) {
								console.error(error);
							}
						});
					}




	</script>

	<!-- Modal de edición de empresa y profesor -->
	<div class="modal fade" id="editarEmpresaModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Editar asignación de empresa y profesor</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div>Seleccione la empresa:</div>
					<select id="empresaSelect" class="form-control">
					</select>
					<br>
					<div>Seleccione el profesor:</div>
					<select id="profesorSelect" class="form-control">
						<option value="" selected>Sin profesor</option>
						@isset($profesores)
							@foreach($profesores as $profesor)
								<option value="{{ $profesor->id }}">{{ $profesor->nombre }}</option>
							@endforeach
						@endisset
					</select>
					<br>
					<div>Observaciones:</div>
					<input type="text" id="observacionesInput" class="form-control" placeholder="Observaciones">

					<br>
					<div class="form-check">
						<input type="checkbox" class="form-check-input" id="habilitarAlumnoCheck" checked>
						<label class="form-check-label" for="habilitarAlumnoCheck">Alumno habilitado</label>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="guardarCambiosBtn">Guardar Cambios</button>
				</div>
			</div>
		</div>
	</div>

@endsection