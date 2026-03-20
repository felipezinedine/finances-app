// Inicializa a aplicação web (carrega estado e renderiza o layout)
// Deve ser carregado APÓS state.js e finances-init.js

document.addEventListener('DOMContentLoaded', () => {
  loadState();
  showApp();
});
