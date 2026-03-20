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
      @auth
          <div class="user-card">
            <div class="user-avatar" id="sidebar-avatar">{{ getInitialsUser() }}</div>
            <div>
              <div class="user-name" id="sidebar-name">{{ userName() }}</div>
              <div class="user-email" id="sidebar-email">{{ userEmail() }}</div>
            </div>
          </div>
      @else 
          <div class="user-card">
            <div class="user-avatar" id="sidebar-avatar">JS</div>
            <div>
              <div class="user-name" id="sidebar-name">João Silva</div>
              <div class="user-email" id="sidebar-email">joao@email.com</div>
            </div>
          </div>
      @endauth
      <a class="btn btn-ghost btn-sm btn-full" style="margin-top:8px;color:var(--red)" href="{{ route('logout') }}">⬅️ Sair</a>
    </div>
  </aside>