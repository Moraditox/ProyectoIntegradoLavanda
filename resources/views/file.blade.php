<!--Vista para subir datos de archivos PDF de informes del alumnado-->
@extends('layouts.app')
@if (Session::has('success'))
    <success>
        {{ session()->get('success') }}
    </success>
@endif
@if (Session::has('error'))
    <error>
        {{ session()->get('error') }}
    </error>
@endif
@section('content')
    <h1>Informes del alumnado sobre las prácticas en empresas</h1>
        <form id="subir" name="subir" action="{{route('file.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
            <label><p>Seleccione archivo a subir:</p>
            <input type="file" id="file" name="file"></label>
            <br>
            <button type="submit" class="btn btn-primary"> Guardar </button>
        </form>
    <div class="container">
        <div class="row">
        @if( isset($data))
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <th>id</th>
                    <th>alumno</th>
                    <th>curso</th>
                    <th>trimestre</th>
                    <th>ciclo_formativo</th>
                    <th>centro_trabajo</th>
                    <th>tutor_trabajo</th>
                    <th>profesor_seguimiento</th>
                    <th>created_at</th>
                    <th>updated_at</th>
                    <th> </th>
                </tr>
                @foreach ($data as $element)
                    <tr>
                        <td>{{$element->id}}</td>
                        <td>{{$element->alumno}}</td>
                        <td>{{$element->curso}}</td>
                        <td>{{$element->trimestre}}</td>
                        <td>{{$element->ciclo_formativo}}</td>
                        <td>{{$element->centro_trabajo}}</td>
                        <td>{{$element->tutor_trabajo}}</td>
                        <td>{{$element->profesor_seguimiento}}</td>
                        <td>{{$element->created_at}}</td>
                        <td>{{$element->updated_at}}</td>
                        <td>
                            <button class="showButton btn btn-primary" data-bs-toggle="modal" data-bs-target="#show" 
                            data-value="{{utf8_encode(json_encode($element, JSON_UNESCAPED_UNICODE))}}">
                                Ver
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>No hay informes del alumnado.</p>
        @endif
        </div>
    </div>
    
@endsection
<style>
    .modal{
        text-align:center   
    }
    .modal-dialog{
        background-color: white;
        padding: 0.5em;
        margin-top: auto;
        margin-bottom: auto;
    }
</style>
<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.showButton').on('click', function () {
        $('#dataInModal').html( "" );
        let informe = $(this).data('value');
        let salida = "";
        $.each(informe, function(key, value){
            salida += `<p>${key}: ${value}</p>`;
        });
        $('#dataInModal').html( salida );
    });
});
</script>

<!--modal para mostrar todos los datos del informe-->
<div class="modal fade" id="show" title="Modal show"
data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >Datos del informe</h5>
            </div>
            <div class="modal-body" style="text-align: center">
                <div id="dataInModal"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>