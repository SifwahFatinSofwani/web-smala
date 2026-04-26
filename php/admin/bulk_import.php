<?php
// ============================================================
//  Admin API: Bulk Import Alumni — VERSI FIX
//  php/admin/bulk_import.php
//  FIX: Universitas baru yang dibuat otomatis sekarang
//       langsung di-geocode via Nominatim
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

// ── Helper: Geocoding via Nominatim ─────────────────────────
function geocodeForImport(string $nama, string $kota = ''): array {
    if (!ini_get('allow_url_fopen')) {
        return ['lat' => null, 'lng' => null];
    }
    $queries = [];
    if ($kota) {
        $queries[] = $nama . ', ' . $kota . ', Indonesia';
        $queries[] = $kota . ', Indonesia';
    } else {
        $queries[] = $nama . ', Indonesia';
    }
    foreach ($queries as $q) {
        $url = 'https://nominatim.openstreetmap.org/search?q='
            . urlencode($q)
            . '&format=json&limit=1&countrycodes=id';
        $ctx = stream_context_create(['http' => [
            'header'  => "User-Agent: PortalAlumniSMAN5Samarinda/1.0\r\n",
            'timeout' => 6,
            'method'  => 'GET',
        ]]);
        $raw = @file_get_contents($url, false, $ctx);
        if ($raw) {
            $data = json_decode($raw, true);
            if (!empty($data[0]['lat'])) {
                return ['lat' => (float)$data[0]['lat'], 'lng' => (float)$data[0]['lon']];
            }
        }
        sleep(1); // Hormati rate limit Nominatim
    }
    return ['lat' => null, 'lng' => null];
}

// ── Helper: Deteksi pulau dari nama kota ─────────────────────
function getPulauFromKotaImport(string $kota): string {
    $k = strtolower(trim($kota));
    $kalimantan = ['samarinda','balikpapan','banjarmasin','pontianak','palangka raya','tarakan','bontang','singkawang','nunukan','tanjung selor','ketapang','sambas'];
    $jawa       = ['jakarta','depok','bandung','surabaya','semarang','yogyakarta','malang','bogor','bekasi','tangerang','solo','serang','cirebon','jember','kediri'];
    $sumatera   = ['medan','padang','palembang','pekanbaru','banda aceh','jambi','bengkulu','bandar lampung','batam','tanjung pinang','binjai','langsa'];
    foreach ($kalimantan as $v) if (str_contains($k, $v)) return 'Kalimantan';
    foreach ($jawa       as $v) if (str_contains($k, $v)) return 'Jawa';
    foreach ($sumatera   as $v) if (str_contains($k, $v)) return 'Sumatera';
    return 'Lainnya';
}

// ── Pra-load semua universitas untuk matching nama ──────────
$univMap = []; // lowercase nama → ['id', 'nama']
$univStmt = $db->query("SELECT id, nama, kota FROM universitas ORDER BY nama");
foreach ($univStmt->fetchAll() as $u) {
    $key = strtolower(trim($u['nama']));
    $univMap[$key] = ['id' => $u['id'], 'nama' => $u['nama'], 'kota' => $u['kota']];
    // Alias singkat (tanpa kata "universitas/institut")
    $parts = explode(' ', $key);
    if (count($parts) > 1) {
        $alias = implode(' ', array_slice($parts, 1));
        if (!isset($univMap[$alias])) {
            $univMap[$alias] = ['id' => $u['id'], 'nama' => $u['nama'], 'kota' => $u['kota']];
        }
    }
}

$JALUR_VALID = ['SNBP', 'SNBT', 'Mandiri', 'Kedinasan'];
$yearNow     = (int) date('Y');

$inserted = 0;
$skipped  = 0;
$errors   = [];

