<!-- TRANSACTION MODAL -->
<div class="modal-overlay" id="modal-transaction">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Nova Transação</div>
      <button class="btn-close" onclick="closeModal('modal-transaction')">×</button>
    </div>
    <div class="modal-body">
      <div class="type-tabs" id="tx-type-tabs">
        <button class="type-tab active-green" onclick="setTxType('receita',this)">💰 Receita</button>
        <button class="type-tab" onclick="setTxType('despesa',this)">💸 Despesa</button>
        <button class="type-tab" onclick="setTxType('investimento',this)">📈 Investimento</button>
      </div>
      <input type="hidden" id="tx-type-val" value="receita">
      <input type="hidden" id="tx-edit-id" value="">
      <div class="form-group">
        <label class="form-label">Descrição</label>
        <input class="form-input" id="tx-desc" type="text" placeholder="Ex: Salário, Aluguel...">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Valor (R$)</label>
          <input class="form-input money" id="tx-amount" type="text" placeholder="0,00">
        </div>
        <div class="form-group">
          <label class="form-label">Data</label>
          <input class="form-input" id="tx-date" type="date">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group" id="tx-recurrence-group" style="display:none">
          <label class="form-label">Tipo de lançamento</label>
          <select class="form-input" id="tx-recurrence" onchange="toggleInstallments('tx')">
            <option value="none">Único</option>
            <option value="recurring">Recorrente (24x)</option>
            <option value="installment">Parcelado</option>
          </select>
        </div>
        <div class="form-group" id="tx-installments-group" style="display:none">
          <label class="form-label">Parcelas (2-60)</label>
          <input class="form-input" id="tx-installments" type="number" min="2" max="60" value="2">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Categoria</label>
          <select class="form-input" id="tx-category">
            <option value="">Selecionar...</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Conta</label>
          <select class="form-input" id="tx-account">
            <option value="">Selecionar...</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Observação (opcional)</label>
        <input class="form-input" id="tx-note" type="text" placeholder="Adicione uma nota...">
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-transaction')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveTransaction()">Salvar</button>
    </div>
  </div>
</div>

<!-- INVESTMENT MODAL -->
<div class="modal-overlay" id="modal-investment">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Novo Investimento</div>
      <button class="btn-close" onclick="closeModal('modal-investment')">×</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="inv-edit-id" value="">
      <div class="alert alert-info" style="background:rgba(245,185,66,0.1);color:var(--gold);border:1px solid rgba(245,185,66,0.2);padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;">
        💡 O valor investido será descontado do seu saldo atual
      </div>
      <div class="form-group">
        <label class="form-label">Nome do Investimento</label>
        <input class="form-input" id="inv-name" type="text" placeholder="Ex: Tesouro Selic, Ações PETR4...">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tipo</label>
          <select class="form-input" id="inv-type">
            <option value="renda-fixa">Renda Fixa</option>
            <option value="renda-variavel">Renda Variável</option>
            <option value="fundos">Fundos</option>
            <option value="cripto">Criptomoedas</option>
            <option value="imoveis">Imóveis</option>
            <option value="outro">Outro</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Valor Aplicado (R$)</label>
          <input class="form-input money" id="inv-amount" type="text" placeholder="0,00">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Conta de origem</label>
        <select class="form-input" id="inv-account">
          <option value="">Selecionar...</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Data do aporte</label>
        <input class="form-input" id="inv-date" type="date">
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-investment')">Cancelar</button>
      <button class="btn btn-gold" onclick="saveInvestment()">Investir</button>
    </div>
  </div>
</div>

