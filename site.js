(function () {
  'use strict';
  var d = document;

  /* ---- Mobile navigation ---- */
  var header = d.querySelector('.site-header');
  var toggle = d.querySelector('.nav-toggle');
  function setNav(open) {
    d.body.classList.toggle('nav-open', open);
    if (toggle) {
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    }
  }
  if (toggle) {
    toggle.addEventListener('click', function () { setNav(!d.body.classList.contains('nav-open')); });
    d.addEventListener('keydown', function (e) { if (e.key === 'Escape') setNav(false); });
    d.querySelectorAll('.main-nav a').forEach(function (a) { a.addEventListener('click', function () { setNav(false); }); });
  }

  /* ---- Header shadow + back-to-top ---- */
  var topBtn = d.querySelector('.fab-top');
  function onScroll() {
    var y = window.scrollY || d.documentElement.scrollTop;
    if (header) header.classList.toggle('scrolled', y > 8);
    if (topBtn) topBtn.hidden = y < 600;
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* ---- Reveal on scroll ---- */
  var items = d.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); } });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.05 });
    items.forEach(function (el) { io.observe(el); });
  } else {
    items.forEach(function (el) { el.classList.add('in'); });
  }

  /* ---- Services: active category + open from hash ---- */
  var catLinks = d.querySelectorAll('.cat-nav a');
  if (catLinks.length && 'IntersectionObserver' in window) {
    var map = {};
    catLinks.forEach(function (a) { map[a.getAttribute('href').slice(1)] = a; });
    var so = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) {
          catLinks.forEach(function (a) { a.classList.remove('active'); });
          var a = map[en.target.id]; if (a) a.classList.add('active');
        }
      });
    }, { rootMargin: '-20% 0px -70% 0px' });
    d.querySelectorAll('.svc-cat').forEach(function (s) { so.observe(s); });
  }
  if (location.hash.indexOf('#svc-') === 0) {
    var target = d.getElementById(location.hash.slice(1));
    if (target && target.tagName === 'DETAILS') target.open = true;
  }

  /* ---- Services: quick enquiry dialog ---- */
  var dlg = d.getElementById('enquiry-dialog');
  if (dlg && typeof dlg.showModal === 'function') {
    d.querySelectorAll('.js-enquire').forEach(function (b) {
      b.addEventListener('click', function (e) {
        e.preventDefault();
        var s = b.getAttribute('data-service') || '';
        d.getElementById('enquiry-service').value = s;
        d.getElementById('enquiry-subject').value = 'Enquiry: ' + s;
        d.getElementById('enquiry-service-label').textContent = 'Regarding: ' + s;
        dlg.showModal();
      });
    });
    dlg.querySelectorAll('.js-dialog-close').forEach(function (b) { b.addEventListener('click', function () { dlg.close(); }); });
    dlg.addEventListener('click', function (e) { if (e.target === dlg) dlg.close(); });
  } else {
    // No <dialog> support: the link falls back to the contact page.
    d.querySelectorAll('.js-enquire').forEach(function (b) { b.hidden = true; });
  }

  /* ---- Copy link ---- */
  d.querySelectorAll('.js-copy').forEach(function (b) {
    b.addEventListener('click', function () {
      var t = b.getAttribute('data-copy');
      var done = function () { var o = b.innerHTML; b.textContent = 'Link copied'; setTimeout(function () { b.innerHTML = o; }, 1800); };
      if (navigator.clipboard && navigator.clipboard.writeText) { navigator.clipboard.writeText(t).then(done); }
    });
  });

  /* ---- Form validation (client side; the server validates again) ---- */
  function fieldWrap(el) { return el.closest('.field'); }
  function setErr(el, msg) {
    var w = fieldWrap(el); if (!w) return;
    var old = w.querySelector('.field-error.js'); if (old) old.remove();
    w.classList.toggle('has-error', !!msg);
    if (msg) {
      var p = d.createElement('p'); p.className = 'field-error js'; p.setAttribute('role', 'alert'); p.textContent = msg;
      w.appendChild(p); el.setAttribute('aria-invalid', 'true');
    } else { el.removeAttribute('aria-invalid'); }
  }
  function checkField(el) {
    var v = (el.value || '').trim(), label = (fieldWrap(el) && fieldWrap(el).querySelector('label')) ? fieldWrap(el).querySelector('label').textContent.replace('*', '').trim() : 'This field';
    if (el.required && v === '') { setErr(el, 'Please fill in: ' + label + '.'); return false; }
    if (v !== '' && el.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v)) { setErr(el, 'Please enter a valid email address.'); return false; }
    if (v !== '' && el.type === 'tel' && !/^\+?[0-9][0-9\s\-]{7,17}$/.test(v)) { setErr(el, 'Please enter a valid phone number.'); return false; }
    if (el.minLength > 0 && v !== '' && v.length < el.minLength) { setErr(el, 'Please enter at least ' + el.minLength + ' characters.'); return false; }
    setErr(el, ''); return true;
  }
  d.querySelectorAll('form.form').forEach(function (form) {
    var fields = form.querySelectorAll('input:not([type=hidden]):not([type=radio]):not([type=checkbox]):not([name=website_url]), select, textarea');
    fields.forEach(function (el) {
      el.addEventListener('blur', function () { if (el.value !== '' || el.required) checkField(el); });
      el.addEventListener('input', function () { if (fieldWrap(el) && fieldWrap(el).classList.contains('has-error')) checkField(el); });
    });
    form.addEventListener('submit', function (e) {
      var ok = true, first = null;
      fields.forEach(function (el) { if (!checkField(el)) { ok = false; if (!first) first = el; } });
      if (form.getAttribute('data-form') === 'feedback' && !form.querySelector('input[name=rating]:checked')) {
        ok = false;
        var fs = form.querySelector('.rating-field');
        if (fs && !fs.querySelector('.field-error')) {
          var p = d.createElement('p'); p.className = 'field-error'; p.setAttribute('role', 'alert'); p.textContent = 'Please choose a rating from 1 to 5.'; fs.appendChild(p);
        }
        if (!first) first = form.querySelector('input[name=rating]');
      }
      if (!ok) { e.preventDefault(); if (first) first.focus(); return; }
      var btn = form.querySelector('button[type=submit][data-loading]');
      if (btn) { btn.disabled = true; btn.setAttribute('aria-busy', 'true'); btn.textContent = btn.getAttribute('data-loading'); }
    });
  });

  /* ---- Booking: time slots follow the chosen date (Saturday hours differ, Sunday closed) ---- */
  var book = d.querySelector('form[data-form=book]');
  if (book) {
    var slots = {}; try { slots = JSON.parse(book.getAttribute('data-slots')); } catch (e) {}
    var dateEl = book.querySelector('input[name=pref_date]'), timeEl = book.querySelector('select[name=pref_time]');
    var pending = timeEl.value;
    function refill() {
      if (!dateEl.value) return;
      var parts = dateEl.value.split('-'), dt = new Date(+parts[0], +parts[1] - 1, +parts[2]), dow = dt.getDay();
      if (dow === 0) { setErr(dateEl, 'The office is closed on Sundays. Please choose another day.'); timeEl.innerHTML = '<option value="">Select a date first</option>'; return; }
      setErr(dateEl, '');
      var list = dow === 6 ? slots.sat : slots.wk, keep = pending || timeEl.value;
      timeEl.innerHTML = '<option value="">Select a time</option>' + (list || []).map(function (s) {
        return '<option value="' + s[0] + '"' + (s[0] === keep ? ' selected' : '') + '>' + s[1] + '</option>';
      }).join('');
      pending = '';
    }
    dateEl.addEventListener('change', refill);
    if (dateEl.value) refill();
  }
})();
