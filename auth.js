// Toggle tema untuk halaman login & daftar
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('themeToggle');
  const icon = document.getElementById('themeIcon');
  const apply = (light) => {
    document.body.classList.toggle('light-mode', light);
    if (icon) icon.textContent = light ? '🌙' : '☀️';
  };
  apply(localStorage.getItem('theme') === 'light');
  if (btn) {
    btn.addEventListener('click', () => {
      const light = !document.body.classList.contains('light-mode');
      apply(light);
      localStorage.setItem('theme', light ? 'light' : 'dark');
    });
  }
});