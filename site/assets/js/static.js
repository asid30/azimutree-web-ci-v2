document.querySelectorAll('[data-year]').forEach(el => { el.textContent = new Date().getFullYear(); });
const menuButton = document.getElementById('menuToggle');
const menuPanel = document.getElementById('menu');
if (menuButton && menuPanel) {
  new MutationObserver(() => menuButton.setAttribute('aria-expanded', String(!menuPanel.classList.contains('hidden')))).observe(menuPanel, { attributes: true, attributeFilter: ['class'] });
}
// Easter egg only: this browser-side gate is part of the game, not authentication.
const secretForm = document.getElementById('secretForm');
const quiz = document.querySelector('[data-easteregg-quiz]');
function isUnlocked() { try { return sessionStorage.getItem('azimutree-secret') === 'yes'; } catch { return false; } }
if (secretForm) {
  if (isUnlocked()) location.replace('../mybestie/');
  secretForm.addEventListener('submit', event => {
    event.preventDefault();
    if (new FormData(secretForm).get('password').trim() === 'chleoryn') {
      try { sessionStorage.setItem('azimutree-secret', 'yes'); location.assign('../mybestie/'); }
      catch { const error = document.getElementById('secretError'); error.textContent = 'Aktifkan penyimpanan browser untuk melanjutkan.'; error.hidden = false; }
    } else { document.getElementById('secretError').hidden = false; }
  });
}
if (quiz) { if (isUnlocked()) quiz.hidden = false; else location.replace('../secret/'); }
