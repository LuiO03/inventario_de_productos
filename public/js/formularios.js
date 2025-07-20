const inputImagen = document.getElementById('imagen');
  const imgPreview = document.getElementById('preview-imagen');
  const icono = document.getElementById('upload-icon');
  const texto = document.getElementById('preview-texto');
  const nombreArchivo = document.getElementById('nombre-archivo');
  const inputQuitar = document.getElementById('quitar-imagen');
  const dropArea = document.getElementById('preview-container');
  const btnQuitar = document.getElementById('btn-quitar-imagen');

  // Vista previa al seleccionar imagen
  inputImagen?.addEventListener('change', () => {
    const archivo = inputImagen.files[0];
    if (archivo) {
      const reader = new FileReader();
      reader.onload = e => {
        imgPreview.src = e.target.result;
        imgPreview.style.display = 'block';
        icono.style.display = 'none';
        texto.style.display = 'none';
        nombreArchivo.style.display = 'block';
        nombreArchivo.textContent = archivo.name;
        nombreArchivo.title = archivo.name;
        inputQuitar.value = '0';
      };
      reader.readAsDataURL(archivo);
    }
  });

  // Botón quitar imagen
  btnQuitar?.addEventListener('click', () => {
    imgPreview.src = '';
    imgPreview.style.display = 'none';
    nombreArchivo.style.display = 'none';
    icono.style.display = 'block';
    texto.style.display = 'block';
    nombreArchivo.textContent = 'Ningún archivo seleccionado';
    inputImagen.value = '';
    inputQuitar.value = '1';
  });

  // Eventos Drag & Drop
  ['dragenter', 'dragover'].forEach(evt =>
    dropArea.addEventListener(evt, e => {
      e.preventDefault();
      dropArea.classList.add('arrastrando');
    })
  );

  ['dragleave', 'drop'].forEach(evt =>
    dropArea.addEventListener(evt, e => {
      e.preventDefault();
      dropArea.classList.remove('arrastrando');
    })
  );

  dropArea.addEventListener('drop', e => {
    const archivos = e.dataTransfer.files;
    if (archivos.length > 0) {
      inputImagen.files = archivos;
      inputImagen.dispatchEvent(new Event('change'));
    }
  });