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
    const saved = localStorage.getItem('finflow_state');
    if (saved) {
      state = { ...state, ...JSON.parse(saved) };
    }
  } catch(e) {}
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
  document.getElementById('topbar-title').textContent = titles[name] || name;

  if (name === 'dashboard')    renderDashboard();
  if (name === 'transactions') renderTransactions();
  if (name === 'investments')  renderInvestments();
  if (name === 'goals')        renderGoals();
  if (name === 'accounts')     renderAccounts();
  if (name === 'categories')   renderCategories();
  if (name === 'invoices')     renderInvoices();
  if (name === 'reports')      renderReports();
}

function updateUserUI() {
  if (!state.currentUser) return;
  const u = state.currentUser;
  const initials = ((u.name || 'U')[0] + (u.lastname || '')[0]).toUpperCase();
  document.getElementById('sidebar-avatar').textContent = initials;
  document.getElementById('sidebar-name').textContent   = u.name + ' ' + (u.lastname || '');
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
function openModal(id) {
  populateSelects();
  document.getElementById(id).classList.add('active');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('active');
}

// Fecha modal ao clicar no overlay
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
