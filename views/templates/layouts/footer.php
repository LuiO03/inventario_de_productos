            </main>
        </div>
    </div>

</body>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- Bootstrap Bundle (incluye Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables Core -->
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<!-- DataTables Bootstrap 5 JS -->
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>

<script>
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
        lengthMenu: "Mostrar _MENU_ registros",
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

    // Función para actualizar íconos de orden
    function actualizarIconosOrden() {
        $('#tablaProductos thead th').each(function () {
            const $th = $(this);
            const $orderSpan = $th.find('.dt-column-order');
            $orderSpan.html(''); // Limpiar iconos

            if ($th.hasClass('dt-ordering-asc')) {
                $orderSpan.html('<i class="ri-arrow-up-fill text-info pe-3"></i>');
            } else if ($th.hasClass('dt-ordering-desc')) {
                $orderSpan.html('<i class="ri-arrow-down-fill text-info pe-3"></i>');
            } else if ($th.hasClass('dt-orderable-asc') && $th.hasClass('dt-orderable-desc')) {
                $orderSpan.html('<i class="ri-arrow-up-down-line text-info pe-3"></i>');
            }
        });
    }

    $(document).ready(function () {
        const tabla = $('#tablaProductos').DataTable({
            order: [[0, 'desc']],
            pageLength: 10,
            deferRender: true,
            lengthMenu: [[10, 20, 50, 100], [10, 20, 50, 100]],
            ordering: true,
            searching: true,
            responsive: true,
            language: language_es,
            initComplete: function () {
                $('#tablaProductos').removeClass('d-none');
                actualizarIconosOrden(); // Llamar al cargar
                 // Agregar placeholder al input de búsqueda
                $('.dt-search input[type="search"]').attr('placeholder', 'Buscar productos por nombre, código, categoría, precio, etc....');
            }
        });

        // Eventos para mantener iconos actualizados
        $('#tablaProductos').on('draw.dt order.dt', actualizarIconosOrden);

        // Mostrar mensaje personalizado si no hay datos
        if (tabla.data().count() === 0) {
            $('#tablaProductos tbody').html(`
                <tr>
                    <td colspan="5" class="text-center py-3">
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <i class="ri-folder-warning-line fs-1 mb-2"></i>
                            <span>No hay productos registrados.</span>
                        </div>
                    </td>
                </tr>
            `);
        }

        // Funcionalidad del botón de limpiar búsqueda
        const $searchInput = $('.dt-search input[type="search"]');
        const $searchWrapper = $('.dt-search');
        const $clearBtn = $(`
            <button class="clear-search text-danger" type="button">
                <i class="ri-close-circle-fill"></i>
            </button>
        `);

        $searchWrapper.append($clearBtn);

        $searchInput.on('input', function () {
            $clearBtn.toggle($(this).val().length > 0);
        });

        $clearBtn.on('click', function () {
            $searchInput.val('');
            tabla.search('').draw();
            $clearBtn.hide();
        });

        /*
        tabla.on('draw', () => {
            const filas = document.querySelectorAll('#tablaProductos tbody tr');
            filas.forEach(fila => {
                fila.style.animation = 'none'; // Reinicia
                void fila.offsetWidth;
                fila.style.animation = 'fadeInUp 0.3s ease-in-out';
            });
        });
        */
    });
</script>

<script src="<?= BASE_URL ?>public/js/script.js"></script>
</html>