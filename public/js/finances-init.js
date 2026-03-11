function demoLogin() {
  // Sempre recria o estado demo do zero para garantir dados frescos e consistentes
  // Remove usuário demo anterior se existir
  state.users = state.users.filter(u => u.email !== 'demo@finflow.com');

  const demo = { id: 'demo-user', name: 'Demo', lastname: 'User', email: 'demo@finflow.com', pass: 'demo' };
  state.users.push(demo);
  state.currentUser = demo;
  state.balance = 15000;
  state.categories = getDefaultCategories();
  state.accounts = [
    { id: 'acc1', name: 'Conta Corrente', bank: 'Nubank', type: 'corrente', balance: 8500 },
    { id: 'acc2', name: 'Poupança', bank: 'Caixa', type: 'poupanca', balance: 6500 },
  ];
  // Gera dados de exemplo já com as categorias e contas acima definidas
  state.transactions = getSampleTransactions();
  state.investments = getSampleInvestments();
  state.goals = getSampleGoals();
  state.invoices = getSampleInvoices();

  saveState();
  showToast('Entrou com conta demo 🚀', 'info');
  showApp();
}

function populateSelects() {
  const cats = state.categories || [];
  const accs = state.accounts || [];
  const invs = state.investments || [];

  ['tx-category', 'invoice-category'].forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    el.innerHTML = '<option value="">Selecionar...</option>';
    cats.forEach(c => {
      el.innerHTML += `<option value="${c.id}">${c.icon} ${c.name}</option>`;
    });
  });

  ['tx-account', 'inv-account'].forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    el.innerHTML = '<option value="">Selecionar...</option>';
    accs.forEach(a => {
      el.innerHTML += `<option value="${a.id}">${a.name} (${a.bank})</option>`;
    });
  });

  const updInv = document.getElementById('upd-inv-select');
  if (updInv) {
    updInv.innerHTML = '<option value="">Selecionar investimento...</option>';
    invs.forEach(i => {
      updInv.innerHTML += `<option value="${i.id}">${i.name}</option>`;
    });
  }

  const txFilter = document.getElementById('tx-filter-cat');
  if (txFilter) {
    txFilter.innerHTML = '<option value="">Todas categorias</option>';
    cats.forEach(c => {
      txFilter.innerHTML += `<option value="${c.id}">${c.icon} ${c.name}</option>`;
    });
  }
}

function setTxType(type, btn) {
  selectedTxType = type;
  document.getElementById('tx-type-val').value = type;
  document.querySelectorAll('#tx-type-tabs .type-tab').forEach(t => {
    t.className = 'type-tab';
  });
  const classMap = { receita: 'active-green', despesa: 'active-red', investimento: 'active-gold' };
  btn.className = 'type-tab ' + classMap[type];
}

function setCatType(type, btn) {
  selectedCatType = type;
  document.getElementById('cat-type-val').value = type;
  btn.parentElement.querySelectorAll('.type-tab').forEach(t => t.className = 'type-tab');
  const classMap = { receita: 'active-green', despesa: 'active-red', ambos: 'active-accent' };
  btn.className = 'type-tab ' + classMap[type];
}

function selectColor(el, color) {
  selectedCatColor = color;
  document.getElementById('cat-color').value = color;
  document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('selected'));
  el.classList.add('selected');
}

function selectEmoji(el, emoji) {
  selectedCatEmoji = emoji;
  document.getElementById('cat-icon').value = emoji;
  document.querySelectorAll('.emoji-opt').forEach(e => e.classList.remove('selected'));
  el.classList.add('selected');
}

// ============================================================
// TRANSACTIONS
// ============================================================
function saveTransaction() {
  const desc = document.getElementById('tx-desc').value.trim();
  const amount = parseFloat(document.getElementById('tx-amount').value);
  const date = document.getElementById('tx-date').value;
  const catId = document.getElementById('tx-category').value;
  const accId = document.getElementById('tx-account').value;
  const note = document.getElementById('tx-note').value.trim();
  const type = document.getElementById('tx-type-val').value;
  const editId = document.getElementById('tx-edit-id').value;

  if (!desc || !amount || !date) { showToast('Preencha todos os campos', 'error'); return; }

  if (editId) {
    const idx = state.transactions.findIndex(t => t.id === editId);
    if (idx !== -1) {
      const old = state.transactions[idx];
      reverseBalanceEffect(old);
      state.transactions[idx] = { ...old, desc, amount, date, catId, accId, note, type };
      applyBalanceEffect(state.transactions[idx]);
    }
    showToast('Transação atualizada ✅', 'success');
  } else {
    const tx = { id: uid(), desc, amount, date, catId, accId, note, type };
    state.transactions.unshift(tx);
    applyBalanceEffect(tx);
    showToast(type === 'receita' ? '💰 Receita registrada!' : type === 'despesa' ? '💸 Despesa registrada!' : '📈 Investimento registrado!', 'success');
  }

  saveState();
  closeModal('modal-transaction');
  clearTxForm();
  renderDashboard();
  renderTransactions();
}

function applyBalanceEffect(tx) {
  if (tx.type === 'receita') state.balance += tx.amount;
  else if (tx.type === 'despesa') state.balance -= tx.amount;
  else if (tx.type === 'investimento') state.balance -= tx.amount;
}

function reverseBalanceEffect(tx) {
  if (tx.type === 'receita') state.balance -= tx.amount;
  else if (tx.type === 'despesa') state.balance += tx.amount;
  else if (tx.type === 'investimento') state.balance += tx.amount;
}

function clearTxForm() {
  document.getElementById('tx-desc').value = '';
  document.getElementById('tx-amount').value = '';
  document.getElementById('tx-note').value = '';
  document.getElementById('tx-edit-id').value = '';
  document.getElementById('tx-date').value = new Date().toISOString().split('T')[0];
}

