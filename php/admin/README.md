# Portal Alumni SMAN 5 Samarinda — Panduan Instalasi

## Struktur File Baru/Diubah

```
project/
├── database/
│   └── schema.sql              ← ✅ BARU: Jalankan ini pertama kali
│
├── php/
│   ├── config/
│   │   └── db.php              ← ✅ BARU: Konfigurasi database
│   │
│   ├── api/
│   │   ├── verify-nisn.php     ← ✅ BARU: API cek NISN
│   │   └── submit-laporan.php  ← ✅ BARU: API kirim laporan siswa
│   │
│   ├── admin/
│   │   ├── auth.php            ← ✅ BARU: Proses login/logout
│   │   ├── sidebar.php         ← ✅ BARU: Sidebar include (semua halaman admin)
│   │   ├── topbar-avatar.php   ← ✅ BARU: Topbar include
│   │   ├── dashboard.php       ← 🔄 DIUBAH: Stats dari DB
│   │   ├── alumni.php          ← ✅ BARU: CRUD data alumni
│   │   ├── laporan.php         ← ✅ BARU: Approve/reject laporan siswa
│   │   └── nisn.php            ← ✅ BARU: Kelola NISN + import CSV
│   │
│   ├── alumni.php              ← 🔄 DIUBAH: Data dari DB (ganti alumni.html)
│   └── lapor.php               ← ✅ BARU: Form laporan siswa + verif NISN
```

---

## Cara Instalasi

### 1. Setup Database
```sql
-- Di phpMyAdmin atau MySQL CLI:
source /path/to/database/schema.sql
```

### 2. Konfigurasi Koneksi
Edit file `php/config/db.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // user MySQL kamu
define('DB_PASS', '');           // password MySQL kamu
define('DB_NAME', 'portal_alumni');
```

### 3. Ganti Password Admin
```php
// Di terminal atau script sekali pakai:
echo password_hash('passwordbaru', PASSWORD_BCRYPT);
// Salin hasilnya, UPDATE ke tabel admin_users
```

Atau langsung di MySQL:
```sql
UPDATE admin_users 
SET password = '$2y$10$...(hash dari password_hash())...' 
WHERE username = 'admin';
```

### 4. Update Link di index.php
Di bagian CTA `index.php`, tombol "Lapor Data" sudah mengarah ke `lapor.php`.
Pastikan link-link lain konsisten.

### 5. Ubah alumni.html → alumni.php
- Hapus atau rename `php/alumni.html`
- Gunakan `php/alumni.php` yang baru (data dari DB)
- Update semua link yang mengarah ke `alumni.html` menjadi `alumni.php`

---

## Alur Sistem

```
SISWA
  └─ Buka lapor.php
       ├─ Input NISN → verify-nisn.php (cek di tabel nisn_list)
       ├─ Jika valid → tampilkan form isi data kampus
       └─ Submit → laporan_masuk (status: pending)

ADMIN
  └─ Login dashboard.php
       ├─ Lihat notifikasi laporan pending
       ├─ laporan.php → Approve → data masuk ke tabel alumni
       │                Reject  → flag NISN di-reset
       ├─ alumni.php  → Input manual + Edit + Hapus
       └─ nisn.php    → Kelola daftar NISN + Import CSV
```

---

## Login Admin Default
- Username: `admin`
- Password: `password` (SEGERA GANTI setelah instalasi!)

---

## Import NISN Massal (CSV)
Format file CSV:
```
nisn,nama_siswa,angkatan
1234567890,Nama Siswa,2024
0987654321,Siswa Lain,2024
```

Upload di halaman Admin → Kelola NISN → Import CSV.

---

## Catatan Keamanan
1. Ganti password admin segera setelah instalasi
2. Aktifkan HTTPS di server produksi
3. Tambahkan rate limiting di `verify-nisn.php` untuk mencegah brute force NISN
4. Pertimbangkan menambahkan CAPTCHA di form `lapor.php`
5. Backup database secara berkala
