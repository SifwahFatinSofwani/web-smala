<?php
// ============================================================
//  Admin API: Bulk Import Alumni
//  php/admin/bulk_import.php
//  Method : POST | Content-Type: application/json
//  Body   : { "rows": [ { nama, nisn, universitas_nama,
//                          prodi, jalur, angkatan }, ... ] }
// ============================================================
require_once __DIR__ . '/db.php';
requireAdmin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan.']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
$rows = $body['rows'] ?? [];

if (empty($rows) || !is_array($rows)) {
    echo json_encode(['success' => false, 'message' => 'Data kosong atau tidak valid.']);
    exit;
}

$db = getDB();

// ── Pra-load semua universitas untuk matching nama ──────────
$univMap = []; // lowercase nama → id
$univStmt = $db->query("SELECT id, nama, kota FROM universitas ORDER BY nama");
foreach ($univStmt->fetchAll() as $u) {
    $univMap[strtolower(trim($u['nama']))] = ['id' => $u['id'], 'nama' => $u['nama']];
    // Alias singkat: "univ. mulawarman" → juga cocokkan "mulawarman" dll
    $parts = explode(' ', strtolower(trim($u['nama'])));
    if (count($parts) > 1) {
        $alias = implode(' ', array_slice($parts, 1)); // hilangkan kata pertama (universitas/inst.)
        if (!isset($univMap[$alias])) {
            $univMap[$alias] = ['id' => $u['id'], 'nama' => $u['nama']];
        }
    }
}

$JALUR_VALID = ['SNBP', 'SNBT', 'Mandiri', 'Kedinasan'];
$yearNow     = (int) date('Y');

$inserted = 0;
$skipped  = 0;
$errors   = [];   // detail error per baris

foreach ($rows as $idx => $row) {
    $lineNo = $idx + 2; // baris 1 = header

    // ── Normalisasi field ───────────────────────────────────
    $nama     = trim($row['nama']     ?? $row['Nama']     ?? '');
    $nisn     = trim($row['nisn']     ?? $row['NISN']     ?? '');
    $univRaw  = trim($row['universitas_nama'] ?? $row['universitas'] ?? $row['Universitas'] ?? '');
    $prodi    = trim($row['prodi']    ?? $row['Prodi']    ?? $row['jurusan'] ?? $row['Jurusan'] ?? '');
    $jalur    = trim($row['jalur']    ?? $row['Jalur']    ?? '');
    $angkatan = (int) ($row['angkatan'] ?? $row['Angkatan'] ?? $row['tahun'] ?? 0);

    // ── Validasi wajib ──────────────────────────────────────
    if (!$nama) {
        $errors[] = "Baris $lineNo: kolom 'nama' kosong – dilewati.";
        $skipped++;
        continue;
    }
    if (!$univRaw) {
        $errors[] = "Baris $lineNo ($nama): kolom 'universitas_nama' kosong – dilewati.";
        $skipped++;
        continue;
    }
    if (!$prodi) {
        $errors[] = "Baris $lineNo ($nama): kolom 'prodi' kosong – dilewati.";
        $skipped++;
        continue;
    }
    if (!in_array($jalur, $JALUR_VALID)) {
        $errors[] = "Baris $lineNo ($nama): jalur '$jalur' tidak valid (harus SNBP/SNBT/Mandiri/Kedinasan) – dilewati.";
        $skipped++;
        continue;
    }
    if ($angkatan < 2015 || $angkatan > $yearNow + 1) {
        $errors[] = "Baris $lineNo ($nama): angkatan '$angkatan' tidak valid – dilewati.";
        $skipped++;
        continue;
    }

    // ── Match universitas ───────────────────────────────────
    $univKey  = strtolower($univRaw);
    $univMatch = $univMap[$univKey] ?? null;

    // Fuzzy fallback: cek apakah input ada di dalam nama universitas
    if (!$univMatch) {
        foreach ($univMap as $key => $val) {
            if (str_contains($key, $univKey) || str_contains($univKey, $key)) {
                $univMatch = $val;
                break;
            }
        }
    }

    $univId   = $univMatch ? $univMatch['id'] : null;
    $univNama = $univMatch ? $univMatch['nama'] : $univRaw; // simpan apa adanya jika tidak cocok

    // Jika universitas tidak ada, otomatis insert ke tabel universitas
    if (!$univId) {
        try {
            $insUniv = $db->prepare("INSERT INTO universitas (kode, nama, kota) VALUES (?, ?, '')");
            $kode = 'IMPORT_' . strtoupper(substr(preg_replace('/[^A-Z]/i', '', $univRaw), 0, 8));
            $insUniv->execute([$kode . '_' . rand(100,999), $univRaw]);
            $univId   = (int) $db->lastInsertId();
            $univNama = $univRaw;
            // tambahkan ke map untuk baris berikutnya
            $univMap[$univKey] = ['id' => $univId, 'nama' => $univNama];
        } catch (\PDOException $e) {
            // Duplikat kode, coba ambil yang sudah ada
            $q = $db->prepare("SELECT id, nama FROM universitas WHERE nama = ?");
            $q->execute([$univRaw]);
            $found = $q->fetch();
            if ($found) {
                $univId   = $found['id'];
                $univNama = $found['nama'];
            } else {
                $errors[] = "Baris $lineNo ($nama): universitas '$univRaw' tidak dapat ditambahkan – dilewati.";
                $skipped++;
                continue;
            }
        }
    }

    // ── Insert alumni ───────────────────────────────────────
    try {
        $nisnVal = ($nisn && preg_match('/^\d{10}$/', $nisn)) ? $nisn : null;

        $ins = $db->prepare("
            INSERT INTO alumni
                (nama, nisn, universitas_id, universitas_nama, prodi, jalur, angkatan, input_oleh, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'admin', 'aktif')
        ");
        $ins->execute([
            htmlspecialchars(strip_tags($nama), ENT_QUOTES, 'UTF-8'),
            $nisnVal,
            $univId,
            $univNama,
            htmlspecialchars(strip_tags($prodi),  ENT_QUOTES, 'UTF-8'),
            $jalur,
            $angkatan,
        ]);
        $inserted++;
    } catch (\PDOException $e) {
        $errors[] = "Baris $lineNo ($nama): gagal disimpan – " . $e->getMessage();
        $skipped++;
    }
}

echo json_encode([
    'success'  => true,
    'inserted' => $inserted,
    'skipped'  => $skipped,
    'errors'   => $errors,
    'message'  => "$inserted alumni berhasil diimpor, $skipped dilewati.",
]);