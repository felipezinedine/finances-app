// ============================================================
// STATE — Compartilhado entre todos os módulos
// Deve ser carregado PRIMEIRO no HTML
// ============================================================

let state = {
  users: [],
  currentUser: null,
  balance: 0,
  transactions: [],
  investments: [],
  goals: [],
  accounts: [],
  categories: [],
  invoices: [],
};

let selectedTxType = 'receita';
let selectedCatType = 'receita';
let selectedCatColor = '#7c5cfc';
let selectedCatEmoji = '💰';

// ============================================================
// PERSISTÊNCIA
// ============================================================
function loadState() {
  try {
    const initial = window.initialState;
    const savedRaw = localStorage.getItem('finflow_state');

    if (initial && typeof initial === 'object') {
      if (savedRaw) {
        const saved = JSON.parse(savedRaw);
        // Se o usuário for o mesmo, mantemos o estado salvo (para não perder alterações locais)
        if (saved.currentUser?.id && initial.currentUser?.id && saved.currentUser.id === initial.currentUser.id) {
          state = { ...state, ...saved };
          return;
        }
      }

      // Novo usuário ou estado inexistente: inicializa com dados do servidor
      state = { ...state, ...initial };
      saveState();
      return;
    }

    if (savedRaw) {
      state = { ...state, ...JSON.parse(savedRaw) };
    }
  } catch (e) {}
}

function saveState() {
  try {
    localStorage.setItem('finflow_state', JSON.stringify(state));
  } catch(e) {}
}

// ============================================================
// UTILITÁRIOS — usados por todos os módulos
// ============================================================
function uid() {
  return Math.random().toString(36).substr(2, 9);
}

function fmt(val) {
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val || 0);
}

function fmtDate(d) {
  if (!d) return '';
  const dt = new Date(d + 'T00:00:00');
  return dt.toLocaleDateString('pt-BR');
}

function showToast(msg, type = 'info') {
  const toast = document.getElementById('toast');
  const item = document.createElement('div');
  item.className = `toast-item toast-${type}`;
  item.textContent = msg;
  toast.appendChild(item);
  setTimeout(() => item.remove(), 3000);
}

// ============================================================
// NAVEGAÇÃO — usada por auth e landing
// ============================================================
function goTo(pageId) {
  document.querySelectorAll('.page, .auth-page').forEach(p => {
    p.classList.remove('active');
    p.style.display = '';
  });
  document.getElementById('app-layout').classList.remove('active');
  document.getElementById('app-layout').style.display = '';
  const target = document.getElementById(pageId);
  if (target) {
    target.style.display = 'flex';
    target.classList.add('active');
  }
}

function showApp() {
  document.querySelectorAll('.page, .auth-page').forEach(p => {
    p.classList.remove('active');
    p.style.display = '';
  });
  document.getElementById('app-layout').classList.add('active');
  document.getElementById('app-layout').style.display = 'flex';

  const today = new Date().toISOString().split('T')[0];
  const txDate  = document.getElementById('tx-date');
  const invDate = document.getElementById('inv-date');
  const updDate = document.getElementById('upd-inv-date');
  if (txDate)  txDate.value  = today;
  if (invDate) invDate.value = today;
  if (updDate) updDate.value = today;

  showPage('dashboard');
  updateUserUI();
}

async function refreshState() {
  try {
    const res = await fetch('/state', { cache: 'no-store', credentials: 'same-origin' });
    const json = await res.json().catch(() => ({}));
    if (res.ok && json && json.currentUser) {
      applyServerState(json);
    }
  } catch (e) {
    console.error('Erro ao atualizar estado:', e);
  }
}

function showPage(name) {
  document.querySelectorAll('.inner-page').forEach(p => p.classList.remove('active'));
  const target = document.getElementById('page-' + name);
  if (target) target.classList.add('active');

  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => {
    if (n.getAttribute('onclick') && n.getAttribute('onclick').includes("'" + name + "'")) {
      n.classList.add('active');
    }
  });

  const titles = {
    dashboard:    'Dashboard',
    transactions: 'Transações',
    invoices:     'Faturas',
    investments:  'Investimentos',
    goals:        'Metas',
    accounts:     'Contas',
    categories:   'Categorias',
    reports:      'Relatórios',
  };
  const topbarTitle = document.getElementById('topbar-title');
  if (topbarTitle) topbarTitle.textContent = titles[name] || name;

  if (name === 'dashboard')    renderDashboard();
  if (name === 'transactions') renderTransactions();
  if (name === 'investments')  renderInvestments();
  if (name === 'goals')        renderGoals();
  if (name === 'accounts')     renderAccounts();
  if (name === 'categories')   renderCategories();
  if (name === 'invoices')     renderInvoices();
  if (name === 'reports')      renderReports();

  // Recarrega dados do servidor para manter os valores atualizados
  refreshState();
}

function updateUserUI() {
  if (!state.currentUser) return;
  const u = state.currentUser;

  const first = (u.name || 'U')[0] || 'U';
  const last = (u.lastname || '')[0] || '';
  const initials = (first + last).toUpperCase();

  document.getElementById('sidebar-avatar').textContent = initials;
  document.getElementById('sidebar-name').textContent   = u.name + (u.lastname ? ' ' + u.lastname : '');
  document.getElementById('sidebar-email').textContent  = u.email;
}

