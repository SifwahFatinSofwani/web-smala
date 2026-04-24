-- ============================================================
--  PORTAL ALUMNI SMAN 5 SAMARINDA – Database Schema
-- ============================================================

CREATE DATABASE IF NOT EXISTS portal_alumni
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE portal_alumni;

-- ── 1. Tabel Admin ──────────────────────────────────────────
CREATE TABLE IF NOT EXISTS admin_users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(50)  NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,   -- bcrypt hash
  nama       VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin: username=admin / password=admin123
INSERT INTO admin_users (username, password, nama) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator');
-- GANTI password di atas dengan: password_hash('passwordbaru', PASSWORD_BCRYPT)


-- ── 2. Tabel NISN (didisi admin) ────────────────────────────
CREATE TABLE IF NOT EXISTS nisn_list (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  nisn         VARCHAR(20)  NOT NULL UNIQUE,
  nama_siswa   VARCHAR(150) NOT NULL,
  angkatan     YEAR         NOT NULL,
  sudah_lapor  TINYINT(1)   DEFAULT 0,
  created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- Contoh data NISN
INSERT INTO nisn_list (nisn, nama_siswa, angkatan) VALUES
('1234567890', 'Andi Firmansyah',   2023),
('0987654321', 'Siti Rahayu',       2023),
('1122334455', 'Dimas Prasetyo',    2022),
('5544332211', 'Nurul Hidayah',     2022),
('9988776655', 'Rizky Maulana',     2023),
('1231231230', 'Ayu Putri Lestari', 2023),
('3213213210', 'Faisal Hakim',      2022),
('6546546540', 'Dewi Anggraini',    2021),
('7897897890', 'Bagas Santana',     2022),
('4564564560', 'Nisa Maharani',     2021);


-- ── 3. Tabel Universitas ────────────────────────────────────
CREATE TABLE IF NOT EXISTS universitas (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  kode       VARCHAR(20)  NOT NULL UNIQUE,
  nama       VARCHAR(200) NOT NULL,
  kota       VARCHAR(100),
  pulau      VARCHAR(50),
  lat        DECIMAL(9,6),
  lng        DECIMAL(9,6),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO universitas (kode, nama, kota, pulau, lat, lng) VALUES
('UNMUL',  'Universitas Mulawarman',          'Samarinda',     'Kalimantan', -0.502200, 117.153600),
('UB',     'Universitas Brawijaya',            'Malang',        'Jawa',       -7.966600, 112.632600),
('UGM',    'Universitas Gadjah Mada',          'Yogyakarta',    'Jawa',       -7.795600, 110.369500),
('UNDIP',  'Universitas Diponegoro',           'Semarang',      'Jawa',       -7.004500, 110.420200),
('ITK',    'Institut Teknologi Kalimantan',    'Balikpapan',    'Kalimantan', -1.274200, 116.852600),
('ITB',    'Institut Teknologi Bandung',       'Bandung',       'Jawa',       -6.917500, 107.619100),
('UNLAM',  'Universitas Lambung Mangkurat',    'Banjarmasin',   'Kalimantan', -3.319400, 114.590500),
('UNTAN',  'Universitas Tanjungpura',          'Pontianak',     'Kalimantan', -0.026300, 109.342500),
('UNHAS',  'Universitas Hasanuddin',           'Makassar',      'Sulawesi',   -5.147700, 119.432700),
('UI',     'Universitas Indonesia',            'Depok',         'Jawa',       -6.360100, 106.826700),
('UPR',    'Universitas Palangka Raya',        'Palangka Raya', 'Kalimantan', -2.209900, 113.913300),
('ITS',    'Institut Teknologi Sepuluh Nopember','Surabaya',    'Jawa',       -7.281300, 112.795200),
('AKPOL',  'Akademi Kepolisian',               'Semarang',      'Jawa',       -7.063600, 110.412200),
('STPN',   'Sekolah Tinggi Pertanahan Nasional','Yogyakarta',   'Jawa',       -7.800000, 110.380000),
('LAINNYA','Universitas Lainnya',              '',              '',            0.000000,   0.000000);


-- ── 4. Tabel Alumni (data terverifikasi) ────────────────────
CREATE TABLE IF NOT EXISTS alumni (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  nama             VARCHAR(150) NOT NULL,
  nisn             VARCHAR(20)  DEFAULT NULL,
  universitas_id   INT          NOT NULL,
  universitas_nama VARCHAR(200) NOT NULL,   -- denormalized untuk kemudahan
  prodi            VARCHAR(200) NOT NULL,
  jalur            ENUM('SNBP','SNBT','Mandiri','Kedinasan') NOT NULL,
  angkatan         YEAR         NOT NULL,
  input_oleh       ENUM('siswa','admin') DEFAULT 'admin',
  status           ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at       TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  updated_at       TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (universitas_id) REFERENCES universitas(id) ON UPDATE CASCADE
);

-- Contoh data awal
INSERT INTO alumni (nama, universitas_id, universitas_nama, prodi, jalur, angkatan, input_oleh) VALUES
('Andi Firmansyah',   3,  'Universitas Gadjah Mada',        'Teknik Informatika',   'SNBT',    2023, 'admin'),
('Siti Rahayu',       1,  'Universitas Mulawarman',          'Kedokteran',           'SNBT',    2023, 'admin'),
('Dimas Prasetyo',    6,  'Institut Teknologi Bandung',      'Teknik Sipil',         'SNBT',    2022, 'admin'),
('Nurul Hidayah',     10, 'Universitas Indonesia',           'Psikologi',            'Mandiri', 2022, 'admin'),
('Rizky Maulana',     4,  'Universitas Diponegoro',          'Ilmu Hukum',           'SNBP',    2023, 'admin'),
('Ayu Putri Lestari', 2,  'Universitas Brawijaya',           'Agribisnis',           'SNBP',    2023, 'admin'),
('Faisal Hakim',      5,  'Institut Teknologi Kalimantan',   'Teknik Mesin',         'SNBP',    2022, 'admin'),
('Dewi Anggraini',    1,  'Universitas Mulawarman',          'FKIP Matematika',      'SNBP',    2021, 'admin'),
('Bagas Santana',     9,  'Universitas Hasanuddin',          'Teknik Elektro',       'SNBT',    2022, 'admin'),
('Nisa Maharani',     10, 'Universitas Indonesia',           'Ilmu Komunikasi',      'Mandiri', 2021, 'admin'),
('Hendra Saputra',    3,  'Universitas Gadjah Mada',         'Akuntansi',            'SNBT',    2021, 'admin'),
('Zahra Nabila',      7,  'Universitas Lambung Mangkurat',   'Hukum',                'SNBP',    2023, 'admin'),
('Irfan Ramadhan',    6,  'Institut Teknologi Bandung',      'Teknik Kimia',         'SNBT',    2023, 'admin'),
('Laila Fitriani',    8,  'Universitas Tanjungpura',         'Ekonomi Pembangunan',  'SNBP',    2022, 'admin'),
('Wahyu Hidayat',     2,  'Universitas Brawijaya',           'Ilmu Administrasi',    'Mandiri', 2021, 'admin'),
('Cindy Permata',     1,  'Universitas Mulawarman',          'Farmasi',              'SNBT',    2023, 'admin'),
('Eko Prasetyo',      11, 'Universitas Palangka Raya',       'FKIP Biologi',         'SNBP',    2021, 'admin'),
('Mega Aulia',        5,  'Institut Teknologi Kalimantan',   'Teknik Elektro',       'SNBT',    2023, 'admin'),
('Rudi Hartono',      4,  'Universitas Diponegoro',          'Teknik Mesin',         'SNBT',    2022, 'admin'),
('Putri Ramadhani',   3,  'Universitas Gadjah Mada',         'Kedokteran Gigi',      'Mandiri', 2023, 'admin'),
('Riko Wijaya',       13, 'Akademi Kepolisian',              'Ilmu Kepolisian',      'Kedinasan', 2023, 'admin');


-- ── 5. Tabel Laporan Masuk (dari siswa, belum terverif) ─────
CREATE TABLE IF NOT EXISTS laporan_masuk (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  nisn             VARCHAR(20)  NOT NULL,
  nama             VARCHAR(150) NOT NULL,
  universitas_id   INT          NOT NULL,
  universitas_nama VARCHAR(200) NOT NULL,
  prodi            VARCHAR(200) NOT NULL,
  jalur            ENUM('SNBP','SNBT','Mandiri','Kedinasan') NOT NULL,
  angkatan         YEAR         NOT NULL,
  catatan          TEXT         DEFAULT NULL,
  status           ENUM('pending','approved','rejected') DEFAULT 'pending',
  ip_address       VARCHAR(45)  DEFAULT NULL,
  submitted_at     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  reviewed_at      TIMESTAMP    NULL,
  reviewed_by      INT          NULL,
  FOREIGN KEY (universitas_id) REFERENCES universitas(id) ON UPDATE CASCADE,
  FOREIGN KEY (reviewed_by)    REFERENCES admin_users(id) ON DELETE SET NULL
);
