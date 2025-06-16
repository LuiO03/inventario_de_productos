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
        "decimal": "",
        "emptyTable": `
            <div class="d-flex flex-column align-items-center justify-content-center py-3">
                <i class="ri-database-2-line fs-1 mb-2"></i>
                <span>No hay datos disponibles en la tabla</span>
            </div>
        `,
        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
        "infoEmpty": "Mostrando 0 a 0 de 0 registros",
        "infoFiltered": "(filtrado de _MAX_ registros totales)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar  _MENU_Registros",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": `
            <div class="d-flex flex-column align-items-center justify-content-center py-3">
                <i class="ri-search-eye-line fs-1 mb-2"></i>
                <span>No se encontraron registros coincidentes</span>
            </div>
        `,
        "paginate": {
            "first": "Primero",
            "last": "Último",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    };

    $(document).ready(function () {
        const tabla = $('#tablaProductos').DataTable({
            order: [[0, 'desc']],
            initComplete: function () {
                // Mostrar la tabla cuando DataTable ya está lista
                $('#tablaProductos').removeClass('d-none');
            },
            pageLength: 5,
            lengthMenu: [ [5, 20, 50, 100], [5, 20, 50, 100] ],
            ordering: true,
            searching: true,
            responsive: true,
            language: language_es,
        });

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

        const $searchInput = $('.dt-search input[type="search"]');
        const $searchWrapper = $('.dt-search');

        // Crear el botón de limpiar
        const $clearBtn = $(`
            <button class="clear-search text-danger" type="button">
                <i class="ri-close-circle-fill"></i>
            </button>` // ri-close-line
        );

        $searchWrapper.append($clearBtn);

        // Mostrar el botón si hay texto
        $searchInput.on('input', function () {
            $clearBtn.toggle($(this).val().length > 0);
        });

        // Limpiar búsqueda al hacer clic
        $clearBtn.on('click', function () {
            $searchInput.val('');
            tabla.search('').draw(); // limpiar búsqueda de DataTables
            $clearBtn.hide();
        });
        /* 
        tabla.on('draw', () => {
            const filas = document.querySelectorAll('#tablaProductos tbody tr');
            filas.forEach(fila => {
                fila.style.animation = 'none'; // Reinicia
                void fila.offsetWidth; // Trigger reflow
                fila.style.animation = 'fadeInUp 0.3s ease-in-out';
            });
        });*/
    });
</script>

<script src="<?= BASE_URL ?>public/js/script.js"></script>
</html>