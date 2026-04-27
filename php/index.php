<?php
// ============================================================
//  Halaman Utama — Portal Alumni SMAN 5 Samarinda
//  php/index.php
//  VERSI UPDATE: Data peta diambil dinamis dari database
// ============================================================
require_once __DIR__ . '/config/db.php';

// ── Ambil filter angkatan ──
$filterAngkatan = $_GET['angkatan'] ?? '';

// ── Query data untuk peta bubble D3 (hanya kampus ber-koordinat) ──
$mapData = [];
try {
    $db   = getDB();
    
    // Ambil daftar angkatan untuk dropdown
    $listAngkatan = $db->query("SELECT DISTINCT angkatan FROM alumni WHERE status='aktif' ORDER BY angkatan DESC")->fetchAll(PDO::FETCH_COLUMN);

    $sqlMap = "
        SELECT
            u.kode,
            u.nama,
            u.kota,
            u.jenis,
            COALESCE(u.pulau, 'Lainnya') AS pulau,
            u.lat,
            u.lng,
            a.angkatan,
            COUNT(a.id)                       AS jumlah,
            SUM(a.jalur = 'SNBP')             AS snbp,
            SUM(a.jalur = 'SNBT')             AS snbt,
            SUM(a.jalur = 'Mandiri')          AS mandiri,
            SUM(a.jalur = 'Kedinasan')        AS kedinasan
        FROM universitas u
        INNER JOIN alumni a ON a.universitas_id = u.id AND a.status = 'aktif'
        WHERE u.lat IS NOT NULL
          AND u.lng IS NOT NULL
          AND u.lat != 0
          AND u.lng != 0
        GROUP BY u.id, a.angkatan
        HAVING jumlah > 0
        ORDER BY jumlah DESC
    ";
    
    $stmt = $db->query($sqlMap);
    $mapData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // fallback ke array kosong — peta tetap tampil tanpa bubble
    $mapData = [];
}

// ── Query statistik ringkas ──
$statTotal    = 0; $statUniv = 0; $statKota = 0; $statPctTerbaru = 0;
try {
    $statTotal       = (int) $db->query("SELECT COUNT(*) FROM alumni WHERE status='aktif'")->fetchColumn();
    $statUniv        = (int) $db->query("SELECT COUNT(DISTINCT universitas_id) FROM alumni WHERE status='aktif'")->fetchColumn();
    $statKota        = (int) $db->query("SELECT COUNT(DISTINCT u.kota) FROM alumni a JOIN universitas u ON u.id=a.universitas_id WHERE a.status='aktif' AND u.kota != ''")->fetchColumn();
    $latestYear      = (int) $db->query("SELECT MAX(angkatan) FROM alumni WHERE status='aktif'")->fetchColumn();
    $totalLatest     = (int) $db->query("SELECT COUNT(*) FROM alumni WHERE status='aktif' AND angkatan=$latestYear")->fetchColumn();
    // persentase yang lolos SNBP+SNBT (exclude Mandiri & Kedinasan) pada tahun terbaru
    $snpmLatest      = (int) $db->query("SELECT COUNT(*) FROM alumni WHERE status='aktif' AND angkatan=$latestYear AND jalur IN('SNBP','SNBT')")->fetchColumn();
    $statPctTerbaru  = $totalLatest > 0 ? round($snpmLatest / $totalLatest * 100) : 0;
} catch (Exception $e) {}

// ── Query distribusi jalur untuk donut ──
$jalurDist = ['SNBP' => 0, 'SNBT' => 0, 'Mandiri' => 0, 'Kedinasan' => 0];
try {
    $rows = $db->query("SELECT jalur, COUNT(*) as cnt FROM alumni WHERE status='aktif' GROUP BY jalur")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) $jalurDist[$r['jalur']] = (int)$r['cnt'];
} catch (Exception $e) {}

