@extends('layouts.app') @section('template_title') {{ $convocatoria->observaciones ?? __('Show Convocatoria') }} @endsection

<style>
	.enlace-alumno {
		        color: black;
		        text-decoration: none;
		    }
		
	
</style>

@section('content')
<section class="content container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header" style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
					@if(session('success'))
					<div class="alert alert-success">
						{{ session('success') }}
					</div>
					@endif

					<div class="float-left">
						<span class="card-title">{{ __('Información de la') }} convocatoria</span>
					</div>
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
						<strong>Cursos:</strong> @php $ciclosArray = explode(', ', $cursoUnico['ciclos']); $totalCiclos = count($ciclosArray); @endphp @foreach($ciclosArray as $index => $ciclo) {{ $cursoUnico['curso'] }} {{ $ciclo }} @if ($index
						< $totalCiclos - 1) , @endif @endforeach </div>
							@endforeach
					</div>

					<div style="margin:20px" class="card mt-4">
						<ul style="gap:20px" class="nav nav-tabs">
							<li class="nav-item">
								<a class="nav-link active" id="fct-tab" data-toggle="tab" href="#fct">FCT</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="actuaciones-tab" data-toggle="tab" href="#actuaciones">Actuaciones</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="empresas-tab" data-toggle="tab" href="#empresas">Empresas</a>
							</li>
						</ul>

						    <div class="tab-content">
							<div class="tab-pane fade show active" id="fct">
								<br>
								<form action="{{ route('matriculas.index') }}" method="GET" class="form-inline">
									<div class="input-group w-100">
										<input type="text" class="form-control mr-2" name="search" id="search" placeholder="Buscar alumnos">

										<!-- Select para filtrar por Ciclo -->
										<select class="form-control mr-2" name="ciclo" id="ciclo">
                                            <option value="">Seleccionar Ciclo</option>
                                            <option value="ASIR">ASIR</option>
                                            <option value="DAW">DAW</option>
                                            <option value="DAMP">DAMP</option>
                                        </select>

										<div class="input-group-append">
											<button class="btn btn-outline-success" type="submit">Buscar</button>
										</div>
									</div>
								</form>

								<table class="table mt-4">
									<thead>
										<tr>
											<th>Alumno</th>
											<th>Ciclo</th>
											<th>Empresa</th>
											<th>Profesor</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
										@foreach($matriculas as $matricula) @php $empresa = optional($matricula->alumnado->asignaciones)->empresa; $alumno = $matricula->alumnado; $rutaAlumno = route('alumnos.infoAlumno', $alumno->id); @endphp
										<tr class="matricula-row {{ empty(optional($matricula->alumnado->asignaciones)->empresa) ? 'sin-empresa' : '' }} {{ empty(optional($matricula->alumnado->asignaciones)->profesor) ? 'sin-profesor' : '' }}" data-matricula-id="{{$matricula->id}}">
											<td style="width:250px">
												<a href="{{ $rutaAlumno }}" class="enlace-alumno {{ (empty(optional($matricula->alumnado->asignaciones)->empresa) || empty(optional($matricula->alumnado->asignaciones)->profesor)) ? 'text-danger' : '' }}">{{ $alumno->apellido1 }} {{ $alumno->apellido2 }} {{ $alumno->nombre }}</a>
											</td>
											<td>{{ $matricula->curso_academico->ciclo }}</td>
											<td>
												@php $asignacionEmpresa = optional($matricula->alumnado->asignaciones)->empresa; @endphp @if($asignacionEmpresa) {{ $asignacionEmpresa->nombre }} @else Sin asignación de empresa @endif
											</td>
											<td>
												@php $asignacionProfesor = optional($matricula->alumnado->asignaciones)->profesor; @endphp @if($asignacionProfesor) {{ $asignacionProfesor->nombre }} {{ $asignacionProfesor->apellido1 }} @else Sin asignación de profesor @endif
											</td>
											<td>
												<button class="btn btn-success btn-sm editarEmpresaBtn" data-alumnado-id="{{ $matricula->alumnado->id }}" data-toggle="modal" data-target="#editarEmpresaModal">
                                                    Asignar/Editar
                                                </button>
												<button class="btn btn-primary btn-sm informesBtn" data-alumno-id="{{ $alumno->id }}" data-toggle="modal" data-target="#informesModal">
                                                    Informes
                                                </button>
                                                

											</td>
										</tr>
										@endforeach
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
								<!-- Agregar el formulario de búsqueda -->
								<br>
								<form action="{{ route('empresa.indexConvocatoria') }}" method="GET" class="form-inline">
									<div class="input-group w-100">
										<input type="text" class="form-control mr-2" name="search" id="search" placeholder="Buscar empresas">
										<div class="input-group-append">
											<button type="submit" class="btn btn-outline-success">Buscar</button>
										</div>
									</div>
								</form>

								<!-- <button style="margin:20px;padding:10px;font-size:15px;width:300px">Añadir empresa a convocatoria</button> -->
								<table class="table mt-4">
									<thead>
										<tr>
											<th>Empresa</th>
											<th>Contacto</th>
											<th>Teléfono</th>
											<th>Email</th>
											<th>Participación</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
										@foreach($convocatoria_empresas as $convocatoria_empresa) @php $empresa = $convocatoria_empresa->empresa; @endphp
										<tr>
											<td>{{ $empresa->nombre}}</td>
											<td>{{ $empresa->persona_contacto}}</td>
											<td>{{ $empresa->telefono_contacto}}</td>
											<td>{{ $empresa->correo_contacto}}</td>
											<td>
												<form action="{{ route('enviar-correo-participar', ['empresa' => $empresa->id, 'convocatoria' => $convocatoria->id]) }}" method="POST" id="participarForm-{{ $empresa->id }}">
													@csrf
													<button type="button" class="btn btn-primary btn-sm" onclick="showConfirmationModalParticipar('{{ $empresa->nombre ?? '' }}', {{ $empresa->id }})">Participar</button>
												</form>
											</td>
											<!-- Modal de confirmación -->
											<div class="modal fade" id="confirmationModalParticipar" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
												<div class="modal-dialog" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="modalTitle">Confirmación</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body">
															<p id="modalMessageParticipar"></p>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
															<button type="button" class="btn btn-primary" id="confirmButton2">Confirmar</button>


														</div>
													</div>
												</div>
											</div>
											<td>
												<button type="button" class="btn btn-primary btn-sm btn-ver-detalles" title="Ver detalles participación" data-toggle="modal" data-target="#modalPlazas{{ $empresa->id }}" data-nombre-empresa="{{ $empresa->nombre }}">
                                                    <i class="fas fa-eye"></i>
                                                </button> @foreach ($convocatoriaEmpresaPlazas as $plaza)
												<!-- Modal -->
												<div class="modal fade" id="modalPlazas{{ $empresa->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title" id="exampleModalLabel">Detalle de Todas las Plazas</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
															</div>
															<div class="modal-body">
																<p><strong>Nombre empresa:</strong> {{ $empresa->nombre }}</p>

																@foreach ($convocatoriaEmpresaPlazas->where('empresa_id', $empresa->id) as $plazaEmpresa)
																<p><strong>Ciclo:</strong> {{ $plazaEmpresa->ciclo ? $plazaEmpresa->ciclo->ciclo : 'N/A' }}</p>
																<p><strong>Número de plazas:</strong> {{ $plazaEmpresa->numero_plazas }}</p>
																<p><strong>Perfil:</strong> {{ $plazaEmpresa->perfil }}</p>
																<p><strong>Tareas:</strong> {{ $plazaEmpresa->tareas }}</p>
																<p><strong>Observaciones:</strong> {{ $plazaEmpresa->observaciones }}</p>
																@endforeach
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
															</div>
														</div>
													</div>
												</div>
												@endforeach


												<a class="btn btn-sm btn-success" title="Editar" href="{{ route('empresas.edit', $empresa->id) }}">
													<i class="fa fa-fw fa-edit"></i>
												</a>
												<button type="button" class="btn btn-sm btn-danger" title="Eliminar de la convocatoria" data-toggle="modal" data-target="#confirmModal{{ $convocatoria_empresa->id }}">
                                                <i class="fa fa-fw fa-trash"></i>
                                            </button>

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
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
								</div>
							</div>
					</div>
				</div>
			</div>

