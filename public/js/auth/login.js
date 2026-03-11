// ============================================================
// AUTH — LOGIN
// Depende de: state.js
// ============================================================

/**
 * Lê os campos do formulário de login, valida e autentica o usuário.
 * Chamado pelo botão "Entrar" na página #login-page.
 */
function login() {
  const email    = document.getElementById('login-email').value.trim();
  const pass     = document.getElementById('login-pass').value;
  const alertEl  = document.getElementById('login-alert');

  // Limpa alerta anterior
  alertEl.style.display = 'none';

  if (!email || !pass) {
    _showLoginError('⚠️ Preencha e-mail e senha');
    return;
  }

  const user = state.users.find(u => u.email === email && u.pass === pass);

  if (!user) {
    _showLoginError('⚠️ E-mail ou senha incorretos');
    return;
  }

  state.currentUser = user;
  saveState();
  showToast('Bem-vindo de volta! 👋', 'success');
  showApp();
}

/** Exibe mensagem de erro no formulário de login */
function _showLoginError(msg) {
  const alertEl = document.getElementById('login-alert');
  alertEl.style.display = 'flex';
  alertEl.className     = 'alert alert-error';
  alertEl.textContent   = msg;
}