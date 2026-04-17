/* ======================================================
   ADMIN.JS – Portal Alumni SMAN 5 Samarinda
   ====================================================== */

const STORAGE_KEY = 'sma5_sidebar_collapsed';

// ── 1. SIDEBAR COLLAPSE / EXPAND ─────────────────────────
(function() {
  const sidebar    = document.getElementById('sidebar');
  const adminPage  = document.getElementById('adminPage');
  const toggleBtn  = document.getElementById('sidebarToggle');
  const expandBtn  = document.getElementById('sidebarExpand');
  if (!sidebar || !adminPage) return;

  // Restore state from localStorage
  if (localStorage.getItem(STORAGE_KEY) === 'true') {
    sidebar.classList.add('collapsed');
    adminPage.classList.add('sidebar-collapsed');
  }

  function collapse() {
    sidebar.classList.add('collapsed');
    adminPage.classList.add('sidebar-collapsed');
    localStorage.setItem(STORAGE_KEY, 'true');
  }

  function expand() {
    sidebar.classList.remove('collapsed');
    adminPage.classList.remove('sidebar-collapsed');
    localStorage.setItem(STORAGE_KEY, 'false');
  }

  if (toggleBtn) toggleBtn.addEventListener('click', collapse);
  if (expandBtn) expandBtn.addEventListener('click', expand);
})();


// ── 2. MOBILE SIDEBAR OPEN / CLOSE ──────────────────────
(function() {
  const sidebar   = document.getElementById('sidebar');
  const overlay   = document.getElementById('sidebarOverlay');
  const hamburger = document.getElementById('hamburger');
  const closeBtn  = document.getElementById('sidebarClose');
  if (!sidebar) return;

  function openSidebar() {
    sidebar.classList.add('open');
    overlay && overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar() {
    sidebar.classList.remove('open');
    overlay && overlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  if (hamburger) hamburger.addEventListener('click', openSidebar);
  if (closeBtn)  closeBtn.addEventListener('click', closeSidebar);
  if (overlay)   overlay.addEventListener('click', closeSidebar);

  // Close mobile menu on nav link click
  sidebar.querySelectorAll('.nav-item').forEach(function(link) {
    link.addEventListener('click', function() {
      if (window.innerWidth <= 860) closeSidebar();
    });
  });
})();


// ── 3. AVATAR DROPDOWN ──────────────────────────────────
(function() {
  const btn      = document.getElementById('avatarBtn');
  const dropdown = document.getElementById('avatarDropdown');
  if (!btn || !dropdown) return;

  btn.addEventListener('click', function(e) {
    e.stopPropagation();
    dropdown.classList.toggle('open');
  });
  document.addEventListener('click', function(e) {
    if (!btn.contains(e.target)) dropdown.classList.remove('open');
  });
})();


// ── 4. ACTIVE NAV ITEM (auto-detect current page) ───────
(function() {
  const page = window.location.pathname.split('/').pop() || 'dashboard.php';
  document.querySelectorAll('.nav-item[id]').forEach(function(el) {
    const href = el.getAttribute('href') || '';
    if (href === page) {
      document.querySelectorAll('.nav-item').forEach(function(n) {
        n.classList.remove('active');
      });
      el.classList.add('active');
    }
  });
})();


// ── 5. TABLE: CONFIRM DELETE ─────────────────────────────
document.querySelectorAll('.red-icon').forEach(function(btn) {
  btn.addEventListener('click', function() {
    const row  = btn.closest('tr');
    const name = row ? (row.querySelector('.user-cell span')?.textContent ?? 'data ini') : 'data ini';
    if (confirm('Yakin ingin menghapus data ' + name + '?')) {
      row.style.transition = 'opacity .3s, transform .3s';
      row.style.opacity    = '0';
      row.style.transform  = 'translateX(-10px)';
      setTimeout(function() { row.remove(); }, 300);
    }
  });
});


// ── 6. REPORT: APPROVE / REJECT ──────────────────────────
document.querySelectorAll('.green-btn').forEach(function(btn) {
  btn.addEventListener('click', function() {
    const item = btn.closest('.report-item');
    const name = item?.querySelector('strong')?.textContent ?? '';
    animateOut(item, 'right', function() {
      item.remove();
      showToast('✅ Laporan ' + name + ' disetujui!', 'green');
    });
  });
});

document.querySelectorAll('.red-btn').forEach(function(btn) {
  btn.addEventListener('click', function() {
    const item = btn.closest('.report-item');
    const name = item?.querySelector('strong')?.textContent ?? '';
    animateOut(item, 'left', function() {
      item.remove();
      showToast('❌ Laporan ' + name + ' ditolak.', 'red');
    });
  });
});

function animateOut(el, dir, cb) {
  if (!el) return;
  el.style.transition  = 'opacity .25s, transform .25s';
  el.style.opacity     = '0';
  el.style.transform   = dir === 'right' ? 'translateX(18px)' : 'translateX(-18px)';
  setTimeout(cb, 260);
}


// ── 7. TOAST NOTIFICATION ────────────────────────────────
function showToast(message, type) {
  const existing = document.querySelector('.toast-notif');
  if (existing) existing.remove();

  const toast = document.createElement('div');
  toast.className = 'toast-notif';
  toast.textContent = message;

  const colors = {
    green:  { bg: '#16a34a', shadow: 'rgba(22,163,74,.25)' },
    red:    { bg: '#dc2626', shadow: 'rgba(220,38,38,.25)' },
    blue:   { bg: '#3b6cf4', shadow: 'rgba(59,108,244,.25)' },
  };
  const c = colors[type] || colors.blue;

  Object.assign(toast.style, {
    position:    'fixed',
    bottom:      '24px',
    right:       '24px',
    background:  c.bg,
    color:       'white',
    padding:     '12px 20px',
    borderRadius:'10px',
    fontSize:    '13px',
    fontWeight:  '600',
    fontFamily:  "'Plus Jakarta Sans', sans-serif",
    boxShadow:   '0 4px 20px ' + c.shadow,
    zIndex:      '9999',
    transform:   'translateY(12px)',
    opacity:     '0',
    transition:  'transform .3s, opacity .3s',
    maxWidth:    '300px',
    lineHeight:  '1.5',
  });
  document.body.appendChild(toast);

  requestAnimationFrame(function() {
    requestAnimationFrame(function() {
      toast.style.transform = 'translateY(0)';
      toast.style.opacity   = '1';
    });
  });

  setTimeout(function() {
    toast.style.opacity   = '0';
    toast.style.transform = 'translateY(12px)';
    setTimeout(function() { toast.remove(); }, 320);
  }, 3200);
}
