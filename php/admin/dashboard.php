<?php
// ============================================================
//  Admin: Dashboard
//  php/admin/dashboard.php
// ============================================================
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$db = getDB();

// ── Statistik dari DB ─────────────────────────────────────────
$totalAlumni   = $db->query("SELECT COUNT(*) FROM alumni WHERE status='aktif'")->fetchColumn();
$totalUniv     = $db->query("SELECT COUNT(DISTINCT universitas_id) FROM alumni WHERE status='aktif'")->fetchColumn();


// Pengunjung hari ini (simulasi – bisa integrasikan dengan Google Analytics API)
$todayVisitor  = rand(280, 420); // Ganti dengan data nyata

// Alumni terbaru (5 terakhir)
$terbaru = $db->query("
    SELECT a.*, u.kota
    FROM alumni a
    JOIN universitas u ON a.universitas_id = u.id
    WHERE a.status='aktif'
    ORDER BY a.created_at DESC
    LIMIT 5
")->fetchAll();

// Distribusi jalur
$jalurDist = $db->query("
    SELECT jalur, COUNT(*) as cnt
    FROM alumni WHERE status='aktif'
    GROUP BY jalur
")->fetchAll(PDO::FETCH_KEY_PAIR);

$totalJalur   = array_sum($jalurDist);
$snbpPct  = $totalJalur ? round(($jalurDist['SNBP']??0)/$totalJalur*100) : 0;
$snbtPct  = $totalJalur ? round(($jalurDist['SNBT']??0)/$totalJalur*100) : 0;
$mandiriPct = $totalJalur ? round(($jalurDist['Mandiri']??0)/$totalJalur*100) : 0;
$kedinasanPct = $totalJalur ? (100 - $snbpPct - $snbtPct - $mandiriPct) : 0;

// Tren alumni per tahun
$tren = $db->query("
    SELECT angkatan, COUNT(*) as cnt
    FROM alumni WHERE status='aktif'
    GROUP BY angkatan ORDER BY angkatan
")->fetchAll(PDO::FETCH_KEY_PAIR);

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin – Portal Alumni SMAN 5 Samarinda</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../../css/admin.css">
  <style>
    /* Stat card warna sidebar teal */
    .stat-card--blue {
      background: #d6eaf0 !important;
      border-color: #a8cdd9 !important;
    }
    .stat-card--blue .stat-label { color: #1a5570 !important; }
    .stat-card--blue .stat-value  { color: #0d3a52 !important; }
    .stat-card--blue .stat-change { color: #1a5570 !important; }
    .stat-card--blue .stat-change { color: #1a5570 !important; }

    /* Sesuaikan jumlah grid stats agar pas dengan 3 kartu */
    .stats-grid { grid-template-columns: repeat(3, 1fr) !important; }
    /* Tabel tidak overflow horizontal */
    .data-table td { word-break: break-word; }
    .data-table td, .data-table th { white-space: normal !important; }

    /* Card tabel memenuhi kolom */
    .content-grid { align-items: start; }
    .content-grid > .card:first-child { height: 100%; }

    /* Warna teks normal di dalam tabel (bukan biru link) */
    .data-table td { color: var(--text) !important; padding: 22px 15px !important; }
  </style>
</head>
<body class="admin-page" id="adminPage">

  <!-- ===== SIDEBAR ===== -->
  <?php include 'sidebar.php'; ?>
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <div class="main-wrap" id="mainWrap">
    <!-- Topbar -->
    <header class="topbar">
      <div class="topbar-left">
        <button class="hamburger" id="hamburger" aria-label="Menu"><i class="fa-solid fa-bars"></i></button>
        <div class="breadcrumb">
          <span class="breadcrumb-home"><i class="fa-solid fa-house"></i></span>
          <span class="sep"><i class="fa-solid fa-chevron-right"></i></span>
          <span class="breadcrumb-active">Dashboard</span>
        </div>
      </div>
      <div class="topbar-right">
        <div class="topbar-search">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" placeholder="Cari alumni, universitas..." id="dashSearch" onkeydown="if(event.key==='Enter'&&this.value) window.location='alumni.php?q='+encodeURIComponent(this.value)">
        </div>
        <?php include 'topbar-avatar.php'; ?>
      </div>
    </header>

    <!-- Content -->
    <main class="content">

      <!-- Page Title -->
      <div class="page-title">
        <div>
          <h2>Dashboard</h2>
          <p>Selamat datang kembali, <strong><?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Admin') ?></strong> 👋</p>
        </div>
        <a href="alumni.php?action=tambah" class="btn-primary-sm">
          <i class="fa-solid fa-plus"></i> Tambah Alumni
        </a>
      </div>

      <!-- Stats Cards -->
      <div class="stats-grid">
        <div class="stat-card stat-card--blue">
          <div class="stat-body">
            <p class="stat-label">Total Alumni</p>
            <h3 class="stat-value"><?= number_format($totalAlumni) ?></h3>
            <span class="stat-change up">Data terverifikasi</span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-body">
            <p class="stat-label">Universitas Terdaftar</p>
            <h3 class="stat-value"><?= $totalUniv ?></h3>
            <span class="stat-change up">Kampus unik</span>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-body">
            <p class="stat-label">Pengunjung Hari Ini</p>
            <h3 class="stat-value"><?= number_format($todayVisitor) ?></h3>
            <span class="stat-change up">+18%</span>
          </div>
        </div>
      </div>


      <!-- Content Grid -->
      <div class="content-grid">

        <!-- Tabel Alumni Terbaru -->
        <div class="card">
          <div class="card-header">
            <h3>Alumni Terbaru</h3>
            <a href="alumni.php" class="see-all">Lihat Semua <i class="fa-solid fa-arrow-right"></i></a>
          </div>
          <div class="table-wrap" style="overflow-x:unset;">
            <table class="data-table" style="width:100%;table-layout:fixed;">
              <colgroup>
                <col style="width:30%">
                <col style="width:10%">
                <col style="width:28%">
                <col style="width:10%">
                <col style="width:12%">
                <col style="width:10%">
              </colgroup>
              <thead>
                <tr><th>Nama</th><th>Angkatan</th><th>Universitas</th><th>Jalur</th><th>Sumber</th><th></th></tr>
              </thead>
              <tbody>
                <?php
                $avColors = [
                  ['#f3f4f6','#4b5563']
                ];
                foreach ($terbaru as $i => $a):
                  $c    = $avColors[$i % count($avColors)];
                  $init = strtoupper(mb_substr($a['nama'], 0, 1));
                ?>
                <tr>
                  <td>
                    <div class="user-cell">
                      <div class="user-ava" style="background:<?= $c[0] ?>;color:<?= $c[1] ?>;"><?= $init ?></div>
                      <div>
                        <div style="font-weight:700;font-size:13px;"><?= htmlspecialchars($a['nama']) ?></div>
                        <?php if ($a['kota']): ?><div style="font-size:11px;color:#9ca3af;"><?= htmlspecialchars($a['kota']) ?></div><?php endif; ?>
                      </div>
                    </div>
                  </td>
                  <td><?= $a['angkatan'] ?></td>
                  <td style="font-size:12px;white-space:normal;"><?= htmlspecialchars($a['universitas_nama']) ?></td>
                  <td>
                    <span class="pill <?= $a['jalur']==='SNBP'?'pill-green':($a['jalur']==='SNBT'?'pill-blue':($a['jalur']==='Kedinasan'?'pill-yellow':'pill-red')) ?>">
                      <?= htmlspecialchars($a['jalur']) ?>
                    </span>
                  </td>
                  <td>
                    <span class="pill <?= $a['input_oleh']==='admin' ? 'pill-blue' : 'pill-green' ?>">
                      <?= $a['input_oleh']==='admin' ? 'Admin' : 'Siswa' ?>
                    </span>
                  </td>
                  <td>
                    <div class="action-btns">
                      <a href="alumni.php?action=edit&id=<?= $a['id'] ?>" class="btn-icon blue-icon"><i class="fa-solid fa-pen"></i></a>
                      <a href="alumni.php?action=delete&id=<?= $a['id'] ?>" class="btn-icon red-icon"
                        onclick="return confirm('Hapus <?= addslashes($a['nama']) ?>?')">
                        <i class="fa-solid fa-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($terbaru)): ?>
                <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-user-graduate"></i><p>Belum ada data alumni.</p></div></td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Side Cards -->
        <div class="side-cards">


          <!-- Distribusi Jalur -->
          <div class="card">
            <div class="card-header">
              <h3><i class="fa-solid fa-chart-pie"></i> Distribusi Jalur</h3>
            </div>
            <div class="donut-wrap">
              <?php
              // Hitung sudut donut berdasarkan data real
              $circumference = 276.46; // 2*pi*44
              $snbpDash  = round($circumference * ($snbpPct / 100));
              $snbtDash  = round($circumference * ($snbtPct / 100));
              $mandiriDash = round($circumference * ($mandiriPct / 100));
              $snbpOffset = 0;
              $snbtOffset = -$snbpDash;
              $mandiriOffset = -($snbpDash + $snbtDash);
              ?>
              <svg viewBox="0 0 120 120" class="donut-chart">
                <circle cx="60" cy="60" r="44" fill="none" stroke="#e4eaf0" stroke-width="13"/>
                <?php if ($snbpPct > 0): ?>
                <circle cx="60" cy="60" r="44" fill="none" stroke="#22c55e" stroke-width="13"
                  stroke-dasharray="<?= $snbpDash ?> <?= $circumference - $snbpDash ?>"
                  stroke-dashoffset="<?= $snbpOffset ?>" stroke-linecap="round"/>
                <?php endif; ?>
                <?php if ($snbtPct > 0): ?>
                <circle cx="60" cy="60" r="44" fill="none" stroke="#3b6cf4" stroke-width="13"
                  stroke-dasharray="<?= $snbtDash ?> <?= $circumference - $snbtDash ?>"
                  stroke-dashoffset="<?= $snbtOffset ?>" stroke-linecap="round"/>
                <?php endif; ?>
                <?php if ($mandiriPct > 0): ?>
                <circle cx="60" cy="60" r="44" fill="none" stroke="#f59e0b" stroke-width="13"
                  stroke-dasharray="<?= $mandiriDash ?> <?= $circumference - $mandiriDash ?>"
                  stroke-dashoffset="<?= $mandiriOffset ?>" stroke-linecap="round"/>
                <?php endif; ?>
                <text x="60" y="55" text-anchor="middle" fill="#1a2636" font-size="11" font-weight="700" font-family="Plus Jakarta Sans"><?= number_format($totalAlumni) ?></text>
                <text x="60" y="68" text-anchor="middle" fill="#8898aa" font-size="7" font-family="Plus Jakarta Sans">Alumni</text>
              </svg>
            </div>
            <div class="donut-legend">
              <?php foreach ([
                ['SNBT',     '#3b6cf4', $snbtPct],
                ['SNBP',     '#22c55e', $snbpPct],
                ['Mandiri',  '#f59e0b', $mandiriPct],
                ['Kedinasan','#8b5cf6', $kedinasanPct],
              ] as [$label, $col, $pct]):
                if ($pct <= 0) continue;
              ?>
              <div class="legend-item">
                <span class="lg-dot" style="background:<?= $col ?>;"></span>
                <span><?= $label ?></span>
                <strong><?= $pct ?>%</strong>
              </div>
              <?php endforeach; ?>
            </div>
          </div>


          <!-- Tren Alumni -->
          <div class="card">
            <div class="card-header">
              <h3><i class="fa-solid fa-arrow-trend-up"></i> Tren Alumni (5 Tahun Terakhir)</h3>
            </div>
            <div style="padding: 20px; display:flex; flex-direction:column; gap:14px;">
              <?php 
              // Ambil 5 data terakhir
              $tren5 = array_slice($tren, -5, 5, true);
              $maxTren = !empty($tren5) ? max($tren5) : 1; 
              foreach ($tren5 as $thn => $jml): 
                $barW = round(($jml / $maxTren) * 100);
              ?>
              <div style="display:flex; flex-direction:column; gap:6px;">
                <div style="display:flex; justify-content:space-between; font-size:12.5px; font-weight:700; color:var(--text-soft);">
                  <span>Angkatan <?= htmlspecialchars($thn) ?></span>
                  <span><?= $jml ?> Lulusan</span>
                </div>
                <div style="width:100%; height:8px; background:var(--bg); border-radius:4px; overflow:hidden;">
                  <div style="width:<?= $barW ?>%; height:100%; background:var(--accent); border-radius:4px; transition:width 1s ease;"></div>
                </div>
              </div>
              <?php endforeach; ?>
              <?php if (empty($tren)): ?>
              <div style="text-align:center; font-size:12.5px; color:var(--muted); padding:10px;">Belum ada data tren.</div>
              <?php endif; ?>
            </div>
          </div>

        </div>
      </div>

    </main>
  </div>

  <script src="../../js/admin.js"></script>
</body>
</html>
