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

  <!-- Leaflet (peta) -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">

  <!-- CSS -->
  <link rel="stylesheet" href="../css/style.css">

  <!--
    CATATAN PENGGUNAAN PHP:
    Ganti semua teks statis di bawah dengan variabel PHP saat diintegrasikan:
      - "SMAN 5 Samarinda"  → <?php echo $nama_sekolah; ?>
      - "2023"              → <?php echo $tahun_lulusan; ?>
      - "72"                → <?php echo $total_alumni_luar; ?>
      - "Lihat Data Alumni Anda" href="#" → href="pages/data-alumni.php"
      - "Lapor Data"        → href="pages/lapor.php"
      - "Data Alumni"       → href="pages/data-alumni.php"
      - "Jadwal SNPMB"      → href="pages/jadwal.php"
      - Copyright year      → <?php echo date("Y"); ?>
  -->
</head>
<body class="bg-cross">

  <!-- ===== NAVBAR ===== -->
  <nav class="navbar" id="navbar">
    <div class="container nav-inner">

      <a href="index.html" class="nav-brand">
        <!-- Ganti src dengan path logo asli: ../image/logosma5.png -->
        <img src="../image/logosma5.png" alt="Logo SMAN 5 Samarinda" class="nav-logo">
        <span>SMAN 5 Samarinda</span>
      </a>

      <div class="nav-menu">
        <a href="#" class="active">Beranda</a>
        <a href="alumni.html">Data Alumni</a>
        <a href="#jadwal">Jadwal SNPMB</a>
      </div>

      <!-- Hamburger -->
      <button class="menu-btn" id="menuBtn" aria-label="Buka menu" aria-expanded="false">
        <i class="fa-solid fa-bars" id="menuIcon"></i>
      </button>
    </div>
  </nav>

  <!-- Mobile Menu Panel -->
  <div class="mobile-menu" id="mobileMenu" role="navigation" aria-label="Menu mobile">
    <a href="#" class="active">Beranda</a>
    <a href="data-alumni.html">Data Alumni</a>
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
          <input
            type="text"
            id="heroSearch"
            placeholder="Cari Kampus / Jurusan..."
            class="search-input"
            aria-label="Cari kampus atau jurusan"
          >
          <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
          </svg>
        </div>

        <!-- Pesan hasil search -->
        <p id="searchMsg" style="font-size:13px;color:#2563eb;margin-bottom:8px;margin-top:-6px;min-height:18px;font-weight:600;"></p>

        <!-- CTA -->
        <a href="data-alumni.html" class="btn-action">
          <span>Lihat Data Alumni</span>
          <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
          </svg>
        </a>
      </div>

      <!-- Gambar -->
      <div class="hero-image">
        <div class="img-card">
          <!-- Ganti src dengan: ../image/sma5.png -->
          <img
            src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=800&q=80"
            alt="Siswa SMAN 5 Samarinda"
            loading="lazy"
          >
          <div class="img-overlay">
            <span class="badge">Galeri</span>
            <p style="color:white;font-weight:700;font-size:17px;">Angkatan Lulusan Tahun 2023</p>
          </div>
        </div>
      </div>

    </div>
  </section>


  <!-- ===== SEBARAN UNIVERSITAS ===== -->
  <section class="bg-dots">
    <div class="container">

      <div class="section-header">
        <h2 class="section-title">Sebaran Universitas</h2>
        <p class="section-subtitle">Data riwayat penyebaran alumni di Perguruan Tinggi Negeri.</p>
      </div>

      <div class="grid-charts">

        <!-- Luar Kalimantan -->
        <div>
          <h3 class="chart-category-title">
            <i class="fa-solid fa-plane-departure"></i> Top 5 Kampus di Luar Kalimantan
          </h3>
          <div>

            <div class="bar-item">
              <span class="bar-name">UB</span>
              <div class="bar-icon-wrapper"><i class="fa-solid fa-building-columns"></i></div>
              <div class="bar-track">
                <div class="bar-fill bar-ub"></div>
                <span class="bar-label">72 Siswa</span>
              </div>
            </div>

            <div class="bar-item">
              <span class="bar-name">UGM</span>
              <div class="bar-icon-wrapper"><i class="fa-solid fa-building-columns"></i></div>
              <div class="bar-track">
                <div class="bar-fill bar-ugm"></div>
                <span class="bar-label">60 Siswa</span>
              </div>
            </div>

            <div class="bar-item">
              <span class="bar-name">UNDIP</span>
              <div class="bar-icon-wrapper"><i class="fa-solid fa-building-columns"></i></div>
              <div class="bar-track">
                <div class="bar-fill bar-undip"></div>
                <span class="bar-label">50 Siswa</span>
              </div>
            </div>

            <div class="bar-item">
              <span class="bar-name">ITB</span>
              <div class="bar-icon-wrapper"><i class="fa-solid fa-building-columns"></i></div>
              <div class="bar-track">
                <div class="bar-fill bar-itb"></div>
                <span class="bar-label">40 Siswa</span>
              </div>
            </div>

            <div class="bar-item">
              <span class="bar-name">UI</span>
              <div class="bar-icon-wrapper"><i class="fa-solid fa-building-columns"></i></div>
              <div class="bar-track">
                <div class="bar-fill bar-ui"></div>
                <span class="bar-label">20 Siswa</span>
              </div>
            </div>

          </div>
        </div>

        <!-- Dalam Kalimantan -->
        <div>
          <h3 class="chart-category-title">
            <i class="fa-solid fa-map-location-dot"></i> Top 5 Kampus di Kalimantan
          </h3>
          <div>

            <div class="bar-item">
              <span class="bar-name">UNMUL</span>
              <div class="bar-icon-wrapper"><i class="fa-solid fa-building-columns"></i></div>
              <div class="bar-track">
                <div class="bar-fill bar-unmul"></div>
                <span class="bar-label">125 Siswa</span>
              </div>
            </div>

            <div class="bar-item">
              <span class="bar-name">ITK</span>
              <div class="bar-icon-wrapper"><i class="fa-solid fa-building-columns"></i></div>
              <div class="bar-track">
                <div class="bar-fill bar-itk"></div>
                <span class="bar-label">54 Siswa</span>
              </div>
            </div>

            <div class="bar-item">
              <span class="bar-name">UNLAM</span>
              <div class="bar-icon-wrapper"><i class="fa-solid fa-building-columns"></i></div>
              <div class="bar-track">
                <div class="bar-fill bar-unlam"></div>
                <span class="bar-label">32 Siswa</span>
              </div>
            </div>

            <div class="bar-item">
              <span class="bar-name">UNTAN</span>
              <div class="bar-icon-wrapper"><i class="fa-solid fa-building-columns"></i></div>
              <div class="bar-track">
                <div class="bar-fill bar-untan"></div>
                <span class="bar-label">24 Siswa</span>
              </div>
            </div>

            <div class="bar-item">
              <span class="bar-name">UPR</span>
              <div class="bar-icon-wrapper"><i class="fa-solid fa-building-columns"></i></div>
              <div class="bar-track">
                <div class="bar-fill bar-upr"></div>
                <span class="bar-label">12 Siswa</span>
              </div>
            </div>

          </div>
        </div>

      </div><!-- /grid-charts -->

      <!-- Kedinasan -->
      <div class="stat-card-container">
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


  <!-- ===== PETA ===== -->
  <section class="map-section">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Jejak Alumni se-Nusantara</h2>
        <p class="section-subtitle">Dari Samarinda menyebar ke berbagai perguruan tinggi bergengsi di penjuru negeri.</p>
      </div>

      <div class="map-container">
        <div id="alumniMap" style="width:100%;height:100%;"></div>
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
            <p>Kami hanya menampilkan <strong>Top 5</strong> destinasi kampus per kriteria demi menjaga kerapian website. Untuk data yang lebih mendalam, Anda bisa hubungi pihak sekolah atau kunjungi halaman Data Alumni.</p>
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

  <!-- Scroll to Top -->
  <button class="scroll-top" id="scrollTop" aria-label="Scroll ke atas">
    <i class="fa-solid fa-chevron-up"></i>
  </button>


  <!-- ===== SCRIPTS ===== -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

  <script>
  // ── 1. NAVBAR: hide on scroll down, show on scroll up ──
  (function() {
    const navbar = document.getElementById('navbar');
    let lastY = 0;

    window.addEventListener('scroll', function() {
      const y = window.scrollY;
      if (y > lastY && y > 80) {
        navbar.classList.add('hidden');
      } else {
        navbar.classList.remove('hidden');
      }
      navbar.classList.toggle('scrolled', y > 10);
      lastY = y <= 0 ? 0 : y;
    }, { passive: true });
  })();


  // ── 2. HAMBURGER MENU ──
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

    // Tutup menu saat klik link
    menu.querySelectorAll('a').forEach(function(a) {
      a.addEventListener('click', function() {
        open = false;
        menu.classList.remove('open');
        btn.setAttribute('aria-expanded', false);
        icon.className = 'fa-solid fa-bars';
      });
    });

    // Tutup menu saat klik di luar
    document.addEventListener('click', function(e) {
      if (open && !menu.contains(e.target) && !btn.contains(e.target)) {
        open = false;
        menu.classList.remove('open');
        btn.setAttribute('aria-expanded', false);
        icon.className = 'fa-solid fa-bars';
      }
    });
  })();


  // ── 3. HERO SEARCH ──
  (function() {
    // Data kampus statis — sambungkan ke DB saat integrasi PHP
    var kampusData = [
      { nama: 'Universitas Brawijaya',          singkatan: 'UB',    jurusan: ['Teknik Sipil', 'Hukum', 'Ilmu Komunikasi'] },
      { nama: 'Universitas Gadjah Mada',         singkatan: 'UGM',   jurusan: ['Kedokteran', 'Teknik Elektro', 'Akuntansi'] },
      { nama: 'Universitas Diponegoro',          singkatan: 'UNDIP',  jurusan: ['Teknik Informatika', 'Ilmu Hukum', 'Manajemen'] },
      { nama: 'Institut Teknologi Bandung',      singkatan: 'ITB',   jurusan: ['Teknik Kimia', 'Teknik Fisika', 'Arsitektur'] },
      { nama: 'Universitas Indonesia',           singkatan: 'UI',    jurusan: ['Psikologi', 'Ilmu Politik', 'Bisnis'] },
      { nama: 'Universitas Mulawarman',          singkatan: 'UNMUL', jurusan: ['Kehutanan', 'Pertanian', 'Kedokteran'] },
      { nama: 'Institut Teknologi Kalimantan',   singkatan: 'ITK',   jurusan: ['Teknik Mesin', 'Teknik Elektro'] },
      { nama: 'Universitas Lambung Mangkurat',   singkatan: 'UNLAM', jurusan: ['Hukum', 'FKIP', 'Teknik'] },
      { nama: 'Universitas Tanjungpura',         singkatan: 'UNTAN', jurusan: ['Ekonomi', 'Teknik Sipil'] },
      { nama: 'Universitas Palangka Raya',       singkatan: 'UPR',   jurusan: ['FKIP', 'Pertanian'] },
    ];

    var input = document.getElementById('heroSearch');
    var msg   = document.getElementById('searchMsg');

    input.addEventListener('input', function() {
      var q = this.value.trim().toLowerCase();
      if (!q) { msg.textContent = ''; return; }

      var results = kampusData.filter(function(k) {
        return k.nama.toLowerCase().includes(q) ||
               k.singkatan.toLowerCase().includes(q) ||
               k.jurusan.some(function(j) { return j.toLowerCase().includes(q); });
      });

      if (results.length === 0) {
        msg.textContent = 'Kampus / jurusan tidak ditemukan dalam data.';
        msg.style.color = '#ef4444';
      } else {
        msg.textContent = 'Ditemukan: ' + results.map(function(r){ return r.singkatan; }).join(', ');
        msg.style.color = '#2563eb';
      }
    });

    // Enter → arahkan ke halaman data alumni
    input.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' && this.value.trim()) {
        window.location.href = 'data-alumni.html?q=' + encodeURIComponent(this.value.trim());
      }
    });
  })();


  // ── 4. PETA LEAFLET ──
  document.addEventListener('DOMContentLoaded', function() {
    var samarinda = [-0.5022, 117.1536];

    var map = L.map('alumniMap', {
      scrollWheelZoom: false,
      attributionControl: false
    }).setView([-1.5, 118.0], window.innerWidth < 768 ? 4.2 : 5);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
      subdomains: 'abcd',
      maxZoom: 20
    }).addTo(map);

    // Ikon asal (Samarinda)
    var originIcon = L.divIcon({
      className: '',
      html: '<div style="width:16px;height:16px;background:#ef4444;border-radius:50%;border:3px solid white;box-shadow:0 0 0 0 rgba(239,68,68,.6);animation:ping 2s infinite;"></div>',
      iconSize: [16,16], iconAnchor: [8,8]
    });

    // Ikon tujuan
    var destIcon = L.divIcon({
      className: '',
      html: '<div style="width:12px;height:12px;background:#2563eb;border-radius:50%;border:2px solid white;box-shadow:0 2px 6px rgba(37,99,235,.5);"></div>',
      iconSize: [12,12], iconAnchor: [6,6]
    });

    // Inject animasi ping ke head
    var s = document.createElement('style');
    s.textContent = '@keyframes ping{0%{box-shadow:0 0 0 0 rgba(239,68,68,.6)}70%{box-shadow:0 0 0 14px rgba(239,68,68,0)}100%{box-shadow:0 0 0 0 rgba(239,68,68,0)}}';
    document.head.appendChild(s);

    L.marker(samarinda, {icon: originIcon})
     .addTo(map)
     .bindPopup('<b>SMAN 5 Samarinda</b><br>Titik Asal Alumni');

    var destinations = [
      { name:'Universitas Indonesia (UI)',              lat:-6.3634, lng:106.8285, n:65,  city:'Depok/Jakarta' },
      { name:'Institut Teknologi Bandung (ITB)',        lat:-6.8915, lng:107.6106, n:40,  city:'Bandung' },
      { name:'Universitas Gadjah Mada (UGM)',           lat:-7.7669, lng:110.3785, n:80,  city:'Yogyakarta' },
      { name:'Institut Teknologi Sepuluh Nopember',     lat:-7.2823, lng:112.7949, n:55,  city:'Surabaya' },
      { name:'Universitas Hasanuddin (UNHAS)',          lat:-5.1328, lng:119.4880, n:30,  city:'Makassar' },
    ];

    destinations.forEach(function(d) {
      var pt = [d.lat, d.lng];
      L.marker(pt, {icon: destIcon})
       .addTo(map)
       .bindPopup(
         '<b>' + d.name + '</b><br>' + d.city +
         '<br><span style="color:#2563eb;font-weight:700;">' + d.n + ' Alumni</span>'
       );
      L.polyline([samarinda, pt], {
        color: '#94a3b8', weight: 2, dashArray: '5,5', opacity: .8
      }).addTo(map);
    });
  });


  // ── 5. SCROLL TO TOP ──
  (function() {
    var btn = document.getElementById('scrollTop');
    window.addEventListener('scroll', function() {
      btn.classList.toggle('visible', window.scrollY > 400);
    }, { passive: true });
    btn.addEventListener('click', function() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  })();

  </script>

</body>
</html>