function editTransaction(id) {
  const tx = state.transactions.find(t => t.id === id);
  if (!tx) return;
  openModal('modal-transaction');
  setTimeout(() => {
    document.getElementById('tx-edit-id').value = tx.id;
    document.getElementById('tx-desc').value = tx.desc;
    document.getElementById('tx-amount').value = tx.amount;
    document.getElementById('tx-date').value = tx.date;
    document.getElementById('tx-note').value = tx.note || '';
    document.getElementById('tx-category').value = tx.catId || '';
    document.getElementById('tx-account').value = tx.accId || '';

    const tabs = document.querySelectorAll('#tx-type-tabs .type-tab');
    tabs.forEach(t => t.className = 'type-tab');
    const classMap = { receita: 0, despesa: 1, investimento: 2 };
    const classNames = ['active-green', 'active-red', 'active-gold'];
    const idx = classMap[tx.type] || 0;
    tabs[idx].className = 'type-tab ' + classNames[idx];
    document.getElementById('tx-type-val').value = tx.type;
    selectedTxType = tx.type;
  }, 50);
}

function deleteTransaction(id) {
  const tx = state.transactions.find(t => t.id === id);
  if (!tx) return;
  if (!confirm('Excluir esta transação?')) return;
  reverseBalanceEffect(tx);
  state.transactions = state.transactions.filter(t => t.id !== id);
  saveState();
  showToast('Transação excluída', 'info');
  renderTransactions();
  renderDashboard();
}

function renderTransactions() {
  const search = (document.getElementById('tx-search')?.value || '').toLowerCase();
  const filterType = document.getElementById('tx-filter-type')?.value || '';
  const filterCat = document.getElementById('tx-filter-cat')?.value || '';

  const txs = state.transactions.filter(tx => {
    const matchSearch = !search || tx.desc.toLowerCase().includes(search);
    const matchType = !filterType || tx.type === filterType;
    const matchCat = !filterCat || tx.catId === filterCat;
    return matchSearch && matchType && matchCat;
  });

  populateSelects();
  const tbody = document.getElementById('tx-table-body');
  const empty = document.getElementById('tx-empty');
  if (!tbody) return;

  if (!txs.length) {
    tbody.innerHTML = '';
    empty.classList.remove('hidden');
    return;
  }
  empty.classList.add('hidden');

  tbody.innerHTML = txs.map(tx => {
    const cat = state.categories.find(c => c.id === tx.catId);
    const acc = state.accounts.find(a => a.id === tx.accId);
    const typeClass = { receita: 'badge-green', despesa: 'badge-red', investimento: 'badge-gold' }[tx.type];
    const typeLabel = { receita: '💰 Receita', despesa: '💸 Despesa', investimento: '📈 Investimento' }[tx.type];
    const amtStyle = tx.type === 'receita' ? 'style="color:var(--green)"' : 'style="color:var(--red)"';
    const amtSign = tx.type === 'receita' ? '+' : '-';
    return `<tr>
      <td><div style="font-weight:600">${tx.desc}</div>${tx.note ? `<div class="text-xs" style="color:var(--text3)">${tx.note}</div>` : ''}</td>
      <td><span class="badge ${typeClass}">${typeLabel}</span></td>
      <td>${cat ? `<span style="display:flex;align-items:center;gap:6px"><span style="width:8px;height:8px;border-radius:50%;background:${cat.color};flex-shrink:0"></span>${cat.icon} ${cat.name}</span>` : '<span style="color:var(--text3)">—</span>'}</td>
      <td style="color:var(--text2)">${fmtDate(tx.date)}</td>
      <td style="color:var(--text2)">${acc ? acc.name : '—'}</td>
      <td ${amtStyle} style="font-weight:700;font-family:'Syne',sans-serif">${amtSign}${fmt(tx.amount)}</td>
      <td>
        <div style="display:flex;gap:4px">
          <button class="btn btn-ghost btn-icon btn-sm" onclick="editTransaction('${tx.id}')" title="Editar">✏️</button>
          <button class="btn btn-ghost btn-icon btn-sm" onclick="deleteTransaction('${tx.id}')" title="Excluir" style="color:var(--red)">🗑️</button>
        </div>
      </td>
    </tr>`;
  }).join('');
}

// ============================================================
// INVESTMENTS
// ============================================================
function saveInvestment() {
  const name = document.getElementById('inv-name').value.trim();
  const type = document.getElementById('inv-type').value;
  const amount = parseFloat(document.getElementById('inv-amount').value);
  const accId = document.getElementById('inv-account').value;
  const date = document.getElementById('inv-date').value;

  if (!name || !amount) { showToast('Preencha todos os campos', 'error'); return; }

  const inv = { id: uid(), name, type, invested: amount, currentValue: amount, accId, date, history: [{ date, value: amount }] };
  state.investments.push(inv);
  state.balance -= amount;

  // Registra também como transação para aparecer no histórico
  state.transactions.unshift({ id: uid(), desc: `Investimento: ${name}`, amount, date, type: 'investimento', catId: '', accId, note: type });

  saveState();
  closeModal('modal-investment');
  document.getElementById('inv-name').value = '';
  document.getElementById('inv-amount').value = '';
  showToast('Investimento registrado 📈', 'success');
  renderInvestments();
  renderDashboard();
}

function updateInvestmentValue() {
  const id = document.getElementById('upd-inv-select').value;
  const val = parseFloat(document.getElementById('upd-inv-val').value);
  const date = document.getElementById('upd-inv-date').value;

  if (!id || !val) { showToast('Selecione o investimento e informe o valor', 'error'); return; }

  const inv = state.investments.find(i => i.id === id);
  if (!inv) return;

  // Atualiza apenas o valor de mercado — NÃO afeta saldo em conta
  inv.currentValue = val;
  inv.history = inv.history || [];
  inv.history.push({ date, value: val });

  saveState();
  closeModal('modal-update-invest');
  showToast('Valor de mercado atualizado ✅', 'success');
  renderInvestments();
}

