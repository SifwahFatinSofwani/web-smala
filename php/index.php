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
            <a href="index.php" class="nav-brand" style="text-decoration: none; color: inherit;">
                <!-- Ganti src di bawah ini dengan lokasi/nama gambar logo asli sekolah -->
                <img src="../image/logosma5.png" alt="Logo Sekolah" class="nav-logo">
                <span><?php echo $nama_sekolah; ?></span>
            </a>
            
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
                <div class="search-box">
                    <input type="text" placeholder="Cari Kampus / Jurusan..." class="search-input">
                    <!-- Ikon Kaca Pembesar -->
                    <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                
                <a href="#" target="_blank" class="btn-action" style="text-decoration: none;">
                    <span>Lihat Data Alumni Anda</span>
                    <!-- Ikon Eksternal / User -->
                    <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </a>
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

    <!-- SECTION 1.5: PETA SEBARAN ALUMNI -->
    <section class="map-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Jejak Alumni se-Nusantara</h2>
                <p class="section-subtitle">Dari Samarinda menyebar ke berbagai perguruan tinggi bergengsi di penjuru negeri.</p>
            </div>
            <div class="map-container" style="padding: 0; border: none; overflow: hidden; height: 450px; background: transparent; box-shadow: var(--shadow-md); border-radius: 24px;">
                <!-- Leaflet CSS & JS -->
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
                
                <div id="alumniMap" style="width: 100%; height: 100%; z-index: 1;"></div>
                
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        // Titik Samarinda
                        var samarindaPoint = [-0.5022, 117.1536];
                        
                        // Inisialisasi Map (Zoom ke area Indonesia)
                        var map = L.map('alumniMap', {
                            scrollWheelZoom: false,
                            attributionControl: false // Menghilangkan watermark
                        }).setView([-2.5489, 118.0149], 5);

                        // Gunakan Base Map Carto Light agar estetik & bersih
                        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                            attribution: '&copy; OpenStreetMap contributors',
                            subdomains: 'abcd',
                            maxZoom: 20
                        }).addTo(map);

                        // CSS Animation ping untuk map
                        var style = document.createElement('style');
                        style.innerHTML = `
                            @keyframes pingMap {
                                0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
                                70% { box-shadow: 0 0 0 15px rgba(239, 68, 68, 0); }
                                100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
                            }
                        `;
                        document.head.appendChild(style);

                        // Ikon Samarinda
                        var originIcon = L.divIcon({
                            className: 'custom-origin',
                            html: "<div style='background-color:#ef4444; width:16px; height:16px; border-radius:50%; border:3px solid white; box-shadow: 0 0 10px rgba(239,68,68,0.8); animation: pingMap 2s infinite;'></div>",
                            iconSize: [16, 16],
                            iconAnchor: [8, 8]
                        });

                        // Ikon Tujuan
                        var destIcon = L.divIcon({
                            className: 'custom-dest',
                            html: "<div style='background-color:#2563eb; width:12px; height:12px; border-radius:50%; border:2px solid white; box-shadow: 0 0 5px rgba(37,99,235,0.8);'></div>",
                            iconSize: [12, 12],
                            iconAnchor: [6, 6]
                        });

                        // Tambah Marker Samarinda
                        L.marker(samarindaPoint, {icon: originIcon})
                         .addTo(map)
                         .bindPopup("<b>SMAN 5 Samarinda</b><br>Titik Asal Alumni");

                        // Data Tujuan
                        var destinations = [
                            { name: "Universitas Indonesia (UI)", lat:-6.3634, lng:106.8285, students: 65, city: "Depok/Jakarta" },
                            { name: "Institut Teknologi Bandung (ITB)", lat:-6.8915, lng:107.6106, students: 40, city: "Bandung" },
                            { name: "Universitas Gadjah Mada (UGM)", lat:-7.7669, lng:110.3785, students: 80, city: "Yogyakarta" },
                            { name: "Institut Teknologi Sepuluh Nopember (ITS)", lat:-7.2823, lng:112.7949, students: 55, city: "Surabaya" },
                            { name: "Universitas Hasanuddin (UNHAS)", lat:-5.1328, lng:119.4880, students: 30, city: "Makassar" }
                        ];

                        // Tambah Marker & Garis Tujuan
                        destinations.forEach(function(dest) {
                            var destPoint = [dest.lat, dest.lng];
                            
                            L.marker(destPoint, {icon: destIcon})
                             .addTo(map)
                             .bindPopup("<b>" + dest.name + "</b><br>" + dest.city + "<br><span style='color:#2563eb; font-weight:bold;'>" + dest.students + " Alumni</span>");
                            
                            L.polyline([samarindaPoint, destPoint], {
                                color: '#94a3b8', 
                                weight: 2, 
                                dashArray: '5, 5', 
                                opacity: 0.8
                            }).addTo(map);
                        });
                    });
                </script>
            </div>
        </div>
    </section>

    <!-- SECTION 1: TREN MINAT JURUSAN -->
    <section class="trend-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Tren Minat Program Studi</h2>
                <p class="section-subtitle">8 Rumpun keilmuan favorit yang menjadi pilihan utama alumni kami.</p>
            </div>
            <div class="trend-grid">
                <!-- Card 1 (Teknologi Informasi & Komputer) -->
                <div class="trend-card">
                    <div class="trend-icon" style="background: #e0f2fe; color: #0284c7;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                        </svg>
                    </div>
                    <div class="trend-info">
                        <h3>Teknologi Informasi & Komputer</h3>
                        <div class="progress-bar"><div class="progress-fill" style="width: 20%; background: #0ea5e9;"></div></div>
                        <span class="trend-stat">20% Peminat</span>
                    </div>
                </div>
                <!-- Card 2 (Teknik & Rekayasa) -->
                <div class="trend-card">
                    <div class="trend-icon" style="background: #fed7aa; color: #c2410c;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.832M11.42 15.17l-1.028-1.028M11.42 15.17l-4.242 4.242a2.652 2.652 0 01-3.75-3.75l4.242-4.242-1.028-1.028m8.72 4.708l1.028 1.028m-4.708-8.72l1.028 1.028M15.17 11.42l4.242-4.242a2.652 2.652 0 00-3.75-3.75l-4.242 4.242-1.028-1.028m-8.72 4.708l1.028 1.028" />
                        </svg>
                    </div>
                    <div class="trend-info">
                        <h3>Teknik & Rekayasa</h3>
                        <div class="progress-bar"><div class="progress-fill" style="width: 18%; background: #f97316;"></div></div>
                        <span class="trend-stat">18% Peminat</span>
                    </div>
                </div>
                <!-- Card 3 (Bisnis & Administrasi) -->
                <div class="trend-card">
                    <div class="trend-icon" style="background: #fef08a; color: #ca8a04;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                    </div>
                    <div class="trend-info">
                        <h3>Bisnis & Administrasi</h3>
                        <div class="progress-bar"><div class="progress-fill" style="width: 14%; background: #eab308;"></div></div>
                        <span class="trend-stat">14% Peminat</span>
                    </div>
                </div>
                <!-- Card 4 (Kedokteran & Kesehatan) -->
                <div class="trend-card">
                    <div class="trend-icon" style="background: #dcfce7; color: #16a34a;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </div>
                    <div class="trend-info">
                        <h3>Kedokteran & Kesehatan</h3>
                        <div class="progress-bar"><div class="progress-fill" style="width: 15%; background: #22c55e;"></div></div>
                        <span class="trend-stat">15% Peminat</span>
                    </div>
                </div>
                <!-- Card 5 (Psikologi & Ilmu Sosial) -->
                <div class="trend-card">
                    <div class="trend-icon" style="background: #e0e7ff; color: #4338ca;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
                        </svg>
                    </div>
                    <div class="trend-info">
                        <h3>Psikologi & Ilmu Sosial</h3>
                        <div class="progress-bar"><div class="progress-fill" style="width: 12%; background: #6366f1;"></div></div>
                        <span class="trend-stat">12% Peminat</span>
                    </div>
                </div>
                <!-- Card 6 (Hukum & Pemerintahan) -->
                <div class="trend-card">
                    <div class="trend-icon" style="background: #fee2e2; color: #dc2626;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                    </div>
                    <div class="trend-info">
                        <h3>Hukum & Pemerintahan</h3>
                        <div class="progress-bar"><div class="progress-fill" style="width: 8%; background: #ef4444;"></div></div>
                        <span class="trend-stat">8% Peminat</span>
                    </div>
                </div>
                <!-- Card 7 (Seni & Desain Kreatif) -->
                <div class="trend-card">
                    <div class="trend-icon" style="background: #fdf4ff; color: #c026d3;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.854-3.853a3 3 0 00-4.243-4.242l-3.853 3.854a15.995 15.995 0 00-4.648 4.764m3.42 3.42a6 6 0 00-3.42-3.42" />
                        </svg>
                    </div>
                    <div class="trend-info">
                        <h3>Seni & Desain Kreatif</h3>
                        <div class="progress-bar"><div class="progress-fill" style="width: 8%; background: #d946ef;"></div></div>
                        <span class="trend-stat">8% Peminat</span>
                    </div>
                </div>
                <!-- Card 8 (Pendidikan & Keguruan) -->
                <div class="trend-card">
                    <div class="trend-icon" style="background: #f1f5f9; color: #475569;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div class="trend-info">
                        <h3>Pendidikan & Keguruan</h3>
                        <div class="progress-bar"><div class="progress-fill" style="width: 5%; background: #64748b;"></div></div>
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
    <!-- SECTION 5: CTA (Call To Action) -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-box">
                <div class="cta-content">
                    <h2>Anda Bagian Dari Sejarah Kami?</h2>
                    <p>Bantu kami melengkapi data sebaran lulusan. Jika Anda alumni SMAN 5 Samarinda yang baru diterima di Perguruan Tinggi, mari laporkan kampusnya sekarang!</p>
                </div>
                <div class="cta-action">
                    <a href="#" class="btn-primary btn-cta">Lapor Data <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container nav-container">
            <!-- Tautan Logo Navbar -->
            <a href="index.php" class="nav-brand" style="text-decoration: none; color: inherit;">
                <img src="../image/logosma5.png" alt="Logo SMA 5" class="nav-logo">
                SMA 5 Samarinda
            </a>
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