</section>

<!-- Modal de Confirmación -->
<!-- <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalTitle">Confirmación</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
			</div>
			<div class="modal-body">
				<p id="modalMessage">¿Estás seguro de que quieres enviar el correo?</p>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<a href="#" class="btn btn-primary" id="confirmButton">Confirmar</a>
			</div>
		</div>
	</div>
</div> -->

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

<!-- Scripts -->
<script>
	$(document).ready(function () {
		        // Función para mostrar el contenido del modal de informes
		        function mostrarInformes(alumnoId) {

		            var informesContent = `
		                <table class="table mt-4">
		                    <thead>
		                        <tr>
		                            <th>Seguimiento</th>
		                            <th>Documentación Final</th>
		                            <th>Subida de archivos</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        <tr>
		                            <!-- contenido -->
		                            <td>
		                                @if ($empresa)
											<a href="#" class="btn btn-primary btn-sm" title="Enviar formulario a la empresa" onclick="showConfirmationModalEmpresa('{{ route('enviar-correo-empresa', $empresa->id) }}', 'empresa', '{{ $empresa->nombre }}')">
												<i class="fas fa-building"></i>
											</a>
											@else @endif
		
											<a href="#" class="btn btn-primary btn-sm" title="Enviar formulario al alumno" onclick="showConfirmationModalAlumno('{{ route('enviar-correo-alumno', $matricula->id) }}', 'alumno', '{{ $alumno->nombre }}')">
												<i class="fas fa-user"></i>
											</a>
											<button class="btn btn-primary btn-sm" onclick="showFormularioModal('{{ route('verFormularioSeguimiento', $alumno->id) }}', {{ $alumno->id }})">
												<i class="fas fa-eye"></i>
											</button>
		                            </td>
		                            <!-- Modal de Confirmación -->
													<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title" id="modalTitle">Confirmación</h5>
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&times;</span>
																	</button>
																</div>
																<div class="modal-body">
																	<p id="modalMessage">¿Estás seguro de que quieres enviar el correo?</p>
																</div>
		
																<div class="modal-footer">
																	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
																	<a href="#" class="btn btn-primary" id="confirmButton">Confirmar</a>
																</div>
															</div>
														</div>
													</div>
		
		                            <td>
		                                @if ($empresa)
														<a href="#" class="btn btn-primary btn-sm" title="Enviar PDF a la empresa" onclick="showConfirmationModalEmpresa('{{ route('enviar-correo-pdf-empresa', $empresa->id ?? '') }}', 'pdf-empresa', '{{ $empresa->nombre ?? '' }}')">
															<i class="fas fa-file-pdf"></i> PDF Empresa
														</a><br><br> @endif @if ($alumno)
														<a href="#" class="btn btn-primary btn-sm" title="Enviar PDF al alumno" onclick="showConfirmationModalAlumno('{{ route('enviar-correo-pdf-alumno', $alumno->id ?? '') }}', 'pdf-alumno', '{{ $alumno->nombre ?? '' }}')">
															<i class="fas fa-file-pdf"></i> PDF Alumno
														</a>
														@endif
		                            </td>
		
		                            <td>
		                                <form style="width:370px" action="{{ route('informes.store') }}" method="POST" enctype="multipart/form-data">
															@csrf
															<input type="hidden" name="empresa_id" value="{{ optional($empresa)->id }}">
															<input type="hidden" name="alumno_id" id="alumno_id" value="{{ $alumno->id }}">
		
		
															<label for="tipo_informe">Tipo de informe:</label>
															<select name="tipo_informe" id="tipo_informe">
		                                <option value="alumnado">Informe de Alumnado</option>
		                                <option value="profesorado">Informe de Profesorado</option>
		                                <option value="ficha_semanal">Ficha Semanal</option>
		                            </select>
		
		
		
		
															<input type="file" name="file" accept=".pdf">
															<button type="submit" class="btn btn-primary btn-sm">
		                                <i class="fas fa-upload"></i>
		                            </button>
														</form>
		                            </td>
		
		                        </tr>
		                    </tbody>
		                </table>
		            `;
		            // Inserta el contenido en el modal
		            $('#informesModalContent').html(informesContent);
		        }
		
		        // Manejador de eventos para el botón Informes
		        $('.informesBtn').on('click', function () {
		            var alumnoId = $(this).data('alumno-id');
		            // Muestra el modal y carga la información del alumno correspondiente
		            $('#informesModal').modal('show');
		            mostrarInformes(alumnoId);
		        });
		    });
		
	
