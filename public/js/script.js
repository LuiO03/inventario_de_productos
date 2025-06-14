const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});



const toggleDarkModeBtn = document.getElementById('toggleDarkMode');

function applyTheme(theme) {
  const body = document.body;
  const isDark = theme === 'dark';

  body.classList.toggle('dark-mode', isDark);

  // Cambiar clases en las tablas
  const tables = document.querySelectorAll('table');
  tables.forEach(table => {
    table.classList.remove(isDark ? 'table-light' : 'table-dark');
    table.classList.add(isDark ? 'table-dark' : 'table-light');
  });

  // Guardar en localStorage
  localStorage.setItem('theme', isDark ? 'dark' : 'light');
}

toggleDarkModeBtn.addEventListener('click', () => {
  const isDark = !document.body.classList.contains('dark-mode');
  applyTheme(isDark ? 'dark' : 'light');
});

// Aplicar tema al cargar la página
window.addEventListener('DOMContentLoaded', () => {
  const savedTheme = localStorage.getItem('theme') || 'light';
  applyTheme(savedTheme);
});