function renderInvestments() {
  const invs = state.investments || [];
  const totalInvested = invs.reduce((s, i) => s + i.invested, 0);
  const totalCurrent = invs.reduce((s, i) => s + (i.currentValue || i.invested), 0);
  const totalReturn = totalCurrent - totalInvested;
  const returnPct = totalInvested > 0 ? ((totalReturn / totalInvested) * 100).toFixed(1) : '0.0';

  document.getElementById('invest-stats').innerHTML = `
    <div class="stat-card gold"><div class="stat-icon">💰</div><div class="stat-label">Total Investido</div><div class="stat-value gold">${fmt(totalInvested)}</div></div>
    <div class="stat-card accent"><div class="stat-icon">📊</div><div class="stat-label">Valor Atual</div><div class="stat-value accent">${fmt(totalCurrent)}</div></div>
    <div class="stat-card ${totalReturn >= 0 ? 'green' : 'red'}"><div class="stat-icon">${totalReturn >= 0 ? '📈' : '📉'}</div><div class="stat-label">Rendimento</div><div class="stat-value ${totalReturn >= 0 ? 'green' : 'red'}">${totalReturn >= 0 ? '+' : ''}${fmt(totalReturn)}</div><div class="stat-change">${returnPct}% total</div></div>
    <div class="stat-card"><div class="stat-icon">🗂️</div><div class="stat-label">Ativos</div><div class="stat-value">${invs.length}</div></div>
  `;

  const grid = document.getElementById('invest-grid');
  if (!invs.length) {
    grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><div class="empty-icon">📈</div><div class="empty-title">Nenhum investimento</div><div class="empty-desc">Comece investindo clicando em "+ Novo Investimento"</div></div>`;
    return;
  }

  const typeLabels = { 'renda-fixa': 'Renda Fixa', 'renda-variavel': 'Renda Variável', fundos: 'Fundos', cripto: 'Criptomoedas', imoveis: 'Imóveis', outro: 'Outro' };

  grid.innerHTML = invs.map(inv => {
    const cur = inv.currentValue || inv.invested;
    const ret = cur - inv.invested;
    const pct = inv.invested > 0 ? ((ret / inv.invested) * 100).toFixed(1) : '0.0';
    const retClass = ret >= 0 ? 'green' : 'red';
    return `<div class="invest-card">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:4px">
        <div class="invest-name">${inv.name}</div>
        <button class="btn btn-ghost btn-icon btn-sm" onclick="deleteInvestment('${inv.id}')" style="color:var(--red)">🗑️</button>
      </div>
      <div class="invest-type">${typeLabels[inv.type] || inv.type}</div>
      <div class="invest-value">${fmt(cur)}</div>
      <div class="invest-change" style="color:var(--${retClass})">
        ${ret >= 0 ? '▲' : '▼'} ${fmt(Math.abs(ret))} (${pct}%)
      </div>
      <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);display:flex;justify-content:space-between;font-size:12px;color:var(--text3)">
        <span>Aplicado: ${fmt(inv.invested)}</span>
        <span>${fmtDate(inv.date)}</span>
      </div>
    </div>`;
  }).join('');
}

function deleteInvestment(id) {
  if (!confirm('Excluir este investimento?')) return;
  const inv = state.investments.find(i => i.id === id);
  if (inv) state.balance += inv.invested;
  state.investments = state.investments.filter(i => i.id !== id);
  saveState();
  showToast('Investimento removido', 'info');
  renderInvestments();
}

// ============================================================
// GOALS
// ============================================================
function saveGoal() {
  const name = document.getElementById('goal-name').value.trim();
  const target = parseFloat(document.getElementById('goal-target').value);
  const current = parseFloat(document.getElementById('goal-current').value) || 0;
  const deadline = document.getElementById('goal-deadline').value;
  const icon = document.getElementById('goal-icon').value || '🎯';
  const editId = document.getElementById('goal-edit-id').value;

  if (!name || !target) { showToast('Preencha nome e valor alvo', 'error'); return; }

  if (editId) {
    const idx = state.goals.findIndex(g => g.id === editId);
    if (idx !== -1) state.goals[idx] = { ...state.goals[idx], name, target, deadline, icon };
    showToast('Meta atualizada ✅', 'success');
  } else {
    if (current > 0) state.balance -= current;
    state.goals.push({ id: uid(), name, target, current, deadline, icon });
    showToast('Meta criada! 🎯', 'success');
  }

  saveState();
  closeModal('modal-goal');
  document.getElementById('goal-name').value = '';
  document.getElementById('goal-target').value = '';
  document.getElementById('goal-current').value = '0';
  document.getElementById('goal-edit-id').value = '';
  renderGoals();
  renderDashboard();
}

function openContribute(id) {
  const goal = state.goals.find(g => g.id === id);
  if (!goal) return;
  document.getElementById('contrib-goal-id').value = id;
  document.getElementById('contrib-goal-desc').textContent = `Meta: ${goal.name} — ${fmt(goal.current)} / ${fmt(goal.target)}`;
  document.getElementById('contrib-amount').value = '';
  openModal('modal-goal-contribute');
}

function contributeGoal() {
  const id = document.getElementById('contrib-goal-id').value;
  const amount = parseFloat(document.getElementById('contrib-amount').value);
  if (!amount) { showToast('Informe o valor', 'error'); return; }
  const goal = state.goals.find(g => g.id === id);
  if (!goal) return;
  goal.current = (goal.current || 0) + amount;
  state.balance -= amount;
  saveState();
  closeModal('modal-goal-contribute');
  showToast(`+${fmt(amount)} adicionado à meta! 🎯`, 'success');
  renderGoals();
  renderDashboard();
}

function deleteGoal(id) {
  if (!confirm('Excluir esta meta?')) return;
  const goal = state.goals.find(g => g.id === id);
  if (goal) state.balance += goal.current || 0;
  state.goals = state.goals.filter(g => g.id !== id);
  saveState();
  showToast('Meta removida', 'info');
  renderGoals();
}

function renderGoals() {
  const goals = state.goals || [];
  const grid = document.getElementById('goals-grid');
  if (!goals.length) {
    grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><div class="empty-icon">🎯</div><div class="empty-title">Nenhuma meta criada</div><div class="empty-desc">Crie sua primeira meta financeira!</div></div>`;
    return;
  }
  // Cores fixas por índice para evitar re-render aleatório
  const colorOptions = ['accent', 'green', 'gold', 'blue'];
  grid.innerHTML = goals.map((g, idx) => {
    const pct = Math.min((g.current / g.target) * 100, 100);
    const color = colorOptions[idx % colorOptions.length];
    const done = pct >= 100;
    return `<div class="goal-card">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px">
        <div style="font-size:24px">${g.icon || '🎯'}</div>
        <div style="display:flex;gap:4px">
          ${done ? '<span class="badge badge-green">✅ Concluída</span>' : ''}
          <button class="btn btn-ghost btn-icon btn-sm" onclick="deleteGoal('${g.id}')" style="color:var(--red)">🗑️</button>
        </div>
      </div>
      <div class="goal-name">${g.name}</div>
      <div class="goal-sub">${g.deadline ? `Prazo: ${fmtDate(g.deadline)}` : 'Sem prazo definido'}</div>
      <div class="goal-amounts">
        <span class="goal-current">${fmt(g.current)}</span>
        <span class="goal-target">de ${fmt(g.target)}</span>
      </div>
      <div class="progress-bar"><div class="progress-fill ${done ? 'green' : color}" style="width:${pct}%"></div></div>
      <div style="display:flex;justify-content:space-between;margin-top:6px;font-size:12px;color:var(--text3)">
        <span>${pct.toFixed(0)}% concluído</span>
        <span>Falta: ${fmt(Math.max(g.target - g.current, 0))}</span>
      </div>
      ${!done ? `<button class="btn btn-primary btn-sm btn-full" style="margin-top:12px" onclick="openContribute('${g.id}')">+ Contribuir</button>` : ''}
    </div>`;
  }).join('');
}