<!-- UPDATE INVESTMENT VALUE MODAL -->
<div class="modal-overlay" id="modal-update-invest">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Atualizar Valor de Investimento</div>
      <button class="btn-close" onclick="closeModal('modal-update-invest')">×</button>
    </div>
    <div class="modal-body">
      <div class="alert" style="background:rgba(58,157,248,0.1);color:var(--blue);border:1px solid rgba(58,157,248,0.2);padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;">
        ℹ️ Atualizar o valor não afeta seu saldo em conta — apenas o valor de mercado
      </div>
      <div class="form-group">
        <label class="form-label">Investimento</label>
        <select class="form-input" id="upd-inv-select">
          <option value="">Selecionar investimento...</option>
        </select>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Valor atual (R$)</label>
          <input class="form-input money" id="upd-inv-val" type="text" placeholder="0,00">
        </div>
        <div class="form-group">
          <label class="form-label">Data da atualização</label>
          <input class="form-input" id="upd-inv-date" type="date">
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-update-invest')">Cancelar</button>
      <button class="btn btn-primary" onclick="updateInvestmentValue()">Atualizar</button>
    </div>
  </div>
</div>

<!-- GOAL MODAL -->
<div class="modal-overlay" id="modal-goal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Nova Meta Financeira</div>
      <button class="btn-close" onclick="closeModal('modal-goal')">×</button>
    </div>
    <div class="modal-body">
      <div class="alert" style="background:rgba(124,92,252,0.1);color:var(--accent);border:1px solid rgba(124,92,252,0.2);padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;">
        🎯 O valor reservado para a meta será descontado do saldo atual
      </div>
      <input type="hidden" id="goal-edit-id" value="">
      <div class="form-group">
        <label class="form-label">Nome da Meta</label>
        <input class="form-input" id="goal-name" type="text" placeholder="Ex: Fundo de emergência, Viagem...">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Valor alvo (R$)</label>
          <input class="form-input money" id="goal-target" type="text" placeholder="5000,00">
        </div>
        <div class="form-group">
          <label class="form-label">Valor inicial (R$)</label>
          <input class="form-input money" id="goal-current" type="text" placeholder="0,00" value="0">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Prazo</label>
          <input class="form-input" id="goal-deadline" type="date">
        </div>
        <div class="form-group">
          <label class="form-label">Ícone</label>
          <input class="form-input" id="goal-icon" type="text" placeholder="🎯" maxlength="2">
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-goal')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveGoal()">Criar Meta</button>
    </div>
  </div>
</div>

<!-- CONTRIBUTE TO GOAL MODAL -->
<div class="modal-overlay" id="modal-goal-contribute">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Contribuir com Meta</div>
      <button class="btn-close" onclick="closeModal('modal-goal-contribute')">×</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="contrib-goal-id">
      <p style="font-size:14px;color:var(--text2);margin-bottom:16px" id="contrib-goal-desc"></p>
      <div class="form-group">
        <label class="form-label">Valor a adicionar (R$)</label>
        <input class="form-input money" id="contrib-amount" type="text" placeholder="0,00">
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-goal-contribute')">Cancelar</button>
      <button class="btn btn-primary" onclick="contributeGoal()">Adicionar</button>
    </div>
  </div>
</div>

<!-- ACCOUNT MODAL -->
<div class="modal-overlay" id="modal-account">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Nova Conta</div>
      <button class="btn-close" onclick="closeModal('modal-account')">×</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="acc-edit-id" value="">
      <div class="form-group">
        <label class="form-label">Nome da conta</label>
        <input class="form-input" id="acc-name" type="text" placeholder="Ex: Conta Corrente, Poupança...">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Banco/Instituição</label>
          <input class="form-input" id="acc-bank" type="text" placeholder="Ex: Nubank, Itaú...">
        </div>
        <div class="form-group">
          <label class="form-label">Tipo</label>
          <select class="form-input" id="acc-type">
            <option value="corrente">Conta Corrente</option>
            <option value="poupanca">Poupança</option>
            <option value="carteira">Carteira</option>
            <option value="investimento">Investimento</option>
            <option value="outro">Outro</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Saldo atual (R$)</label>
        <input class="form-input money" id="acc-balance" type="text" placeholder="0,00">
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-account')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveAccount()">Salvar</button>
    </div>
  </div>
</div>

