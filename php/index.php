<?php
// Contoh Variabel PHP Dinamis (Bisa disambungkan ke Database nantinya)
$nama_sekolah = "SMAN 5 Samarinda";
$tahun_lulusan = 2023;
$total_alumni_luar = 72;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Kampus - Portal Alumni <?php echo $nama_sekolah; ?></title>
    
    <!-- FontAwesome untuk Ikon -->
    <link rel="stylesheet" href="[https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css](https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css)">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="[https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap](https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap)" rel="stylesheet">
    
    <!-- MENGHUBUNGKAN FILE CSS DARI FOLDER 'css/' DENGAN TRIK ANTI-CACHE -->
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
    
    <!-- Jika file index.php ini berada di dalam sub-folder (seperti folder pages/), gunakan baris di bawah ini: -->
    <!-- <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>"> -->
</head>
<body class="bg-cross">

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="container nav-inner">
            <div class="nav-brand">
                <!-- Ganti src di bawah ini dengan lokasi/nama gambar logo asli sekolah -->
                <img src="../image/logosma5.png" alt="Logo Sekolah" class="nav-logo">
                <span><?php echo $nama_sekolah; ?></span>
            </div>
            
            <div class="nav-menu">
                <a href="#" class="active">Beranda</a>
                <a href="#">Data Alumni</a>
                <a href="#">Jadwal SNPMB</a>
            </div>

            <button class="menu-btn">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="container grid-2-col">
            
            <!-- Area Teks & Tombol -->
            <div class="hero-content">
                <h1 class="hero-title">
                    Cari Kampus<br>Impian Anda<br>dari Riwayat<br>Alumni yang ada
                </h1>
                
                <p class="hero-desc">
                    Lihat data kampus yang dimasuki oleh alumni anda jalur SNBP/SNBT dengan mudah serta lengkap.
                </p>

                <!-- Tombol Action -->
                <button class="btn-action">
                    <span>Cari Kampus / Jurusan</span>
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <button class="btn-action">
                    <span>Lihat Data Alumni Anda</span>
                    <i class="fa-solid fa-circle-user"></i>
                </button>
            </div>

            <!-- Area Gambar -->
            <div class="hero-image">
                <div class="img-card">
                    <img src="../image/sma5.png" alt="Siswa SMAN 5">
                    <div class="img-overlay">
                        <span class="badge">Galeri</span>
                        <p style="color: white; font-weight: 700; font-size: 18px;">Angkatan Lulusan Tahun <?php echo $tahun_lulusan; ?></p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION SEBARAN UNIVERSITAS -->
    <section class="bg-dots">
        <div class="container">
            
            <div class="section-header">
                <h2 class="section-title">Sebaran Universitas</h2>
                <p class="section-subtitle">Data riwayat penyebaran alumni di Perguruan Tinggi.</p>
            </div>

            <div class="grid-charts">
                
                <!-- KATEGORI 1: LUAR KALIMANTAN -->
                <div>
                    <h3 class="chart-category-title">
                        <i class="fa-solid fa-plane-departure"></i> Top 5 Kampus di Luar Kalimantan
                    </h3>
                    
                    <div>
                        <!-- Bar 1 (UB) -->
                        <div class="bar-item">
                            <div class="bar-icon-wrapper">
                                <i class="fa-solid fa-building-columns" style="color: #64748b;"></i>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill bar-ub"></div>
                                <span class="bar-label"><?php echo $total_alumni_luar; ?> Siswa</span>
                            </div>
                        </div>

                        <!-- Bar 2 (UGM) -->
                        <div class="bar-item">
                            <div class="bar-icon-wrapper">
                                <i class="fa-solid fa-building-columns" style="color: #64748b;"></i>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill bar-ugm"></div>
                                <span class="bar-label">60 Siswa</span>
                            </div>
                        </div>

                        <!-- Bar 3 (UNDIP) -->
                        <div class="bar-item">
                            <div class="bar-icon-wrapper">
                                <i class="fa-solid fa-building-columns" style="color: #64748b;"></i>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill bar-undip"></div>
                                <span class="bar-label">50 Siswa</span>
                            </div>
                        </div>

                        <!-- Bar 4 (ITB) -->
                        <div class="bar-item">
                            <div class="bar-icon-wrapper">
                                <i class="fa-solid fa-building-columns" style="color: #64748b;"></i>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill bar-itb"></div>
                                <span class="bar-label">40 Siswa</span>
                            </div>
                        </div>

                        <!-- Bar 5 (UI) -->
                        <div class="bar-item">
                            <div class="bar-icon-wrapper" style="padding: 6px;">
                                <i class="fa-solid fa-building-columns" style="color: #64748b;"></i>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill bar-ui"></div>
                                <span class="bar-label">20 Siswa</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KATEGORI 2: DALAM KALIMANTAN -->
                <div>
                    <h3 class="chart-category-title">
                        <i class="fa-solid fa-map-location-dot"></i> Top 5 Kampus di Kalimantan
                    </h3>
                    
                    <div>
                        <!-- Bar 1 (UNMUL) -->
                        <div class="bar-item">
                            <div class="bar-icon-wrapper">
                                <i class="fa-solid fa-building-columns" style="color: #64748b;"></i>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill bar-unmul"></div>
                                <span class="bar-label">125 Siswa</span>
                            </div>
                        </div>

                        <!-- Bar 2 (ITK) -->
                        <div class="bar-item">
                            <div class="bar-icon-wrapper">
                                <i class="fa-solid fa-building-columns" style="color: #64748b;"></i>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill bar-itk"></div>
                                <span class="bar-label">54 Siswa</span>
                            </div>
                        </div>

                        <!-- Bar 3 (UNLAM) -->
                        <div class="bar-item">
                            <div class="bar-icon-wrapper">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill bar-unlam"></div>
                                <span class="bar-label">32 Siswa</span>
                            </div>
                        </div>

                        <!-- Bar 4 (UNTAN) -->
                        <div class="bar-item">
                            <div class="bar-icon-wrapper">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill bar-untan"></div>
                                <span class="bar-label">24 Siswa</span>
                            </div>
                        </div>

                        <!-- Bar 5 (UPR) -->
                        <div class="bar-item">
                            <div class="bar-icon-wrapper">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill bar-upr"></div>
                                <span class="bar-label">12 Siswa</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            
            <!-- Statistik Kedinasan -->
            <div class="stat-card-container">
                <h4 style="font-weight: 700; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-shield-halved" style="color: #eab308;"></i> Info Jalur Kedinasan & Aparat
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

    <!-- SECTION 1: TREN MINAT JURUSAN -->
    <section class="trend-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Tren Minat Program Studi</h2>
                <p class="section-subtitle">6 Rumpun keilmuan favorit yang menjadi pilihan utama alumni kami.</p>
            </div>
            <div class="trend-grid">
                <!-- Card 1 -->
                <div class="trend-card">
                    <div class="trend-icon" style="background: #e0f2fe; color: #0284c7;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                        </svg>
                    </div>
                    <div class="trend-info">
                        <h3>Teknik Informatika & IT</h3>
                        <div class="progress-bar"><div class="progress-fill" style="width: 35%; background: #0ea5e9;"></div></div>
                        <span class="trend-stat">35% Peminat</span>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="trend-card">
                    <div class="trend-icon" style="background: #dcfce7; color: #16a34a;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </div>
                    <div class="trend-info">
                        <h3>Kedokteran & Kesehatan</h3>
                        <div class="progress-bar"><div class="progress-fill" style="width: 25%; background: #22c55e;"></div></div>
                        <span class="trend-stat">25% Peminat</span>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="trend-card">
                    <div class="trend-icon" style="background: #fef08a; color: #ca8a04;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                    </div>
                    <div class="trend-info">
                        <h3>Ekonomi & Bisnis</h3>
                        <div class="progress-bar"><div class="progress-fill" style="width: 20%; background: #eab308;"></div></div>
                        <span class="trend-stat">20% Peminat</span>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="trend-card">
                    <div class="trend-icon" style="background: #fee2e2; color: #dc2626;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                    </div>
                    <div class="trend-info">
                        <h3>Hukum & Hubungan Internasional</h3>
                        <div class="progress-bar"><div class="progress-fill" style="width: 12%; background: #ef4444;"></div></div>
                        <span class="trend-stat">12% Peminat</span>
                    </div>
                </div>
                <!-- Card 5 -->
                <div class="trend-card">
                    <div class="trend-icon" style="background: #f3e8ff; color: #9333ea;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.854-3.853a3 3 0 00-4.243-4.242l-3.853 3.854a15.995 15.995 0 00-4.648 4.764m3.42 3.42a6 6 0 00-3.42-3.42" />
                        </svg>
                    </div>
                    <div class="trend-info">
                        <h3>Seni & Desain Kreatif</h3>
                        <div class="progress-bar"><div class="progress-fill" style="width: 8%; background: #a855f7;"></div></div>
                        <span class="trend-stat">8% Peminat</span>
                    </div>
                </div>
                <!-- Card 6 -->
                <div class="trend-card">
                    <div class="trend-icon" style="background: #ccfbf1; color: #0f766e;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                        </svg>
                    </div>
                    <div class="trend-info">
                        <h3>Akademi & Pendidikan</h3>
                        <div class="progress-bar"><div class="progress-fill" style="width: 5%; background: #14b8a6;"></div></div>
                        <span class="trend-stat">5% Peminat</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: TIMELINE SNPMB -->
    <section class="timeline-section bg-cross">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Alur Persiapan Masuk PTN</h2>
                <p class="section-subtitle">Catat estimasi jadwal tahapan seleksi agar kamu tidak tertinggal informasi.</p>
            </div>
            <div class="timeline-wrapper">
                <div class="timeline-line"></div>
                
                <!-- Item 1 -->
                <div class="timeline-item">
                    <div class="timeline-dot" style="border-color: #0ea5e9;"></div>
                    <div class="timeline-content">
                        <span class="t-date" style="background: #e0f2fe; color: #0284c7;">Januari - Februari</span>
                        <h3>SNBP (Jalur Prestasi)</h3>
                        <p>Pembuatan akun SNPMB, penetapan siswa eligible, dan pendaftaran berbasis nilai rapor serta prestasi.</p>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="timeline-item">
                    <div class="timeline-dot" style="border-color: #eab308;"></div>
                    <div class="timeline-content">
                        <span class="t-date" style="background: #fef08a; color: #ca8a04;">Maret - Mei</span>
                        <h3>SNBT (Jalur Tes)</h3>
                        <p>Pendaftaran UTBK, pelaksanaan ujian berbasis komputer serentak, dan pengumuman tingkat nasional.</p>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="timeline-item">
                    <div class="timeline-dot" style="border-color: #22c55e;"></div>
                    <div class="timeline-content">
                        <span class="t-date" style="background: #dcfce7; color: #16a34a;">Juni - Agustus</span>
                        <h3>Mandiri & Kedinasan</h3>
                        <p>Ujian mandiri masing-masing PTN dan tahapan seleksi (fisik, akademik, psikologi) sekolah kedinasan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: KISAH SUKSES ALUMNI -->
    <section class="testi-section bg-dots">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Kisah Sukses Alumni</h2>
                <p class="section-subtitle">Inspirasi, motivasi, dan tips belajar langsung dari kakak tingkatmu.</p>
            </div>
            <div class="testi-grid">
                <!-- Testi 1 -->
                <div class="testi-card">
                    <i class="fa-solid fa-quote-left quote-icon"></i>
                    <p class="testi-text">"Rahasia lolos UTBK bukan belajar semalaman, tapi konsisten latihan 15 soal sehari. Jangan andalkan sistem kebut semalam!"</p>
                    <div class="testi-author">
                        <div class="t-avatar" style="background: #e0f2fe; color: #0284c7;"><i class="fa-solid fa-user-graduate"></i></div>
                        <div class="t-info">
                            <h4>Budi Santoso</h4>
                            <span>Teknik Sipil, ITS</span>
                        </div>
                    </div>
                </div>
                <!-- Testi 2 -->
                <div class="testi-card">
                    <i class="fa-solid fa-quote-left quote-icon"></i>
                    <p class="testi-text">"Untuk SNBP, perhatikan grafik nilaimu (Smt 1-5). Kalau sedikit turun, imbangi dengan sertifikat lomba level nasional/provinsi."</p>
                    <div class="testi-author">
                        <div class="t-avatar" style="background: #dcfce7; color: #16a34a;"><i class="fa-solid fa-user-nurse"></i></div>
                        <div class="t-info">
                            <h4>Siti Nurbaya</h4>
                            <span>Kedokteran, UNMUL</span>
                        </div>
                    </div>
                </div>
                <!-- Testi 3 -->
                <div class="testi-card">
                    <i class="fa-solid fa-quote-left quote-icon"></i>
                    <p class="testi-text">"Kedisiplinan adalah kunci. Tes kedinasan butuh fisik mumpuni. Rutin lari dan jaga asupan gizi sejak semester 4 itu sangat krusial."</p>
                    <div class="testi-author">
                        <div class="t-avatar" style="background: #fef08a; color: #ca8a04;"><i class="fa-solid fa-user-shield"></i></div>
                        <div class="t-info">
                            <h4>Riko Wijaya</h4>
                            <span>Taruna Akademi Kepolisian</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: FAQ -->
    <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Pertanyaan Sering Diajukan</h2>
                <p class="section-subtitle">Dapatkan jawaban singkat terkait data dan portal alumni kita.</p>
            </div>
            <div class="faq-wrapper">
                <details class="faq-item" open>
                    <summary>Apakah data yang ditampilkan akurat?</summary>
                    <div class="faq-answer">
                        <p>Tentu. Data yang kami tampilkan dihimpun langsung berdasarkan pelaporan alumni ke pihak bimbingan konseling SMAN 5 Samarinda dengan verifikasi silang bersama Perguruan Tinggi.</p>
                    </div>
                </details>
                
                <details class="faq-item">
                    <summary>Mengapa ada kampus yang tidak masuk dalam grafik?</summary>
                    <div class="faq-answer">
                        <p>Kami hanya menampilkan <strong>Top 5</strong> destinasi kampus per kriteria demi menjaga kerapian website. Jika Anda ingin data yang mendalam, Anda bisa hubungi pihak sekolah.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Bisa minta kontak Kakak Tingkat yang kuliah di tujuan saya?</summary>
                    <div class="faq-answer">
                        <p>Portal web tidak menyediakan data pribadi alumni. Namun, Anda bisa mengunjungi Ruang BK untuk melihat jejak rekam mereka dan meminta kontak jika memang diizinkan oleh yang bersangkutan.</p>
                    </div>
                </details>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="footer-logo">
                <i class="fa-solid fa-school"></i>
            </div>
            <h2>Tracer Study <?php echo $nama_sekolah; ?></h2>
            <p>Membantu memetakan masa depan dengan data masa lalu.</p>
            <p class="copy">© <?php echo date("Y"); ?> Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script>
        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar');

        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Menyembunyikan navbar saat scroll ke bawah (melewati tinggi navbar)
            if (scrollTop > lastScrollTop && scrollTop > 64) {
                navbar.classList.add('hidden');
            } else {
                // Menampilkan navbar saat scroll ke atas
                navbar.classList.remove('hidden');
            }
            
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // Untuk peramban mobile
        });
    </script>
</body>
</html>
