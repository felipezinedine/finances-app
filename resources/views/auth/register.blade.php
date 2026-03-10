<div id="register-page" class="auth-page">
  <div class="auth-left">
    <div class="auth-logo">
      <div class="auth-logo-icon">💸</div>
      FinFlow
    </div>
    <h1 class="auth-headline">Comece a controlar<br>seu <span>dinheiro</span></h1>
    <p class="auth-sub">Crie sua conta gratuitamente e tenha visão completa das suas finanças em minutos.</p>
    <div class="auth-features">
      <div class="auth-feat"><div class="auth-feat-icon">✅</div>Controle de receitas e despesas</div>
      <div class="auth-feat"><div class="auth-feat-icon">📈</div>Gestão de investimentos</div>
      <div class="auth-feat"><div class="auth-feat-icon">🎯</div>Metas e objetivos financeiros</div>
      <div class="auth-feat"><div class="auth-feat-icon">🏦</div>Múltiplas contas bancárias</div>
    </div>
  </div>
  <form action="">
    <div class="auth-right">
      <h2 class="auth-title">Criar conta</h2>
      <p class="auth-desc">Preencha as informações abaixo para começar</p>
      <div id="reg-alert" style="display:none"></div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Nome</label>
          <input class="form-input" id="reg-name" type="text" placeholder="João">
        </div>
        <div class="form-group">
          <label class="form-label">Sobrenome</label>
          <input class="form-input" id="reg-lastname" type="text" placeholder="Silva">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">E-mail</label>
        <input class="form-input" id="reg-email" type="email" placeholder="joao@email.com">
      </div>
      <div class="form-group">
        <label class="form-label">Senha</label>
        <input class="form-input" id="reg-pass" type="password" placeholder="••••••••">
      </div>
      <div class="form-group">
        <label class="form-label">Saldo inicial</label>
        <input class="form-input" id="reg-balance" type="number" placeholder="0,00" min="0">
      </div>
      <button class="btn btn-primary btn-full" onclick="register()">Criar conta grátis</button>
      <div class="auth-link-row">Já tem conta? <a class="auth-link" onclick="goTo('login-page')">Entrar</a></div>
    </div>
  </form>
</div>