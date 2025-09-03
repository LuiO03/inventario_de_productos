document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.check-row');
    const checkboxColumnClass = 'column-check-td';
    let modoSeleccion = false; // <- NUEVO: estado global solo para móvil
    let longPressTimeout = null;

    function actualizarCantidadSeleccionados() {
        const seleccionados = document.querySelectorAll('.check-row:checked');
        const eliminarSeleccion = document.querySelector('.eliminar-seleccion');
        const btnEliminar = document.querySelector('.eliminar-seleccion .export-text');
        const cancelarSeleccion = document.querySelector('.cancelar-seleccion');
        const btnPdf = document.querySelector('#formExportarPdf .export-text');
        const btnExcel = document.querySelector('#formExportarExcel .export-text');

        document.querySelectorAll('tbody tr').forEach(fila => {
            const checkbox = fila.querySelector('.check-row');
            fila.classList.toggle('fila-seleccionada', checkbox && checkbox.checked);
        });

        if (seleccionados.length > 0) {
            eliminarSeleccion.classList.add('visible');
            cancelarSeleccion.classList.add('visible');
            btnEliminar.textContent = `Eliminar (${seleccionados.length})`;
            btnPdf.textContent = `PDF (${seleccionados.length})`;
            btnExcel.textContent = `Excel (${seleccionados.length})`;
        } else {
            eliminarSeleccion.classList.remove('visible');
            cancelarSeleccion.classList.remove('visible');
            btnEliminar.textContent = `Eliminar`;
            btnPdf.textContent = `PDF`;
            btnExcel.textContent = `Excel`;
            modoSeleccion = false;
        }
    }

    // ========= Cancelar seleccion =========
    document.getElementById('btnCancelarSeleccion').addEventListener('click', function () {
        document.querySelectorAll('.check-row').forEach(chk => chk.checked = false);
        document.getElementById('checkAll').checked = false;
        document.querySelectorAll('tr').forEach(row => row.classList.remove('fila-seleccionada'));
        actualizarCantidadSeleccionados();
        modoSeleccion = false; // <- salir del modo selección en móvil
    });

    // ========= GESTO DE SELECCIÓN MÚLTIPLE (mouse - desktop) =========
    let isMouseDown = false;
    let toggleState = false;
    let gestureTimeout = null;
    let startIndex = null;
    let endIndex = null;

    const iniciarSeleccionMultiple = (checkbox, index) => {
        isMouseDown = true;
        toggleState = !checkbox.checked;
        startIndex = index;
        endIndex = index;
        actualizarSeleccionMultiple();
    };

    const actualizarSeleccionMultiple = () => {
        const min = Math.min(startIndex, endIndex);
        const max = Math.max(startIndex, endIndex);

        checkboxes.forEach((chk, i) => {
            if (i >= min && i <= max) {
                chk.checked = toggleState;
                chk.dispatchEvent(new Event('change'));
            }
        });
    };

    document.querySelectorAll('td.column-check-td').forEach((td, index) => {
        td.setAttribute('data-index', index);
    });

    document.addEventListener('mousedown', function (e) {
        const td = e.target.closest('td');
        const checkbox = td?.querySelector('input[type="checkbox"]');

        if (td?.classList.contains(checkboxColumnClass) && checkbox) {
            const index = parseInt(td.getAttribute('data-index'));
            gestureTimeout = setTimeout(() => {
                iniciarSeleccionMultiple(checkbox, index);
            }, 500);
            e.preventDefault();
        }
    });

    document.addEventListener('mouseup', () => {
        clearTimeout(gestureTimeout);
        isMouseDown = false;
    });

    document.addEventListener('mouseover', function (e) {
        if (!isMouseDown) return;
        const td = e.target.closest('td');
        if (!td?.classList.contains(checkboxColumnClass)) return;

        const index = parseInt(td.getAttribute('data-index'));
        if (!isNaN(index)) {
            endIndex = index;
            actualizarSeleccionMultiple();
        }
    });

    // ========= GESTO TOUCH (solo móvil) =========
    if ('ontouchstart' in window) {
        document.querySelectorAll('tbody tr').forEach(fila => {
            const checkbox = fila.querySelector('.check-row');
            if (!checkbox) return;

            // Long-press inicial para entrar en modo selección
            fila.addEventListener('touchstart', () => {
                if (modoSeleccion) return; // ya estamos en modo selección
                longPressTimeout = setTimeout(() => {
                    checkbox.checked = true;
                    checkbox.dispatchEvent(new Event('change'));
                    modoSeleccion = true; // activar modo selección
                }, 800); // tiempo recomendado
            }, { passive: true });

            fila.addEventListener('touchend', () => clearTimeout(longPressTimeout));
            fila.addEventListener('touchcancel', () => clearTimeout(longPressTimeout));

            // Tap normal cuando ya estamos en modo selección
            fila.addEventListener('click', (e) => {
                if (!modoSeleccion) return;
                // evitar conflicto con botones/links
                if (e.target.closest('button, a, input, label')) return;
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            });
        });
    }

    // ========= CHECK ALL =========
    checkAll.addEventListener('change', function () {
        const isChecked = this.checked;
        checkboxes.forEach(chk => chk.checked = isChecked);
        actualizarCantidadSeleccionados();
    });

    // ========= CAMBIO INDIVIDUAL =========
    checkboxes.forEach(chk => {
        chk.addEventListener('change', actualizarCantidadSeleccionados);
    });

    // ========= CLICK EN CELDA PARA TOGGLE =========
    document.querySelectorAll('td.column-check-td').forEach(td => {
        td.addEventListener('click', function (e) {
            if (e.target.tagName.toLowerCase() === 'input') return;
            const checkbox = this.querySelector('input[type="checkbox"]');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            }
        });
    });

    actualizarCantidadSeleccionados();

    // ========= Quitar seleccionados al hacer click fuera del contenedor =========
    document.addEventListener('click', function (e) {
        const contenedor = document.querySelector('.contenedor');
        if (!contenedor.contains(e.target)) {
            document.querySelectorAll('.check-row').forEach(chk => chk.checked = false);
            const checkAll = document.getElementById('checkAll');
            if (checkAll) checkAll.checked = false;
            document.querySelectorAll('tr').forEach(row => row.classList.remove('fila-seleccionada'));
            actualizarCantidadSeleccionados();
            modoSeleccion = false; // también salimos del modo selección
        }
    });
});


