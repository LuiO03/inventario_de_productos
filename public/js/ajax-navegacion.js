document.addEventListener('DOMContentLoaded', function () {
  function cargarContenido(url) {
    fetch(url + '?ajax=1')
      .then(res => res.text())
      .then(html => {
        document.getElementById('contenido-principal').innerHTML = html;
        window.history.pushState(null, '', url);
        reactivarEventos(); // volver a activar enlaces si es necesario
      })
      .catch(err => {
        console.error('Error al cargar el contenido:', err);
      });
  }

  function reactivarEventos() {
    document.querySelectorAll('.ajax-link').forEach(enlace => {
      enlace.onclick = function (e) {
        e.preventDefault();
        const url = this.getAttribute('href');
        cargarContenido(url);
      };
    });
  }

  reactivarEventos();

  window.addEventListener('popstate', function () {
    cargarContenido(location.pathname);
  });
});
