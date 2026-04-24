<?php
// ============================================================
//  API: Verifikasi NISN
//  php/api/verify-nisn.php
//  Method: POST | Content-Type: application/json
//  Body  : { "nisn": "1234567890" }
// ============================================================

require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method tidak diizinkan.');
}

// Ambil body JSON
$body = json_decode(file_get_contents('php://input'), true);
$nisn = isset($body['nisn']) ? trim($body['nisn']) : '';

// Validasi format NISN (10 digit angka)
if (!preg_match('/^\d{10}$/', $nisn)) {
    jsonResponse(false, 'Format NISN tidak valid. NISN harus 10 digit angka.');
}

try {
    $db   = getDB();
    $stmt = $db->prepare('SELECT id, nama_siswa, angkatan, sudah_lapor FROM nisn_list WHERE nisn = ?');
    $stmt->execute([$nisn]);
    $row  = $stmt->fetch();

    if (!$row) {
        jsonResponse(false, 'NISN tidak ditemukan dalam data siswa SMAN 5 Samarinda.');
    }

    if ($row['sudah_lapor']) {
        jsonResponse(false, 'NISN ini sudah pernah digunakan untuk melaporkan data. Hubungi admin jika ada kesalahan.');
    }

    // NISN valid dan belum lapor
    jsonResponse(true, 'NISN terverifikasi!', [
        'nama'      => $row['nama_siswa'],
        'angkatan'  => $row['angkatan'],
    ]);

} catch (PDOException $e) {
    jsonResponse(false, 'Terjadi kesalahan server. Coba lagi nanti.');
}
