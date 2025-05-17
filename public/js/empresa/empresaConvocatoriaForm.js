document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('gradosTable').getElementsByTagName('tbody')[0];
    const addRowButton = document.getElementById('addRow');
    let rowIndex = 1; // Índice inicial para las filas

    // Ponemos un buscador en los select de profesor y alumno
    $('#alumno_referencia_id').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Selecciona un alumno',
    });

    $('#profesor_referencia_id').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Selecciona un profesor',
    });

    // Mensaje para la fila vacía
    const emptyRowMessage = "No hay plazas asignadas a la empresa actualmente";

    // Función para mostrar la fila vacía
    function showEmptyRow() {
        const emptyRow = document.createElement('tr');
        emptyRow.classList.add('empty-row');
        emptyRow.innerHTML = `<td colspan="6" class="text-center text-muted">${emptyRowMessage}</td>`;
        table.appendChild(emptyRow);
    }

    // Función para comprobar si la tabla está vacía
    function checkEmptyTable() {
        if (table.getElementsByTagName('tr').length === 0) {
            showEmptyRow();
        }
    }

    // Inicialmente, si no hay filas (por si acaso), mostramos la fila vacía
    checkEmptyTable();

    addRowButton.addEventListener('click', function () {
        // Si hay una fila vacía, la quitamos antes de añadir una nueva
        const emptyRow = table.querySelector('.empty-row');
        if (emptyRow) emptyRow.remove();

        const currentRows = table.getElementsByTagName('tr').length;
        if (currentRows >= especialidadesCount) {
            alert("No puedes añadir más filas que especialidades disponibles.");
            return;
        }

        const newRow = table.insertRow();

        // Construir las opciones del select
        let options = '';
        especialidades.forEach(function (esp) {
            options += `<option value="${esp}">${esp}</option>`;
        });

        newRow.innerHTML = `
            <td>
                <select name="especialidades[${rowIndex}][nombre]" class="form-control">
                    ${options}
                </select>
            </td>
            <td>
                <input type="number" name="especialidades[${rowIndex}][plazas]" class="form-control" placeholder="Número de Plazas" min="1" required>
            </td>
            <td>
                <input type="text" name="especialidades[${rowIndex}][perfil]" class="form-control" placeholder="Perfil" required>
            </td>
            <td>
                <input type="text" name="especialidades[${rowIndex}][tareas]" class="form-control" placeholder="Tareas" required>
            </td>
            <td>
                <input type="text" name="especialidades[${rowIndex}][observaciones]" class="form-control" placeholder="Observaciones" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger remove-row" title="Eliminar esta fila de la tabla">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;
        rowIndex++; // Incrementar el índice para la siguiente fila
    });

    table.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-row') || event.target.closest('.remove-row')) {
            const row = event.target.closest('tr');
            row.remove();
            // Si la tabla se queda vacía, mostramos la fila vacía
            checkEmptyTable();
        }
    });
});