// ============================================================
// ACCOUNTS
// ============================================================
function saveAccount() {
  const name = document.getElementById('acc-name').value.trim();
  const bank = document.getElementById('acc-bank').value.trim();
  const type = document.getElementById('acc-type').value;
  const balance = parseFloat(document.getElementById('acc-balance').value) || 0;
  const editId = document.getElementById('acc-edit-id').value;

  if (!name) { showToast('Informe o nome da conta', 'error'); return; }

  if (editId) {
    const idx = state.accounts.findIndex(a => a.id === editId);
    if (idx !== -1) state.accounts[idx] = { ...state.accounts[idx], name, bank, type, balance };
    showToast('Conta atualizada ✅', 'success');
  } else {
    state.accounts.push({ id: uid(), name, bank, type, balance });
    state.balance += balance;
    showToast('Conta criada! 🏦', 'success');
  }

  saveState();
  closeModal('modal-account');
  document.getElementById('acc-name').value = '';
  document.getElementById('acc-bank').value = '';
  document.getElementById('acc-balance').value = '';
  document.getElementById('acc-edit-id').value = '';
  renderAccounts();
}

function deleteAccount(id) {
  if (!confirm('Excluir esta conta?')) return;
  state.accounts = state.accounts.filter(a => a.id !== id);
  saveState();
  showToast('Conta removida', 'info');
  renderAccounts();
}

