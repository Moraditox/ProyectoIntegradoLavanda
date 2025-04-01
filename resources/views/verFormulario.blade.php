<style>

.modal-content{
    width:700px;

}
</style>
@if($formularioDataAlumno)
<h2>Formulario seguimiento alumno:</h2>
<div>
    <p><strong>- Actividades formativas y tareas que realiza la empresa: </strong>{{ $formularioDataAlumno->actividades_formativas_y_tareas_que_realiza_la_empresa }}</p>
    <p><strong>- Actividades y tareas que estas realizando en la empresa: </strong>{{ $formularioDataAlumno->actividades_y_tareas_que_estas_realizando_en_la_empresa }}</p>
    <p><strong>- Posibilidades formativas que ofrece la empresa: </strong>{{ $formularioDataAlumno->posibilidades_formativas_que_ofrece_la_empresa }}</p>
    <p><strong>- Cumplimiento del programa formativo por parte de la empresa. </strong>{{ $formularioDataAlumno->cumplimiento_del_programa_formativo_por_parte_de_la_empresa }}</p>
    <p><strong>- Seguimiento realizado por el tutor/a del centro de trabajo. </strong>{{ $formularioDataAlumno->seguimiento_realizado_por_el_tutor_del_centro_de_trabajo }}</p>
    <p><strong>- Seguimiento hecho por tu profesor/a. </strong>{{ $formularioDataAlumno->seguimiento_hecho_por_tu_profesor }}</p>
    <p><strong>- Adecuación de la formación recibida en el centro docente con las prácticas realizadas. </strong>{{ $formularioDataAlumno->adecuacion__formacion_recibida_en_centro_docente_con_practicas }}</p>
    <p><strong>- Integración en el entorno laboral. </strong>{{ $formularioDataAlumno->integracion_en_el_entorno_laboral }}</p>
    <p><strong>- Observaciones. </strong>{{ $formularioDataAlumno->observaciones }}</p>
    <p><strong>- Sugerencias generales de mejora. </strong>{{ $formularioDataAlumno->sugerencias_de_mejora }}</p>
    <p><strong>- Valoración general de las prácticas. </strong>{{ $formularioDataAlumno->valoracion_general_de_las_practicas }}</p>
</div>
@else
<p>No hay formulario de seguimiento del alumno disponible.</p>
@endif
@if($formularioDataEmpresa)
<h2>Formulario seguimiento empresa:</h2>
<div>
    <p><strong>- Competencias profesionales: </strong>{{ $formularioDataEmpresa->competencias_profesionales }}</p>
    <p><strong>- Competencias organizativas: </strong>{{ $formularioDataEmpresa->competencias_organizativas }}</p>
    <p><strong>- Competencias relacionales: </strong>{{ $formularioDataEmpresa->competencias_relacionales }}</p>
    <p><strong>- Capacidad de respuesta a las contingencias: </strong>{{
        $formularioDataEmpresa->capacidad_de_respuesta_a_las_contingencias }}</p>
    <p><strong>- Capacidad de aprendizaje: </strong>{{ $formularioDataEmpresa->capacidad_de_aprendizaje }}</p>
    <p><strong>- Cumplimiento de las normas: </strong>{{ $formularioDataEmpresa->cumplimiento_de_las_normas }}</p>
    <p><strong>- Actividades formativas y tareas realizadas en la empresa: </strong>{{
        $formularioDataEmpresa->actividades_formativas_y_tareas_realizadas_en_la_empresa }}</p>
    <p><strong>- Observaciones sobre competencias profesionales: </strong>{{
        $formularioDataEmpresa->observaciones_sobre_competencias_profesionales }}</p>
    <p><strong>- Observaciones sobre competencias personales y sociales: </strong>{{
        $formularioDataEmpresa->observaciones_sobre_competencias_personales_y_sociales }}</p>
    <p><strong>-Sugerencias generales de mejora: </strong>{{ $formularioDataEmpresa->sugerencias_generales_de_mejora }}
    </p>
</div>
@else
    <p>No hay formulario de seguimiento de la empresa disponible.</p>
@endif