// ── Query top 8 universitas untuk stacked bar ──
$top8 = [];
try {
    $stmt = $db->query("
        SELECT
            u.kode,
            COUNT(a.id)              AS jumlah,
            SUM(a.jalur='SNBP')      AS snbp,
            SUM(a.jalur='SNBT')      AS snbt,
            SUM(a.jalur='Mandiri')   AS mandiri
        FROM alumni a
        JOIN universitas u ON u.id = a.universitas_id
        WHERE a.status = 'aktif'
        GROUP BY u.id
        ORDER BY jumlah DESC
        LIMIT 8
    ");
    $top8 = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

// ── Query tren per tahun (5 tahun terakhir) ──
$tren = [];
try {
    $stmt = $db->query("
        SELECT angkatan, COUNT(*) AS cnt
        FROM alumni
        WHERE status = 'aktif'
        GROUP BY angkatan
        ORDER BY angkatan ASC
        LIMIT 5
    ");
    $tren = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

// ── Kedinasan stats ──
$polri = 0; $akpol = 0;
try {
    $polri = (int)$db->query("SELECT COUNT(*) FROM alumni WHERE status='aktif' AND jalur='Kedinasan' AND universitas_nama NOT LIKE '%AKPOL%'")->fetchColumn();
    $akpol = (int)$db->query("SELECT COUNT(*) FROM alumni WHERE status='aktif' AND jalur='Kedinasan' AND universitas_nama LIKE '%AKPOL%'")->fetchColumn();
} catch (Exception $e) {}

// ── Universitas list untuk lapor.php dropdown (dipakai di bawah jika include) ──
// (tidak dipakai di index, tapi disiapkan)
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal Alumni SMAN 5 Samarinda</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <style>
    /* Custom Modern Dropdown for Map Filter */
    .custom-dropdown {
      position: relative;
      min-width: 200px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      user-select: none;
    }
    .dropdown-selected {
      padding: 10px 20px;
      background-color: #ffffff;
      border: 2px solid transparent;
      border-radius: 999px;
      font-size: 14.5px;
      font-weight: 700;
      color: #1e293b;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .dropdown-selected:hover {
      border-color: #e2e8f0;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
      transform: translateY(-2px);
    }
    .custom-dropdown.open .dropdown-selected {
      border-color: #3b82f6;
      box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
      transform: translateY(-2px);
    }
    .dropdown-selected i {
      color: #3b82f6;
      font-size: 14px;
      transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .custom-dropdown.open .dropdown-selected i {
      transform: rotate(180deg);
    }
    .dropdown-options {
      position: absolute;
      top: calc(100% + 8px);
      left: 0;
      right: 0;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      z-index: 100;
      border: 1px solid #e2e8f0;
    }
    .custom-dropdown.open .dropdown-options {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }
    .dropdown-option {
      padding: 12px 20px;
      font-size: 14px;
      font-weight: 600;
      color: #475569;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .dropdown-option:not(:last-child) {
      border-bottom: 1px solid #f1f5f9;
    }
    .dropdown-option:hover {
      background-color: #f8fafc;
      color: #3b82f6;
      padding-left: 24px;
    }
    .dropdown-option.active {
      color: #3b82f6;
      background-color: #eff6ff;
      font-weight: 700;
    }
    .dropdown-option .check-icon {
      opacity: 0;
      transform: scale(0.8);
      transition: all 0.2s ease;
    }
    .dropdown-option.active .check-icon {
      opacity: 1;
      transform: scale(1);
    }
  </style>
</head>
<body class="bg-cross">

  <!-- ===== NAVBAR ===== -->
  <nav class="navbar" id="navbar">
    <div class="container nav-inner">
      <a href="index.php" class="nav-brand">
        <img src="../image/logosma5.png" alt="Logo SMAN 5 Samarinda" class="nav-logo">
        <span>SMAN 5 Samarinda</span>
      </a>
      <div class="nav-menu">
        <a href="#" class="active">Beranda</a>
        <a href="alumni.php">Data Alumni</a>
        <a href="#jadwal">Jadwal SNPMB</a>
      </div>
      <button class="menu-btn" id="menuBtn" aria-label="Buka menu" aria-expanded="false">
        <i class="fa-solid fa-bars" id="menuIcon"></i>
      </button>
    </div>
  </nav>

  <div class="mobile-menu" id="mobileMenu" role="navigation" aria-label="Menu mobile">
    <a href="#" class="active">Beranda</a>
    <a href="alumni.php">Data Alumni</a>
    <a href="#jadwal">Jadwal SNPMB</a>
  </div>


  <!-- ===== HERO ===== -->
  <section class="hero-section">
    <div class="container grid-2-col">
      <div class="hero-content">
        <h1 class="hero-title">
          Cari Kampus<br>Impian Anda<br>dari Riwayat<br>Alumni yang ada
        </h1>
        <p class="hero-desc">
          Lihat data kampus yang dimasuki oleh alumni jalur SNBP/SNBT dengan mudah serta lengkap.
        </p>
        <div class="search-box" role="search">
          <input type="text" id="heroSearch" placeholder="Cari Kampus / Jurusan..."
            class="search-input" aria-label="Cari kampus atau jurusan">
          <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
          </svg>
        </div>
        <p id="searchMsg" style="font-size:13px;color:#2563eb;margin-bottom:8px;margin-top:-6px;min-height:18px;font-weight:600;"></p>
        <a href="alumni.php" class="btn-action">
          <span>Lihat Data Alumni</span>
          <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
          </svg>
        </a>
      </div>
      <div class="hero-image">
        <div class="img-card">
          <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=800&q=80"
            alt="Siswa SMAN 5 Samarinda" loading="lazy">
          <div class="img-overlay">
            <span class="badge">Galeri</span>
            <p style="color:white;font-weight:700;font-size:17px;">Angkatan Lulusan Tahun <?= date('Y') - 1 ?></p>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- ===== VISUALISASI DATA ===== -->
  <section class="bg-dots" id="visualisasi">
    <div class="container">

      <!-- Header -->
      <div class="section-header">
        <div class="section-eyebrow">Data &amp; Statistik</div>
        <h2 class="section-title">Visualisasi Sebaran Alumni</h2>
        <p class="section-subtitle">
          Gambaran lengkap destinasi kampus, jalur masuk, dan tren penerimaan alumni
          SMAN 5 Samarinda ke Perguruan Tinggi Negeri dari tahun ke tahun.
        </p>
      </div>

      <!-- ── 1. STAT STRIP (dari DB) ── -->
      <div class="viz-stat-strip">
        <div class="viz-stat-card">
          <div class="viz-stat-accent" style="background:#2563eb;"></div>
          <div class="viz-stat-label"><i class="fa-solid fa-users" style="color:#2563eb;"></i> Total Alumni</div>
          <div class="viz-stat-val"><?= number_format($statTotal) ?></div>
          <div class="viz-stat-sub">data terverifikasi</div>
        </div>
        <div class="viz-stat-card">
          <div class="viz-stat-accent" style="background:#2563eb;"></div>
          <div class="viz-stat-label"><i class="fa-solid fa-building-columns" style="color:#2563eb;"></i> Universitas</div>
          <div class="viz-stat-val"><?= $statUniv ?></div>
          <div class="viz-stat-sub">kampus berbeda</div>
        </div>
        <div class="viz-stat-card">
          <div class="viz-stat-accent" style="background:#2563eb;"></div>
          <div class="viz-stat-label"><i class="fa-solid fa-map-location-dot" style="color:#2563eb;"></i> Kota Tujuan</div>
          <div class="viz-stat-val"><?= $statKota ?></div>
          <div class="viz-stat-sub">kota di Indonesia</div>
        </div>
        <div class="viz-stat-card">
          <div class="viz-stat-accent" style="background:#2563eb;"></div>
          <div class="viz-stat-label"><i class="fa-solid fa-chart-line" style="color:#2563eb;"></i> Lolos PTN</div>
          <div class="viz-stat-val"><?= $statPctTerbaru ?><span style="font-size:20px;font-weight:700;">%</span></div>
          <div class="viz-stat-sub">angkatan <?= $latestYear ?? date('Y') - 1 ?></div>
        </div>
      </div>


      <!-- ── 2. PETA BUBBLE MAP D3 (data dari DB) ── -->
      <div style="margin-bottom:48px;">
        <div class="section-header" style="margin-bottom:28px; display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px;">
          <div>
            <div class="section-eyebrow">Peta Interaktif</div>
            <h2 class="section-title" style="font-size:26px; margin-bottom:8px;">Jejak Alumni se-Nusantara</h2>
            <p class="section-subtitle" style="margin:0;">
              Ukuran lingkaran proporsional dengan jumlah alumni. Arahkan kursor ke tiap lingkaran untuk detail lengkap.
            </p>
          </div>
          <div style="display:flex; gap:12px; flex-wrap:wrap;">
            <!-- Dropdown Filter Angkatan -->
            <div class="custom-dropdown" id="mapAngkatanDropdown">
              <div class="dropdown-selected" id="mapAngkatanSelected">
                <span><?= $filterAngkatan ? 'Angkatan ' . htmlspecialchars($filterAngkatan) : 'Semua Angkatan' ?></span>
                <i class="fa-solid fa-chevron-down"></i>
              </div>
              <div class="dropdown-options" id="mapAngkatanOptions">
                <div class="dropdown-option <?= $filterAngkatan === '' ? 'active' : '' ?>" data-value="">Semua Angkatan <i class="fa-solid fa-check check-icon"></i></div>
                <?php foreach($listAngkatan as $akt): ?>
                <div class="dropdown-option <?= $filterAngkatan == $akt ? 'active' : '' ?>" data-value="<?= $akt ?>">Angkatan <?= htmlspecialchars($akt) ?> <i class="fa-solid fa-check check-icon"></i></div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Dropdown Filter Jenis Kampus -->
            <div class="custom-dropdown" id="mapFilterDropdown">
              <div class="dropdown-selected" id="mapFilterSelected">
                <span>Semua Kampus</span>
                <i class="fa-solid fa-chevron-down"></i>
              </div>
              <div class="dropdown-options" id="mapFilterOptions">
                <div class="dropdown-option active" data-value="">Semua Kampus <i class="fa-solid fa-check check-icon"></i></div>
                <div class="dropdown-option" data-value="PTN">PTN (Negeri) <i class="fa-solid fa-check check-icon"></i></div>
                <div class="dropdown-option" data-value="PTS">PTS (Swasta) <i class="fa-solid fa-check check-icon"></i></div>
                <div class="dropdown-option" data-value="Kedinasan">Kedinasan <i class="fa-solid fa-check check-icon"></i></div>
              </div>
            </div>
          </div>
        </div>

        <div class="viz-map-wrapper">
          <div id="d3-indonesia-map">
            <div class="map-loading-state">
              <i class="fa-solid fa-spinner fa-spin"></i> Memuat peta…
            </div>
          </div>
          <div class="map-bubble-tooltip" id="bubbleTooltip"></div>

          <div class="map-legend-row">
            <div class="map-legend-item">
              <div class="map-legend-dot" style="background:#ef4444;box-shadow:0 0 0 3px rgba(239,68,68,.2);"></div>
              Samarinda (Asal)
            </div>
            <div class="map-legend-item">
              <div class="map-legend-dot" style="background:#2563eb;"></div>
              Kalimantan
            </div>
            <div class="map-legend-item">
              <div class="map-legend-dot" style="background:#7c3aed;"></div>
              Jawa
            </div>
            <div class="map-legend-item">
              <div class="map-legend-dot" style="background:#0891b2;"></div>
              Luar Jawa &amp; Kalimantan
            </div>
            <div class="map-legend-item" style="margin-left:auto;">
              <svg width="64" height="14" viewBox="0 0 64 14" style="overflow:visible;">
                <circle cx="7" cy="7" r="5" fill="rgba(37,99,235,.2)" stroke="#2563eb" stroke-width="1.5"/>
                <circle cx="29" cy="7" r="8" fill="rgba(37,99,235,.2)" stroke="#2563eb" stroke-width="1.5"/>
                <circle cx="54" cy="7" r="11" fill="rgba(37,99,235,.2)" stroke="#2563eb" stroke-width="1.5"/>
              </svg>
              <span style="font-size:12px;">&nbsp;= jumlah alumni</span>
            </div>
          </div>
        </div>
      </div>


      <!-- ── 3. CHARTS ROW ── -->
      <div style="margin-bottom:48px;">
        <div class="section-header" style="margin-bottom:28px;">
          <div class="section-eyebrow">Analisis Jalur Masuk</div>
          <h2 class="section-title" style="font-size:26px;">Distribusi per Kampus &amp; Jalur Seleksi</h2>
        </div>

        <div class="viz-charts-grid">
          <!-- Stacked Bar -->
          <div class="viz-card">
            <div class="viz-card-label">Universitas Tujuan</div>
            <div class="viz-card-title">Jumlah Alumni per Kampus (Top 8)</div>
            <div class="chartjs-legend">
              <div class="chartjs-legend-item"><div class="chartjs-legend-swatch" style="background:#16a34a;"></div> SNBP</div>
              <div class="chartjs-legend-item"><div class="chartjs-legend-swatch" style="background:#2563eb;"></div> SNBT</div>
              <div class="chartjs-legend-item"><div class="chartjs-legend-swatch" style="background:#d97706;"></div> Mandiri</div>
            </div>
            <div style="position:relative;height:320px;">
              <canvas id="chartStackedBar"></canvas>
            </div>
          </div>

          <div class="viz-right-col">
            <!-- Donut -->
            <div class="viz-card">
              <div class="viz-card-label">Jalur Seleksi</div>
              <div class="viz-card-title">Distribusi Jalur Masuk</div>
              <div class="donut-layout">
                <div class="donut-canvas-wrap">
                  <canvas id="chartDonut" width="150" height="150"></canvas>
                  <div class="donut-center-label">
                    <div class="donut-center-val"><?= number_format($statTotal) ?></div>
                    <div class="donut-center-sub">TOTAL</div>
                  </div>
                </div>
                <div class="donut-legend">
                  <?php
                  $totalJalur = array_sum($jalurDist);
                  $jalurColors = ['SNBP' => '#16a34a', 'SNBT' => '#2563eb', 'Mandiri' => '#d97706', 'Kedinasan' => '#7c3aed'];
                  foreach ($jalurDist as $jalur => $cnt):
                    if ($cnt === 0) continue;
                    $pct = $totalJalur > 0 ? round($cnt / $totalJalur * 100) : 0;
                  ?>
                  <div class="donut-legend-item">
                    <div class="dl-swatch" style="background:<?= $jalurColors[$jalur] ?>;"></div>
                    <div class="dl-name"><?= $jalur ?></div>
                    <div class="dl-val"><?= $cnt ?> <span class="dl-pct"><?= $pct ?>%</span></div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>

            <!-- Trend sparkline -->
            <div class="viz-card">
              <div class="viz-card-label">Tren Tahunan</div>
              <div class="viz-card-title">Alumni Lolos PTN per Tahun</div>
              <div class="trend-year-cards">
                <?php
                $cardColors = [
                  ['#f0fdf4','#bbf7d0','#15803d'],
                  ['#eff6ff','#bfdbfe','#1d4ed8'],
                  ['#faf5ff','#ddd6fe','#7c3aed'],
                ];
                foreach (array_slice($tren, -3) as $i => $t):
                  $c = $cardColors[$i % 3];
                ?>
                <div class="trend-year-card" style="background:<?= $c[0] ?>;border-color:<?= $c[1] ?>;">
                  <div class="tyc-yr"><?= $t['angkatan'] ?></div>
                  <div class="tyc-val" style="color:<?= $c[2] ?>;"><?= $t['cnt'] ?></div>
                  <div class="tyc-delta" style="color:<?= $c[2] ?>;">
                    <?php if ($i === 0): ?>baseline<?php else: ?>
                    <?php $prev = $tren[array_search($t, $tren) - 1]['cnt'] ?? $t['cnt'];
                    $delta = $prev > 0 ? round(($t['cnt'] - $prev) / $prev * 100) : 0;
                    echo ($delta >= 0 ? '▲ +' : '▼ ') . $delta . '%'; ?>
                    <?php endif; ?>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
              <div style="position:relative;height:90px;">
                <canvas id="chartTrend"></canvas>
              </div>
            </div>

          </div>
        </div>
      </div>


      <!-- ── 4. HEATMAP & SCATTER ── -->
      <div>
        <div class="section-header" style="margin-bottom:28px;">
          <div class="section-eyebrow">Deep Dive</div>
          <h2 class="section-title" style="font-size:26px;">Minat Program Studi &amp; Keketatan Kampus</h2>
        </div>
        <div style="display:grid;grid-template-columns:1fr;gap:24px;">
          <div class="viz-card">
            <div class="viz-card-label">Heatmap Minat</div>
            <div class="viz-card-title">Program Studi × Angkatan (% Peminat)</div>
            <div style="overflow-x:auto;">
              <table class="heatmap-table" id="heatmapTable"></table>
            </div>
            <p class="heatmap-note">Warna semakin gelap = semakin banyak peminat pada tahun tersebut.</p>
          </div>
          <div class="viz-card">
            <div class="viz-card-label">Scatter Plot</div>
            <div class="viz-card-title">Jumlah Alumni vs Keketatan Kampus (SNBT)</div>
            <div class="chartjs-legend" style="margin-bottom:16px;">
              <div class="chartjs-legend-item"><div class="chartjs-legend-swatch" style="background:#2563eb;border-radius:50%;"></div> Kalimantan</div>
              <div class="chartjs-legend-item"><div class="chartjs-legend-swatch" style="background:#7c3aed;border-radius:50%;"></div> Jawa</div>
              <div class="chartjs-legend-item"><div class="chartjs-legend-swatch" style="background:#0891b2;border-radius:50%;"></div> Lainnya</div>
            </div>
            <div style="position:relative;height:320px;">
              <canvas id="chartScatter"></canvas>
            </div>
            <p class="scatter-caption">Sumbu X = Estimasi rasio keketatan. Sumbu Y = Jumlah alumni yang diterima.</p>
          </div>
        </div>
      </div>

      <!-- Kedinasan -->
      <div class="stat-card-container" style="margin-top:48px;">
        <h4>
          <i class="fa-solid fa-shield-halved" style="color:#eab308;font-size:16px;"></i>
          Info Jalur Kedinasan &amp; Aparat
        </h4>
        <div class="stat-grid">
          <div class="stat-box polri">
            <p>Bintara Polri</p>
            <h4><?= $polri ?> <span>Orang</span></h4>
          </div>
          <div class="stat-box akpol">
            <p>Taruna AKPOL</p>
            <h4><?= $akpol ?> <span>Orang</span></h4>
          </div>
        </div>
      </div>

    </div>
  </section>


  <!-- ===== TREN JURUSAN ===== -->
  <section class="trend-section">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Tren Minat Program Studi</h2>
        <p class="section-subtitle">8 Rumpun keilmuan favorit pilihan utama alumni kami.</p>
      </div>
      <div class="trend-grid">
        <div class="trend-card">
          <div class="trend-icon" style="background:#f1f5f9;color:#475569;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/></svg></div>
          <div class="trend-info"><h3>Teknologi Informasi &amp; Komputer</h3><div class="progress-bar"><div class="progress-fill" style="width:20%;background:#64748b;"></div></div><span class="trend-stat">20% Peminat</span></div>
        </div>
        <div class="trend-card">
          <div class="trend-icon" style="background:#f1f5f9;color:#475569;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.832M11.42 15.17l-1.028-1.028m0 0L7.17 11.11m4.242 4.06l-1.028-1.028m-4.24 4.242a2.652 2.652 0 01-3.75-3.75l4.242-4.242-1.028-1.028"/></svg></div>
          <div class="trend-info"><h3>Teknik &amp; Rekayasa</h3><div class="progress-bar"><div class="progress-fill" style="width:18%;background:#64748b;"></div></div><span class="trend-stat">18% Peminat</span></div>
        </div>
        <div class="trend-card">
          <div class="trend-icon" style="background:#f1f5f9;color:#475569;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg></div>
          <div class="trend-info"><h3>Bisnis &amp; Administrasi</h3><div class="progress-bar"><div class="progress-fill" style="width:14%;background:#64748b;"></div></div><span class="trend-stat">14% Peminat</span></div>
        </div>
        <div class="trend-card">
          <div class="trend-icon" style="background:#f1f5f9;color:#475569;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg></div>
          <div class="trend-info"><h3>Kedokteran &amp; Kesehatan</h3><div class="progress-bar"><div class="progress-fill" style="width:15%;background:#64748b;"></div></div><span class="trend-stat">15% Peminat</span></div>
        </div>
        <div class="trend-card">
          <div class="trend-icon" style="background:#f1f5f9;color:#475569;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z"/></svg></div>
          <div class="trend-info"><h3>Psikologi &amp; Ilmu Sosial</h3><div class="progress-bar"><div class="progress-fill" style="width:12%;background:#64748b;"></div></div><span class="trend-stat">12% Peminat</span></div>
        </div>
        <div class="trend-card">
          <div class="trend-icon" style="background:#f1f5f9;color:#475569;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/></svg></div>
          <div class="trend-info"><h3>Hukum &amp; Pemerintahan</h3><div class="progress-bar"><div class="progress-fill" style="width:8%;background:#64748b;"></div></div><span class="trend-stat">8% Peminat</span></div>
        </div>
        <div class="trend-card">
          <div class="trend-icon" style="background:#f1f5f9;color:#475569;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.854-3.853a3 3 0 00-4.243-4.242l-3.853 3.854a15.995 15.995 0 00-4.648 4.764m3.42 3.42a6 6 0 00-3.42-3.42"/></svg></div>
          <div class="trend-info"><h3>Seni &amp; Desain Kreatif</h3><div class="progress-bar"><div class="progress-fill" style="width:8%;background:#64748b;"></div></div><span class="trend-stat">8% Peminat</span></div>
        </div>
        <div class="trend-card">
          <div class="trend-icon" style="background:#f1f5f9;color:#475569;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg></div>
          <div class="trend-info"><h3>Pendidikan &amp; Keguruan</h3><div class="progress-bar"><div class="progress-fill" style="width:5%;background:#64748b;"></div></div><span class="trend-stat">5% Peminat</span></div>
        </div>
      </div>
    </div>
  </section>


  <!-- ===== TIMELINE ===== -->
  <section class="timeline-section bg-cross" id="jadwal">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Alur Persiapan Masuk PTN</h2>
        <p class="section-subtitle">Catat estimasi jadwal tahapan seleksi agar tidak tertinggal informasi.</p>
      </div>
      <div class="timeline-wrapper">
        <div class="timeline-line"></div>
        <div class="timeline-item">
          <div class="timeline-dot" style="border-color:#64748b;"></div>
          <div class="timeline-content">
            <span class="t-date" style="background:#f1f5f9;color:#475569;">Januari – Februari</span>
            <h3>SNBP (Jalur Prestasi)</h3>
            <p>Pembuatan akun SNPMB, penetapan siswa eligible, dan pendaftaran berbasis nilai rapor serta prestasi.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot" style="border-color:#64748b;"></div>
          <div class="timeline-content">
            <span class="t-date" style="background:#f1f5f9;color:#475569;">Maret – Mei</span>
            <h3>SNBT (Jalur Tes)</h3>
            <p>Pendaftaran UTBK, pelaksanaan ujian berbasis komputer serentak, dan pengumuman tingkat nasional.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot" style="border-color:#64748b;"></div>
          <div class="timeline-content">
            <span class="t-date" style="background:#f1f5f9;color:#475569;">Juni – Agustus</span>
            <h3>Mandiri &amp; Kedinasan</h3>
            <p>Ujian mandiri masing-masing PTN dan tahapan seleksi sekolah kedinasan.</p>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- ===== TESTIMONI ===== -->
  <section class="testi-section bg-dots">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Kisah Sukses Alumni</h2>
        <p class="section-subtitle">Inspirasi, motivasi, dan tips belajar langsung dari kakak tingkatmu.</p>
      </div>
      <div class="testi-grid">
        <div class="testi-card">
          <i class="fa-solid fa-quote-left quote-icon"></i>
          <p class="testi-text">"Rahasia lolos UTBK bukan belajar semalaman, tapi konsisten latihan 15 soal sehari. Jangan andalkan sistem kebut semalam!"</p>
          <div class="testi-author">
            <div class="t-avatar" style="background:#e0f2fe;color:#0284c7;"><i class="fa-solid fa-user-graduate"></i></div>
            <div class="t-info"><h4>Budi Santoso</h4><span>Teknik Sipil, ITS</span></div>
          </div>
        </div>
        <div class="testi-card">
          <i class="fa-solid fa-quote-left quote-icon"></i>
          <p class="testi-text">"Untuk SNBP, perhatikan grafik nilaimu (Smt 1–5). Kalau sedikit turun, imbangi dengan sertifikat lomba level nasional/provinsi."</p>
          <div class="testi-author">
            <div class="t-avatar" style="background:#dcfce7;color:#16a34a;"><i class="fa-solid fa-user-nurse"></i></div>
            <div class="t-info"><h4>Siti Nurbaya</h4><span>Kedokteran, UNMUL</span></div>
          </div>
        </div>
        <div class="testi-card">
          <i class="fa-solid fa-quote-left quote-icon"></i>
          <p class="testi-text">"Kedisiplinan adalah kunci. Tes kedinasan butuh fisik mumpuni. Rutin lari dan jaga asupan gizi sejak semester 4 itu sangat krusial."</p>
          <div class="testi-author">
            <div class="t-avatar" style="background:#fef08a;color:#ca8a04;"><i class="fa-solid fa-user-shield"></i></div>
            <div class="t-info"><h4>Riko Wijaya</h4><span>Taruna Akademi Kepolisian</span></div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- ===== FAQ ===== -->
  <section class="faq-section">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Pertanyaan Sering Diajukan</h2>
        <p class="section-subtitle">Jawaban singkat terkait data dan portal alumni kita.</p>
      </div>
      <div class="faq-wrapper">
        <details class="faq-item" open>
          <summary>Apakah data yang ditampilkan akurat?</summary>
          <div class="faq-answer"><p>Tentu. Data dihimpun langsung berdasarkan pelaporan alumni ke pihak bimbingan konseling SMAN 5 Samarinda dengan verifikasi silang bersama Perguruan Tinggi terkait.</p></div>
        </details>
        <details class="faq-item">
          <summary>Mengapa ada kampus yang tidak masuk dalam grafik?</summary>
          <div class="faq-answer"><p>Kami hanya menampilkan <strong>Top 8</strong> destinasi kampus per kriteria demi menjaga kerapian website. Untuk data lebih mendalam kunjungi halaman Data Alumni.</p></div>
        </details>
        <details class="faq-item">
          <summary>Bisa minta kontak Kakak Tingkat yang kuliah di tujuan saya?</summary>
          <div class="faq-answer"><p>Portal web tidak menyediakan data pribadi alumni. Namun, Anda bisa mengunjungi Ruang BK untuk melihat jejak rekam dan meminta kontak jika diizinkan yang bersangkutan.</p></div>
        </details>
        <details class="faq-item">
          <summary>Bagaimana cara melaporkan data saya sebagai alumni baru?</summary>
          <div class="faq-answer"><p>Klik tombol <strong>"Lapor Data"</strong> di bagian bawah halaman ini, lalu isi formulir dengan data diri dan universitas yang Anda masuki.</p></div>
        </details>
      </div>
    </div>
  </section>


  <!-- ===== CTA ===== -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-box">
        <div class="cta-content">
          <h2>Anda Bagian Dari Sejarah Kami?</h2>
          <p>Bantu kami melengkapi data sebaran lulusan. Jika Anda alumni SMAN 5 Samarinda yang baru diterima di Perguruan Tinggi, mari laporkan kampusnya sekarang!</p>
        </div>
        <a href="lapor.php" class="btn-primary">Lapor Data <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>
  </section>


  <!-- ===== FOOTER ===== -->
  <footer class="footer">
    <div class="container footer-grid">
      <div class="brand-col">
        <h3 class="footer-title">SMAN 5 SAMARINDA</h3>
        <p class="footer-desc">Destinasi pendidikan menengah atas unggulan di Kalimantan Timur.</p>
        <div class="footer-contact-item"><i class="fa-solid fa-location-dot" style="margin-top:0;"></i><span>Jl. Ir. H. Juanda No. 1, Air Hitam, Samarinda Ulu, Kalimantan Timur 75124</span></div>
      </div>
      <div class="link-col">
        <h3 class="footer-title">Jelajahi</h3>
        <ul class="footer-links">
          <li><a href="index.php"><i class="fa-solid fa-chevron-right"></i> Beranda</a></li>
          <li><a href="alumni.php"><i class="fa-solid fa-chevron-right"></i> Data Alumni</a></li>
          <li><a href="#"><i class="fa-solid fa-chevron-right"></i> Galeri Prestasi</a></li>
          <li><a href="#"><i class="fa-solid fa-chevron-right"></i> Tentang Kami</a></li>
        </ul>
      </div>
      <div class="contact-col">
        <h3 class="footer-title">Hubungi Kami</h3>
        <div class="footer-contact-list">
          <div class="footer-contact-item"><i class="fa-solid fa-envelope"></i><span>info@sman5samarinda.sch.id</span></div>
          <div class="footer-contact-item"><i class="fa-solid fa-phone"></i><span>(0541) 1234567</span></div>
          <div class="footer-contact-item"><i class="fa-regular fa-clock"></i><span>08:00 - 15:00 WITA</span></div>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container"><p>&copy; <?= date('Y') ?> SMAN 5 Samarinda. All Rights Reserved.</p></div>
    </div>
  </footer>

  <button class="scroll-top" id="scrollTop" aria-label="Scroll ke atas">
    <i class="fa-solid fa-chevron-up"></i>
  </button>


  <!-- ===== SCRIPTS ===== -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/7.8.5/d3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/topojson/3.0.2/topojson.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>

  <script>
  /* ══════════════════════════════════════════════════════════
     DATA DINAMIS — diambil dari PHP/Database
     Tidak perlu edit manual lagi!
  ══════════════════════════════════════════════════════════ */

  // Data peta: query dari DB (kampus ber-koordinat + jumlah alumni > 0)
  const UNIV_DATA = <?= json_encode(array_map(function($u) {
      return [
          'kode'    => $u['kode']    ?? '',
          'nama'    => $u['nama']    ?? '',
          'kota'    => $u['kota']    ?? '',
          'jenis'   => $u['jenis']   ?? 'PTN',
          'pulau'   => $u['pulau']   ?? 'Lainnya',
          'lat'     => (float)($u['lat'] ?? 0),
          'lng'     => (float)($u['lng'] ?? 0),
          'angkatan'=> (string)($u['angkatan'] ?? ''),
          'jumlah'  => (int)$u['jumlah'],
          'snbp'    => (int)($u['snbp']    ?? 0),
          'snbt'    => (int)($u['snbt']    ?? 0),
          'mandiri' => (int)($u['mandiri'] ?? 0),
      ];
  }, $mapData), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?>;

  // Top 8 untuk stacked bar
  const TOP8_DATA = <?= json_encode(array_map(function($u) {
      return [
          'kode'    => $u['kode']    ?? '',
          'jumlah'  => (int)$u['jumlah'],
          'snbp'    => (int)($u['snbp']    ?? 0),
          'snbt'    => (int)($u['snbt']    ?? 0),
          'mandiri' => (int)($u['mandiri'] ?? 0),
      ];
  }, $top8), JSON_UNESCAPED_UNICODE) ?>;

  // Distribusi jalur untuk donut
  const JALUR_DIST = <?= json_encode($jalurDist) ?>;

  // Tren per tahun untuk sparkline
  const TREN_DATA = <?= json_encode(array_map(function($t) {
      return ['tahun' => (string)$t['angkatan'], 'cnt' => (int)$t['cnt']];
  }, $tren)) ?>;

  // Konstanta warna per pulau
  const COLOR_PULAU = { 'Kalimantan':'#2563eb', 'Jawa':'#7c3aed', 'Lainnya':'#0891b2' };
  const SAMARINDA   = { lat:-0.5022, lng:117.1536 };

  // Heatmap (masih statis — bisa diubah ke DB nanti)
  const PRODI_HEATMAP = [
    { nama:'Teknologi Informasi', pct:[16, 18, 20] },
    { nama:'Teknik & Rekayasa',   pct:[20, 19, 18] },
    { nama:'Kedokteran & Kesh.',  pct:[14, 14, 15] },
    { nama:'Bisnis & Adm.',       pct:[12, 13, 14] },
    { nama:'Psikologi & Sosial',  pct:[13, 12, 12] },
    { nama:'Hukum & Pemerintahan',pct:[10,  9,  8] },
    { nama:'Seni & Desain',       pct:[ 8,  8,  8] },
    { nama:'Pendidikan',          pct:[ 7,  7,  5] },
  ];


  /* ══════════════════
     1. PETA BUBBLE D3
  ══════════════════ */
  (function() {
    const wrap    = document.getElementById('d3-indonesia-map');
    const tooltip = document.getElementById('bubbleTooltip');
    const filterDropdown = document.getElementById('mapFilterDropdown');
    const filterSelected = document.getElementById('mapFilterSelected');
    const filterOptions  = document.getElementById('mapFilterOptions');
    let countriesData;
    let currentJenisFilter = '';
    let currentAngkatanFilter = '';
    
    const W = wrap.clientWidth || 860;
    const H = Math.round(W * 0.44);

    const proj = d3.geoMercator()
      .center([118, -2])
      .scale(W * 1.05)
      .translate([W / 2, H / 2]);

    const pathGen = d3.geoPath().projection(proj);

    d3.json('https://cdn.jsdelivr.net/npm/world-atlas@2/countries-110m.json').then(world => {
      countriesData = topojson.feature(world, world.objects.countries);
      renderMap();
      
      const angkatanDropdown = document.getElementById('mapAngkatanDropdown');
      const angkatanSelected = document.getElementById('mapAngkatanSelected');
      const angkatanOptions  = document.querySelectorAll('#mapAngkatanOptions .dropdown-option');

      // Custom Dropdown Logic (Jenis Kampus)
      if (filterDropdown && filterSelected && filterOptions) {
        filterSelected.addEventListener('click', (e) => {
          e.stopPropagation();
          filterDropdown.classList.toggle('open');
          if (angkatanDropdown) angkatanDropdown.classList.remove('open');
        });

        filterOptions.querySelectorAll('.dropdown-option').forEach(opt => {
          opt.addEventListener('click', () => {
            filterOptions.querySelectorAll('.dropdown-option').forEach(o => o.classList.remove('active'));
            opt.classList.add('active');
            const val = opt.getAttribute('data-value');
            filterSelected.querySelector('span').textContent = val ? opt.textContent.trim() : 'Semua Kampus';
            filterDropdown.classList.remove('open');
            currentJenisFilter = val;
            renderMap();
          });
        });
      }

      // Custom Dropdown Logic (Angkatan)
      if (angkatanDropdown && angkatanSelected && angkatanOptions) {
        angkatanSelected.addEventListener('click', (e) => {
          e.stopPropagation();
          angkatanDropdown.classList.toggle('open');
          if (filterDropdown) filterDropdown.classList.remove('open');
        });

        angkatanOptions.forEach(opt => {
          opt.addEventListener('click', () => {
            angkatanOptions.forEach(o => o.classList.remove('active'));
            opt.classList.add('active');
            const val = opt.getAttribute('data-value');
            angkatanSelected.querySelector('span').textContent = val ? opt.textContent.trim() : 'Semua Angkatan';
            angkatanDropdown.classList.remove('open');
            currentAngkatanFilter = val;
            renderMap();
          });
        });
      }

      document.addEventListener('click', (e) => {
        if (filterDropdown && !filterDropdown.contains(e.target)) filterDropdown.classList.remove('open');
        if (angkatanDropdown && !angkatanDropdown.contains(e.target)) angkatanDropdown.classList.remove('open');
      });
    }).catch(() => {
      document.getElementById('d3-indonesia-map').innerHTML =
        '<div class="map-loading-state"><i class="fa-solid fa-triangle-exclamation"></i> Gagal memuat peta. Periksa koneksi.</div>';
    });

    function renderMap() {
      d3.select('#d3-indonesia-map').html('');
      const svg = d3.select('#d3-indonesia-map')
        .append('svg')
        .attr('viewBox', `0 0 ${W} ${H}`)
        .attr('width', '100%')
        .style('display', 'block');

      svg.append('rect').attr('width', W).attr('height', H)
         .attr('fill', '#dbeafe').attr('rx', 12);

      svg.append('g').selectAll('path')
        .data(countriesData.features)
        .join('path')
        .attr('d', pathGen)
        .attr('fill', d => d.id === '360' ? '#bfdbfe' : '#e0eaf7')
        .attr('stroke', '#b8cfe0').attr('stroke-width', 0.4);

      svg.append('g').selectAll('path')
        .data(countriesData.features.filter(d => d.id === '360'))
        .join('path')
        .attr('d', pathGen)
        .attr('fill', '#dbeafe').attr('stroke', '#93c5fd').attr('stroke-width', 1);

      let filteredData = UNIV_DATA;
      if (currentJenisFilter) {
        filteredData = filteredData.filter(u => u.jenis === currentJenisFilter);
      }
      if (currentAngkatanFilter) {
        filteredData = filteredData.filter(u => u.angkatan === currentAngkatanFilter);
      }

      // Agregasi karena data UNIV_DATA sekarang pecah per angkatan
      let mapObj = {};
      filteredData.forEach(u => {
        if (!mapObj[u.kode]) {
          mapObj[u.kode] = {...u, jumlah:0, snbp:0, snbt:0, mandiri:0};
        }
        mapObj[u.kode].jumlah += u.jumlah;
        mapObj[u.kode].snbp += u.snbp;
        mapObj[u.kode].snbt += u.snbt;
        mapObj[u.kode].mandiri += u.mandiri;
      });
      let mapArr = Object.values(mapObj);

      if (mapArr.length === 0) {
        svg.append('text')
           .attr('x', W/2).attr('y', H/2)
           .attr('text-anchor','middle')
           .attr('font-size', 14).attr('font-weight','600')
           .attr('font-family',"'Plus Jakarta Sans',sans-serif")
           .attr('fill','#64748b')
           .text('Tidak ada kampus yang sesuai filter.');
        return;
      }

      const rScale = d3.scaleSqrt()
        .domain([0, d3.max(mapArr, d => d.jumlah)])
        .range([0, 36]);

      const origin = proj([SAMARINDA.lng, SAMARINDA.lat]);

      // Garis putus
      mapArr.forEach(u => {
        if (Math.abs(u.lat - SAMARINDA.lat) < 0.1 && Math.abs(u.lng - SAMARINDA.lng) < 0.1) return;
        const pt = proj([u.lng, u.lat]);
        svg.append('line')
          .attr('x1', origin[0]).attr('y1', origin[1])
          .attr('x2', pt[0]).attr('y2', pt[1])
          .attr('stroke', '#94a3b8').attr('stroke-width', 0.8)
          .attr('stroke-dasharray', '4,3').attr('opacity', 0.55);
      });

      // Bubble
      mapArr.forEach(u => {
        const pt  = proj([u.lng, u.lat]);
        const r   = rScale(u.jumlah);
        const isSamarinda = Math.abs(u.lat - SAMARINDA.lat) < 0.5 && Math.abs(u.lng - SAMARINDA.lng) < 0.5;
        const col = isSamarinda ? '#ef4444' : (COLOR_PULAU[u.pulau] || '#0891b2');

        svg.append('circle')
          .attr('cx', pt[0]).attr('cy', pt[1])
          .attr('r', r + 6).attr('fill', col).attr('opacity', 0.1);

        svg.append('circle')
          .attr('cx', pt[0]).attr('cy', pt[1])
          .attr('r', r)
          .attr('fill', col).attr('opacity', 0.3)
          .attr('stroke', col).attr('stroke-width', 1.5)
          .attr('cursor', 'pointer')
          .on('mousemove', function(event) {
            const box = wrap.getBoundingClientRect();
            tooltip.innerHTML =
              '<strong>' + u.nama + '</strong> (' + u.jenis + ')<br>' +
              '<span class="tt-jumlah">' + u.jumlah + ' alumni</span><br>' +
              '<span class="tt-detail">SNBP ' + u.snbp + ' · SNBT ' + u.snbt + ' · Mandiri ' + u.mandiri + '</span>';
            tooltip.style.opacity = '1';
            tooltip.style.left = (event.clientX - box.left + 14) + 'px';
            tooltip.style.top  = (event.clientY - box.top  - 52) + 'px';
          })
          .on('mouseleave', () => { tooltip.style.opacity = '0'; });

        const fs = Math.max(9, Math.min(r * 0.5, 13));
        svg.append('text')
          .attr('x', pt[0]).attr('y', pt[1] + fs * 0.38)
          .attr('text-anchor', 'middle')
          .attr('font-size', fs).attr('font-weight', '800')
          .attr('font-family', "'Plus Jakarta Sans', sans-serif")
          .attr('fill', 'white').attr('pointer-events', 'none')
          .text(u.jumlah);
      });

      // Titik Samarinda
      svg.append('circle').attr('cx', origin[0]).attr('cy', origin[1])
        .attr('r', 8).attr('fill', '#ef4444').attr('stroke', 'white').attr('stroke-width', 2.5);
      svg.append('circle').attr('cx', origin[0]).attr('cy', origin[1])
        .attr('r', 14).attr('fill', 'none').attr('stroke', '#ef4444').attr('stroke-width', 2).attr('opacity', 0.4);
      svg.append('text')
        .attr('x', origin[0] + 16).attr('y', origin[1] - 10)
        .attr('font-size', 11).attr('font-weight', '800')
        .attr('font-family', "'Plus Jakarta Sans', sans-serif")
        .attr('fill', '#1e293b')
        .text('SMAN 5 Samarinda');
    }
  })();


  /* ═══════════════════════════
     2. STACKED BAR (Chart.js)
  ═══════════════════════════ */
  (function() {
    if (!TOP8_DATA.length) return;
    new Chart(document.getElementById('chartStackedBar'), {
      type: 'bar',
      data: {
        labels: TOP8_DATA.map(u => u.kode || u.nama),
        datasets: [
          { label:'SNBP',    data: TOP8_DATA.map(u => u.snbp),    backgroundColor:'#16a34a' },
          { label:'SNBT',    data: TOP8_DATA.map(u => u.snbt),    backgroundColor:'#2563eb' },
          { label:'Mandiri', data: TOP8_DATA.map(u => u.mandiri), backgroundColor:'#d97706' },
        ]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend:{ display:false } },
        scales: {
          x: { stacked:true, grid:{ display:false },
               ticks:{ font:{ family:"'Plus Jakarta Sans'", weight:'700', size:12 }, color:'#64748b' }},
          y: { stacked:true, border:{ display:false },
               grid:{ color:'rgba(0,0,0,.05)' },
               ticks:{ font:{ family:"'Plus Jakarta Sans'", size:11 }, color:'#94a3b8' }}
        }
      }
    });
  })();


  /* ═══════════════════════
     3. DONUT (Chart.js)
  ═══════════════════════ */
  (function() {
    const jalurColors = { SNBP:'#16a34a', SNBT:'#2563eb', Mandiri:'#d97706', Kedinasan:'#7c3aed' };
    const labels = Object.keys(JALUR_DIST).filter(k => JALUR_DIST[k] > 0);
    const values = labels.map(k => JALUR_DIST[k]);
    const colors = labels.map(k => jalurColors[k] || '#94a3b8');

    new Chart(document.getElementById('chartDonut'), {
      type: 'doughnut',
      data: {
        datasets: [{
          data: values,
          backgroundColor: colors,
          borderWidth: 3, borderColor:'#ffffff', hoverOffset:4,
        }]
      },
      options: {
        responsive: false, cutout:'70%',
        plugins: { legend:{ display:false } }
      }
    });
  })();


  /* ═══════════════════════════
     4. TREND LINE (Chart.js)
  ═══════════════════════════ */
  (function() {
    if (!TREN_DATA.length) return;
    new Chart(document.getElementById('chartTrend'), {
      type: 'line',
      data: {
        labels: TREN_DATA.map(t => t.tahun),
        datasets: [{
          data: TREN_DATA.map(t => t.cnt),
          borderColor:'#7c3aed',
          backgroundColor:'rgba(124,58,237,.08)',
          borderWidth:2.5, pointRadius:4,
          pointBackgroundColor:'#7c3aed',
          fill:true, tension:0.4
        }]
      },
      options: {
        responsive:true, maintainAspectRatio:false,
        plugins:{ legend:{ display:false } },
        scales:{
          x:{ grid:{ display:false }, ticks:{ font:{ family:"'Plus Jakarta Sans'", size:11, weight:'700' }, color:'#94a3b8' }},
          y:{ border:{ display:false }, grid:{ color:'rgba(0,0,0,.04)' }, ticks:{ font:{ family:"'Plus Jakarta Sans'", size:10 }, color:'#94a3b8' }}
        }
      }
    });
  })();


  /* ═══════════════════════
     5. HEATMAP TABLE
  ═══════════════════════ */
  (function() {
    const years  = ['2021','2022','2023'];
    const maxPct = Math.max(...PRODI_HEATMAP.flatMap(r => r.pct));
    const tbl    = document.getElementById('heatmapTable');

    function heatColor(pct) {
      const t = pct / maxPct;
      if      (t < 0.3) return { bg:`rgba(219,234,254,${0.4+t})`,     text:'#1e40af' };
      else if (t < 0.6) return { bg:`rgba(59,130,246,${0.45+t*0.4})`, text:'#ffffff' };
      else               return { bg:`rgba(29,78,216,${0.75+t*0.25})`, text:'#ffffff' };
    }

    let html = `<thead><tr><th style="text-align:left;">Program Studi</th>${years.map(y=>`<th>${y}</th>`).join('')}</tr></thead><tbody>`;
    PRODI_HEATMAP.forEach(row => {
      html += `<tr><td>${row.nama}</td>${row.pct.map(p=>{const {bg,text}=heatColor(p);return `<td><div class="heatmap-cell" style="background:${bg};color:${text};">${p}%</div></td>`;}).join('')}</tr>`;
    });
    tbl.innerHTML = html + '</tbody>';
  })();


  /* ═══════════════════════════════
     6. SCATTER / BUBBLE (Chart.js)
     Menggunakan data UNIV_DATA dari DB
  ═══════════════════════════════ */
  (function() {
    // Buat scatter data dari UNIV_DATA
    // keketatan = estimasi berdasarkan ukuran kampus (bisa diganti data nyata)
    const keketatanEst = {
      'UNMUL':2.1, 'UB':5.4, 'UGM':8.2, 'UNDIP':7.6, 'ITK':3.5,
      'ITB':11.8, 'UNLAM':4.2, 'UNTAN':6.1, 'UNHAS':9.5, 'UI':14.3,
      'UPR':2.8, 'ITS':10.2, 'AKPOL':12.5, 'UNS':6.8, 'UNPAD':7.9,
    };

    const scatterData = UNIV_DATA
      .filter(u => u.jumlah > 0)
      .map(u => ({
        kode: u.kode,
        x: keketatanEst[u.kode] || (3 + Math.random() * 8), // fallback estimasi acak
        y: u.jumlah,
        r: Math.max(4, Math.min(Math.sqrt(u.jumlah) * 1.8, 16)),
        pulau: u.pulau,
      }));

    if (!scatterData.length) return;

    new Chart(document.getElementById('chartScatter'), {
      type: 'bubble',
      data: {
        datasets: scatterData.map(d => ({
          label: d.kode,
          data: [{ x:d.x, y:d.y, r:d.r }],
          backgroundColor: (COLOR_PULAU[d.pulau] || '#64748b') + '55',
          borderColor:      COLOR_PULAU[d.pulau] || '#64748b',
          borderWidth: 2,
        }))
      },
      options: {
        responsive:true, maintainAspectRatio:false,
        layout:{ padding:20 },
        plugins:{
          legend:{ display:false },
          tooltip:{ callbacks:{ label: ctx => {
            const d = scatterData[ctx.datasetIndex];
            return ` ${d.kode}: ${d.y} alumni · Keketatan ×${d.x.toFixed(1)}`;
          }}}
        },
        scales:{
          x:{ title:{ display:true, text:'Keketatan (pendaftar ÷ kursi)', font:{ family:"'Plus Jakarta Sans'", size:12, weight:'700' }, color:'#64748b' },
              min:0, max:18, grid:{ color:'rgba(0,0,0,.04)' }, ticks:{ font:{ family:"'Plus Jakarta Sans'", size:11 }, color:'#94a3b8' }},
          y:{ title:{ display:true, text:'Jumlah alumni diterima', font:{ family:"'Plus Jakarta Sans'", size:12, weight:'700' }, color:'#64748b' },
              min:0, border:{ display:false }, grid:{ color:'rgba(0,0,0,.04)' }, ticks:{ font:{ family:"'Plus Jakarta Sans'", size:11 }, color:'#94a3b8' }}
        }
      },
      plugins:[{
        afterDatasetDraw(chart, args) {
          const { ctx } = chart;
          const d    = scatterData[args.index];
          const meta = chart.getDatasetMeta(args.index);
          if (!meta.data[0]) return;
          const { x, y } = meta.data[0];
          ctx.save();
          ctx.font = "bold 11px 'Plus Jakarta Sans', sans-serif";
          ctx.fillStyle = '#1e293b';
          ctx.textAlign = 'center';
          ctx.fillText(d.kode, x, y + 4);
          ctx.restore();
        }
      }]
    });
  })();


  /* ─── Navbar ─── */
  (function() {
    const navbar = document.getElementById('navbar');
    let lastY = 0;
    window.addEventListener('scroll', function() {
      const y = window.scrollY;
      if (y > lastY && y > 80) navbar.classList.add('hidden');
      else navbar.classList.remove('hidden');
      navbar.classList.toggle('scrolled', y > 10);
      lastY = y <= 0 ? 0 : y;
    }, { passive:true });
  })();

  /* ─── Hamburger ─── */
  (function() {
    const btn = document.getElementById('menuBtn'), menu = document.getElementById('mobileMenu'), icon = document.getElementById('menuIcon');
    let open = false;
    btn.addEventListener('click', function() {
      open = !open; menu.classList.toggle('open', open);
      btn.setAttribute('aria-expanded', open);
      icon.className = open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
    });
    menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
      open = false; menu.classList.remove('open'); icon.className = 'fa-solid fa-bars';
    }));
    document.addEventListener('click', e => {
      if (open && !menu.contains(e.target) && !btn.contains(e.target)) {
        open = false; menu.classList.remove('open'); icon.className = 'fa-solid fa-bars';
      }
    });
  })();



  /* ─── Hero Search ─── */
  (function() {
    const input = document.getElementById('heroSearch');
    const msg   = document.getElementById('searchMsg');
    const names = UNIV_DATA.map(u => u.nama);
    input.addEventListener('input', function() {
      const q = this.value.trim().toLowerCase();
      if (!q) { msg.textContent = ''; return; }
      const found = names.filter(n => n.toLowerCase().includes(q));
      if (found.length === 0) {
        msg.textContent = 'Kampus tidak ditemukan dalam data.'; msg.style.color = '#ef4444';
      } else {
        msg.textContent = 'Ditemukan: ' + found.slice(0,4).join(', ') + (found.length > 4 ? '...' : '');
        msg.style.color = '#2563eb';
      }
    });
    input.addEventListener('keydown', e => {
      if (e.key === 'Enter' && input.value.trim())
        window.location.href = 'alumni.php?q=' + encodeURIComponent(input.value.trim());
    });
  })();

  /* ─── Scroll to top ─── */
  (function() {
    const btn = document.getElementById('scrollTop');
    window.addEventListener('scroll', () => btn.classList.toggle('visible', window.scrollY > 400), { passive:true });
    btn.addEventListener('click', () => window.scrollTo({ top:0, behavior:'smooth' }));
  })();

  </script>

</body>
</html>