// ============================================================
// AUTH — DEMO
// Depende de: state.js, auth.register.js (getDefaultCategories)
// ============================================================

/**
 * Entra com a conta demo, sempre recriando o estado do zero
 * para garantir dados frescos e consistentes.
 *
 * Pode ser chamado de 3 formas:
 *   1. Botão "Entrar com conta demo" na página de login
 *   2. Diretamente via URL: ?demo  |  #demo  |  /demo
 *   3. Programaticamente: demoLogin()
 */
function demoLogin() {
  // Remove sessão demo anterior para recriar sempre limpa
  state.users = state.users.filter(u => u.email !== 'demo@finflow.com');

  const demo = {
    id:       'demo-user',
    name:     'Demo',
    lastname: 'User',
    email:    'demo@finflow.com',
    pass:     'demo',
  };

  state.users.push(demo);
  state.currentUser = demo;
  state.balance     = 15000;

  // Ordem importa: categorias e contas devem existir
  // antes de gerar transações que as referenciam
  state.categories = getDefaultCategories();
  state.accounts   = _getDemoAccounts();
  state.transactions = getSampleTransactions();
  state.investments  = getSampleInvestments();
  state.goals        = getSampleGoals();
  state.invoices     = getSampleInvoices();

  saveState();
  showToast('Entrou com conta demo 🚀', 'info');

  if (document.getElementById('app-layout')) {
    updateUserUI();
    // Adia a renderização para o próximo frame do browser,
    // garantindo que todos os elementos do DOM já estão disponíveis
    requestAnimationFrame(() => showPage('dashboard'));
  } else {
    showApp();
  }
}

// ============================================================
// DADOS DE EXEMPLO
// ============================================================

function _getDemoAccounts() {
  return [
    { id: 'acc1', name: 'Conta Corrente', bank: 'Nubank', type: 'corrente',  balance: 8500 },
    { id: 'acc2', name: 'Poupança',       bank: 'Caixa',  type: 'poupanca',  balance: 6500 },
  ];
}

/**
 * Gera transações de exemplo distribuídas nos últimos 2 meses
 * para que o gráfico mensal do dashboard já tenha dados visíveis.
 * Depende de state.categories e state.accounts já populados.
 */
function getSampleTransactions() {
  const cats = state.categories;
  const accs = state.accounts;
  const now  = new Date();

  // Helper para gerar uma data ISO no mês relativo a partir de hoje
  const d = (monthOffset, day) =>
    new Date(now.getFullYear(), now.getMonth() + monthOffset, day)
      .toISOString().split('T')[0];

  return [
    // Mês atual
    { id: uid(), desc: 'Salário Mensal',    amount: 5000, type: 'receita', catId: cats[0]?.id, accId: accs[0]?.id, date: d(0,  5), note: '' },
    { id: uid(), desc: 'Freelance Design',  amount: 1500, type: 'receita', catId: cats[1]?.id, accId: accs[0]?.id, date: d(0, 15), note: '' },
    { id: uid(), desc: 'Aluguel',           amount: 1200, type: 'despesa', catId: cats[4]?.id, accId: accs[0]?.id, date: d(0, 10), note: '' },
    { id: uid(), desc: 'Supermercado',      amount:  350, type: 'despesa', catId: cats[2]?.id, accId: accs[0]?.id, date: d(0, 12), note: '' },
    { id: uid(), desc: 'Academia',          amount:   80, type: 'despesa', catId: cats[5]?.id, accId: accs[0]?.id, date: d(0,  1), note: '' },
    { id: uid(), desc: 'Netflix + Spotify', amount:   60, type: 'despesa', catId: cats[6]?.id, accId: accs[0]?.id, date: d(0,  3), note: '' },
    // Mês anterior (popula o gráfico)
    { id: uid(), desc: 'Salário Mensal',    amount: 5000, type: 'receita', catId: cats[0]?.id, accId: accs[0]?.id, date: d(-1,  5), note: '' },
    { id: uid(), desc: 'Aluguel',           amount: 1200, type: 'despesa', catId: cats[4]?.id, accId: accs[0]?.id, date: d(-1, 10), note: '' },
    { id: uid(), desc: 'Supermercado',      amount:  420, type: 'despesa', catId: cats[2]?.id, accId: accs[0]?.id, date: d(-1, 14), note: '' },
    { id: uid(), desc: 'Transporte',        amount:  200, type: 'despesa', catId: cats[3]?.id, accId: accs[0]?.id, date: d(-1, 20), note: '' },
  ];
}

function getSampleInvestments() {
  const today = new Date().toISOString().split('T')[0];
  return [
    { id: uid(), name: 'Tesouro Selic 2026', type: 'renda-fixa',     invested: 3000, currentValue: 3180, date: today, history: [{ date: today, value: 3180 }] },
    { id: uid(), name: 'PETR4',              type: 'renda-variavel', invested: 2000, currentValue: 2340, date: today, history: [{ date: today, value: 2340 }] },
    { id: uid(), name: 'Bitcoin',            type: 'cripto',         invested:  500, currentValue:  720, date: today, history: [{ date: today, value:  720 }] },
  ];
}

function getSampleGoals() {
  return [
    { id: uid(), name: 'Fundo de Emergência', target: 10000, current: 3500, deadline: '2025-12-31', icon: '🛡️' },
    { id: uid(), name: 'Viagem Europa',        target:  8000, current: 1200, deadline: '2026-06-30', icon: '✈️' },
  ];
}

function getSampleInvoices() {
  const now = new Date();
  const d = day => new Date(now.getFullYear(), now.getMonth(), day).toISOString().split('T')[0];
  return [
    { id: uid(), desc: 'Cartão Nubank', amount: 850, due: d(20), status: 'pendente', catId: '' },
    { id: uid(), desc: 'Conta de Luz',  amount: 120, due: d(15), status: 'atrasado', catId: '' },
  ];
}