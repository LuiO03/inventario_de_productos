// Configuración del idioma en español para DataTables
const language_es = {
    decimal: "",
    emptyTable: `
        <div class="d-flex flex-column align-items-center justify-content-center text-center py-3">
            <i class="ri-database-2-line fs-1 mb-2"></i>
            <span>No hay datos disponibles en la tabla</span>
        </div>
    `,
    info: "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
    infoEmpty: "Mostrando 0 a 0 de 0 registros",
    infoFiltered: "(filtrado de _MAX_ registros totales)",
    lengthMenu: "Filas por página _MENU_ ",
    loadingRecords: "Cargando...",
    processing: "Procesando...",
    search: "Buscar:",
    zeroRecords: `
        <div class="d-flex flex-column text-center align-items-center justify-content-center py-3">
            <i class="ri-search-eye-line fs-1 mb-2"></i>
            <span>No se encontraron registros coincidentes</span>
        </div>
    `,
    paginate: {
        first: `<i class="ri-skip-left-line me-1 text-primario"></i> <span class="text-label">Primero</span>`,
        previous: `<i class="ri-arrow-left-s-line me-1 text-primario"></i> <span class="text-label">Anterior</span>`,
        next: `<span class="text-label">Siguiente</span> <i class="ri-arrow-right-s-line ms-1 text-primario"></i>`,
        last: `<span class="text-label">Último</span> <i class="ri-skip-right-line ms-1 text-primario"></i>`
    }
};

// Actualiza los íconos de orden en las columnas de la tabla
function actualizarIconosOrden() {
    $('#tabla thead th').each(function () {
        const $th = $(this);
        const $orderSpan = $th.find('.dt-column-order');
        $orderSpan.html(''); // Limpiar íconos previos

        if ($th.hasClass('dt-ordering-asc')) {
            $orderSpan.html('<i class="ri-sort-alphabet-asc orden-icon"></i>');
        } else if ($th.hasClass('dt-ordering-desc')) {
            $orderSpan.html('<i class="ri-sort-alphabet-desc orden-icon"></i>');
        } else if ($th.hasClass('dt-orderable-asc') && $th.hasClass('dt-orderable-desc')) {
            $orderSpan.html('<i class="ri-arrow-up-down-line orden-icon-none"></i>');
        }
    });
}
// Inicialización del DataTable y personalización
$(document).ready(function () {
    // Obtener índice de la columna con clase column-id-th
    const indiceColumnaId = $('#tabla thead th.column-id-th').index();
    const indiceColumnaFecha = $('#tabla thead th.column-fecha-th').index();
    const indiceColumnaEstado = $('#tabla thead th.column-estado-th').index();
    let columnDefs = [
    { orderable: false, targets: ['.column-check-th', '.column-opciones-th', '.column-estado-th', '.column-opciones-sm-th', '.column-user-th'] }
    ];

    if (indiceColumnaFecha !== -1) {
        columnDefs.push({ targets: indiceColumnaFecha, visible: false });
    }
    window.tabla = $('#tabla').DataTable({
        dom: 'tip',
        responsive: true, // 👈 ajusta columnas automáticamente
        autoWidth: false, // 👈 evita que fije anchos innecesarios
        scrollX: false,   // 👈 asegura que no meta scroll horizontal
        order: [[indiceColumnaId, 'asc']],// Orden inicial por la primera columna descendente antes [[1, 'asc']], 
        pageLength: 10,                  // Cantidad de registros por página
        deferRender: true,              // Mejora rendimiento en tablas grandes
        lengthMenu: [[10, 20, 50, 100], [10, 20, 50, 100]],
        ordering: true,
        searching: true,
        language: language_es,
        columnDefs: columnDefs,
        initComplete: function () {
            // Mostrar tabla una vez inicializada
            $('#tabla').addClass('ready');
            actualizarIconosOrden();
        },
    });

    // Buscador personalizado
    $('#buscadorPersonalizado').on('keyup', function () {
        tabla.search(this.value).draw();
    });

    // Selector de cantidad de registros
    $('#selectorCantidad').on('change', function () {
    tabla.page.len(this.value).draw();
    });

    // Filtro por Estado
    $('#filtroEstado').on('change', function () {
        const valor = this.value;

        if (valor === '') {
            tabla.column(indiceColumnaEstado).search('').draw();
        } else {
            tabla.column(indiceColumnaEstado).search(valor).draw();
        }
    });

    // Filtro por Rol
    const indiceColumnaRol = $('#tabla thead th.column-rol-th').index();
    $('#filtroRol').on('change', function () {
        const valor = this.value;

        if (valor === '') {
            tabla.column(indiceColumnaRol).search('').draw();
        } else {
            tabla.column(indiceColumnaRol).search('^' + valor + '$', true, false).draw();
        }
    });

    // Filtro de ordenamiento personalizado
    $('#filtroOrden').on('change', function () {
        const valor = this.value;

        switch (valor) {
            case 'nombre_asc':
                tabla.order([$('.column-name-th').index(), 'asc']).draw();
                break;
            case 'nombre_desc':
                tabla.order([$('.column-name-th').index(), 'desc']).draw();
                break;
            case 'fecha_desc':
                tabla.order([indiceColumnaFecha, 'desc']).draw(); // 👈 usamos el índice guardado
                break;
            case 'fecha_asc':
                tabla.order([indiceColumnaFecha, 'asc']).draw();
                break;
            default:
                tabla.order([]).draw();
        }
    });

    // Actualizar íconos cuando cambia el orden o se redibuja
    $('#tabla').on('draw.dt order.dt', actualizarIconosOrden);

    // Si no hay datos, mostrar un mensaje personalizado
    if (tabla.data().count() === 0) {
        $('#tabla tbody').html(`
            <tr>
                <td colspan="5" class="text-center py-3">
                    <div class="d-flex flex-column align-items-center text-center justify-content-center">
                        <i class="ri-folder-warning-line fs-1 mb-2"></i>
                        <span>No hay productos registrados.</span>
                    </div>
                </td>
            </tr>
        `);
    }

    // Botón para limpiar el buscador personalizado
    const $buscador = $('#buscadorPersonalizado');
    const $clearBuscador = $(`
        <button class="buscador-clear" type="button" style="display:none;">
            <i class="ri-close-circle-fill"></i>
        </button>
    `);

    // Insertar botón dentro del contenedor del buscador
    $('.buscador-container').css('position', 'relative').append($clearBuscador);

    // Mostrar/ocultar botón
    $buscador.on('input', function () {
        $clearBuscador.toggle($(this).val().length > 0);
    });

    // Limpiar búsqueda
    $clearBuscador.on('click', function () {
        $buscador.val('');
        tabla.search('').draw();
        $clearBuscador.hide();
    });

    // Animación de filas al redibujar (opcional)
    tabla.on('draw', () => {
        const filas = document.querySelectorAll('#tabla tbody tr');
        filas.forEach(fila => {
            fila.style.animation = 'slideInLeft 0.3s ease-in-out';
        });
    });
});