foreach ($rows as $idx => $row) {
    $lineNo = $idx + 2;

    // ── Normalisasi field ───────────────────────────────────
    $nama     = trim($row['nama']     ?? $row['Nama']     ?? '');
    $nisn     = trim($row['nisn']     ?? $row['NISN']     ?? '');
    $univRaw  = trim($row['universitas_nama'] ?? $row['universitas'] ?? $row['Universitas'] ?? '');
    $prodi    = trim($row['prodi']    ?? $row['Prodi']    ?? $row['jurusan'] ?? $row['Jurusan'] ?? '');
    $jalur    = trim($row['jalur']    ?? $row['Jalur']    ?? '');
    $angkatan = (int)($row['angkatan'] ?? $row['Angkatan'] ?? $row['tahun'] ?? 0);

    // ── Validasi wajib ──────────────────────────────────────
    if (!$nama)    { $errors[] = "Baris $lineNo: kolom 'nama' kosong – dilewati."; $skipped++; continue; }
    if (!$univRaw) { $errors[] = "Baris $lineNo ($nama): kolom 'universitas_nama' kosong – dilewati."; $skipped++; continue; }
    if (!$prodi)   { $errors[] = "Baris $lineNo ($nama): kolom 'prodi' kosong – dilewati."; $skipped++; continue; }
    if (!in_array($jalur, $JALUR_VALID)) {
        $errors[] = "Baris $lineNo ($nama): jalur '$jalur' tidak valid – dilewati.";
        $skipped++; continue;
    }
    if ($angkatan < 2015 || $angkatan > $yearNow + 1) {
        $errors[] = "Baris $lineNo ($nama): angkatan '$angkatan' tidak valid – dilewati.";
        $skipped++; continue;
    }

    // ── Match universitas ───────────────────────────────────
    $univKey   = strtolower($univRaw);
    $univMatch = $univMap[$univKey] ?? null;

    // Fuzzy fallback
    if (!$univMatch) {
        foreach ($univMap as $key => $val) {
            if (str_contains($key, $univKey) || str_contains($univKey, $key)) {
                $univMatch = $val;
                break;
            }
        }
    }

    $univId   = $univMatch ? $univMatch['id'] : null;
    $univNama = $univMatch ? $univMatch['nama'] : $univRaw;
    $univKota = $univMatch ? ($univMatch['kota'] ?? '') : '';

    // ── AUTO-CREATE + GEOCODE universitas baru ──────────────
    if (!$univId) {
        try {
            // Coba extract nama kota dari nama universitas
            // Contoh: "Universitas Sumatera Utara" → coba geocode langsung
            $extractedKota = '';
            $namaLower = strtolower($univRaw);
            $kotaHints = [
                'sumatera utara' => 'Medan',
                'sumatera barat' => 'Padang',
                'sumatera selatan' => 'Palembang',
                'riau' => 'Pekanbaru',
                'mulawarman' => 'Samarinda',
                'kalimantan timur' => 'Samarinda',
                'kalimantan barat' => 'Pontianak',
                'kalimantan selatan' => 'Banjarmasin',
                'kalimantan tengah' => 'Palangka Raya',
                'hasanuddin' => 'Makassar',
                'sulawesi' => 'Makassar',
                'jember' => 'Jember',
                'brawijaya' => 'Malang',
                'diponegoro' => 'Semarang',
                'gadjah mada' => 'Yogyakarta',
                'airlangga' => 'Surabaya',
                'lambung mangkurat' => 'Banjarmasin',
                'tanjungpura' => 'Pontianak',
                'palangka raya' => 'Palangka Raya',
            ];
            foreach ($kotaHints as $hint => $kota) {
                if (str_contains($namaLower, $hint)) {
                    $extractedKota = $kota;
                    break;
                }
            }

            // Geocoding untuk universitas baru
            $geo   = geocodeForImport($univRaw, $extractedKota);
            $pulau = $extractedKota ? getPulauFromKotaImport($extractedKota) : 'Lainnya';

            // Generate kode unik
            $kode = 'IMP_' . strtoupper(preg_replace('/[^A-Z]/i', '', $univRaw));
            $kode = substr($kode, 0, 18) . '_' . rand(100, 999);

            $insUniv = $db->prepare("INSERT INTO universitas (kode, nama, kota, lat, lng, pulau) VALUES (?, ?, ?, ?, ?, ?)");
            $insUniv->execute([
                $kode,
                $univRaw,
                $extractedKota,
                $geo['lat'],
                $geo['lng'],
                $pulau,
            ]);
            $univId   = (int) $db->lastInsertId();
            $univNama = $univRaw;
            $univKota = $extractedKota;

            // Tambahkan ke map untuk baris berikutnya
            $univMap[$univKey] = ['id' => $univId, 'nama' => $univNama, 'kota' => $univKota];

            // Info ke log
            if ($geo['lat']) {
                $errors[] = "INFO: Universitas baru '$univRaw' ditambahkan dengan koordinat ({$geo['lat']}, {$geo['lng']}).";
            } else {
                $errors[] = "INFO: Universitas baru '$univRaw' ditambahkan TANPA koordinat (geocoding gagal). Lengkapi via Admin → Universitas.";
            }

        } catch (\PDOException $e) {
            // Kalau duplikat kode, coba ambil yang sudah ada
            $q = $db->prepare("SELECT id, nama, kota FROM universitas WHERE nama = ?");
            $q->execute([$univRaw]);
            $found = $q->fetch();
            if ($found) {
                $univId   = $found['id'];
                $univNama = $found['nama'];
                $univKota = $found['kota'] ?? '';
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
            htmlspecialchars(strip_tags($prodi), ENT_QUOTES, 'UTF-8'),
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