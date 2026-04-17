<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin – Portal Alumni SMAN 5 Samarinda</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../../css/admin.css">
</head>
<body class="admin-page" id="adminPage">

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar" id="sidebar">

    <div class="sidebar-header">
      <!-- Brand -->
      <div class="sidebar-brand">
        <div class="brand-icon-sm">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="9" rx="1.5"/>
            <rect x="14" y="3" width="7" height="5" rx="1.5"/>
            <rect x="14" y="12" width="7" height="9" rx="1.5"/>
            <rect x="3" y="16" width="7" height="5" rx="1.5"/>
          </svg>
        </div>
        <span class="brand-label">Admin</span>
      </div>

      <div style="display:flex;align-items:center;gap:6px;">
        <!-- Collapse button (visible when expanded) -->
        <button class="sidebar-toggle" id="sidebarToggle" title="Perkecil sidebar" aria-label="Perkecil">
          <i class="fa-solid fa-angles-left"></i>
        </button>
        <!-- Expand button (visible when collapsed) -->
        <button class="sidebar-expand" id="sidebarExpand" title="Perbesar sidebar" aria-label="Perbesar">
          <i class="fa-solid fa-angles-right"></i>
        </button>
        <!-- Mobile close button -->
        <button class="sidebar-close" id="sidebarClose" aria-label="Tutup">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
    </div>

    <nav class="sidebar-nav">

      <!-- Dashboard -->
      <a href="dashboard.php" class="nav-item active" id="nav-dashboard" data-tooltip="Dashboard">
        <span class="nav-icon">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="9" rx="1.5"/>
            <rect x="14" y="3" width="7" height="5" rx="1.5"/>
            <rect x="14" y="12" width="7" height="9" rx="1.5"/>
            <rect x="3" y="16" width="7" height="5" rx="1.5"/>
          </svg>
        </span>
        <span class="nav-text">Dashboard</span>
      </a>

      <!-- Data Alumni -->
      <a href="alumni.php" class="nav-item" id="nav-alumni" data-tooltip="Data Alumni">
        <span class="nav-icon">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2"/>
            <path d="M3 9h18M3 15h18M9 3v18"/>
          </svg>
        </span>
        <span class="nav-text">Data Alumni</span>
      </a>

      <!-- Laporan Masuk -->
      <a href="laporan.php" class="nav-item" id="nav-laporan" data-tooltip="Laporan Masuk">
        <span class="nav-icon">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
          </svg>
        </span>
        <span class="nav-text">Laporan Masuk</span>
        <span class="badge-dot">3</span>
      </a>

      <!-- Universitas -->
      <a href="universitas.php" class="nav-item" id="nav-univ" data-tooltip="Universitas">
        <span class="nav-icon">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 21h18M9 21V7l-6 3 6-9 6 9-6-3v14M15 21V11h6v10"/>
          </svg>
        </span>
        <span class="nav-text">Universitas</span>
      </a>

      <p class="nav-label">Konten</p>

      <!-- Testimoni -->
      <a href="testimoni.php" class="nav-item" id="nav-testi" data-tooltip="Testimoni">
        <span class="nav-icon">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
          </svg>
        </span>
        <span class="nav-text">Testimoni</span>
      </a>

      <!-- Pengaturan -->
      <a href="pengaturan.php" class="nav-item" id="nav-settings" data-tooltip="Pengaturan">
        <span class="nav-icon">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/>
          </svg>
        </span>
        <span class="nav-text">Pengaturan</span>
      </a>

      <!-- Keluar -->
      <a href="logout.php" class="nav-item nav-logout" data-tooltip="Keluar">
        <span class="nav-icon">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
          </svg>
        </span>
        <span class="nav-text">Keluar</span>
      </a>

    </nav>
  </aside>

  <!-- Overlay (mobile) -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- ===== MAIN ===== -->
  <div class="main-wrap" id="mainWrap">

    <!-- Topbar -->
    <header class="topbar">
      <div class="topbar-left">
        <!-- Hamburger (mobile) -->
        <button class="hamburger" id="hamburger" aria-label="Menu">
          <i class="fa-solid fa-bars"></i>
        </button>
        <div class="breadcrumb">
          <span class="breadcrumb-home"><i class="fa-solid fa-house"></i></span>
          <span class="sep"><i class="fa-solid fa-chevron-right"></i></span>
          <span class="breadcrumb-active">Dashboard</span>
        </div>
      </div>

      <div class="topbar-right">
        <!-- Search -->
        <div class="topbar-search">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" placeholder="Cari alumni, universitas...">
        </div>
        <!-- Notifikasi -->
        <button class="topbar-icon" aria-label="Notifikasi">
          <i class="fa-regular fa-bell"></i>
          <span class="notif-dot"></span>
        </button>
        <!-- Avatar -->
        <div class="admin-avatar" id="avatarBtn">
          <div class="avatar-circle">A</div>
          <div class="avatar-info">
            <p class="avatar-name">Admin</p>
            <p class="avatar-role">Super Admin</p>
          </div>
          <i class="fa-solid fa-chevron-down av-arrow"></i>
          <div class="avatar-dropdown" id="avatarDropdown">
            <a href="profil.php"><i class="fa-regular fa-user"></i> Profil Saya</a>
            <a href="pengaturan.php"><i class="fa-solid fa-gear"></i> Pengaturan</a>
            <hr>
            <a href="logout.php" class="logout-link"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
          </div>
        </div>
      </div>
    </header>

    <!-- Content -->
    <main class="content">

      <!-- Page Title -->
      <div class="page-title">
        <div>
          <h2>Dashboard</h2>
          <p>Selamat datang kembali, <strong>Admin</strong> 👋</p>
        </div>
        <a href="alumni.php?action=tambah" class="btn-primary-sm">
          <i class="fa-solid fa-plus"></i> Tambah Alumni
        </a>
      </div>

      <!-- Stats Cards -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon-wrap blue"><i class="fa-solid fa-user-graduate"></i></div>
          <div class="stat-body">
            <p class="stat-label">Total Alumni</p>
            <h3 class="stat-value">1.248</h3>
            <span class="stat-change up"><i class="fa-solid fa-arrow-trend-up"></i> +24 bulan ini</span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon-wrap green"><i class="fa-solid fa-building-columns"></i></div>
          <div class="stat-body">
            <p class="stat-label">Universitas Terdaftar</p>
            <h3 class="stat-value">87</h3>
            <span class="stat-change up"><i class="fa-solid fa-arrow-trend-up"></i> +5 baru</span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon-wrap yellow"><i class="fa-solid fa-inbox"></i></div>
          <div class="stat-body">
            <p class="stat-label">Laporan Pending</p>
            <h3 class="stat-value">3</h3>
            <span class="stat-change down"><i class="fa-solid fa-circle-exclamation"></i> Perlu verifikasi</span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon-wrap purple"><i class="fa-solid fa-eye"></i></div>
          <div class="stat-body">
            <p class="stat-label">Pengunjung Hari Ini</p>
            <h3 class="stat-value">342</h3>
            <span class="stat-change up"><i class="fa-solid fa-arrow-trend-up"></i> +18%</span>
          </div>
        </div>
      </div>

      <!-- Content Grid -->
      <div class="content-grid">

        <!-- Table Card -->
        <div class="card">
          <div class="card-header">
            <h3><i class="fa-solid fa-clock-rotate-left"></i> Alumni Terbaru</h3>
            <a href="alumni.php" class="see-all">Lihat Semua <i class="fa-solid fa-arrow-right"></i></a>
          </div>
          <div class="table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Nama</th><th>Angkatan</th><th>Universitas</th><th>Jalur</th><th>Status</th><th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><div class="user-cell"><div class="user-ava" style="background:#e0f2fe;color:#0369a1;">B</div><span>Budi Santoso</span></div></td>
                  <td>2023</td><td>ITS Surabaya</td>
                  <td><span class="pill pill-blue">SNBT</span></td>
                  <td><span class="pill pill-green">Terverifikasi</span></td>
                  <td><div class="action-btns"><button class="btn-icon blue-icon" title="Edit"><i class="fa-solid fa-pen"></i></button><button class="btn-icon red-icon" title="Hapus"><i class="fa-solid fa-trash"></i></button></div></td>
                </tr>
                <tr>
                  <td><div class="user-cell"><div class="user-ava" style="background:#dcfce7;color:#166534;">S</div><span>Siti Nurbaya</span></div></td>
                  <td>2023</td><td>UNMUL</td>
                  <td><span class="pill pill-green">SNBP</span></td>
                  <td><span class="pill pill-green">Terverifikasi</span></td>
                  <td><div class="action-btns"><button class="btn-icon blue-icon" title="Edit"><i class="fa-solid fa-pen"></i></button><button class="btn-icon red-icon" title="Hapus"><i class="fa-solid fa-trash"></i></button></div></td>
                </tr>
                <tr>
                  <td><div class="user-cell"><div class="user-ava" style="background:#fef9c3;color:#92400e;">R</div><span>Riko Wijaya</span></div></td>
                  <td>2023</td><td>AKPOL</td>
                  <td><span class="pill pill-yellow">Kedinasan</span></td>
                  <td><span class="pill pill-green">Terverifikasi</span></td>
                  <td><div class="action-btns"><button class="btn-icon blue-icon" title="Edit"><i class="fa-solid fa-pen"></i></button><button class="btn-icon red-icon" title="Hapus"><i class="fa-solid fa-trash"></i></button></div></td>
                </tr>
                <tr>
                  <td><div class="user-cell"><div class="user-ava" style="background:#ede9fe;color:#5b21b6;">D</div><span>Dian Pertiwi</span></div></td>
                  <td>2024</td><td>UGM</td>
                  <td><span class="pill pill-blue">SNBT</span></td>
                  <td><span class="pill pill-yellow">Menunggu</span></td>
                  <td><div class="action-btns"><button class="btn-icon blue-icon" title="Edit"><i class="fa-solid fa-pen"></i></button><button class="btn-icon red-icon" title="Hapus"><i class="fa-solid fa-trash"></i></button></div></td>
                </tr>
                <tr>
                  <td><div class="user-cell"><div class="user-ava" style="background:#fee2e2;color:#991b1b;">A</div><span>Andi Pratama</span></div></td>
                  <td>2024</td><td>ITB</td>
                  <td><span class="pill pill-blue">SNBT</span></td>
                  <td><span class="pill pill-yellow">Menunggu</span></td>
                  <td><div class="action-btns"><button class="btn-icon blue-icon" title="Edit"><i class="fa-solid fa-pen"></i></button><button class="btn-icon red-icon" title="Hapus"><i class="fa-solid fa-trash"></i></button></div></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Right side cards -->
        <div class="side-cards">

          <!-- Laporan Masuk -->
          <div class="card">
            <div class="card-header">
              <h3><i class="fa-solid fa-inbox"></i> Laporan Masuk</h3>
              <span class="badge-pill">3 Baru</span>
            </div>
            <div class="report-list">
              <div class="report-item">
                <div class="report-av" style="background:#e0f2fe;color:#0369a1;">F</div>
                <div class="report-info"><strong>Fajar Nugroho</strong><span>Universitas Hasanuddin</span></div>
                <div class="report-actions"><button class="btn-xs green-btn" title="Setujui"><i class="fa-solid fa-check"></i></button><button class="btn-xs red-btn" title="Tolak"><i class="fa-solid fa-xmark"></i></button></div>
              </div>
              <div class="report-item">
                <div class="report-av" style="background:#dcfce7;color:#166534;">N</div>
                <div class="report-info"><strong>Nadia Rahayu</strong><span>Universitas Indonesia</span></div>
                <div class="report-actions"><button class="btn-xs green-btn" title="Setujui"><i class="fa-solid fa-check"></i></button><button class="btn-xs red-btn" title="Tolak"><i class="fa-solid fa-xmark"></i></button></div>
              </div>
              <div class="report-item">
                <div class="report-av" style="background:#ede9fe;color:#5b21b6;">M</div>
                <div class="report-info"><strong>M. Rizky</strong><span>ITK Balikpapan</span></div>
                <div class="report-actions"><button class="btn-xs green-btn" title="Setujui"><i class="fa-solid fa-check"></i></button><button class="btn-xs red-btn" title="Tolak"><i class="fa-solid fa-xmark"></i></button></div>
              </div>
            </div>
          </div>

          <!-- Distribusi Jalur -->
          <div class="card">
            <div class="card-header">
              <h3><i class="fa-solid fa-chart-pie"></i> Distribusi Jalur</h3>
            </div>
            <div class="donut-wrap">
              <svg viewBox="0 0 120 120" class="donut-chart">
                <circle cx="60" cy="60" r="44" fill="none" stroke="#e4eaf0" stroke-width="13"/>
                <circle cx="60" cy="60" r="44" fill="none" stroke="#3b6cf4" stroke-width="13"
                  stroke-dasharray="124 153" stroke-dashoffset="0" stroke-linecap="round"/>
                <circle cx="60" cy="60" r="44" fill="none" stroke="#22c55e" stroke-width="13"
                  stroke-dasharray="96 277" stroke-dashoffset="-124" stroke-linecap="round"/>
                <circle cx="60" cy="60" r="44" fill="none" stroke="#f59e0b" stroke-width="13"
                  stroke-dasharray="55 221" stroke-dashoffset="-220" stroke-linecap="round"/>
                <text x="60" y="55" text-anchor="middle" fill="#1a2636" font-size="11" font-weight="700" font-family="Plus Jakarta Sans">1.248</text>
                <text x="60" y="68" text-anchor="middle" fill="#8898aa" font-size="7" font-family="Plus Jakarta Sans">Alumni</text>
              </svg>
            </div>
            <div class="donut-legend">
              <div class="legend-item"><span class="lg-dot" style="background:#3b6cf4;"></span><span>SNBT</span><strong>45%</strong></div>
              <div class="legend-item"><span class="lg-dot" style="background:#22c55e;"></span><span>SNBP</span><strong>35%</strong></div>
              <div class="legend-item"><span class="lg-dot" style="background:#f59e0b;"></span><span>Mandiri</span><strong>20%</strong></div>
            </div>
          </div>

        </div><!-- /side-cards -->
      </div><!-- /content-grid -->

    </main>
  </div><!-- /main-wrap -->

  <script src="../../js/admin.js"></script>
</body>
</html>
