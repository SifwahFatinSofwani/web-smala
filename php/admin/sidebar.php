<!-- ============================================================
     SIDEBAR INCLUDE – dipakai semua halaman admin
     php/admin/sidebar.php
     ============================================================ -->
<?php
// Deteksi halaman aktif
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
function navActive(string $page, string $current): string {
    return $page === $current ? ' active' : '';
}
?>
<aside class="sidebar" id="sidebar">

  <div class="sidebar-header">
    <div class="sidebar-brand">
      <div class="brand-icon-sm">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="9" rx="1.5"/>
          <rect x="14" y="3" width="7" height="5" rx="1.5"/>
          <rect x="14" y="12" width="7" height="9" rx="1.5"/>
          <rect x="3" y="16" width="7" height="5" rx="1.5"/>
        </svg>
      </div>
      <span class="brand-label">Admin Panel</span>
    </div>
    <div style="display:flex;align-items:center;gap:6px;">
      <button class="sidebar-toggle" id="sidebarToggle" title="Perkecil" aria-label="Perkecil">
        <i class="fa-solid fa-angles-left"></i>
      </button>
      <button class="sidebar-expand" id="sidebarExpand" title="Perbesar" aria-label="Perbesar">
        <i class="fa-solid fa-angles-right"></i>
      </button>
      <button class="sidebar-close" id="sidebarClose" aria-label="Tutup">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
  </div>

  <nav class="sidebar-nav">

    <!-- Dashboard -->
    <a href="dashboard.php" class="nav-item<?= navActive('dashboard',$currentPage) ?>" data-tooltip="Dashboard">
      <span class="nav-icon">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/>
          <rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>
        </svg>
      </span>
      <span class="nav-text">Dashboard</span>
    </a>

    <!-- Data Alumni -->
    <a href="alumni.php" class="nav-item<?= navActive('alumni',$currentPage) ?>" data-tooltip="Data Alumni">
      <span class="nav-icon">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="18" height="18" rx="2"/>
          <path d="M3 9h18M3 15h18M9 3v18"/>
        </svg>
      </span>
      <span class="nav-text">Data Alumni</span>
    </a>

    <!-- Laporan Masuk -->
    <?php
    // Hitung pending (cek dari DB jika tersedia)
    $pendingCount = 0;
    try {
        $pdb = getDB();
        $pendingCount = $pdb->query("SELECT COUNT(*) FROM laporan_masuk WHERE status='pending'")->fetchColumn();
    } catch (Exception $e) {}
    ?>
    <a href="laporan.php" class="nav-item<?= navActive('laporan',$currentPage) ?>" data-tooltip="Laporan Masuk">
      <span class="nav-icon">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
        </svg>
      </span>
      <span class="nav-text">Laporan Masuk</span>
      <?php if ($pendingCount > 0): ?>
      <span class="badge-dot"><?= $pendingCount ?></span>
      <?php endif; ?>
    </a>

    <!-- Universitas -->
    <a href="universitas.php" class="nav-item<?= navActive('universitas',$currentPage) ?>" data-tooltip="Universitas">
      <span class="nav-icon">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 21h18M9 21V7l-6 3 6-9 6 9-6-3v14M15 21V11h6v10"/>
        </svg>
      </span>
      <span class="nav-text">Universitas</span>
    </a>

    <!-- NISN Manager -->
    <a href="nisn.php" class="nav-item<?= navActive('nisn',$currentPage) ?>" data-tooltip="Kelola NISN">
      <span class="nav-icon">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
          <rect x="2" y="5" width="20" height="14" rx="2"/>
          <path d="M2 10h20"/>
        </svg>
      </span>
      <span class="nav-text">Kelola NISN</span>
    </a>

    <p class="nav-label">Konten</p>

    <!-- Testimoni -->
    <a href="testimoni.php" class="nav-item<?= navActive('testimoni',$currentPage) ?>" data-tooltip="Testimoni">
      <span class="nav-icon">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
        </svg>
      </span>
      <span class="nav-text">Testimoni</span>
    </a>

    <!-- Pengaturan -->
    <a href="pengaturan.php" class="nav-item<?= navActive('pengaturan',$currentPage) ?>" data-tooltip="Pengaturan">
      <span class="nav-icon">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="3"/>
          <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
        </svg>
      </span>
      <span class="nav-text">Pengaturan</span>
    </a>

    <!-- Keluar -->
    <a href="auth.php?action=logout" class="nav-item nav-logout" data-tooltip="Keluar">
      <span class="nav-icon">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
        </svg>
      </span>
      <span class="nav-text">Keluar</span>
    </a>

  </nav>
</aside>
