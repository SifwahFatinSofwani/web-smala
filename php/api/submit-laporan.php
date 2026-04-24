<?php
// ============================================================
//  API: Submit Laporan Alumni
//  php/api/submit-laporan.php
//  Method: POST | Content-Type: application/json
// ============================================================

require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method tidak diizinkan.');
}

$body = json_decode(file_get_contents('php://input'), true);

// Validasi field wajib
$required = ['nisn','universitas_id','prodi','jalur','angkatan'];
foreach ($required as $f) {
    if (empty($body[$f])) {
        jsonResponse(false, "Field '$f' wajib diisi.");
    }
}

$nisn          = trim($body['nisn']);
$univ_id       = (int) $body['universitas_id'];
$prodi         = clean($body['prodi']);
$jalur         = $body['jalur'];
$angkatan      = (int) $body['angkatan'];
$catatan       = isset($body['catatan']) ? clean($body['catatan']) : '';
$ip            = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';

// Validasi jalur
$jalur_valid = ['SNBP','SNBT','Mandiri','Kedinasan'];
if (!in_array($jalur, $jalur_valid)) {
    jsonResponse(false, 'Jalur tidak valid.');
}

// Validasi angkatan (reasonable range)
$year = date('Y');
if ($angkatan < 2015 || $angkatan > $year) {
    jsonResponse(false, 'Tahun angkatan tidak valid.');
}

try {
    $db = getDB();

    // 1. Cek ulang NISN masih valid & belum lapor
    $stmt = $db->prepare('SELECT id, nama_siswa, sudah_lapor FROM nisn_list WHERE nisn = ?');
    $stmt->execute([$nisn]);
    $nisn_row = $stmt->fetch();

    if (!$nisn_row) {
        jsonResponse(false, 'NISN tidak ditemukan.');
    }
    if ($nisn_row['sudah_lapor']) {
        jsonResponse(false, 'NISN ini sudah pernah digunakan.');
    }

    // 2. Ambil nama universitas dari tabel
    $stmt2 = $db->prepare('SELECT nama FROM universitas WHERE id = ?');
    $stmt2->execute([$univ_id]);
    $univ_row = $stmt2->fetch();

    if (!$univ_row) {
        jsonResponse(false, 'Universitas tidak ditemukan.');
    }

    // 3. Cek duplikat laporan pending dari NISN yang sama
    $stmt3 = $db->prepare("SELECT id FROM laporan_masuk WHERE nisn = ? AND status = 'pending'");
    $stmt3->execute([$nisn]);
    if ($stmt3->fetch()) {
        jsonResponse(false, 'Laporan dari NISN ini sedang menunggu verifikasi admin.');
    }

    // 4. Insert ke laporan_masuk
    $insert = $db->prepare('
        INSERT INTO laporan_masuk (nisn, nama, universitas_id, universitas_nama, prodi, jalur, angkatan, catatan, ip_address)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $insert->execute([
        $nisn,
        $nisn_row['nama_siswa'],
        $univ_id,
        $univ_row['nama'],
        $prodi,
        $jalur,
        $angkatan,
        $catatan,
        $ip
    ]);

    // 5. Tandai NISN sudah lapor (sementara, akan di-reset jika ditolak)
    //    Kita tandai 'sudah_lapor' hanya setelah approved; di sini cukup biarkan pending
    //    Opsional: bisa langsung tandai agar tidak kirim 2x
    $db->prepare('UPDATE nisn_list SET sudah_lapor = 1 WHERE nisn = ?')->execute([$nisn]);

    jsonResponse(true, 'Laporan berhasil dikirim! Tunggu verifikasi dari admin sekolah (maks. 3 hari kerja).');

} catch (PDOException $e) {
    jsonResponse(false, 'Terjadi kesalahan server. Coba lagi nanti.');
}
