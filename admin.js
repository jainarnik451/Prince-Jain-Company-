(function () {
  'use strict';
  var d = document;
  var t = d.querySelector('.side-toggle');
  if (t) t.addEventListener('click', function () {
    var o = d.body.classList.toggle('side-open'); t.setAttribute('aria-expanded', o ? 'true' : 'false');
  });
  d.addEventListener('keydown', function (e) { if (e.key === 'Escape') d.body.classList.remove('side-open'); });
  d.querySelectorAll('form[data-confirm]').forEach(function (f) {
    f.addEventListener('submit', function (e) { if (!window.confirm(f.getAttribute('data-confirm'))) e.preventDefault(); });
  });
  d.querySelectorAll('form[data-confirm-delete]').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      var s = e.submitter; if (s && s.value === 'delete' && !window.confirm('Delete this feedback permanently?')) e.preventDefault();
    });
  });
  d.querySelectorAll('form').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      if (e.defaultPrevented) return;
      var s = e.submitter; if (s && s.type === 'submit') { setTimeout(function () { s.disabled = true; }, 0); }
    });
  });
})();