function logout() {
  state.currentUser = null;
  saveState();
  // Não chama goTo() — o redirect é feito pelo Laravel na rota de logout
}

// ============================================================
// MODAL HELPERS — usados por todos os módulos
// ============================================================

/**
 * Abre um modal de forma robusta
 * @param {string} id - ID do modal (ex: 'modal-transaction')
 */
function openModal(id) {
  console.log('🔓 openModal called:', id);
  try {
    // Popula os selects com categorias, contas, etc
    if (typeof populateSelects === 'function') {
      try { 
        populateSelects(); 
        console.log('✓ populateSelects executado');
      } catch(e) { 
        console.error('✗ Erro ao populateSelects:', e); 
      }
    }
    
    
    // Fecha todos os outros modals primeiro
    document.querySelectorAll('.modal-overlay.active').forEach(m => {
      if (m.id !== id) m.classList.remove('active');
    });
    
    // Abre o modal específico
    const modal = document.getElementById(id);
    if (!modal) {
      console.error(`✗ Modal '${id}' não encontrado no DOM`);
      return false;
    }
    
    modal.classList.add('active');

    // Resetar formulários quando abrimos os modals de criação (não edição)
    if (id === 'modal-transaction' && typeof clearTxForm === 'function') {
      clearTxForm();
    }

    if (id === 'modal-investment') {
      const el = (sel) => document.getElementById(sel);
      // Só limpar se não estiver editando (edit-id vazio)
      const isEditing = el('inv-edit-id') && el('inv-edit-id').value.trim() !== '';
      if (!isEditing) {
        if (el('inv-edit-id')) el('inv-edit-id').value = '';
        if (el('inv-name')) el('inv-name').value = '';
        if (el('inv-amount')) el('inv-amount').value = '';
        if (el('inv-account')) el('inv-account').value = '';
        if (el('inv-type')) el('inv-type').value = 'renda-fixa';
        if (el('inv-date')) el('inv-date').value = new Date().toISOString().split('T')[0];
      }
    }

    if (id === 'modal-goal') {
      document.getElementById('goal-edit-id').value = '';
      document.getElementById('goal-name').value = '';
      document.getElementById('goal-target').value = '';
      document.getElementById('goal-current').value = '0';
      document.getElementById('goal-deadline').value = '';
      document.getElementById('goal-icon').value = '🎯';
    }

    if (id === 'modal-account') {
      document.getElementById('acc-edit-id').value = '';
      document.getElementById('acc-name').value = '';
      document.getElementById('acc-bank').value = '';
      document.getElementById('acc-balance').value = '';
      document.getElementById('acc-type').value = 'corrente';
    }

    if (id === 'modal-category') {
      document.getElementById('cat-edit-id').value = '';
      document.getElementById('cat-name').value = '';
      document.getElementById('cat-type-val').value = 'receita';
      document.getElementById('cat-icon').value = '💰';
      document.getElementById('cat-color').value = '#7c5cfc';
    }

    if (id === 'modal-invoice') {
      document.getElementById('invoice-edit-id').value = '';
      document.getElementById('invoice-desc').value = '';
      document.getElementById('invoice-amount').value = '';
      document.getElementById('invoice-due').value = new Date().toISOString().split('T')[0];
      const rec = document.getElementById('invoice-recurrence');
      if (rec) rec.value = 'none';
      const inst = document.getElementById('invoice-installments');
      if (inst) inst.value = '2';
      document.getElementById('invoice-status').value = 'pendente';
    }

    // Garantir que os campos de parcelas reflitam o valor atual dos selects
    if (typeof toggleInstallments === 'function') {
      try {
        toggleInstallments('tx');
        toggleInstallments('invoice');
      } catch (e) {
        console.error('✗ Erro ao toggleInstallments:', e);
      }
    }

    console.log(`✓ Modal '${id}' aberto com sucesso`);
    return true;
  } catch(e) {
    console.error(`✗ Erro ao abrir modal '${id}':`, e);
    return false;
  }
}

/**
 * Fecha um modal
 * @param {string} id - ID do modal (ex: 'modal-transaction')
 */
function closeModal(id) {
  try {
    const modal = document.getElementById(id);
    if (modal) {
      modal.classList.remove('active');
      return true;
    }
    return false;
  } catch(e) {
    console.error(`Erro ao fechar modal '${id}':`, e);
    return false;
  }
}

/**
 * Fecha todos os modals abertos
 */
function closeAllModals() {
  document.querySelectorAll('.modal-overlay.active').forEach(modal => {
    modal.classList.remove('active');
  });
}

// Fecha modal ao clicar no overlay (executa imediatamente, não em DOMContentLoaded)
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => {
      if (e.target === overlay) overlay.classList.remove('active');
    });
  });
});


document.addEventListener('livewire:init', () => {

    Livewire.on('toast', (data) => {
        console.log('Toast event received:', data);
        showToast(data[0].message, data[0].type);
    });
});