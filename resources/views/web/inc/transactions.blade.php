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