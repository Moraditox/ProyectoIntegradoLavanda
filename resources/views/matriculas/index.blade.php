@extends('layouts.app')

@section('template_title')
FCT
@endsection

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

					<div style="margin:20px" class="card mt-4">
						<ul style="gap:20px" class="nav nav-tabs">
							<li class="nav-item">
								<a class="nav-link active" id="fct-tab" data-toggle="tab" href="#fct">FCT</a>
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
												@php $asignacionProfesor = optional($matricula->alumnado->asignaciones)->profesor; @endphp @if($asignacionProfesor) {{ $asignacionProfesor->nombre }} @else Sin asignación de profesor @endif
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
												

												
											</td>
										</tr>
										
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

<!-- <div class="modal fade" id="editarEmpresaModal" tabindex="-1" role="dialog">
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
</div> -->

@endsection