function prepararEnvio(formId, inputId) {
    document.getElementById(formId).addEventListener('submit', function (e) {
        const seleccionados = Array.from(document.querySelectorAll('.check-row:checked'))
                                .map(chk => chk.value);
        document.getElementById(inputId).value = seleccionados.join(',');
    });
}
// Asigna la función a ambos formularios
prepararEnvio('formExportarPdf', 'idsSeleccionadosPdf');
prepararEnvio('formExportarExcel', 'idsSeleccionadosExcel');



document.addEventListener("DOMContentLoaded", () => {
    const menus = document.querySelectorAll(".menu-opciones-movil");

    menus.forEach(menu => {
        const btnMenu = menu.querySelector(".btn-menu");
        const lista = menu.querySelector(".menu-lista");

        btnMenu.addEventListener("click", (e) => {
        e.stopPropagation();

        // Cerrar todos los menús antes de abrir el actual
        document.querySelectorAll(".menu-lista").forEach(m => {
            m.style.display = "none";
        });

        // Toggle del menú actual
        lista.style.display = "flex";
        });
    });

    // Cerrar todos si haces click fuera
    document.addEventListener("click", () => {
        document.querySelectorAll(".menu-lista").forEach(m => {
        m.style.display = "none";
        });
    });
});

// ========= Manejo del botón ATRÁS del móvil =========
function activarModoSeleccion() {
    if (!modoSeleccion) {
        modoSeleccion = true;
        history.pushState({ seleccion: true }, ""); // agregamos un estado al historial
    }
}

function desactivarModoSeleccion() {
    document.querySelectorAll('.check-row').forEach(chk => chk.checked = false);
    document.getElementById('checkAll').checked = false;
    document.querySelectorAll('tr').forEach(row => row.classList.remove('fila-seleccionada'));
    actualizarCantidadSeleccionados();
    if (modoSeleccion) {
        modoSeleccion = false;
        // Volvemos el historial a como estaba (evita quedarse con un "atrás" vacío)
        if (history.state && history.state.seleccion) {
            history.back();
        }
    }
}

// Escuchar el botón atrás
window.addEventListener('popstate', (event) => {
    if (modoSeleccion && (!event.state || !event.state.seleccion)) {
        // Si estamos en modo selección y retroceden → cancelamos
        desactivarModoSeleccion();
    }
});
