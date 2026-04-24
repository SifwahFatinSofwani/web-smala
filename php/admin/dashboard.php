<?php
require_once __DIR__ . '/db.php';
requireAdmin();

$db = getDB();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin ΓÇô Portal Alumni SMAN 5 Samarinda</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../../css/admin.css">
</head>
<body class="admin-page" id="adminPage">

  <!-- ===== SIDEBAR ===== -->
  <?php include 'sidebar.php'; ?>

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
          <p>Selamat datang kembali, <strong>Admin</strong> ≡ƒæï</p>
        </div>
        <a href="alumni.php?action=tambah" class="btn-primary-sm">
          <i class="fa-solid fa-plus"></i> Tambah Alumni
        </a>
      </div>

            <!-- Stats Cards -->
      <div class="stats-grid">
        
        <div class="stat-card" style="background: var(--sb-bg); border: 1px solid var(--sb-border);">
          <div class="stat-top">
            <div class="stat-titles">
              <p class="stat-label" style="text-transform:uppercase; font-size:11px; letter-spacing:0.5px;">Total Alumni</p>
              <h3 class="stat-value" style="font-size:24px; margin:0;">1.248</h3>
            </div>
          </div>
          <span class="stat-change up" style="margin-left: 0;">+24 bulan ini</span>
        </div>

        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-titles">
              <p class="stat-label" style="text-transform:uppercase; font-size:11px; letter-spacing:0.5px;">Universitas Terdaftar</p>
              <h3 class="stat-value" style="font-size:24px; margin:0;">87</h3>
            </div>
          </div>
          <span class="stat-change up" style="margin-left: 0;">+5 baru</span>
        </div>

        <div class="stat-card" style="background: var(--sb-bg); border: 1px solid var(--sb-border);">
          <div class="stat-top">
            <div class="stat-titles">
              <p class="stat-label" style="text-transform:uppercase; font-size:11px; letter-spacing:0.5px;">Laporan Pending</p>
              <h3 class="stat-value" style="font-size:24px; margin:0;">3</h3>
            </div>
          </div>
          <span class="stat-change down" style="margin-left: 0;">Perlu verifikasi</span>
        </div>

        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-titles">
              <p class="stat-label" style="text-transform:uppercase; font-size:11px; letter-spacing:0.5px;">Pengunjung Hari Ini</p>
              <h3 class="stat-value" style="font-size:24px; margin:0;">342</h3>
            </div>
          </div>
          <span class="stat-change up" style="margin-left: 0;">+18%</span>
        </div>

      </div>

      <!-- Content Grid -->
      <div class="content-grid">

        <!-- Table Card -->
        <div class="card">
          <div class="card-header">
            <h3>Alumni Terbaru</h3>
            <a href="alumni.php" class="see-all">Lihat Semua <i class="fa-solid fa-arrow-right"></i></a>
          </div>
          <div class="table-wrap">
            <table class="data-table dash-table">
              <thead>
                <tr>
                  <th>Nama</th><th>Angkatan</th><th>Universitas</th><th>Jalur</th><th>Status</th><th></th>
                </tr>
              </thead>
              <tbody>
<tr>
                  <td><div class="user-cell"><div class="user-ava" style="background:#e8f0f5;color:#1a2636;">B</div><span>Budi Santoso</span></div></td>
                  <td>2023</td><td>ITS Surabaya</td>
                  <td><span class="pill pill-blue">SNBT</span></td>
                  <td><span class="pill pill-green">Terverifikasi</span></td>
                  <td><div class="action-btns"><button class="btn-icon blue-icon" title="Edit"><i class="fa-solid fa-pen"></i></button><button class="btn-icon red-icon" title="Hapus"><i class="fa-solid fa-trash"></i></button></div></td>
                </tr>
<tr>
                  <td><div class="user-cell"><div class="user-ava" style="background:#e8f0f5;color:#1a2636;">S</div><span>Siti Nurbaya</span></div></td>
                  <td>2023</td><td>UNMUL</td>
                  <td><span class="pill pill-green">SNBP</span></td>
                  <td><span class="pill pill-green">Terverifikasi</span></td>
                  <td><div class="action-btns"><button class="btn-icon blue-icon" title="Edit"><i class="fa-solid fa-pen"></i></button><button class="btn-icon red-icon" title="Hapus"><i class="fa-solid fa-trash"></i></button></div></td>
                </tr>
<tr>
                  <td><div class="user-cell"><div class="user-ava" style="background:#e8f0f5;color:#1a2636;">R</div><span>Riko Wijaya</span></div></td>
                  <td>2023</td><td>AKPOL</td>
                  <td><span class="pill pill-yellow">Kedinasan</span></td>
                  <td><span class="pill pill-green">Terverifikasi</span></td>
                  <td><div class="action-btns"><button class="btn-icon blue-icon" title="Edit"><i class="fa-solid fa-pen"></i></button><button class="btn-icon red-icon" title="Hapus"><i class="fa-solid fa-trash"></i></button></div></td>
                </tr>
<tr>
                  <td><div class="user-cell"><div class="user-ava" style="background:#e8f0f5;color:#1a2636;">D</div><span>Dian Pertiwi</span></div></td>
                  <td>2024</td><td>UGM</td>
                  <td><span class="pill pill-blue">SNBT</span></td>
                  <td><span class="pill pill-yellow">Menunggu</span></td>
                  <td><div class="action-btns"><button class="btn-icon blue-icon" title="Edit"><i class="fa-solid fa-pen"></i></button><button class="btn-icon red-icon" title="Hapus"><i class="fa-solid fa-trash"></i></button></div></td>
                </tr>
<tr>
                  <td><div class="user-cell"><div class="user-ava" style="background:#e8f0f5;color:#1a2636;">A</div><span>Andi Pratama</span></div></td>
                  <td>2024</td><td>ITB</td>
                  <td><span class="pill pill-blue">SNBT</span></td>
                  <td><span class="pill pill-yellow">Menunggu</span></td>
                  <td><div class="action-btns"><button class="btn-icon blue-icon" title="Edit"><i class="fa-solid fa-pen"></i></button><button class="btn-icon red-icon" title="Hapus"><i class="fa-solid fa-trash"></i></button></div></td>
                </tr>
<tr>
                  <td><div class="user-cell"><div class="user-ava" style="background:#e8f0f5;color:#1a2636;">S</div><span>Siti Nurbaya</span></div></td>
                  <td>2023</td><td>UNMUL</td>
                  <td><span class="pill pill-green">SNBP</span></td>
                  <td><span class="pill pill-green">Terverifikasi</span></td>
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
              <h3>Laporan Masuk</h3>
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
              <h3>Distribusi Jalur</h3>
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