</script>

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
	$(document).ready(function () {
			            $('.editarEmpresaBtn').on('click', function () {
			                var alumnadoId = $(this).data('alumnado-id');
			
			                $.ajax({
			                    type: "GET",
			                    url: "{{ route('empresasDisponibles', $convocatoria->id) }}",
			                    success: function (empresas) {
			                        var empresaSelect = $('#empresaSelect');
			                        empresaSelect.empty(); 
			
			                        $.each(empresas, function (index, convocatoria_empresa) {
                                    if (convocatoria_empresa.empresa) {
                                        var nombreEmpresa = convocatoria_empresa.empresa.nombre;
                                        var numeroPlazas = convocatoria_empresa.numero_plazas; // Número de plazas ofrecidas por la empresa
                                        var optionText = nombreEmpresa + ' - Plazas: ' + numeroPlazas;
                                        empresaSelect.append($('<option>', {
                                            value: convocatoria_empresa.empresa.id,
                                            text: optionText
                                        }));
                                    }
                                });

			                        empresaSelect.data('alumnado-id', alumnadoId);
			
			                        $('#editarEmpresaModal').modal('show');
									$('#editarEmpresaModal .close').on('click', () => $('#editarEmpresaModal').modal('hide'))
			                    },
			                    error: function (xhr, status, error) {
			                        console.error(xhr.responseText);
			                        alert("Error al cargar las empresas. Intente nuevamente.");
			                    }
			                });
			                $.ajax({
			                    type: "GET",
			                    url: "{{ url('profesores-disponibles') }}",
			                    data: { alumnadoId: alumnadoId },
			                    success: function (data) {
			                        var profesorSelect = $('#profesorSelect');
			                        profesorSelect.empty();
			
			                        // Rellenar el select con los profesores disponibles
			                        $.each(data.profesores, function (index, profesor) {
			                            profesorSelect.append($('<option>', {
			                                value: profesor.id,
			                                text: profesor.nombre
			                            }));
			                        });
			
			                        $('#editarEmpresaModal').modal('show');
			                    },
			                    error: function (xhr, status, error) {
			                        console.error(xhr.responseText);
			                        alert("Error al cargar los profesores. Intente nuevamente.");
			                    }
			                });
			            });
			
			        $('#guardarCambiosBtn').on('click', function () {
			            var alumnadoId = $('#empresaSelect').data('alumnado-id');
			            var selectedEmpresaId = $('#empresaSelect').val();
			            var selectedProfesorId = $('#profesorSelect').val(); 
			
			            $.ajax({
			                type: 'POST',
			                url: '{{ route("editar-asignacion-empresa") }}',
			                data: {
			                    _token: '{{ csrf_token() }}',
			                    alumnadoId: alumnadoId,
			                    empresaId: selectedEmpresaId,
			                    profesorId: selectedProfesorId 
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
                </select><br>
				<div>Seleccione el profesor:</div>
				<select id="profesorSelect" class="form-control">
                </select>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="guardarCambiosBtn">Guardar Cambios</button>
			</div>

		</div>
	</div>
</div>

@endsection