// validaciones-genericas.js

// ==========================
// VALIDACIÓN DE CAMPOS
// ==========================
export function validarCampo({ id, iconoId, estadoId, errorId, label, min = 0, max = Infinity, esSelect = false }) {
    const input = document.getElementById(id);
    const icono = document.getElementById(iconoId);
    const estado = document.getElementById(estadoId);
    const error = document.getElementById(errorId);

    const evento = esSelect ? 'change' : 'blur';
    input.addEventListener(evento, validar);
    input.addEventListener('blur', validar);

    estado.addEventListener('click', () => {
        if (estado.classList.contains('ri-close-circle-fill')) {
            input.value = '';
            input.dispatchEvent(new Event('blur'));
            input.focus();
        }
    });

    function validar() {
        const valor = input.value.trim();
        let valido = true;
        let mensaje = '';

        if (esSelect) {
            if (valor === '') {
                valido = false;
                mensaje = `<i class="ri-shield-keyhole-fill"></i>&ensp;Debe seleccionar un ${label}.`;
            }
        } else {
            if (valor.length < min) {
                valido = false;
                mensaje = `<i class="ri-shield-keyhole-fill"></i>&ensp;${label} debe tener al menos ${min} caracteres.`;
            } else if (valor.length > max) {
                valido = false;
                mensaje = `<i class="ri-shield-keyhole-fill"></i>&ensp;${label} no puede superar ${max} caracteres.`;
            }
        }

        // Mostrar mensaje y estilos
        error.innerHTML = mensaje;
        input.classList.toggle('input-error', !valido);
        input.classList.toggle('input-success', valido);
        icono.classList.toggle('icono-error', !valido);
        icono.classList.toggle('icono-success', valido);
        estado.classList.toggle('ri-close-circle-fill', !valido);
        estado.classList.toggle('ri-checkbox-circle-fill', valido);
        estado.style.color = valido ? 'var(--color-success)' : 'var(--color-danger)';
        estado.style.display = 'inline-block';
    }

    return validar;
}

// ==========================
// VALIDACIÓN DE FORMULARIO
// ==========================
export function validarFormulario({ formSelector, campos }) {
    const form = document.querySelector(formSelector);

    form.addEventListener('submit', function (e) {
        let hayError = false;

        campos.forEach(({ id }) => {
            const campo = document.getElementById(id);
            campo.dispatchEvent(new Event('blur'));

            const error = document.getElementById('error-' + id);
            if (error && error.innerHTML !== '') hayError = true;
        });

        if (!validarImagen()) hayError = true;

        if (hayError) e.preventDefault();
    });
}

// ==========================
// VALIDACIÓN DE IMAGEN
// ==========================
export function validarImagen() {
    const inputImagen = document.getElementById('imagen');
    const imgPreview = document.getElementById('preview-imagen');
    const icono = document.getElementById('upload-icon');
    const texto = document.getElementById('preview-texto');
    const errorImagen = document.getElementById('error-imagen');
    const archivo = inputImagen.files[0];

    const maxSize = 3 * 1024 * 1024; // 3MB
    const tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];

    let valido = true;
    let mensaje = '';

    if (!archivo) {
        valido = false;
        mensaje = `<i class="ri-shield-keyhole-fill"></i>&ensp;Debe seleccionar una imagen.`;
    } else if (archivo.size > maxSize) {
        valido = false;
        mensaje = `<i class="ri-shield-keyhole-fill"></i>&ensp;La imagen excede el tamaño máximo de 3 MB.`;
    } else if (!tiposPermitidos.includes(archivo.type)) {
        valido = false;
        mensaje = `<i class="ri-shield-keyhole-fill"></i>&ensp;Formato no permitido. Solo JPG, PNG o WebP.`;
    }

    if (!valido) {
        errorImagen.innerHTML = mensaje;
        inputImagen.classList.add('input-error');
        inputImagen.classList.remove('input-success');
        imgPreview.src = '';
        imgPreview.style.display = 'none';
        icono.style.display = 'block';
        texto.style.display = 'block';
    } else {
        errorImagen.innerHTML = '';
        inputImagen.classList.remove('input-error');
        inputImagen.classList.add('input-success');
    }

    return valido;
}

// ==========================
// EVENTO CAMBIO DE IMAGEN
// ==========================
const inputImagen = document.getElementById('imagen');
if (inputImagen) {
    inputImagen.addEventListener('change', () => {
        validarImagen(); // Valida inmediatamente al seleccionar
    });
}

// ==========================
// BOTÓN LIMPIAR CAMPOS
// ==========================
const btnLimpiar = document.getElementById('btnLimpiar');
if (btnLimpiar) {
    btnLimpiar.addEventListener('click', () => {
        const campos = [
            { id: 'nombre', evento: 'blur' },
            { id: 'descripcion', evento: 'blur' },
            { id: 'estado', evento: 'change' }
        ];

        campos.forEach(({ id, evento }) => {
            const input = document.getElementById(id);
            if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            } else {
                input.value = '';
            }
            input.dispatchEvent(new Event(evento));
        });

        // Reset de imagen
        inputImagen.value = '';
        const imgPreview = document.getElementById('preview-imagen');
        const icono = document.getElementById('upload-icon');
        const texto = document.getElementById('preview-texto');
        imgPreview.src = '';
        imgPreview.style.display = 'none';
        icono.style.display = 'block';
        texto.style.display = 'block';

        validarImagen(); // Forzar validación visual
    });
}
