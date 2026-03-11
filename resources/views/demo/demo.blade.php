@extends('layouts.master')

@push('styles') @endpush

@section('content')
    <div id="app-layout" class="app-layout active">
      <!-- SIDEBAR -->
      <aside class="sidebar">
        <div class="sidebar-logo">
          <div class="sidebar-logo-icon">💸</div>
          FinFlow
        </div>
        <nav class="sidebar-nav">
          <div class="nav-section-label">Principal</div>
          <button class="nav-item active" onclick="showPage('dashboard')">
            <span class="nav-icon">📊</span> Dashboard
          </button>
          <button class="nav-item" onclick="showPage('transactions')">
            <span class="nav-icon">↕️</span> Transações
          </button>
          <button class="nav-item" onclick="showPage('invoices')">
            <span class="nav-icon">🧾</span> Faturas
          </button>

          <div class="nav-section-label">Patrimônio</div>
          <button class="nav-item" onclick="showPage('investments')">
            <span class="nav-icon">📈</span> Investimentos
          </button>
          <button class="nav-item" onclick="showPage('goals')">
            <span class="nav-icon">🎯</span> Metas
          </button>

          <div class="nav-section-label">Organização</div>
          <button class="nav-item" onclick="showPage('accounts')">
            <span class="nav-icon">🏦</span> Contas
          </button>
          <button class="nav-item" onclick="showPage('categories')">
            <span class="nav-icon">🏷️</span> Categorias
          </button>
          <button class="nav-item" onclick="showPage('reports')">
            <span class="nav-icon">📉</span> Relatórios
          </button>
        </nav>
        <div class="sidebar-user">
          <div class="user-card">
            <div class="user-avatar" id="sidebar-avatar">DE</div>
            <div>
              <div class="user-name" id="sidebar-name">DEMO</div>
              <div class="user-email" id="sidebar-email">demo@finflow.com</div>
            </div>
          </div>
          <a class="btn btn-ghost btn-sm btn-full" style="margin-top:8px;color:var(--red)" href="{{ route('home') }}">⬅️ Sair</a>
        </div>
      </aside>

      <!-- MAIN -->
      <div class="main-content">
        <div class="topbar">
          <div class="topbar-title" id="topbar-title">Dashboard</div>
          <div class="topbar-right">
            <button class="btn btn-primary btn-sm" onclick="openModal('modal-transaction')">+ Nova Transação</button>
          </div>
        </div>

        <div class="content">
          <!-- DASHBOARD -->
          <div id="page-dashboard" class="inner-page active">
            <div class="grid-4 mb-3" id="dashboard-stats"></div>
            <div class="grid-2 mb-3">
              <!-- Monthly chart -->
              <div class="card">
                <div class="card-header">
                  <div class="card-title">Fluxo Mensal</div>
                  <span class="badge badge-gray text-xs" id="chart-period">últimos 6 meses</span>
                </div>
                <div class="chart-area" id="monthly-chart"></div>
                <div class="chart-labels" id="monthly-labels"></div>
              </div>
              <!-- Donut -->
              <div class="card">
                <div class="card-header">
                  <div class="card-title">Por Categoria</div>
                </div>
                <div class="donut-wrap" id="donut-chart-wrap"></div>
              </div>
            </div>
            <div class="grid-2">
              <!-- Recent transactions -->
              <div class="card">
                <div class="card-header">
                  <div class="card-title">Últimas Transações</div>
                  <button class="btn btn-ghost btn-sm" onclick="showPage('transactions')">Ver todas →</button>
                </div>
                <div id="recent-transactions-list"></div>
              </div>
              <!-- Quick summary -->
              <div class="card">
                <div class="card-header">
                  <div class="card-title">Resumo das Metas</div>
                  <button class="btn btn-ghost btn-sm" onclick="showPage('goals')">Ver metas →</button>
                </div>
                <div id="goals-summary"></div>
              </div>
            </div>
          </div>

          <!-- TRANSACTIONS -->
          <div id="page-transactions" class="inner-page">
            <div class="section-header">
              <div>
                <div class="section-title">Transações</div>
                <div class="section-sub">Gerencie todas as suas movimentações</div>
              </div>
              <button class="btn btn-primary" onclick="openModal('modal-transaction')">+ Nova Transação</button>
            </div>
            <div class="filter-bar">
              <input class="form-input" style="width:200px" type="text" placeholder="🔍 Buscar..." id="tx-search" oninput="renderTransactions()">
              <select class="form-input" style="width:140px" id="tx-filter-type" onchange="renderTransactions()">
                <option value="">Todos os tipos</option>
                <option value="receita">Receita</option>
                <option value="despesa">Despesa</option>
                <option value="investimento">Investimento</option>
              </select>
              <select class="form-input" style="width:160px" id="tx-filter-cat" onchange="renderTransactions()">
                <option value="">Todas categorias</option>
              </select>
            </div>
            <div class="card">
              <div class="table-wrap">
                <table>
                  <thead>
                    <tr>
                      <th>Descrição</th>
                      <th>Tipo</th>
                      <th>Categoria</th>
                      <th>Data</th>
                      <th>Conta</th>
                      <th>Valor</th>
                      <th>Ações</th>
                    </tr>
                  </thead>
                  <tbody id="tx-table-body"></tbody>
                </table>
              </div>
              <div id="tx-empty" class="empty-state hidden">
                <div class="empty-icon">↕️</div>
                <div class="empty-title">Nenhuma transação encontrada</div>
                <div class="empty-desc">Adicione sua primeira transação clicando no botão acima</div>
              </div>
            </div>
          </div>

          <!-- INVOICES -->
          <div id="page-invoices" class="inner-page">
            <div class="section-header">
              <div>
                <div class="section-title">Faturas</div>
                <div class="section-sub">Controle suas faturas e contas a pagar</div>
              </div>
              <button class="btn btn-primary" onclick="openModal('modal-invoice')">+ Nova Fatura</button>
            </div>
            <div class="grid-3 mb-3" id="invoice-stats"></div>
            <div id="invoice-list"></div>
          </div>

          <!-- INVESTMENTS -->
          <div id="page-investments" class="inner-page">
            <div class="section-header">
              <div>
                <div class="section-title">Investimentos</div>
                <div class="section-sub">Acompanhe sua carteira de investimentos</div>
              </div>
              <div style="display:flex;gap:8px">
                <button class="btn btn-secondary" onclick="openModal('modal-update-invest')">✏️ Atualizar Valor</button>
                <button class="btn btn-gold" onclick="openModal('modal-investment')">+ Novo Investimento</button>
              </div>
            </div>
            <div class="grid-4 mb-3" id="invest-stats"></div>
            <div class="grid-3" id="invest-grid"></div>
          </div>

          <!-- GOALS -->
          <div id="page-goals" class="inner-page">
            <div class="section-header">
              <div>
                <div class="section-title">Metas Financeiras</div>
                <div class="section-sub">Defina objetivos e acompanhe seu progresso</div>
              </div>
              <button class="btn btn-primary" onclick="openModal('modal-goal')">+ Nova Meta</button>
            </div>
            <div id="goals-grid" class="grid-3"></div>
          </div>

          <!-- ACCOUNTS -->
          <div id="page-accounts" class="inner-page">
            <div class="section-header">
              <div>
                <div class="section-title">Contas Bancárias</div>
                <div class="section-sub">Gerencie suas contas e carteiras</div>
              </div>
              <button class="btn btn-primary" onclick="openModal('modal-account')">+ Nova Conta</button>
            </div>
            <div class="grid-3" id="accounts-grid"></div>
          </div>

          <!-- CATEGORIES -->
          <div id="page-categories" class="inner-page">
            <div class="section-header">
              <div>
                <div class="section-title">Categorias</div>
                <div class="section-sub">Organize suas transações</div>
              </div>
              <button class="btn btn-primary" onclick="openModal('modal-category')">+ Nova Categoria</button>
            </div>
            <div id="categories-list"></div>
          </div>

          <!-- REPORTS -->
          <div id="page-reports" class="inner-page">
            <div class="section-title mb-2">Relatórios</div>
            <div class="grid-2 mb-3">
              <div class="card">
                <div class="card-header"><div class="card-title">Receitas vs Despesas</div></div>
                <div id="report-chart-1"></div>
              </div>
              <div class="card">
                <div class="card-header"><div class="card-title">Despesas por Categoria</div></div>
                <div id="report-donut"></div>
              </div>
            </div>
            <div class="grid-2">
              <div class="card">
                <div class="card-header"><div class="card-title">Evolução do Patrimônio</div></div>
                <div id="report-chart-2"></div>
              </div>
              <div class="card">
                <div class="card-header"><div class="card-title">Resumo Anual</div></div>
                <div id="report-summary"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection

@include('modals.all')

@push('scripts') 
    <script src="{{ asset('js/demo/data.js') }}"></script>
    <script src="{{ asset('js/finances-init.js') }}"></script>
@endpush