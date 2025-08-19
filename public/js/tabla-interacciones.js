
  document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.check-row');
    const checkboxColumnClass = 'column-check-td';

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
      }
    }

    // ========= Cancelar seleccion =========
    document.getElementById('btnCancelarSeleccion').addEventListener('click', function () {
        document.querySelectorAll('.check-row').forEach(chk => chk.checked = false);
        document.getElementById('checkAll').checked = false;
        document.querySelectorAll('tr').forEach(row => row.classList.remove('fila-seleccionada'));
        actualizarCantidadSeleccionados();
    });

    // ========= GESTO DE SELECCIÓN MÚLTIPLE (mouse) =========
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
            // No tocamos los demás checkboxes
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

    // ========= GESTO TOUCH (móvil) =========
    let seleccionando = false;
    let estadoInicial = null;

    const cambiarEstadoFila = (fila, estado) => {
      const checkbox = fila.querySelector('.check-row');
      if (checkbox && checkbox.checked !== estado) {
        checkbox.checked = estado;
        checkbox.dispatchEvent(new Event('change'));
      }
    };

    document.querySelectorAll('tbody tr').forEach(fila => {
      const tdCheck = fila.querySelector('.column-check-td');
      if (!tdCheck) return;

      tdCheck.addEventListener('touchstart', () => {
        e.preventDefault();
        const checkbox = fila.querySelector('.check-row');
        if (checkbox) {
          seleccionando = true;
          estadoInicial = !checkbox.checked;
          cambiarEstadoFila(fila, estadoInicial);
        }
      });

      fila.addEventListener('mouseenter', () => {
        if (seleccionando && estadoInicial !== null) {
          cambiarEstadoFila(fila, estadoInicial);
        }
      });
    });

    const terminarSeleccion = () => {
      seleccionando = false;
      estadoInicial = null;
    };

    document.addEventListener('mouseup', terminarSeleccion);
    document.addEventListener('touchend', terminarSeleccion);
    document.addEventListener('mouseleave', terminarSeleccion);
    document.addEventListener('touchcancel', terminarSeleccion);

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

    // ========= GESTO DE PRESIONAR EN COLUMNA NO-CHECKBOX =========
    document.querySelectorAll('tbody tr').forEach(fila => {
      let presionado = false;
      let timeoutId = null;

      const activarCheckbox = () => {
        const checkbox = fila.querySelector('.check-row');
        if (checkbox) {
          checkbox.checked = !checkbox.checked;
          checkbox.dispatchEvent(new Event('change'));
        }
      };

      const iniciarPresionado = (e) => {
        const esCheckboxCol = e.target.closest('td')?.classList.contains(checkboxColumnClass);
        const esElementoInteractivo = e.target.closest('button, a, input, label');

        if (esCheckboxCol || esElementoInteractivo) return;

        presionado = true;
        timeoutId = setTimeout(() => {
          if (presionado) activarCheckbox();
        }, 500);
      };

      const cancelarPresionado = () => {
        presionado = false;
        clearTimeout(timeoutId);
      };

      fila.addEventListener('mousedown', iniciarPresionado);
      fila.addEventListener('mouseup', cancelarPresionado);
      fila.addEventListener('mouseleave', cancelarPresionado);
      fila.addEventListener('touchstart', iniciarPresionado);
      fila.addEventListener('touchend', cancelarPresionado);
      fila.addEventListener('touchcancel', cancelarPresionado);
    });

    actualizarCantidadSeleccionados();
    // ========= Quitar seleccionados al hacer click fuera de la contenedor =========
    document.addEventListener('click', function (e) {
        const contenedor = document.querySelector('.contenedor'); // ID o selector de tu contenedor

        // Si el click NO fue dentro de la contenedor
        if (!contenedor.contains(e.target)) {
            // Deseleccionar todos los checkboxes
            document.querySelectorAll('.check-row').forEach(chk => chk.checked = false);

            // Deseleccionar el "checkAll"
            const checkAll = document.getElementById('checkAll');
            if (checkAll) checkAll.checked = false;

            // Quitar clase visual
            document.querySelectorAll('tr').forEach(row => row.classList.remove('fila-seleccionada'));

            // Resetear contador
            actualizarCantidadSeleccionados();
        }
    });

  });
