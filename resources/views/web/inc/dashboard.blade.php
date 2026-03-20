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