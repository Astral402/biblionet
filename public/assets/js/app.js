document.addEventListener('submit', function (e) {
  var message = e.target.getAttribute('data-confirm');
  if (message && !window.confirm(message)) {
    e.preventDefault();
  }
});

(function () {
  var button = document.getElementById('menuBtn');
  var backdrop = document.getElementById('backdrop');
  if (!button) {
    return;
  }
  var close = function () { document.body.classList.remove('menu-open'); };
  button.addEventListener('click', function () { document.body.classList.toggle('menu-open'); });
  if (backdrop) {
    backdrop.addEventListener('click', close);
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      close();
    }
  });
  document.querySelectorAll('.side-link').forEach(function (link) {
    link.addEventListener('click', close);
  });
})();

document.addEventListener('click', function (e) {
  if (e.target.closest('[data-print]')) {
    window.print();
  }
});
