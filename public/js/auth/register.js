// ============================================================
// AUTH — REGISTER
// Depende de: state.js
// ============================================================

/**
 * Lê os campos do formulário de cadastro, valida e cria a conta.
 * Chamado pelo botão "Criar conta" na página #register-page.
 */
function register() {
  const name       = document.getElementById('reg-name').value.trim();
  const lastname   = document.getElementById('reg-lastname').value.trim();
  const email      = document.getElementById('reg-email').value.trim();
  const pass       = document.getElementById('reg-pass').value;
  const balanceVal = parseFloat(document.getElementById('reg-balance').value) || 0;

  // Limpa alerta anterior
  const alertEl = document.getElementById('reg-alert');
  alertEl.style.display = 'none';

  // Validações
  if (!name || !email || !pass) {
    _showRegisterError('⚠️ Preencha todos os campos obrigatórios');
    return;
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    _showRegisterError('⚠️ Informe um e-mail válido');
    return;
  }
  if (pass.length < 6) {
    _showRegisterError('⚠️ A senha deve ter pelo menos 6 caracteres');
    return;
  }
  if (state.users.find(u => u.email === email)) {
    _showRegisterError('⚠️ Este e-mail já está cadastrado');
    return;
  }

  // Cria usuário e inicializa estado completo
  const user = { id: uid(), name, lastname, email, pass };
  state.users.push(user);
  state.currentUser  = user;
  state.balance      = balanceVal;
  state.transactions = [];
  state.investments  = [];
  state.goals        = [];
  state.invoices     = [];
  state.accounts     = [
    { id: uid(), name: 'Carteira Principal', bank: 'Geral', type: 'carteira', balance: balanceVal },
  ];
  state.categories = getDefaultCategories();

  saveState();
  showToast('Conta criada com sucesso! 🎉', 'success');
  showApp();
}

/** Exibe mensagem de erro no formulário de cadastro */
function _showRegisterError(msg) {
  const alertEl = document.getElementById('reg-alert');
  alertEl.style.display = 'flex';
  alertEl.className     = 'alert alert-error';
  alertEl.textContent   = msg;
}

// ============================================================
// DADOS PADRÃO — criados junto com a conta
// ============================================================

/** Retorna as categorias padrão para novos usuários */
function getDefaultCategories() {
  return [
    { id: uid(), name: 'Salário',     type: 'receita', icon: '💼', color: '#22c98a' },
    { id: uid(), name: 'Freelance',   type: 'receita', icon: '💻', color: '#3a9df8' },
    { id: uid(), name: 'Alimentação', type: 'despesa', icon: '🍔', color: '#f5b942' },
    { id: uid(), name: 'Transporte',  type: 'despesa', icon: '🚗', color: '#f04060' },
    { id: uid(), name: 'Moradia',     type: 'despesa', icon: '🏠', color: '#7c5cfc' },
    { id: uid(), name: 'Saúde',       type: 'despesa', icon: '💊', color: '#f06292' },
    { id: uid(), name: 'Lazer',       type: 'despesa', icon: '🎮', color: '#4dd0e1' },
    { id: uid(), name: 'Educação',    type: 'despesa', icon: '📚', color: '#aed581' },
  ];
}