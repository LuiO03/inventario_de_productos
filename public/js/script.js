const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});

const toggleModeThemeBtn = document.getElementById('toggleModeTheme');

function applyTheme(theme) {
  const isLight = theme === 'light';
  document.documentElement.classList.toggle('light-mode', isLight);

  // Guardar tema en localStorage
  localStorage.setItem('theme', theme);

  // Cambiar ícono y texto del botón
  const icon = toggleModeThemeBtn.querySelector('i');
  const span = toggleModeThemeBtn.querySelector('span');
  if (isLight) {
    icon.className = 'ri-moon-line';
    span.textContent = 'modo oscuro';
  } else {
    icon.className = 'ri-sun-line';
    span.textContent = 'modo claro';
  }
}

toggleModeThemeBtn.addEventListener('click', () => {
  const isCurrentlyLight = document.documentElement.classList.contains('light-mode');
  applyTheme(isCurrentlyLight ? 'dark' : 'light');
});

// Aplicar tema al cargar la página
window.addEventListener('DOMContentLoaded', () => {
  const savedTheme = localStorage.getItem('theme');
  const prefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
  const themeToApply = savedTheme || (prefersLight ? 'light' : 'dark');
  applyTheme(themeToApply);
});
