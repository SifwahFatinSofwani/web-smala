<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal Alumni SMAN 5 Samarinda</title>

  <!-- Preconnect -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- CSS -->
  <link rel="stylesheet" href="../css/style.css">

  <!--
    CATATAN PENGGUNAAN PHP:
    Ganti semua teks statis di bawah dengan variabel PHP saat diintegrasikan.
  -->
</head>
<body class="bg-cross">

  <!-- ===== NAVBAR ===== -->
  <nav class="navbar" id="navbar">
    <div class="container nav-inner">
      <a href="index.html" class="nav-brand">
        <img src="../image/logosma5.png" alt="Logo SMAN 5 Samarinda" class="nav-logo">
        <span>SMAN 5 Samarinda</span>
      </a>
      <div class="nav-menu">
        <a href="#" class="active">Beranda</a>
        <a href="alumni.html">Data Alumni</a>
        <a href="#jadwal">Jadwal SNPMB</a>
      </div>
      <button class="menu-btn" id="menuBtn" aria-label="Buka menu" aria-expanded="false">
        <i class="fa-solid fa-bars" id="menuIcon"></i>
      </button>
    </div>
  </nav>

  <!-- Mobile Menu Panel -->
  <div class="mobile-menu" id="mobileMenu" role="navigation" aria-label="Menu mobile">
    <a href="#" class="active">Beranda</a>
    <a href="alumni.html">Data Alumni</a>
    <a href="#jadwal">Jadwal SNPMB</a>
  </div>


  <!-- ===== HERO ===== -->
  <section class="hero-section">
    <div class="container grid-2-col">

      <!-- Teks -->
      <div class="hero-content">
        <h1 class="hero-title">
          Cari Kampus<br>Impian Anda<br>dari Riwayat<br>Alumni yang ada
        </h1>
        <p class="hero-desc">
          Lihat data kampus yang dimasuki oleh alumni jalur SNBP/SNBT dengan mudah serta lengkap.
        </p>

        <!-- Search -->
        <div class="search-box" role="search">
          <input type="text" id="heroSearch" placeholder="Cari Kampus / Jurusan..."
            class="search-input" aria-label="Cari kampus atau jurusan">
          <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
          </svg>
        </div>
        <p id="searchMsg" style="font-size:13px;color:#2563eb;margin-bottom:8px;margin-top:-6px;min-height:18px;font-weight:600;"></p>

        <a href="alumni.html" class="btn-action">
          <span>Lihat Data Alumni</span>
          <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
          </svg>
        </a>
      </div>

      <!-- Gambar -->
      <div class="hero-image">
        <div class="img-card">
          <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=800&q=80"
            alt="Siswa SMAN 5 Samarinda" loading="lazy">
          <div class="img-overlay">
            <span class="badge">Galeri</span>
            <p style="color:white;font-weight:700;font-size:17px;">Angkatan Lulusan Tahun 2023</p>
          </div>
        </div>
      </div>

    </div>
  </section>


  <!-- =====================================================
       SECTION: VISUALISASI DATA
       ► Menggantikan section "Sebaran Universitas" lama
       ► Berisi: stat strip, bubble map, stacked bar,
                 donut, trend line, heatmap, scatter plot
  ===================================================== -->
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

      <!-- ── 1. STAT STRIP ── -->
      <div class="viz-stat-strip">
        <div class="viz-stat-card">
          <div class="viz-stat-accent" style="background:#2563eb;"></div>
          <div class="viz-stat-label"><i class="fa-solid fa-users" style="color:#2563eb;"></i> Total Alumni</div>
          <div class="viz-stat-val">491</div>
          <div class="viz-stat-sub">data terverifikasi</div>
        </div>
        <div class="viz-stat-card">
          <div class="viz-stat-accent" style="background:#16a34a;"></div>
          <div class="viz-stat-label"><i class="fa-solid fa-building-columns" style="color:#16a34a;"></i> Universitas</div>
          <div class="viz-stat-val">47</div>
          <div class="viz-stat-sub">kampus berbeda</div>
        </div>
        <div class="viz-stat-card">
          <div class="viz-stat-accent" style="background:#d97706;"></div>
          <div class="viz-stat-label"><i class="fa-solid fa-map-location-dot" style="color:#d97706;"></i> Kota Tujuan</div>
          <div class="viz-stat-val">18</div>
          <div class="viz-stat-sub">kota di Indonesia</div>
        </div>
        <div class="viz-stat-card">
          <div class="viz-stat-accent" style="background:#7c3aed;"></div>
          <div class="viz-stat-label"><i class="fa-solid fa-chart-line" style="color:#7c3aed;"></i> Lolos PTN</div>
          <div class="viz-stat-val">68<span style="font-size:20px;font-weight:700;">%</span></div>
          <div class="viz-stat-sub">angkatan 2023</div>
        </div>
      </div>


      <!-- ── 2. PETA BUBBLE MAP D3 ── -->
      <div style="margin-bottom: 48px;">
        <div class="section-header" style="margin-bottom: 28px;">
          <div class="section-eyebrow">Peta Interaktif</div>
          <h2 class="section-title" style="font-size:26px;">Jejak Alumni se-Nusantara</h2>
          <p class="section-subtitle">
            Ukuran lingkaran proporsional dengan jumlah alumni. Arahkan kursor ke tiap lingkaran untuk detail lengkap.
          </p>
        </div>

        <div class="viz-map-wrapper">
          <!-- Container peta D3 -->
          <div id="d3-indonesia-map">
            <div class="map-loading-state">
              <i class="fa-solid fa-spinner fa-spin"></i> Memuat peta…
            </div>
          </div>
          <!-- Tooltip (posisi absolute relatif ke viz-map-wrapper) -->
          <div class="map-bubble-tooltip" id="bubbleTooltip"></div>

          <!-- Legenda -->
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


      <!-- ── 3. CHARTS ROW: Stacked Bar + Donut + Trend ── -->
      <div style="margin-bottom: 48px;">
        <div class="section-header" style="margin-bottom: 28px;">
          <div class="section-eyebrow">Analisis Jalur Masuk</div>
          <h2 class="section-title" style="font-size:26px;">Distribusi per Kampus &amp; Jalur Seleksi</h2>
        </div>

        <div class="viz-charts-grid">

          <!-- Stacked Bar: Jalur per Universitas -->
          <div class="viz-card">
            <div class="viz-card-label">Universitas Tujuan</div>
            <div class="viz-card-title">Jumlah Alumni per Kampus (Top 8)</div>

            <!-- Custom legend -->
            <div class="chartjs-legend">
              <div class="chartjs-legend-item">
                <div class="chartjs-legend-swatch" style="background:#16a34a;"></div> SNBP
              </div>
              <div class="chartjs-legend-item">
                <div class="chartjs-legend-swatch" style="background:#2563eb;"></div> SNBT
              </div>
              <div class="chartjs-legend-item">
                <div class="chartjs-legend-swatch" style="background:#d97706;"></div> Mandiri
              </div>
            </div>

            <div style="position:relative; height:320px;">
              <canvas id="chartStackedBar"></canvas>
            </div>
          </div>

          <!-- Right column -->
          <div class="viz-right-col">

            <!-- Donut: Jalur -->
            <div class="viz-card">
              <div class="viz-card-label">Jalur Seleksi</div>
              <div class="viz-card-title">Distribusi Jalur Masuk</div>
              <div class="donut-layout">
                <div class="donut-canvas-wrap">
                  <canvas id="chartDonut" width="150" height="150"></canvas>
                  <div class="donut-center-label">
                    <div class="donut-center-val" id="donutCenterVal">491</div>
                    <div class="donut-center-sub">TOTAL</div>
                  </div>
                </div>
                <div class="donut-legend">
                  <div class="donut-legend-item">
                    <div class="dl-swatch" style="background:#16a34a;"></div>
                    <div class="dl-name">SNBP</div>
                    <div class="dl-val">197 <span class="dl-pct">40%</span></div>
                  </div>
                  <div class="donut-legend-item">
                    <div class="dl-swatch" style="background:#2563eb;"></div>
                    <div class="dl-name">SNBT</div>
                    <div class="dl-val">221 <span class="dl-pct">45%</span></div>
                  </div>
                  <div class="donut-legend-item">
                    <div class="dl-swatch" style="background:#d97706;"></div>
                    <div class="dl-name">Mandiri</div>
                    <div class="dl-val">73 <span class="dl-pct">15%</span></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Trend sparkline -->
            <div class="viz-card">
              <div class="viz-card-label">Tren Tahunan</div>
              <div class="viz-card-title">Alumni Lolos PTN per Tahun</div>
              <div class="trend-year-cards">
                <div class="trend-year-card" style="background:#f0fdf4;border-color:#bbf7d0;">
                  <div class="tyc-yr">2021</div>
                  <div class="tyc-val" style="color:#15803d;">142</div>
                  <div class="tyc-delta" style="color:#15803d;">baseline</div>
                </div>
                <div class="trend-year-card" style="background:#eff6ff;border-color:#bfdbfe;">
                  <div class="tyc-yr">2022</div>
                  <div class="tyc-val" style="color:#1d4ed8;">168</div>
                  <div class="tyc-delta" style="color:#1d4ed8;">▲ +18%</div>
                </div>
                <div class="trend-year-card" style="background:#faf5ff;border-color:#ddd6fe;">
                  <div class="tyc-yr">2023</div>
                  <div class="tyc-val" style="color:#7c3aed;">181</div>
                  <div class="tyc-delta" style="color:#7c3aed;">▲ +8%</div>
                </div>
              </div>
              <div style="position:relative; height:90px;">
                <canvas id="chartTrend"></canvas>
              </div>
            </div>

          </div>
        </div><!-- /viz-charts-grid -->
      </div>


      <!-- ── 4. HEATMAP & SCATTER ── -->
      <div>
        <div class="section-header" style="margin-bottom: 28px;">
          <div class="section-eyebrow">Deep Dive</div>
          <h2 class="section-title" style="font-size:26px;">Minat Program Studi &amp; Keketatan Kampus</h2>
        </div>

        <div style="display:grid; grid-template-columns:1fr; gap:24px;">

          <!-- Heatmap -->
          <div class="viz-card">
            <div class="viz-card-label">Heatmap Minat</div>
            <div class="viz-card-title">Program Studi × Angkatan (% Peminat)</div>
            <div style="overflow-x:auto;">
              <table class="heatmap-table" id="heatmapTable"></table>
            </div>
            <p class="heatmap-note">Warna semakin gelap = semakin banyak peminat pada tahun tersebut.</p>
          </div>

          <!-- Scatter / Bubble -->
          <div class="viz-card">
            <div class="viz-card-label">Scatter Plot</div>
            <div class="viz-card-title">Jumlah Alumni vs Keketatan Kampus (SNBT)</div>

            <!-- Custom legend scatter -->
            <div class="chartjs-legend" style="margin-bottom:16px;">
              <div class="chartjs-legend-item">
                <div class="chartjs-legend-swatch" style="background:#2563eb;border-radius:50%;"></div> Kalimantan
              </div>
              <div class="chartjs-legend-item">
                <div class="chartjs-legend-swatch" style="background:#7c3aed;border-radius:50%;"></div> Jawa
              </div>
              <div class="chartjs-legend-item">
                <div class="chartjs-legend-swatch" style="background:#0891b2;border-radius:50%;"></div> Lainnya
              </div>
            </div>

            <div style="position:relative; height:320px;">
              <canvas id="chartScatter"></canvas>
            </div>
            <p class="scatter-caption">
              Sumbu X = Estimasi rasio keketatan (pendaftar÷kursi). Sumbu Y = Jumlah alumni yang diterima.
              Radius lingkaran = Jumlah program studi yang diminati.
            </p>
          </div>

        </div>
      </div>


      <!-- ── Kedinasan (tetap ada) ── -->
      <div class="stat-card-container" style="margin-top:48px;">
        <h4>
          <i class="fa-solid fa-shield-halved" style="color:#eab308;font-size:16px;"></i>
          Info Jalur Kedinasan &amp; Aparat
        </h4>
        <div class="stat-grid">
          <div class="stat-box polri">
            <p>Bintara Polri</p>
            <h4>18 <span>Orang</span></h4>
          </div>
          <div class="stat-box akpol">
            <p>Taruna AKPOL</p>
            <h4>5 <span>Orang</span></h4>
          </div>
        </div>
      </div>

    </div>
  </section>
  <!-- ===== END SECTION VISUALISASI ===== -->


  <!-- ===== TREN JURUSAN (tetap) ===== -->
  <section class="trend-section">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Tren Minat Program Studi</h2>
        <p class="section-subtitle">8 Rumpun keilmuan favorit pilihan utama alumni kami.</p>
      </div>

      <div class="trend-grid">

        <div class="trend-card">
          <div class="trend-icon" style="background:#e0f2fe;color:#0284c7;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/>
            </svg>
          </div>
          <div class="trend-info">
            <h3>Teknologi Informasi &amp; Komputer</h3>
            <div class="progress-bar"><div class="progress-fill" style="width:20%;background:#0ea5e9;"></div></div>
            <span class="trend-stat">20% Peminat</span>
          </div>
        </div>

        <div class="trend-card">
          <div class="trend-icon" style="background:#fed7aa;color:#c2410c;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.832M11.42 15.17l-1.028-1.028M11.42 15.17l-4.242 4.242a2.652 2.652 0 01-3.75-3.75l4.242-4.242-1.028-1.028m8.72 4.708l1.028 1.028m-4.708-8.72l1.028 1.028M15.17 11.42l4.242-4.242a2.652 2.652 0 00-3.75-3.75l-4.242 4.242-1.028-1.028m-8.72 4.708l1.028 1.028"/>
            </svg>
          </div>
          <div class="trend-info">
            <h3>Teknik &amp; Rekayasa</h3>
            <div class="progress-bar"><div class="progress-fill" style="width:18%;background:#f97316;"></div></div>
            <span class="trend-stat">18% Peminat</span>
          </div>
        </div>

        <div class="trend-card">
          <div class="trend-icon" style="background:#fef08a;color:#ca8a04;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/>
            </svg>
          </div>
          <div class="trend-info">
            <h3>Bisnis &amp; Administrasi</h3>
            <div class="progress-bar"><div class="progress-fill" style="width:14%;background:#eab308;"></div></div>
            <span class="trend-stat">14% Peminat</span>
          </div>
        </div>

        <div class="trend-card">
          <div class="trend-icon" style="background:#dcfce7;color:#16a34a;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
            </svg>
          </div>
          <div class="trend-info">
            <h3>Kedokteran &amp; Kesehatan</h3>
            <div class="progress-bar"><div class="progress-fill" style="width:15%;background:#22c55e;"></div></div>
            <span class="trend-stat">15% Peminat</span>
          </div>
        </div>

        <div class="trend-card">
          <div class="trend-icon" style="background:#e0e7ff;color:#4338ca;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z"/>
            </svg>
          </div>
          <div class="trend-info">
            <h3>Psikologi &amp; Ilmu Sosial</h3>
            <div class="progress-bar"><div class="progress-fill" style="width:12%;background:#6366f1;"></div></div>
            <span class="trend-stat">12% Peminat</span>
          </div>
        </div>

        <div class="trend-card">
          <div class="trend-icon" style="background:#fee2e2;color:#dc2626;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/>
            </svg>
          </div>
          <div class="trend-info">
            <h3>Hukum &amp; Pemerintahan</h3>
            <div class="progress-bar"><div class="progress-fill" style="width:8%;background:#ef4444;"></div></div>
            <span class="trend-stat">8% Peminat</span>
          </div>
        </div>

        <div class="trend-card">
          <div class="trend-icon" style="background:#fdf4ff;color:#c026d3;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.854-3.853a3 3 0 00-4.243-4.242l-3.853 3.854a15.995 15.995 0 00-4.648 4.764m3.42 3.42a6 6 0 00-3.42-3.42"/>
            </svg>
          </div>
          <div class="trend-info">
            <h3>Seni &amp; Desain Kreatif</h3>
            <div class="progress-bar"><div class="progress-fill" style="width:8%;background:#d946ef;"></div></div>
            <span class="trend-stat">8% Peminat</span>
          </div>
        </div>

        <div class="trend-card">
          <div class="trend-icon" style="background:#f1f5f9;color:#475569;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
            </svg>
          </div>
          <div class="trend-info">
            <h3>Pendidikan &amp; Keguruan</h3>
            <div class="progress-bar"><div class="progress-fill" style="width:5%;background:#64748b;"></div></div>
            <span class="trend-stat">5% Peminat</span>
          </div>
        </div>

      </div>
    </div>
  </section>


  <!-- ===== TIMELINE SNPMB ===== -->
  <section class="timeline-section bg-cross" id="jadwal">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Alur Persiapan Masuk PTN</h2>
        <p class="section-subtitle">Catat estimasi jadwal tahapan seleksi agar tidak tertinggal informasi.</p>
      </div>

      <div class="timeline-wrapper">
        <div class="timeline-line"></div>

        <div class="timeline-item">
          <div class="timeline-dot" style="border-color:#0ea5e9;"></div>
          <div class="timeline-content">
            <span class="t-date" style="background:#e0f2fe;color:#0284c7;">Januari – Februari</span>
            <h3>SNBP (Jalur Prestasi)</h3>
            <p>Pembuatan akun SNPMB, penetapan siswa eligible, dan pendaftaran berbasis nilai rapor serta prestasi.</p>
          </div>
        </div>

        <div class="timeline-item">
          <div class="timeline-dot" style="border-color:#eab308;"></div>
          <div class="timeline-content">
            <span class="t-date" style="background:#fef08a;color:#ca8a04;">Maret – Mei</span>
            <h3>SNBT (Jalur Tes)</h3>
            <p>Pendaftaran UTBK, pelaksanaan ujian berbasis komputer serentak, dan pengumuman tingkat nasional.</p>
          </div>
        </div>

        <div class="timeline-item">
          <div class="timeline-dot" style="border-color:#22c55e;"></div>
          <div class="timeline-content">
            <span class="t-date" style="background:#dcfce7;color:#16a34a;">Juni – Agustus</span>
            <h3>Mandiri &amp; Kedinasan</h3>
            <p>Ujian mandiri masing-masing PTN dan tahapan seleksi (fisik, akademik, psikologi) sekolah kedinasan.</p>
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
            <div class="t-info">
              <h4>Budi Santoso</h4>
              <span>Teknik Sipil, ITS</span>
            </div>
          </div>
        </div>
        <div class="testi-card">
          <i class="fa-solid fa-quote-left quote-icon"></i>
          <p class="testi-text">"Untuk SNBP, perhatikan grafik nilaimu (Smt 1–5). Kalau sedikit turun, imbangi dengan sertifikat lomba level nasional/provinsi."</p>
          <div class="testi-author">
            <div class="t-avatar" style="background:#dcfce7;color:#16a34a;"><i class="fa-solid fa-user-nurse"></i></div>
            <div class="t-info">
              <h4>Siti Nurbaya</h4>
              <span>Kedokteran, UNMUL</span>
            </div>
          </div>
        </div>
        <div class="testi-card">
          <i class="fa-solid fa-quote-left quote-icon"></i>
          <p class="testi-text">"Kedisiplinan adalah kunci. Tes kedinasan butuh fisik mumpuni. Rutin lari dan jaga asupan gizi sejak semester 4 itu sangat krusial."</p>
          <div class="testi-author">
            <div class="t-avatar" style="background:#fef08a;color:#ca8a04;"><i class="fa-solid fa-user-shield"></i></div>
            <div class="t-info">
              <h4>Riko Wijaya</h4>
              <span>Taruna Akademi Kepolisian</span>
            </div>
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
          <div class="faq-answer">
            <p>Tentu. Data yang kami tampilkan dihimpun langsung berdasarkan pelaporan alumni ke pihak bimbingan konseling SMAN 5 Samarinda dengan verifikasi silang bersama Perguruan Tinggi terkait.</p>
          </div>
        </details>
        <details class="faq-item">
          <summary>Mengapa ada kampus yang tidak masuk dalam grafik?</summary>
          <div class="faq-answer">
            <p>Kami hanya menampilkan <strong>Top 8</strong> destinasi kampus per kriteria demi menjaga kerapian website. Untuk data yang lebih mendalam, Anda bisa hubungi pihak sekolah atau kunjungi halaman Data Alumni.</p>
          </div>
        </details>
        <details class="faq-item">
          <summary>Bisa minta kontak Kakak Tingkat yang kuliah di tujuan saya?</summary>
          <div class="faq-answer">
            <p>Portal web tidak menyediakan data pribadi alumni. Namun, Anda bisa mengunjungi Ruang BK untuk melihat jejak rekam mereka dan meminta kontak jika memang diizinkan oleh yang bersangkutan.</p>
          </div>
        </details>
        <details class="faq-item">
          <summary>Bagaimana cara melaporkan data saya sebagai alumni baru?</summary>
          <div class="faq-answer">
            <p>Klik tombol <strong>"Lapor Data"</strong> di bagian bawah halaman ini, lalu isi formulir dengan data diri dan universitas yang Anda masuki. Data Anda akan diverifikasi oleh pihak sekolah sebelum ditampilkan.</p>
          </div>
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
        <div>
          <a href="lapor.html" class="btn-primary">
            Lapor Data <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </section>


  <!-- ===== FOOTER ===== -->
  <footer class="footer">
    <div class="container footer-grid">
      <div class="brand-col">
        <h3 class="footer-title">SMAN 5 SAMARINDA</h3>
        <p class="footer-desc">Destinasi pendidikan menengah atas unggulan di Kalimantan Timur. Kami menyajikan ruang belajar asri, terpadu, serta komunitas siswa yang bebas berekspresi dan berprestasi.</p>
        <div class="footer-contact-item">
          <i class="fa-solid fa-location-dot" style="margin-top:0;"></i>
          <span>Jl. Ir. H. Juanda No. 1, Air Hitam, Kec. Samarinda Ulu, Kota Samarinda, Kalimantan Timur 75124</span>
        </div>
      </div>
      <div class="link-col">
        <h3 class="footer-title">Jelajahi</h3>
        <ul class="footer-links">
          <li><a href="index.php"><i class="fa-solid fa-chevron-right"></i> Beranda</a></li>
          <li><a href="alumni.html"><i class="fa-solid fa-chevron-right"></i> Data Alumni</a></li>
          <li><a href="#"><i class="fa-solid fa-chevron-right"></i> Galeri Prestasi</a></li>
          <li><a href="#"><i class="fa-solid fa-chevron-right"></i> Tentang Kami</a></li>
        </ul>
      </div>
      <div class="contact-col">
        <h3 class="footer-title">Hubungi Kami</h3>
        <div class="footer-contact-list">
          <div class="footer-contact-item">
            <i class="fa-solid fa-envelope"></i>
            <span>info@sman5samarinda.sch.id</span>
          </div>
          <div class="footer-contact-item">
            <i class="fa-solid fa-phone"></i>
            <span>(0541) 1234567</span>
          </div>
          <div class="footer-contact-item">
            <i class="fa-regular fa-clock"></i>
            <span>08:00 - 15:00 WITA</span>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <p>&copy; 2026 SMAN 5 Samarinda. All Rights Reserved.</p>
      </div>
    </div>
  </footer>

  <button class="scroll-top" id="scrollTop" aria-label="Scroll ke atas">
    <i class="fa-solid fa-chevron-up"></i>
  </button>


  <!-- ===== SCRIPTS ===== -->
  <!-- D3.js + TopoJSON untuk Peta Bubble -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/7.8.5/d3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/topojson/3.0.2/topojson.min.js"></script>
  <!-- Chart.js untuk grafik -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>

  <script>
  /* ══════════════════════════════════════════════════════════
     DATA UNIVERSITAS
     → Saat integrasi PHP, generate array ini dari query:
       SELECT kode, nama, kota, pulau, lat, lng,
              COUNT(*) as jumlah,
              SUM(jalur='SNBP') as snbp,
              SUM(jalur='SNBT') as snbt,
              SUM(jalur='Mandiri') as mandiri
       FROM alumni GROUP BY kampus
  ══════════════════════════════════════════════════════════ */
  const UNIV_DATA = [
    { kode:'UNMUL', nama:'Univ. Mulawarman',          kota:'Samarinda',     pulau:'Kalimantan', lat:-0.5022, lng:117.1536, jumlah:125, snbp:55, snbt:50, mandiri:20 },
    { kode:'UB',    nama:'Univ. Brawijaya',            kota:'Malang',        pulau:'Jawa',       lat:-7.9666, lng:112.6326, jumlah:72,  snbp:30, snbt:32, mandiri:10 },
    { kode:'UGM',   nama:'Univ. Gadjah Mada',          kota:'Yogyakarta',    pulau:'Jawa',       lat:-7.7956, lng:110.3695, jumlah:60,  snbp:18, snbt:30, mandiri:12 },
    { kode:'UNDIP', nama:'Univ. Diponegoro',           kota:'Semarang',      pulau:'Jawa',       lat:-7.0045, lng:110.4202, jumlah:50,  snbp:20, snbt:22, mandiri:8  },
    { kode:'ITK',   nama:'Institut Tekn. Kalimantan',  kota:'Balikpapan',    pulau:'Kalimantan', lat:-1.2742, lng:116.8526, jumlah:54,  snbp:24, snbt:22, mandiri:8  },
    { kode:'ITB',   nama:'Institut Tekn. Bandung',     kota:'Bandung',       pulau:'Jawa',       lat:-6.9175, lng:107.6191, jumlah:40,  snbp:10, snbt:25, mandiri:5  },
    { kode:'UNLAM', nama:'Univ. Lambung Mangkurat',    kota:'Banjarmasin',   pulau:'Kalimantan', lat:-3.3194, lng:114.5905, jumlah:32,  snbp:15, snbt:12, mandiri:5  },
    { kode:'UNTAN', nama:'Univ. Tanjungpura',          kota:'Pontianak',     pulau:'Kalimantan', lat:-0.0263, lng:109.3425, jumlah:24,  snbp:12, snbt:8,  mandiri:4  },
    { kode:'UNHAS', nama:'Univ. Hasanuddin',           kota:'Makassar',      pulau:'Lainnya',    lat:-5.1477, lng:119.4327, jumlah:22,  snbp:8,  snbt:10, mandiri:4  },
    { kode:'UI',    nama:'Univ. Indonesia',            kota:'Depok',         pulau:'Jawa',       lat:-6.3601, lng:106.8267, jumlah:20,  snbp:5,  snbt:10, mandiri:5  },
    { kode:'UPR',   nama:'Univ. Palangka Raya',        kota:'Palangka Raya', pulau:'Kalimantan', lat:-2.2099, lng:113.9133, jumlah:12,  snbp:6,  snbt:5,  mandiri:1  },
  ];

  /* Data heatmap prodi
     → Ganti dengan query: SELECT prodi, angkatan, COUNT(*) ...
  */
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

  const COLOR_PULAU = { 'Kalimantan':'#2563eb', 'Jawa':'#7c3aed', 'Lainnya':'#0891b2' };
  const SAMARINDA   = { lat:-0.5022, lng:117.1536 };


  /* ══════════════════
     1. PETA BUBBLE D3
  ══════════════════ */
  (function() {
    const wrap    = document.getElementById('d3-indonesia-map');
    const tooltip = document.getElementById('bubbleTooltip');
    const W = wrap.clientWidth || 860;
    const H = Math.round(W * 0.44);

    const proj = d3.geoMercator()
      .center([118, -2])
      .scale(W * 1.05)
      .translate([W / 2, H / 2]);

    const pathGen = d3.geoPath().projection(proj);

    const svg = d3.select('#d3-indonesia-map')
      .html('')
      .append('svg')
      .attr('viewBox', `0 0 ${W} ${H}`)
      .attr('width', '100%')
      .style('display','block');

    svg.append('rect').attr('width', W).attr('height', H)
      .attr('fill','#dbeafe').attr('rx', 12);

    d3.json('https://cdn.jsdelivr.net/npm/world-atlas@2/countries-110m.json').then(world => {
      const countries = topojson.feature(world, world.objects.countries);

      // Semua negara
      svg.append('g').selectAll('path')
        .data(countries.features)
        .join('path')
        .attr('d', pathGen)
        .attr('fill', d => d.id === '360' ? '#bfdbfe' : '#e0eaf7')
        .attr('stroke','#b8cfe0').attr('stroke-width', 0.4);

      // Indonesia highlight
      svg.append('g').selectAll('path')
        .data(countries.features.filter(d => d.id === '360'))
        .join('path')
        .attr('d', pathGen)
        .attr('fill','#dbeafe').attr('stroke','#93c5fd').attr('stroke-width', 1);

      const rScale  = d3.scaleSqrt()
        .domain([0, d3.max(UNIV_DATA, d => d.jumlah)])
        .range([0, 36]);

      const origin = proj([SAMARINDA.lng, SAMARINDA.lat]);

      // Garis putus dari Samarinda
      UNIV_DATA.forEach(u => {
        if (u.kode === 'UNMUL') return;
        const pt = proj([u.lng, u.lat]);
        svg.append('line')
          .attr('x1', origin[0]).attr('y1', origin[1])
          .attr('x2', pt[0]).attr('y2', pt[1])
          .attr('stroke','#94a3b8').attr('stroke-width', 0.8)
          .attr('stroke-dasharray','4,3').attr('opacity', 0.55);
      });

      // Bubble per universitas
      UNIV_DATA.forEach(u => {
        const pt  = proj([u.lng, u.lat]);
        const r   = rScale(u.jumlah);
        const col = u.kode === 'UNMUL' ? '#ef4444' : (COLOR_PULAU[u.pulau] || '#0891b2');

        // Lingkaran luar (glow)
        svg.append('circle')
          .attr('cx', pt[0]).attr('cy', pt[1])
          .attr('r', r + 6).attr('fill', col).attr('opacity', 0.1);

        // Lingkaran utama
        svg.append('circle')
          .attr('cx', pt[0]).attr('cy', pt[1])
          .attr('r', r)
          .attr('fill', col).attr('opacity', 0.3)
          .attr('stroke', col).attr('stroke-width', 1.5)
          .attr('cursor','pointer')
          .on('mousemove', function(event) {
            const box = wrap.getBoundingClientRect();
            tooltip.innerHTML =
              '<strong>' + u.nama + '</strong><br>' +
              '<span class="tt-jumlah">' + u.jumlah + ' alumni</span><br>' +
              '<span class="tt-detail">SNBP ' + u.snbp + ' · SNBT ' + u.snbt + ' · Mandiri ' + u.mandiri + '</span>';
            tooltip.style.opacity = '1';
            tooltip.style.left = (event.clientX - box.left + 14) + 'px';
            tooltip.style.top  = (event.clientY - box.top  - 52) + 'px';
          })
          .on('mouseleave', () => { tooltip.style.opacity = '0'; });

        // Label angka di dalam bubble
        const fs = Math.max(9, Math.min(r * 0.5, 13));
        svg.append('text')
          .attr('x', pt[0]).attr('y', pt[1] + fs * 0.38)
          .attr('text-anchor','middle')
          .attr('font-size', fs).attr('font-weight','800')
          .attr('font-family',"'Plus Jakarta Sans', sans-serif")
          .attr('fill','white').attr('pointer-events','none')
          .text(u.jumlah);
      });

      // Titik asal Samarinda
      svg.append('circle').attr('cx', origin[0]).attr('cy', origin[1])
        .attr('r', 8).attr('fill','#ef4444')
        .attr('stroke','white').attr('stroke-width', 2.5);
      svg.append('circle').attr('cx', origin[0]).attr('cy', origin[1])
        .attr('r', 14).attr('fill','none')
        .attr('stroke','#ef4444').attr('stroke-width', 2).attr('opacity', 0.4);
      svg.append('text')
        .attr('x', origin[0] + 16).attr('y', origin[1] - 10)
        .attr('font-size', 11).attr('font-weight','800')
        .attr('font-family',"'Plus Jakarta Sans', sans-serif")
        .attr('fill','#1e293b')
        .text('SMAN 5 Samarinda');

    }).catch(() => {
      document.getElementById('d3-indonesia-map').innerHTML =
        '<div class="map-loading-state"><i class="fa-solid fa-triangle-exclamation"></i> Gagal memuat peta. Periksa koneksi.</div>';
    });
  })();


  /* ═══════════════════════════
     2. STACKED BAR (Chart.js)
  ═══════════════════════════ */
  (function() {
    const top8 = [...UNIV_DATA].sort((a,b) => b.jumlah - a.jumlah).slice(0, 8);
    new Chart(document.getElementById('chartStackedBar'), {
      type: 'bar',
      data: {
        labels: top8.map(u => u.kode),
        datasets: [
          { label:'SNBP',    data: top8.map(u => u.snbp),    backgroundColor:'#16a34a' },
          { label:'SNBT',    data: top8.map(u => u.snbt),    backgroundColor:'#2563eb' },
          { label:'Mandiri', data: top8.map(u => u.mandiri), backgroundColor:'#d97706' },
        ]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend:{ display:false } },
        scales: {
          x: { stacked: true, grid:{ display:false },
               ticks:{ font:{ family:"'Plus Jakarta Sans'", weight:'700', size:12 }, color:'#64748b' }},
          y: { stacked: true, border:{ display:false },
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
    const snbp  = UNIV_DATA.reduce((s,u) => s+u.snbp,    0);
    const snbt  = UNIV_DATA.reduce((s,u) => s+u.snbt,    0);
    const mand  = UNIV_DATA.reduce((s,u) => s+u.mandiri, 0);
    const total = snbp + snbt + mand;

    document.getElementById('donutCenterVal').textContent = total;

    // Update legend values dynamically
    const items = document.querySelectorAll('.donut-legend-item');
    [[snbp],[snbt],[mand]].forEach(([v], i) => {
      items[i].querySelector('.dl-val').innerHTML =
        v + ' <span class="dl-pct">' + Math.round(v/total*100) + '%</span>';
    });

    new Chart(document.getElementById('chartDonut'), {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [snbp, snbt, mand],
          backgroundColor: ['#16a34a','#2563eb','#d97706'],
          borderWidth: 3, borderColor:'#ffffff', hoverOffset: 4,
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
    new Chart(document.getElementById('chartTrend'), {
      type: 'line',
      data: {
        labels: ['2019','2020','2021','2022','2023'],
        datasets: [{
          data: [110, 125, 142, 168, 181],
          borderColor: '#7c3aed',
          backgroundColor: 'rgba(124,58,237,.08)',
          borderWidth: 2.5, pointRadius: 4,
          pointBackgroundColor:'#7c3aed',
          fill: true, tension: 0.4
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend:{ display:false } },
        scales: {
          x: { grid:{ display:false },
               ticks:{ font:{ family:"'Plus Jakarta Sans'", size:11, weight:'700' }, color:'#94a3b8' }},
          y: { border:{ display:false }, grid:{ color:'rgba(0,0,0,.04)' },
               ticks:{ font:{ family:"'Plus Jakarta Sans'", size:10 }, color:'#94a3b8' }}
        }
      }
    });
  })();


  /* ═══════════════════════
     5. HEATMAP TABLE
  ═══════════════════════ */
  (function() {
    const years   = ['2021','2022','2023'];
    const maxPct  = Math.max(...PRODI_HEATMAP.flatMap(r => r.pct));
    const tbl     = document.getElementById('heatmapTable');

    function heatColor(pct) {
      const t = pct / maxPct;
      if      (t < 0.3) return { bg:`rgba(219,234,254,${0.4+t})`,   text:'#1e40af' };
      else if (t < 0.6) return { bg:`rgba(59,130,246,${0.45+t*0.4})`, text:'#ffffff' };
      else               return { bg:`rgba(29,78,216,${0.75+t*0.25})`, text:'#ffffff' };
    }

    let html = `<thead><tr>
      <th style="text-align:left;">Program Studi</th>
      ${years.map(y => `<th>${y}</th>`).join('')}
    </tr></thead><tbody>`;

    PRODI_HEATMAP.forEach(row => {
      html += `<tr><td>${row.nama}</td>
        ${row.pct.map(p => {
          const { bg, text } = heatColor(p);
          return `<td><div class="heatmap-cell" style="background:${bg};color:${text};">${p}%</div></td>`;
        }).join('')}
      </tr>`;
    });

    tbl.innerHTML = html + '</tbody>';
  })();


  /* ═══════════════════════════════
     6. SCATTER / BUBBLE (Chart.js)
  ═══════════════════════════════ */
  (function() {
    // keketatan = estimasi (bisa diganti data nyata)
    const scatterData = [
      { kode:'UNMUL', x:2.1,  y:125, r:12, pulau:'Kalimantan' },
      { kode:'UB',    x:5.4,  y:72,  r:8,  pulau:'Jawa'       },
      { kode:'UGM',   x:8.2,  y:60,  r:7,  pulau:'Jawa'       },
      { kode:'UNDIP', x:7.6,  y:50,  r:6,  pulau:'Jawa'       },
      { kode:'ITK',   x:3.5,  y:54,  r:6,  pulau:'Kalimantan' },
      { kode:'ITB',   x:11.8, y:40,  r:5,  pulau:'Jawa'       },
      { kode:'UNLAM', x:4.2,  y:32,  r:5,  pulau:'Kalimantan' },
      { kode:'UNTAN', x:6.1,  y:24,  r:4,  pulau:'Kalimantan' },
      { kode:'UNHAS', x:9.5,  y:22,  r:4,  pulau:'Lainnya'    },
      { kode:'UI',    x:14.3, y:20,  r:4,  pulau:'Jawa'       },
      { kode:'UPR',   x:2.8,  y:12,  r:3,  pulau:'Kalimantan' },
    ];

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
        responsive: true, maintainAspectRatio: false,
        layout: { padding: 20 },
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: ctx => {
                const d = scatterData[ctx.datasetIndex];
                return ` ${d.kode}: ${d.y} alumni · Keketatan ×${d.x}`;
              }
            }
          }
        },
        scales: {
          x: {
            title: { display:true, text:'Keketatan (pendaftar ÷ kursi)',
                     font:{ family:"'Plus Jakarta Sans'", size:12, weight:'700' }, color:'#64748b' },
            min:0, max:18, grid:{ color:'rgba(0,0,0,.04)' },
            ticks:{ font:{ family:"'Plus Jakarta Sans'", size:11 }, color:'#94a3b8' }
          },
          y: {
            title: { display:true, text:'Jumlah alumni diterima',
                     font:{ family:"'Plus Jakarta Sans'", size:12, weight:'700' }, color:'#64748b' },
            min:0, max:145, border:{ display:false }, grid:{ color:'rgba(0,0,0,.04)' },
            ticks:{ font:{ family:"'Plus Jakarta Sans'", size:11 }, color:'#94a3b8' }
          }
        }
      },
      plugins: [{
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




  // ── 1. NAVBAR ──
  (function() {
    const navbar = document.getElementById('navbar');
    let lastY = 0;
    window.addEventListener('scroll', function() {
      const y = window.scrollY;
      if (y > lastY && y > 80) navbar.classList.add('hidden');
      else navbar.classList.remove('hidden');
      navbar.classList.toggle('scrolled', y > 10);
      lastY = y <= 0 ? 0 : y;
    }, { passive: true });
  })();

  // ── 2. HAMBURGER ──
  (function() {
    const btn  = document.getElementById('menuBtn');
    const menu = document.getElementById('mobileMenu');
    const icon = document.getElementById('menuIcon');
    let open = false;
    btn.addEventListener('click', function() {
      open = !open;
      menu.classList.toggle('open', open);
      btn.setAttribute('aria-expanded', open);
      icon.className = open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
    });
    menu.querySelectorAll('a').forEach(function(a) {
      a.addEventListener('click', function() {
        open = false; menu.classList.remove('open');
        btn.setAttribute('aria-expanded', false);
        icon.className = 'fa-solid fa-bars';
      });
    });
    document.addEventListener('click', function(e) {
      if (open && !menu.contains(e.target) && !btn.contains(e.target)) {
        open = false; menu.classList.remove('open');
        btn.setAttribute('aria-expanded', false);
        icon.className = 'fa-solid fa-bars';
      }
    });
  })();

  // ── 3. HERO SEARCH ──
  (function() {
    const kampusData = [
      { nama:'Universitas Brawijaya',          singkatan:'UB',    jurusan:['Teknik Sipil','Hukum','Ilmu Komunikasi'] },
      { nama:'Universitas Gadjah Mada',         singkatan:'UGM',   jurusan:['Kedokteran','Teknik Elektro','Akuntansi'] },
      { nama:'Universitas Diponegoro',          singkatan:'UNDIP', jurusan:['Teknik Informatika','Ilmu Hukum','Manajemen'] },
      { nama:'Institut Teknologi Bandung',      singkatan:'ITB',   jurusan:['Teknik Kimia','Teknik Fisika','Arsitektur'] },
      { nama:'Universitas Indonesia',           singkatan:'UI',    jurusan:['Psikologi','Ilmu Politik','Bisnis'] },
      { nama:'Universitas Mulawarman',          singkatan:'UNMUL', jurusan:['Kehutanan','Pertanian','Kedokteran'] },
      { nama:'Institut Teknologi Kalimantan',   singkatan:'ITK',   jurusan:['Teknik Mesin','Teknik Elektro'] },
      { nama:'Universitas Lambung Mangkurat',   singkatan:'UNLAM', jurusan:['Hukum','FKIP','Teknik'] },
      { nama:'Universitas Tanjungpura',         singkatan:'UNTAN', jurusan:['Ekonomi','Teknik Sipil'] },
      { nama:'Universitas Palangka Raya',       singkatan:'UPR',   jurusan:['FKIP','Pertanian'] },
    ];
    const input = document.getElementById('heroSearch');
    const msg   = document.getElementById('searchMsg');
    input.addEventListener('input', function() {
      const q = this.value.trim().toLowerCase();
      if (!q) { msg.textContent = ''; return; }
      const results = kampusData.filter(k =>
        k.nama.toLowerCase().includes(q) ||
        k.singkatan.toLowerCase().includes(q) ||
        k.jurusan.some(j => j.toLowerCase().includes(q))
      );
      if (results.length === 0) {
        msg.textContent = 'Kampus / jurusan tidak ditemukan dalam data.';
        msg.style.color = '#ef4444';
      } else {
        msg.textContent = 'Ditemukan: ' + results.map(r => r.singkatan).join(', ');
        msg.style.color = '#2563eb';
      }
    });
    input.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' && this.value.trim())
        window.location.href = 'alumni.html?q=' + encodeURIComponent(this.value.trim());
    });
  })();

  // ── 4. SCROLL TO TOP ──
  (function() {
    const btn = document.getElementById('scrollTop');
    window.addEventListener('scroll', function() {
      btn.classList.toggle('visible', window.scrollY > 400);
    }, { passive: true });
    btn.addEventListener('click', function() {
      window.scrollTo({ top:0, behavior:'smooth' });
    });
  })();

  </script>

</body>
</html>