<!-- CATEGORY MODAL -->
<div class="modal-overlay" id="modal-category">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Nova Categoria</div>
      <button class="btn-close" onclick="closeModal('modal-category')">×</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="cat-edit-id" value="">
      <div class="form-group">
        <label class="form-label">Nome da categoria</label>
        <input class="form-input" id="cat-name" type="text" placeholder="Ex: Alimentação, Transporte...">
      </div>
      <div class="form-group">
        <label class="form-label">Tipo</label>
        <div class="type-tabs">
          <button class="type-tab active-green" onclick="setCatType('receita',this)">Receita</button>
          <button class="type-tab" onclick="setCatType('despesa',this)">Despesa</button>
          <button class="type-tab" onclick="setCatType('ambos',this)">Ambos</button>
        </div>
        <input type="hidden" id="cat-type-val" value="receita">
      </div>
      <div class="form-group">
        <label class="form-label">Ícone</label>
        <div class="emoji-picker-row" id="emoji-picker">
          <div class="emoji-opt selected" onclick="selectEmoji(this,'💰')">💰</div>
          <div class="emoji-opt" onclick="selectEmoji(this,'🏠')">🏠</div>
          <div class="emoji-opt" onclick="selectEmoji(this,'🚗')">🚗</div>
          <div class="emoji-opt" onclick="selectEmoji(this,'🍔')">🍔</div>
          <div class="emoji-opt" onclick="selectEmoji(this,'💊')">💊</div>
          <div class="emoji-opt" onclick="selectEmoji(this,'📚')">📚</div>
          <div class="emoji-opt" onclick="selectEmoji(this,'👕')">👕</div>
          <div class="emoji-opt" onclick="selectEmoji(this,'✈️')">✈️</div>
          <div class="emoji-opt" onclick="selectEmoji(this,'🎮')">🎮</div>
          <div class="emoji-opt" onclick="selectEmoji(this,'📱')">📱</div>
          <div class="emoji-opt" onclick="selectEmoji(this,'🏋️')">🏋️</div>
          <div class="emoji-opt" onclick="selectEmoji(this,'💼')">💼</div>
        </div>
        <input type="hidden" id="cat-icon" value="💰">
      </div>
      <div class="form-group">
        <label class="form-label">Cor</label>
        <div class="color-picker-row" id="color-picker">
          <div class="color-swatch selected" style="background:#7c5cfc" onclick="selectColor(this,'#7c5cfc')"></div>
          <div class="color-swatch" style="background:#22c98a" onclick="selectColor(this,'#22c98a')"></div>
          <div class="color-swatch" style="background:#f04060" onclick="selectColor(this,'#f04060')"></div>
          <div class="color-swatch" style="background:#f5b942" onclick="selectColor(this,'#f5b942')"></div>
          <div class="color-swatch" style="background:#3a9df8" onclick="selectColor(this,'#3a9df8')"></div>
          <div class="color-swatch" style="background:#f06292" onclick="selectColor(this,'#f06292')"></div>
          <div class="color-swatch" style="background:#4dd0e1" onclick="selectColor(this,'#4dd0e1')"></div>
          <div class="color-swatch" style="background:#aed581" onclick="selectColor(this,'#aed581')"></div>
        </div>
        <input type="hidden" id="cat-color" value="#7c5cfc">
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-category')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveCategory()">Salvar</button>
    </div>
  </div>
</div>

<!-- INVOICE MODAL -->
<div class="modal-overlay" id="modal-invoice">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Nova Fatura</div>
      <button class="btn-close" onclick="closeModal('modal-invoice')">×</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="invoice-edit-id" value="">
      <div class="form-group">
        <label class="form-label">Descrição</label>
        <input class="form-input" id="invoice-desc" type="text" placeholder="Ex: Cartão Nubank, Conta de luz...">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Valor (R$)</label>
          <input class="form-input money" id="invoice-amount" type="text" placeholder="0,00">
        </div>
        <div class="form-group">
          <label class="form-label">Vencimento</label>
          <input class="form-input" id="invoice-due" type="date">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tipo de lançamento</label>
          <select class="form-input" id="invoice-recurrence" onchange="toggleInstallments('invoice')">
            <option value="none">Único</option>
            <option value="recurring">Recorrente (24x)</option>
            <option value="installment">Parcelado</option>
          </select>
        </div>
        <div class="form-group" id="invoice-installments-group" style="display:none">
          <label class="form-label">Parcelas (2-60)</label>
          <input class="form-input" id="invoice-installments" type="number" min="2" max="60" value="2">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Categoria</label>
          <select class="form-input" id="invoice-category">
            <option value="">Selecionar...</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Status</label>
          <select class="form-input" id="invoice-status">
            <option value="pendente">Pendente</option>
            <option value="pago">Pago</option>
            <option value="atrasado">Atrasado</option>
          </select>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-invoice')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveInvoice()">Salvar</button>
    </div>
  </div>
