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
$pendingLaporan= $db->query("SELECT COUNT(*) FROM laporan_masuk WHERE status='pending'")->fetchColumn();

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

// Laporan masuk terbaru (pending)
$laporanPending = $db->query("
    SELECT l.*, u.nama AS univ_nama
    FROM laporan_masuk l
    JOIN universitas u ON l.universitas_id = u.id
    WHERE l.status = 'pending'
    ORDER BY l.submitted_at DESC
    LIMIT 4
")->fetchAll();
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
</head>
<body class="admin-page" id="adminPage">

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
      <div class="stat-card">
        <div class="stat-icon-wrap"><i class="fa-solid fa-user-graduate" style="color:#3b6cf4;"></i></div>
        <div class="stat-body">
          <p class="stat-label">Total Alumni</p>
          <h3 class="stat-value"><?= number_format($totalAlumni) ?></h3>
          <span class="stat-change up"><i class="fa-solid fa-arrow-trend-up"></i> Data terverifikasi</span>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon-wrap"><i class="fa-solid fa-building-columns" style="color:#22c55e;"></i></div>
        <div class="stat-body">
          <p class="stat-label">Universitas Terdaftar</p>
          <h3 class="stat-value"><?= $totalUniv ?></h3>
          <span class="stat-change up"><i class="fa-solid fa-arrow-trend-up"></i> Kampus unik</span>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon-wrap"><i class="fa-solid fa-inbox" style="color:#f59e0b;"></i></div>
        <div class="stat-body">
          <p class="stat-label">Laporan Pending</p>
          <h3 class="stat-value"><?= $pendingLaporan ?></h3>
          <span class="stat-change <?= $pendingLaporan > 0 ? 'down' : 'up' ?>">
            <i class="fa-solid fa-<?= $pendingLaporan > 0 ? 'circle-exclamation' : 'circle-check' ?>"></i>
            <?= $pendingLaporan > 0 ? 'Perlu verifikasi' : 'Semua terproses' ?>
          </span>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon-wrap"><i class="fa-solid fa-eye" style="color:#8b5cf6;"></i></div>
        <div class="stat-body">
          <p class="stat-label">Pengunjung Hari Ini</p>
          <h3 class="stat-value"><?= number_format($todayVisitor) ?></h3>
          <span class="stat-change up"><i class="fa-solid fa-arrow-trend-up"></i> +18%</span>
        </div>
      </div>
    </div>

    <!-- Shortcut Alert jika ada laporan pending -->
    <?php if ($pendingLaporan > 0): ?>
    <div style="background:#fef9c3;border:1px solid #fde047;border-radius:var(--r-sm);padding:12px 16px;display:flex;align-items:center;justify-content:space-between;gap:12px;font-size:13px;font-weight:600;color:#92400e;">
      <div style="display:flex;align-items:center;gap:10px;">
        <i class="fa-solid fa-triangle-exclamation" style="color:#f59e0b;"></i>
        Ada <strong><?= $pendingLaporan ?> laporan baru</strong> dari siswa yang menunggu verifikasi Anda.
      </div>
      <a href="laporan.php" style="background:#f59e0b;color:white;padding:6px 14px;border-radius:6px;text-decoration:none;font-size:12px;font-weight:700;white-space:nowrap;">
        Review Sekarang →
      </a>
    </div>
    <?php endif; ?>

    <!-- Content Grid -->
    <div class="content-grid">

      <!-- Tabel Alumni Terbaru -->
      <div class="card">
        <div class="card-header">
          <h3><i class="fa-solid fa-clock-rotate-left"></i> Alumni Terbaru</h3>
          <a href="alumni.php" class="see-all">Lihat Semua <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="table-wrap">
          <table class="data-table">
            <thead>
              <tr><th>Nama</th><th>Angkatan</th><th>Universitas</th><th>Jalur</th><th>Sumber</th><th></th></tr>
            </thead>
            <tbody>
              <?php
              $avColors = [
                ['#e0f2fe','#0369a1'],['#dcfce7','#166534'],
                ['#fef9c3','#92400e'],['#ede9fe','#5b21b6'],['#fee2e2','#991b1b']
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
                      <?php if ($a['kota']): ?><div style="font-size:11px;color:var(--muted);"><?= htmlspecialchars($a['kota']) ?></div><?php endif; ?>
                    </div>
                  </div>
                </td>
                <td><?= $a['angkatan'] ?></td>
                <td style="font-size:12px;max-width:140px;white-space:normal;"><?= htmlspecialchars($a['universitas_nama']) ?></td>
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

        <!-- Laporan Pending -->
        <div class="card">
          <div class="card-header">
            <h3><i class="fa-solid fa-inbox"></i> Laporan Pending</h3>
            <?php if ($pendingLaporan > 0): ?>
            <span class="badge-pill"><?= $pendingLaporan ?> Baru</span>
            <?php endif; ?>
          </div>
          <div class="report-list">
            <?php if (empty($laporanPending)): ?>
            <div class="empty-state" style="padding:24px;">
              <i class="fa-solid fa-check-circle" style="color:var(--green);"></i>
              <p>Tidak ada laporan pending</p>
            </div>
            <?php else: ?>
            <?php
            $lpColors = [['#e0f2fe','#0369a1'],['#dcfce7','#166534'],['#ede9fe','#5b21b6'],['#fef9c3','#92400e']];
            foreach ($laporanPending as $i => $l):
              $lc   = $lpColors[$i % count($lpColors)];
              $init = strtoupper(mb_substr($l['nama'], 0, 1));
            ?>
            <div class="report-item">
              <div class="report-av" style="background:<?= $lc[0] ?>;color:<?= $lc[1] ?>;"><?= $init ?></div>
              <div class="report-info">
                <strong><?= htmlspecialchars($l['nama']) ?></strong>
                <span><?= htmlspecialchars($l['universitas_nama']) ?></span>
              </div>
              <div class="report-actions">
                <form method="POST" action="laporan.php" style="display:contents;">
                  <input type="hidden" name="laporan_id" value="<?= $l['id'] ?>">
                  <input type="hidden" name="aksi" value="approve">
                  <button type="submit" class="btn-xs green-btn" title="Setujui"><i class="fa-solid fa-check"></i></button>
                </form>
                <form method="POST" action="laporan.php" style="display:contents;">
                  <input type="hidden" name="laporan_id" value="<?= $l['id'] ?>">
                  <input type="hidden" name="aksi" value="reject">
                  <button type="submit" class="btn-xs red-btn" title="Tolak"><i class="fa-solid fa-xmark"></i></button>
                </form>
              </div>
            </div>
            <?php endforeach; ?>
            <?php if ($pendingLaporan > 4): ?>
            <div style="padding:10px 16px;border-top:1px solid var(--border);">
              <a href="laporan.php" style="font-size:12px;font-weight:700;color:var(--accent);text-decoration:none;">
                +<?= $pendingLaporan - 4 ?> laporan lainnya →
              </a>
            </div>
            <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>

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

      </div>
    </div>

  </main>
</div>

<script src="../../js/admin.js"></script>
</body>
</html>
