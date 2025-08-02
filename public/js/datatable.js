// Configuración del idioma en español para DataTables
const language_es = {
    decimal: "",
    emptyTable: `
        <div class="d-flex flex-column align-items-center justify-content-center text-center py-3">
            <i class="ri-database-2-line fs-1 mb-2"></i>
            <span>No hay datos disponibles en la tabla</span>
        </div>
    `,
    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
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
        first: `<i class="ri-contract-left-fill me-1 text-info"></i> <span class="text-label">Primero</span>`,
        previous: `<i class="ri-arrow-left-fill me-1 text-info"></i> <span class="text-label">Anterior</span>`,
        next: `<span class="text-label">Siguiente</span> <i class="ri-arrow-right-fill ms-1 text-info"></i>`,
        last: `<span class="text-label">Último</span> <i class="ri-contract-right-fill ms-1 text-info"></i>`
    }
};

// Actualiza los íconos de orden en las columnas de la tabla
function actualizarIconosOrden() {
    $('#tabla thead th').each(function () {
        const $th = $(this);
        const $orderSpan = $th.find('.dt-column-order');
        $orderSpan.html(''); // Limpiar íconos previos

        if ($th.hasClass('dt-ordering-asc')) {
            $orderSpan.html('<i class="ri-arrow-up-fill orden-icon"></i>');
        } else if ($th.hasClass('dt-ordering-desc')) {
            $orderSpan.html('<i class="ri-arrow-down-fill orden-icon"></i>');
        } else if ($th.hasClass('dt-orderable-asc') && $th.hasClass('dt-orderable-desc')) {
            $orderSpan.html('<i class="ri-arrow-up-down-line orden-icon-none"></i>');
        }
    });
}

// Inicialización del DataTable y personalización
$(document).ready(function () {
    
    const tabla = $('#tabla').DataTable({
        dom: 'tip', 
        order: [[0, 'asc']],            // Orden inicial por la primera columna descendente
        pageLength: 10,                  // Cantidad de registros por página
        deferRender: true,              // Mejora rendimiento en tablas grandes
        lengthMenu: [[10, 20, 50, 100], [10, 20, 50, 100]],
        ordering: true,
        searching: true,
        language: language_es,
        initComplete: function () {
            // Mostrar tabla una vez inicializada
            $('#tabla').addClass('ready');
            actualizarIconosOrden();
        }
    });

    // Buscador personalizado
    $('#buscadorPersonalizado').on('keyup', function () {
        tabla.search(this.value).draw();
    });

    // Selector de cantidad de registros
    $('#selectorCantidad').on('change', function () {
    tabla.page.len(this.value).draw();
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

    // Funcionalidad del botón para limpiar la búsqueda
    const $searchInput = $('.dt-search input[type="search"]');
    const $searchWrapper = $('.dt-search');
    const $clearBtn = $(`
        <button class="clear-search text-danger fs-4" type="button">
            <i class="ri-close-circle-fill"></i>
        </button>
    `);

    // Agregar botón de limpiar al buscador
    $searchWrapper.append($clearBtn);

    // Mostrar/ocultar botón según si hay texto
    $searchInput.on('input', function () {
        $clearBtn.toggle($(this).val().length > 0);
    });

    // Limpiar búsqueda al hacer clic
    $clearBtn.on('click', function () {
        $searchInput.val('');
        tabla.search('').draw();
        $clearBtn.hide();
    });

    // Animación de filas al redibujar (opcional)
    tabla.on('draw', () => {
        const filas = document.querySelectorAll('#tabla tbody tr');
        filas.forEach(fila => {
            fila.style.animation = 'slideInLeft 0.3s ease-in-out';
        });
    });
});