</div>


{{-- <script>
  function openModal(modalId, options = {}) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    // Exibe o overlay
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    // Configurações específicas por modal
    const setup = {
      'modal-transaction': () => {
        document.getElementById('tx-edit-id').value = '';
        document.getElementById('tx-desc').value = '';
        document.getElementById('tx-amount').value = '';
        document.getElementById('tx-date').value = new Date().toISOString().split('T')[0];
        document.getElementById('tx-note').value = '';
        document.getElementById('tx-recurrence').value = 'none';
        document.getElementById('tx-installments-group').style.display = 'none';

        if (options.type) setTxType(options.type,
          document.querySelector(`#tx-type-tabs .type-tab:nth-child(${
            { receita: 1, despesa: 2, investimento: 3 }[options.type]
          })`)
        );
      },

      'modal-investment': () => {
        document.getElementById('inv-name').value = '';
        document.getElementById('inv-amount').value = '';
        document.getElementById('inv-date').value = new Date().toISOString().split('T')[0];
        document.getElementById('inv-type').value = 'renda-fixa';
      },

      'modal-update-invest': () => {
        document.getElementById('upd-inv-val').value = '';
        document.getElementById('upd-inv-date').value = new Date().toISOString().split('T')[0];
        if (options.investmentId) {
          document.getElementById('upd-inv-select').value = options.investmentId;
        }
      },

      'modal-goal': () => {
        document.getElementById('goal-edit-id').value = '';
        document.getElementById('goal-name').value = '';
        document.getElementById('goal-target').value = '';
        document.getElementById('goal-current').value = '0';
        document.getElementById('goal-deadline').value = '';
        document.getElementById('goal-icon').value = '🎯';
      },

      'modal-goal-contribute': () => {
        document.getElementById('contrib-amount').value = '';
        if (options.goalId) {
          document.getElementById('contrib-goal-id').value = options.goalId;
        }
        if (options.description) {
          document.getElementById('contrib-goal-desc').textContent = options.description;
        }
      },

      'modal-account': () => {
        document.getElementById('acc-edit-id').value = '';
        document.getElementById('acc-name').value = '';
        document.getElementById('acc-bank').value = '';
        document.getElementById('acc-balance').value = '';
        document.getElementById('acc-type').value = 'corrente';
      },

      'modal-category': () => {
        document.getElementById('cat-edit-id').value = '';
        document.getElementById('cat-name').value = '';
        document.getElementById('cat-type-val').value = 'receita';
        document.getElementById('cat-icon').value = '💰';
        document.getElementById('cat-color').value = '#7c5cfc';
      },

      'modal-invoice': () => {
        document.getElementById('inv-edit-id').value = '';
        document.getElementById('invoice-desc').value = '';
        document.getElementById('invoice-amount').value = '';
        document.getElementById('invoice-due').value = new Date().toISOString().split('T')[0];
        document.getElementById('invoice-recurrence').value = 'none';
        document.getElementById('invoice-installments-group').style.display = 'none';
        document.getElementById('invoice-status').value = 'pendente';
      },
    };

    // Executa setup do modal específico, se existir
    if (setup[modalId]) setup[modalId]();

    // Foco no primeiro campo de texto, se houver
    const firstInput = modal.querySelector('input[type="text"], input[type="number"]');
    if (firstInput) setTimeout(() => firstInput.focus(), 50);
  }
</script> --}}