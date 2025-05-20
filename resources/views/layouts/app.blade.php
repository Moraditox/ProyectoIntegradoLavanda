<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/957b82914b.js" crossorigin="anonymous"></script>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('storage/images/favicon.ico') }}">

    <title>{{ config('app.name', 'Lavanda') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        html, body {
            height: 100%;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        #app {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" style="font-size: 1.2rem; display: flex; justify-content: center; align-items: center; width: 100%;">
            <div class="container d-flex justify-content-center align-items-center max-w-100">
                <div class="d-flex align-items-center" style="flex-grow: 1;">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('storage/avatar/iesgrancapitan.png') }}" alt="Logo" style="max-width: 100px; margin-right: 10px;">
                        {{ config('app.name', 'Lavanda') }}
                    </a>
                </div>

                <div id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ url('/home') }}">Convocatorias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ url('/alumnos') }}">Alumnos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ url('/empresas') }}">Empresas</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Administración
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('/alumnado') }}">Alumnado</a></li>
                                <li><a class="dropdown-item" href="{{ url('/profesorado') }}">Profesorado</a></li>
                                <li><a class="dropdown-item" href="{{ url('/trabajadores') }}">Trabajadores</a></li>
                                <li><a class="dropdown-item" href="{{ url('/cursos') }}">Cursos</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="d-flex align-items-center" style="flex-grow: 1;">
                    <ul class="navbar-nav ms-auto">
                        @guest
                            {{-- @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Iniciar sesión') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Registrarse') }}</a>
                                </li>
                            @endif --}}
                            <a href="{{ url('/login-google') }}" class="nav-link">Iniciar sesión con Google</a>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar sesión') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>


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
    <footer class="bg-white border-t border-gray-200">
        <div class="container mx-auto py-4 px-5 flex flex-wrap flex-col sm:flex-row items-center justify-between">
            <p class="text-gray-500 text-sm text-center sm:text-left">&copy; <?php echo date('Y'); ?> Lavanda. Todos los
                derechos reservados.</p>
            <span class="inline-flex sm:ml-auto sm:mt-0 mt-2 justify-center sm:justify-start">
                <p class="text-gray-500 text-sm text-center sm:text-left">Creado por Raúl Pantoja y Álvaro García</p>
            </span>
        </div>
    </footer>
</body>

@yield('scripts')

</html>
