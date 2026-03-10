<div id="login-page" class="auth-page">
  <div class="auth-left">
    <div class="auth-logo">
      <div class="auth-logo-icon">💸</div>
      FinFlow
    </div>
    <h1 class="auth-headline">Bem-vindo<br>de <span>volta</span></h1>
    <p class="auth-sub">Continue de onde parou. Suas finanças estão esperando por você.</p>
    <div class="auth-features">
      <div class="auth-feat"><div class="auth-feat-icon">🔒</div>Seus dados são privados e seguros</div>
      <div class="auth-feat"><div class="auth-feat-icon">⚡</div>Acesso instantâneo ao dashboard</div>
      <div class="auth-feat"><div class="auth-feat-icon">📱</div>Funciona em qualquer dispositivo</div>
    </div>
  </div>
  <div class="auth-right">
    <h2 class="auth-title">Entrar na conta</h2>
    <p class="auth-desc">Informe seu e-mail e senha para continuar</p>
    <div id="login-alert" style="display:none"></div>
    <div class="form-group">
      <label class="form-label">E-mail</label>
      <input class="form-input" id="login-email" type="email" placeholder="joao@email.com">
    </div>
    <div class="form-group">
      <label class="form-label">Senha</label>
      <input class="form-input" id="login-pass" type="password" placeholder="••••••••">
    </div>
    <button class="btn btn-primary btn-full" onclick="login()">Entrar</button>
    <div class="auth-link-row">Não tem conta? <a class="auth-link" onclick="goTo('register-page')">Criar agora</a></div>
    <div class="auth-link-row" style="margin-top:8px"><a class="auth-link" onclick="demoLogin()">Entrar com conta demo →</a></div>
  </div>
</div>