function renderAccounts() {
  const accs = state.accounts || [];
  const grid = document.getElementById('accounts-grid');
  if (!accs.length) {
    grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><div class="empty-icon">🏦</div><div class="empty-title">Nenhuma conta</div><div class="empty-desc">Adicione suas contas bancárias</div></div>`;
    return;
  }
  const gradients = [
    'linear-gradient(135deg,#1a0f3a,#0f1a2e)',
    'linear-gradient(135deg,#0f2a1a,#1a2f0f)',
    'linear-gradient(135deg,#2a1a0f,#3a2a10)',
    'linear-gradient(135deg,#1a1a2e,#2a0f3a)',
  ];
  grid.innerHTML = accs.map((a, i) => `
    <div class="account-card" style="background:${gradients[i % gradients.length]}">
      <div class="account-bank">${a.bank || 'Geral'}</div>
      <div class="account-name">${a.name}</div>
      <div class="account-balance">${fmt(a.balance)}</div>
      <div style="display:flex;justify-content:space-between;align-items:center;margin-top:8px">
        <div class="account-type">${a.type}</div>
        <button class="btn btn-ghost btn-icon btn-sm" onclick="deleteAccount('${a.id}')" style="color:var(--red)">🗑️</button>
      </div>
    </div>
  `).join('');
}

// ============================================================
// CATEGORIES
// ============================================================
function saveCategory() {
  const name = document.getElementById('cat-name').value.trim();
  const type = document.getElementById('cat-type-val').value;
  const icon = document.getElementById('cat-icon').value || '💰';
  const color = document.getElementById('cat-color').value || '#7c5cfc';
  const editId = document.getElementById('cat-edit-id').value;

  if (!name) { showToast('Informe o nome da categoria', 'error'); return; }

  if (editId) {
    const idx = state.categories.findIndex(c => c.id === editId);
    if (idx !== -1) state.categories[idx] = { ...state.categories[idx], name, type, icon, color };
    showToast('Categoria atualizada ✅', 'success');
  } else {
    state.categories.push({ id: uid(), name, type, icon, color });
    showToast('Categoria criada! 🏷️', 'success');
  }

  saveState();
  closeModal('modal-category');
  document.getElementById('cat-name').value = '';
  document.getElementById('cat-edit-id').value = '';
  renderCategories();
}

function deleteCategory(id) {
  if (!confirm('Excluir esta categoria?')) return;
  state.categories = state.categories.filter(c => c.id !== id);
  saveState();
  showToast('Categoria removida', 'info');
  renderCategories();
}

function renderCategories() {
  const cats = state.categories || [];
  const list = document.getElementById('categories-list');
  if (!cats.length) {
    list.innerHTML = `<div class="empty-state"><div class="empty-icon">🏷️</div><div class="empty-title">Nenhuma categoria</div></div>`;
    return;
  }
  const typeLabel = { receita: 'Receita', despesa: 'Despesa', ambos: 'Ambos', investimento: 'Investimento' };
  const typeClass = { receita: 'badge-green', despesa: 'badge-red', ambos: 'badge-accent', investimento: 'badge-gold' };

  list.innerHTML = cats.map(c => {
    const txCount = state.transactions.filter(t => t.catId === c.id).length;
    return `<div class="category-item">
      <div class="category-icon" style="background:${c.color}22">${c.icon}</div>
      <div class="category-info">
        <div class="category-name">${c.name}</div>
        <div class="category-count">${txCount} transaç${txCount === 1 ? 'ão' : 'ões'}</div>
      </div>
      <span class="badge ${typeClass[c.type] || 'badge-gray'}">${typeLabel[c.type] || c.type}</span>
      <div class="category-actions">
        <button class="btn btn-ghost btn-icon btn-sm" onclick="deleteCategory('${c.id}')" style="color:var(--red)">🗑️</button>
      </div>
    </div>`;
  }).join('');
}

// ============================================================
// INVOICES
// ============================================================
function saveInvoice() {
  const desc = document.getElementById('invoice-desc').value.trim();
  const amount = parseFloat(document.getElementById('invoice-amount').value);
  const due = document.getElementById('invoice-due').value;
  const catId = document.getElementById('invoice-category').value;
  const status = document.getElementById('invoice-status').value;

  if (!desc || !amount) { showToast('Preencha todos os campos', 'error'); return; }

  state.invoices.push({ id: uid(), desc, amount, due, catId, status });
  saveState();
  closeModal('modal-invoice');
  showToast('Fatura registrada 🧾', 'success');
  renderInvoices();
}

function markInvoicePaid(id) {
  const inv = state.invoices.find(i => i.id === id);
  if (!inv) return;
  inv.status = 'pago';
  state.balance -= inv.amount;
  state.transactions.unshift({
    id: uid(),
    desc: `Fatura: ${inv.desc}`,
    amount: inv.amount,
    date: new Date().toISOString().split('T')[0],
    type: 'despesa',
    catId: inv.catId,
    accId: '',
    note: 'Fatura paga',
  });
  saveState();
  showToast('Fatura marcada como paga ✅', 'success');
  renderInvoices();
  renderDashboard();
}

function deleteInvoice(id) {
  if (!confirm('Excluir esta fatura?')) return;
  state.invoices = state.invoices.filter(i => i.id !== id);
  saveState();
  showToast('Fatura removida', 'info');
  renderInvoices();
}

function renderInvoices() {
  const invs = state.invoices || [];
  const pending = invs.filter(i => i.status === 'pendente');
  const overdue = invs.filter(i => i.status === 'atrasado');
  const totalPending = pending.reduce((s, i) => s + i.amount, 0);
  const totalOverdue = overdue.reduce((s, i) => s + i.amount, 0);

  document.getElementById('invoice-stats').innerHTML = `
    <div class="stat-card red"><div class="stat-icon">⚠️</div><div class="stat-label">Atrasadas</div><div class="stat-value red">${fmt(totalOverdue)}</div><div class="stat-change">${overdue.length} fatura(s)</div></div>
    <div class="stat-card gold"><div class="stat-icon">🕐</div><div class="stat-label">Pendentes</div><div class="stat-value gold">${fmt(totalPending)}</div><div class="stat-change">${pending.length} fatura(s)</div></div>
    <div class="stat-card"><div class="stat-icon">🧾</div><div class="stat-label">Total</div><div class="stat-value">${invs.length}</div></div>
  `;

  const list = document.getElementById('invoice-list');
  if (!invs.length) {
    list.innerHTML = `<div class="empty-state"><div class="empty-icon">🧾</div><div class="empty-title">Nenhuma fatura</div></div>`;
    return;
  }

  const statusBadge = { pendente: 'badge-gold', pago: 'badge-green', atrasado: 'badge-red' };
  const statusLabel = { pendente: '🕐 Pendente', pago: '✅ Pago', atrasado: '⚠️ Atrasado' };
  const icons = ['🧾', '💳', '💡', '🏠', '📱', '🚗'];

  list.innerHTML = invs.map((inv, i) => `
    <div class="invoice-card">
      <div class="invoice-icon">${icons[i % icons.length]}</div>
      <div class="invoice-info">
        <div class="invoice-name">${inv.desc}</div>
        <div class="invoice-date">Vence: ${fmtDate(inv.due)}</div>
      </div>
      <div>
        <div class="invoice-amount" style="color:var(--red)">${fmt(inv.amount)}</div>
        <div class="invoice-status"><span class="badge ${statusBadge[inv.status]}">${statusLabel[inv.status]}</span></div>
      </div>
      <div style="display:flex;gap:6px;margin-left:8px">
        ${inv.status !== 'pago' ? `<button class="btn btn-green btn-sm" onclick="markInvoicePaid('${inv.id}')">Pagar</button>` : ''}
        <button class="btn btn-ghost btn-icon btn-sm" onclick="deleteInvoice('${inv.id}')" style="color:var(--red)">🗑️</button>
      </div>
    </div>
  `).join('');
}

// ============================================================
// DASHBOARD
// ============================================================
function renderDashboard() {
  const txs = state.transactions || [];
  const totalReceita = txs.filter(t => t.type === 'receita').reduce((s, t) => s + t.amount, 0);
  const totalDespesa = txs.filter(t => t.type === 'despesa').reduce((s, t) => s + t.amount, 0);
  const totalInvestido = (state.investments || []).reduce((s, i) => s + i.invested, 0);

  document.getElementById('dashboard-stats').innerHTML = `
    <div class="stat-card accent"><div class="stat-icon">💳</div><div class="stat-label">Saldo Total</div><div class="stat-value accent">${fmt(state.balance)}</div><div class="stat-change">saldo disponível</div></div>
    <div class="stat-card green"><div class="stat-icon">💰</div><div class="stat-label">Total Receitas</div><div class="stat-value green">${fmt(totalReceita)}</div></div>
    <div class="stat-card red"><div class="stat-icon">💸</div><div class="stat-label">Total Despesas</div><div class="stat-value red">${fmt(totalDespesa)}</div></div>
    <div class="stat-card gold"><div class="stat-icon">📈</div><div class="stat-label">Investimentos</div><div class="stat-value gold">${fmt(totalInvestido)}</div></div>
  `;

  renderMonthlyChart();
  renderDonutChart();
  renderRecentTransactions();
  renderGoalsSummary();
}

function renderMonthlyChart() {
  const months = [];
  const now = new Date();
  for (let i = 5; i >= 0; i--) {
    const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
    months.push({ label: d.toLocaleDateString('pt-BR', { month: 'short' }), year: d.getFullYear(), month: d.getMonth() });
  }

  const data = months.map(m => {
    const txs = (state.transactions || []).filter(t => {
      const d = new Date(t.date + 'T00:00:00');
      return d.getFullYear() === m.year && d.getMonth() === m.month;
    });
    return {
      label: m.label,
      receita: txs.filter(t => t.type === 'receita').reduce((s, t) => s + t.amount, 0),
      despesa: txs.filter(t => t.type === 'despesa').reduce((s, t) => s + t.amount, 0),
    };
  });

  const maxVal = Math.max(...data.flatMap(d => [d.receita, d.despesa]), 1);
  const chart = document.getElementById('monthly-chart');
  const labels = document.getElementById('monthly-labels');

  chart.innerHTML = data.map(d => `
    <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;height:100%;justify-content:flex-end">
      <div style="width:calc(50% - 2px);background:var(--green);opacity:0.85;border-radius:3px 3px 0 0;height:${Math.max((d.receita / maxVal) * 100, 2)}%;display:inline-block"></div>
    </div>
    <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;height:100%;justify-content:flex-end">
      <div style="width:calc(50% - 2px);background:var(--red);opacity:0.85;border-radius:3px 3px 0 0;height:${Math.max((d.despesa / maxVal) * 100, 2)}%;display:inline-block"></div>
    </div>
  `).join('');

  labels.innerHTML = data.map(d => `<div style="flex:2;text-align:center;font-size:11px;color:var(--text3)">${d.label}</div>`).join('');
}

function renderDonutChart() {
  const cats = state.categories || [];
  const txs = (state.transactions || []).filter(t => t.type === 'despesa');
  const total = txs.reduce((s, t) => s + t.amount, 0);

  const byCategory = {};
  txs.forEach(t => {
    if (!t.catId) return;
    byCategory[t.catId] = (byCategory[t.catId] || 0) + t.amount;
  });

  const items = Object.entries(byCategory).map(([id, val]) => {
    const cat = cats.find(c => c.id === id);
    return { name: cat ? cat.name : 'Outros', icon: cat ? cat.icon : '💸', color: cat ? cat.color : '#666', val };
  }).sort((a, b) => b.val - a.val).slice(0, 5);

  if (!items.length) {
    document.getElementById('donut-chart-wrap').innerHTML = `<div class="empty-state" style="width:100%"><div class="empty-icon">📊</div><div class="empty-desc">Sem dados de despesas</div></div>`;
    return;
  }

  const colors = ['#7c5cfc', '#22c98a', '#f04060', '#f5b942', '#3a9df8'];
  let angle = 0;
  const cx = 70, cy = 70, r = 55, inner = 30;
  let paths = '';
  items.forEach((item, i) => {
    const pct = total > 0 ? item.val / total : 0;
    const startAngle = angle;
    const endAngle = angle + pct * 2 * Math.PI;
    const x1 = cx + r * Math.sin(startAngle);
    const y1 = cy - r * Math.cos(startAngle);
    const x2 = cx + r * Math.sin(endAngle);
    const y2 = cy - r * Math.cos(endAngle);
    const ix1 = cx + inner * Math.sin(startAngle);
    const iy1 = cy - inner * Math.cos(startAngle);
    const ix2 = cx + inner * Math.sin(endAngle);
    const iy2 = cy - inner * Math.cos(endAngle);
    const large = pct > 0.5 ? 1 : 0;
    const col = item.color || colors[i % colors.length];
    paths += `<path d="M${x1},${y1} A${r},${r} 0 ${large},1 ${x2},${y2} L${ix2},${iy2} A${inner},${inner} 0 ${large},0 ${ix1},${iy1} Z" fill="${col}" opacity="0.9"/>`;
    angle = endAngle;
  });

  const legend = items.map((item, i) => {
    const col = item.color || colors[i % colors.length];
    const pct = total > 0 ? ((item.val / total) * 100).toFixed(0) : 0;
    return `<div class="donut-item"><div class="donut-dot" style="background:${col}"></div><span style="font-size:13px;color:var(--text2)">${item.icon} ${item.name}</span><span class="donut-pct">${pct}%</span></div>`;
  }).join('');

  document.getElementById('donut-chart-wrap').innerHTML = `
    <svg class="donut-svg" width="140" height="140" viewBox="0 0 140 140">${paths}</svg>
    <div class="donut-legend">${legend}</div>
  `;
}

function renderRecentTransactions() {
  const txs = (state.transactions || []).slice(0, 5);
  const list = document.getElementById('recent-transactions-list');
  if (!txs.length) {
    list.innerHTML = `<div class="empty-state"><div class="empty-icon">↕️</div><div class="empty-desc">Nenhuma transação</div></div>`;
    return;
  }
  list.innerHTML = txs.map(tx => {
    const amtColor = tx.type === 'receita' ? 'var(--green)' : 'var(--red)';
    const amtSign = tx.type === 'receita' ? '+' : '-';
    return `<div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border)">
      <div>
        <div style="font-size:13.5px;font-weight:600">${tx.desc}</div>
        <div style="font-size:12px;color:var(--text3)">${fmtDate(tx.date)}</div>
      </div>
      <div style="font-size:14px;font-weight:700;color:${amtColor}">${amtSign}${fmt(tx.amount)}</div>
    </div>`;
  }).join('');
}

function renderGoalsSummary() {
  const goals = (state.goals || []).slice(0, 3);
  const list = document.getElementById('goals-summary');
  if (!goals.length) {
    list.innerHTML = `<div class="empty-state"><div class="empty-icon">🎯</div><div class="empty-desc">Nenhuma meta criada</div></div>`;
    return;
  }
  list.innerHTML = goals.map(g => {
    const pct = Math.min((g.current / g.target) * 100, 100);
    return `<div style="margin-bottom:16px">
      <div style="display:flex;justify-content:space-between;margin-bottom:6px">
        <span style="font-size:13.5px;font-weight:600">${g.icon || '🎯'} ${g.name}</span>
        <span style="font-size:12px;color:var(--text3)">${pct.toFixed(0)}%</span>
      </div>
      <div class="progress-bar"><div class="progress-fill accent" style="width:${pct}%"></div></div>
      <div style="display:flex;justify-content:space-between;margin-top:4px;font-size:11px;color:var(--text3)">
        <span>${fmt(g.current)}</span><span>${fmt(g.target)}</span>
      </div>
    </div>`;
  }).join('');
}

// ============================================================
// REPORTS
// ============================================================
function renderReports() {
  const txs = state.transactions || [];
  const totalR = txs.filter(t => t.type === 'receita').reduce((s, t) => s + t.amount, 0);
  const totalD = txs.filter(t => t.type === 'despesa').reduce((s, t) => s + t.amount, 0);
  const totalI = (state.investments || []).reduce((s, i) => s + (i.currentValue || i.invested), 0);

  const maxVal = Math.max(totalR, totalD, 1);
  document.getElementById('report-chart-1').innerHTML = `
    <div style="display:flex;gap:24px;align-items:flex-end;height:160px;padding:10px 0">
      <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:8px">
        <div style="font-size:13px;font-weight:600;color:var(--green)">${fmt(totalR)}</div>
        <div style="width:80%;background:var(--green);border-radius:6px 6px 0 0;height:${Math.max((totalR/maxVal)*120,4)}px"></div>
        <div style="font-size:12px;color:var(--text3)">💰 Receitas</div>
      </div>
      <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:8px">
        <div style="font-size:13px;font-weight:600;color:var(--red)">${fmt(totalD)}</div>
        <div style="width:80%;background:var(--red);border-radius:6px 6px 0 0;height:${Math.max((totalD/maxVal)*120,4)}px"></div>
        <div style="font-size:12px;color:var(--text3)">💸 Despesas</div>
      </div>
      <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:8px">
        <div style="font-size:13px;font-weight:600;color:var(--gold)">${fmt(totalI)}</div>
        <div style="width:80%;background:var(--gold);border-radius:6px 6px 0 0;height:${Math.max((totalI/Math.max(totalI,maxVal))*120,4)}px"></div>
        <div style="font-size:12px;color:var(--text3)">📈 Investimentos</div>
      </div>
    </div>
  `;

  const donutEl = document.getElementById('report-donut');
  donutEl.innerHTML = '';
  const wrapper = document.createElement('div');
  wrapper.className = 'donut-wrap';
  donutEl.appendChild(wrapper);
  const cats = state.categories || [];
  const despTxs = txs.filter(t => t.type === 'despesa');
  const total = despTxs.reduce((s, t) => s + t.amount, 0);
  const byCat = {};
  despTxs.forEach(t => { if (t.catId) byCat[t.catId] = (byCat[t.catId] || 0) + t.amount; });
  const items = Object.entries(byCat).map(([id, val]) => {
    const c = cats.find(c => c.id === id);
    return { name: c?.name || 'Outros', icon: c?.icon || '💸', color: c?.color || '#666', val };
  }).sort((a, b) => b.val - a.val).slice(0, 5);

  if (!items.length) {
    donutEl.innerHTML = '<div class="empty-state"><div class="empty-icon">📊</div><div class="empty-desc">Sem dados</div></div>';
  } else {
    let angle = 0;
    const cx = 60, cy = 60, r = 48, inner = 24;
    let paths = '';
    const colors = ['#7c5cfc', '#22c98a', '#f04060', '#f5b942', '#3a9df8'];
    items.forEach((item, i) => {
      const pct = total > 0 ? item.val / total : 0;
      const s = angle, e = angle + pct * 2 * Math.PI;
      const x1 = cx + r * Math.sin(s), y1 = cy - r * Math.cos(s);
      const x2 = cx + r * Math.sin(e), y2 = cy - r * Math.cos(e);
      const ix1 = cx + inner * Math.sin(s), iy1 = cy - inner * Math.cos(s);
      const ix2 = cx + inner * Math.sin(e), iy2 = cy - inner * Math.cos(e);
      const lg = pct > 0.5 ? 1 : 0;
      const col = item.color || colors[i % colors.length];
      paths += `<path d="M${x1},${y1} A${r},${r} 0 ${lg},1 ${x2},${y2} L${ix2},${iy2} A${inner},${inner} 0 ${lg},0 ${ix1},${iy1} Z" fill="${col}" opacity="0.9"/>`;
      angle = e;
    });
    const legend = items.map((item, i) => {
      const col = item.color || colors[i % colors.length];
      const p = total > 0 ? ((item.val / total) * 100).toFixed(0) : 0;
      return `<div class="donut-item"><div class="donut-dot" style="background:${col}"></div><span style="font-size:12px;color:var(--text2)">${item.icon} ${item.name}</span><span class="donut-pct">${p}%</span></div>`;
    }).join('');
    wrapper.innerHTML = `<svg width="120" height="120" viewBox="0 0 120 120">${paths}</svg><div class="donut-legend" style="font-size:12px">${legend}</div>`;
  }

  document.getElementById('report-chart-2').innerHTML = `
    <div style="padding:20px 0">
      <div style="display:flex;justify-content:space-between;margin-bottom:16px">
        <div><div style="font-size:12px;color:var(--text3);margin-bottom:4px">Saldo em conta</div><div style="font-size:18px;font-weight:700;color:var(--accent)">${fmt(state.balance)}</div></div>
        <div><div style="font-size:12px;color:var(--text3);margin-bottom:4px">Em investimentos</div><div style="font-size:18px;font-weight:700;color:var(--gold)">${fmt(totalI)}</div></div>
        <div><div style="font-size:12px;color:var(--text3);margin-bottom:4px">Patrimônio total</div><div style="font-size:18px;font-weight:700;color:var(--green)">${fmt(state.balance + totalI)}</div></div>
      </div>
    </div>
  `;

  const saldo = totalR - totalD;
  document.getElementById('report-summary').innerHTML = `
    <div style="display:flex;flex-direction:column;gap:12px;padding-top:4px">
      ${[
        { label: 'Total de Receitas', val: fmt(totalR), color: 'var(--green)', icon: '💰' },
        { label: 'Total de Despesas', val: fmt(totalD), color: 'var(--red)', icon: '💸' },
        { label: 'Saldo (Receita - Despesa)', val: fmt(saldo), color: saldo >= 0 ? 'var(--green)' : 'var(--red)', icon: saldo >= 0 ? '📈' : '📉' },
        { label: 'Transações registradas', val: txs.length, color: 'var(--text)', icon: '↕️' },
        { label: 'Investimentos ativos', val: (state.investments || []).length, color: 'var(--gold)', icon: '📊' },
        { label: 'Metas ativas', val: (state.goals || []).length, color: 'var(--accent)', icon: '🎯' },
      ].map(r => `
        <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border)">
          <span style="font-size:13px;color:var(--text2)">${r.icon} ${r.label}</span>
          <span style="font-size:14px;font-weight:700;color:${r.color}">${r.val}</span>
        </div>
      `).join('')}
    </div>
  `;
}

// ============================================================
// DEFAULT DATA
// ============================================================
function getDefaultCategories() {
  return [
    { id: uid(), name: 'Salário',      type: 'receita', icon: '💼', color: '#22c98a' },
    { id: uid(), name: 'Freelance',    type: 'receita', icon: '💻', color: '#3a9df8' },
    { id: uid(), name: 'Alimentação',  type: 'despesa', icon: '🍔', color: '#f5b942' },
    { id: uid(), name: 'Transporte',   type: 'despesa', icon: '🚗', color: '#f04060' },
    { id: uid(), name: 'Moradia',      type: 'despesa', icon: '🏠', color: '#7c5cfc' },
    { id: uid(), name: 'Saúde',        type: 'despesa', icon: '💊', color: '#f06292' },
    { id: uid(), name: 'Lazer',        type: 'despesa', icon: '🎮', color: '#4dd0e1' },
    { id: uid(), name: 'Educação',     type: 'despesa', icon: '📚', color: '#aed581' },
  ];
}

function getSampleTransactions() {
  // Usa state.categories e state.accounts que já foram definidos antes desta chamada
  const cats = state.categories;
  const accs = state.accounts;
  const now = new Date();
  const m = (offset = 0) => new Date(now.getFullYear(), now.getMonth() + offset, 1);

  return [
    { id: uid(), desc: 'Salário Mensal',    amount: 5000, type: 'receita', catId: cats[0]?.id, accId: accs[0]?.id, date: new Date(now.getFullYear(), now.getMonth(),     5).toISOString().split('T')[0], note: '' },
    { id: uid(), desc: 'Salário Mensal',    amount: 5000, type: 'receita', catId: cats[0]?.id, accId: accs[0]?.id, date: new Date(now.getFullYear(), now.getMonth() - 1, 5).toISOString().split('T')[0], note: '' },
    { id: uid(), desc: 'Freelance Design',  amount: 1500, type: 'receita', catId: cats[1]?.id, accId: accs[0]?.id, date: new Date(now.getFullYear(), now.getMonth(),    15).toISOString().split('T')[0], note: '' },
    { id: uid(), desc: 'Aluguel',           amount: 1200, type: 'despesa', catId: cats[4]?.id, accId: accs[0]?.id, date: new Date(now.getFullYear(), now.getMonth(),    10).toISOString().split('T')[0], note: '' },
    { id: uid(), desc: 'Aluguel',           amount: 1200, type: 'despesa', catId: cats[4]?.id, accId: accs[0]?.id, date: new Date(now.getFullYear(), now.getMonth() - 1,10).toISOString().split('T')[0], note: '' },
    { id: uid(), desc: 'Supermercado',      amount:  350, type: 'despesa', catId: cats[2]?.id, accId: accs[0]?.id, date: new Date(now.getFullYear(), now.getMonth(),    12).toISOString().split('T')[0], note: '' },
    { id: uid(), desc: 'Academia',          amount:   80, type: 'despesa', catId: cats[5]?.id, accId: accs[0]?.id, date: new Date(now.getFullYear(), now.getMonth(),     1).toISOString().split('T')[0], note: '' },
    { id: uid(), desc: 'Netflix + Spotify', amount:   60, type: 'despesa', catId: cats[6]?.id, accId: accs[0]?.id, date: new Date(now.getFullYear(), now.getMonth(),     3).toISOString().split('T')[0], note: '' },
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
  return [
    { id: uid(), desc: 'Cartão Nubank', amount: 850, due: new Date(now.getFullYear(), now.getMonth(), 20).toISOString().split('T')[0], status: 'pendente', catId: '' },
    { id: uid(), desc: 'Conta de Luz',  amount: 120, due: new Date(now.getFullYear(), now.getMonth(), 15).toISOString().split('T')[0], status: 'atrasado', catId: '' },
  ];
}
