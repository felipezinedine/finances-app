// ============================================================
// DEMO — Página /demo (Blade)
// Carrega dados fictícios e renderiza o dashboard
// Depende de: state.js (deve vir ANTES no master.blade.php)
// ============================================================

document.addEventListener('DOMContentLoaded', () => {
  loadState();
  demoLogin();
});