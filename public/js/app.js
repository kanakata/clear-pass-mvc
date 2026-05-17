
(function () {
  'use strict';

  // ── Auto-dismiss flash messages ───────────────
  document.querySelectorAll('.flash').forEach(function (el) {
    setTimeout(function () {
      el.style.transition = 'opacity .4s, transform .4s';
      el.style.opacity = '0';
      el.style.transform = 'translateX(20px)';
      setTimeout(function () { el.remove(); }, 400);
    }, 5000);
  });

  // ── Mobile nav toggle ─────────────────────────
  var toggle = document.getElementById('mobileToggle');
  var navLinks = document.querySelector('.nav-links');
  if (toggle && navLinks) {
    toggle.addEventListener('click', function () {
      navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
      navLinks.style.flexDirection = 'column';
      navLinks.style.position = 'absolute';
      navLinks.style.top = '70px';
      navLinks.style.left = '0';
      navLinks.style.right = '0';
      navLinks.style.background = 'var(--green-900)';
      navLinks.style.padding = '1rem';
      navLinks.style.gap = '.25rem';
      navLinks.style.zIndex = '999';
    });
  }

  // ── Image preview on file input ───────────────
  document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
    input.addEventListener('change', function () {
      var previewId = input.getAttribute('data-preview');
      var preview = document.getElementById(previewId);
      if (!preview) return;
      var file = input.files[0];
      if (file && file.type.startsWith('image/')) {
        var reader = new FileReader();
        reader.onload = function (e) { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(file);
      }
    });
  });

  // ── Confirm on delete/dangerous actions ───────
  document.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      var msg = el.getAttribute('data-confirm') || 'Are you sure?';
      if (!confirm(msg)) { e.preventDefault(); }
    });
  });

  // ── Quantity input bounds ─────────────────────
  document.querySelectorAll('input[type="number"]').forEach(function (inp) {
    inp.addEventListener('change', function () {
      var min = parseFloat(inp.min);
      var max = parseFloat(inp.max);
      var val = parseFloat(inp.value);
      if (!isNaN(min) && val < min) inp.value = min;
      if (!isNaN(max) && val > max) inp.value = max;
    });
  });

  // ── Order form: update subtotal preview ───────
  var qtyInput  = document.getElementById('orderQty');
  var priceData = document.getElementById('productPrice');
  var totalEl   = document.getElementById('orderTotal');
  if (qtyInput && priceData && totalEl) {
    var unitPrice = parseFloat(priceData.value) || 0;
    qtyInput.addEventListener('input', function () {
      var qty   = parseInt(qtyInput.value) || 0;
      var total = qty * unitPrice;
      totalEl.textContent = 'KES ' + total.toLocaleString('en-KE', { minimumFractionDigits: 2 });
    });
  }

  // ── Message: compose modal ────────────────────
  var composeBtn   = document.getElementById('composeBtn');
  var composeModal = document.getElementById('composeModal');
  var modalClose   = document.getElementById('modalClose');
  if (composeBtn && composeModal) {
    composeBtn.addEventListener('click', function () {
      composeModal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    });
    modalClose && modalClose.addEventListener('click', closeModal);
    composeModal.addEventListener('click', function (e) {
      if (e.target === composeModal) closeModal();
    });
  }
  function closeModal() {
    if (composeModal) { composeModal.style.display = 'none'; document.body.style.overflow = ''; }
  }

  // ── Fade-up observer ─────────────────────────
  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.style.animationPlayState = 'running';
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-up').forEach(function (el) {
      el.style.animationPlayState = 'paused';
      observer.observe(el);
    });
  }

  // ── Status form selects: auto-colour ─────────
  document.querySelectorAll('select[name="status"]').forEach(function (sel) {
    function colour() {
      sel.className = 'form-control status-select status-' + sel.value;
    }
    sel.addEventListener('change', colour);
    colour();
  });

})();
