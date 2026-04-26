<?php
// ============================================================
//  GEOCODE FIX — Portal Alumni SMAN 5 Samarinda
//  Letakkan file ini di: php/admin/geocode_fix.php
//  Akses via browser: http://localhost/sma5/php/admin/geocode_fix.php
//  Setelah selesai, HAPUS file ini dari server!
// ============================================================

require_once __DIR__ . '/db.php';

// Tidak perlu login admin untuk kemudahan penggunaan satu kali
// Tapi tambahkan password sederhana agar aman
define('FIX_PASSWORD', 'sma5fix2024');

$authOk = ($_GET['key'] ?? '') === FIX_PASSWORD;

// ── Helper: geocoding via Nominatim ─────────────────────────
function geocodeUniv(string $nama, string $kota): array {
    $queries = [
        $nama . ', ' . $kota . ', Indonesia',
        $kota . ', Indonesia',
    ];
    foreach ($queries as $q) {
        $url = 'https://nominatim.openstreetmap.org/search?q='
            . urlencode($q)
            . '&format=json&limit=1&countrycodes=id';
        $ctx = stream_context_create(['http' => [
            'header'  => "User-Agent: PortalAlumniSMAN5Samarinda/1.0\r\n",
            'timeout' => 8,
            'method'  => 'GET',
        ]]);
        $raw = @file_get_contents($url, false, $ctx);
        if ($raw) {
            $data = json_decode($raw, true);
            if (!empty($data[0]['lat'])) {
                return ['lat' => (float)$data[0]['lat'], 'lng' => (float)$data[0]['lon'], 'source' => $q];
            }
        }
        sleep(1); // Hormati rate limit Nominatim
    }
    return ['lat' => null, 'lng' => null, 'source' => null];
}

// ── Helper: deteksi pulau ────────────────────────────────────
function getPulau(string $kota): string {
    $k = strtolower(trim($kota));
    $map = [
        'Kalimantan' => ['samarinda','balikpapan','banjarmasin','pontianak','palangka raya','tarakan','bontang','singkawang','nunukan','tanjung selor','ketapang','sambas'],
        'Jawa'       => ['jakarta','depok','bandung','surabaya','semarang','yogyakarta','malang','bogor','bekasi','tangerang','solo','serang','cirebon','jember','kediri','pasuruan'],
        'Sumatera'   => ['medan','padang','palembang','pekanbaru','banda aceh','jambi','bengkulu','bandar lampung','batam','tanjung pinang','binjai','tebing tinggi','langsa'],
        'Sulawesi'   => ['makassar','manado','palu','kendari','gorontalo','parepare','palopo','bitung'],
        'Bali/NT'    => ['denpasar','mataram','kupang','singaraja'],
        'Papua'      => ['jayapura','sorong','manokwari','timika','merauke'],
        'Maluku'     => ['ambon','ternate','sofifi'],
    ];
    foreach ($map as $pulau => $kota_list) {
        foreach ($kota_list as $v) {
            if (str_contains($k, $v)) return $pulau;
        }
    }
    return 'Lainnya';
}

// ── Proses Geocoding (POST) ──────────────────────────────────
$results  = [];
$doAction = false;

if ($authOk && isset($_POST['action'])) {
    $db = getDB();
    $doAction = true;

    if ($_POST['action'] === 'geocode_one') {
        // Geocode satu universitas by ID
        $id   = (int)$_POST['univ_id'];
        $row  = $db->prepare('SELECT * FROM universitas WHERE id=?');
        $row->execute([$id]);
        $univ = $row->fetch();

        if ($univ) {
            $geo   = geocodeUniv($univ['nama'], $univ['kota'] ?? '');
            $pulau = getPulau($univ['kota'] ?? '');
            if ($geo['lat'] !== null) {
                $db->prepare('UPDATE universitas SET lat=?, lng=?, pulau=? WHERE id=?')
                   ->execute([$geo['lat'], $geo['lng'], $pulau, $id]);
                $results[] = ['status'=>'ok', 'nama'=>$univ['nama'], 'lat'=>$geo['lat'], 'lng'=>$geo['lng'], 'source'=>$geo['source']];
            } else {
                $results[] = ['status'=>'fail', 'nama'=>$univ['nama'], 'msg'=>'Nominatim tidak menemukan koordinat'];
            }
        }

    } elseif ($_POST['action'] === 'geocode_all') {
        // Geocode semua yang belum ada koordinat
        $rows = $db->query("SELECT id, nama, kota FROM universitas WHERE (lat IS NULL OR lat=0) AND kota != '' ORDER BY nama")->fetchAll();
        foreach ($rows as $u) {
            $geo   = geocodeUniv($u['nama'], $u['kota']);
            $pulau = getPulau($u['kota']);
            if ($geo['lat'] !== null) {
                $db->prepare('UPDATE universitas SET lat=?, lng=?, pulau=? WHERE id=?')
                   ->execute([$geo['lat'], $geo['lng'], $pulau, $u['id']]);
                $results[] = ['status'=>'ok', 'nama'=>$u['nama'], 'lat'=>$geo['lat'], 'lng'=>$geo['lng'], 'source'=>$geo['source']];
            } else {
                $results[] = ['status'=>'fail', 'nama'=>$u['nama'], 'msg'=>'Tidak ditemukan'];
            }
            // Delay agar Nominatim tidak blokir
            sleep(1);
        }

    } elseif ($_POST['action'] === 'set_manual') {
        // Set koordinat manual
        $id  = (int)$_POST['univ_id'];
        $lat = (float)$_POST['lat'];
        $lng = (float)$_POST['lng'];
        $row = $db->prepare('SELECT nama, kota FROM universitas WHERE id=?');
        $row->execute([$id]);
        $univ = $row->fetch();
        $pulau = getPulau($univ['kota'] ?? '');
        $db->prepare('UPDATE universitas SET lat=?, lng=?, pulau=? WHERE id=?')
           ->execute([$lat, $lng, $pulau, $id]);
        $results[] = ['status'=>'ok', 'nama'=>$univ['nama'], 'lat'=>$lat, 'lng'=>$lng, 'source'=>'manual'];
    }
}

// ── Ambil data untuk ditampilkan ─────────────────────────────
$univList = [];
if ($authOk) {
    $db = getDB();
    $univList = $db->query("
        SELECT u.id, u.nama, u.kota, u.lat, u.lng, u.pulau,
               COUNT(a.id) as jml_alumni
        FROM universitas u
        LEFT JOIN alumni a ON a.universitas_id = u.id AND a.status = 'aktif'
        GROUP BY u.id
        ORDER BY (u.lat IS NULL OR u.lat = 0) DESC, u.nama ASC
    ")->fetchAll();
}

$noCoord = array_filter($univList, fn($u) => !$u['lat'] || $u['lat'] == 0);
$hasCoord = array_filter($univList, fn($u) => $u['lat'] && $u['lat'] != 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Geocode Fix — SMAN 5 Samarinda</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    *{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'Plus Jakarta Sans',sans-serif;background:#f0f4f8;color:#1a2636;padding:24px;}
    .container{max-width:900px;margin:0 auto;}
    h1{font-size:22px;font-weight:800;margin-bottom:4px;}
    .subtitle{font-size:13px;color:#64748b;margin-bottom:24px;}
    .warning{background:#fef9c3;border:1px solid #fde047;border-radius:10px;padding:14px 16px;font-size:13px;font-weight:600;color:#92400e;margin-bottom:20px;display:flex;align-items:center;gap:10px;}
    .card{background:white;border:1px solid #e4eaf0;border-radius:14px;padding:20px;margin-bottom:16px;box-shadow:0 1px 4px rgba(0,0,0,.06);}
    .card h2{font-size:15px;font-weight:800;margin-bottom:14px;display:flex;align-items:center;gap:8px;}
    .stats{display:flex;gap:16px;flex-wrap:wrap;margin-bottom:20px;}
    .stat{background:white;border:1px solid #e4eaf0;border-radius:10px;padding:16px 20px;flex:1;min-width:140px;}
    .stat .val{font-size:28px;font-weight:800;line-height:1;}
    .stat .lbl{font-size:12px;color:#64748b;font-weight:600;margin-top:4px;}
    .stat.red .val{color:#ef4444;}
    .stat.green .val{color:#16a34a;}
    .btn{display:inline-flex;align-items:center;gap:7px;padding:10px 18px;border:none;border-radius:8px;font-family:inherit;font-size:13px;font-weight:700;cursor:pointer;transition:all .15s;}
    .btn-primary{background:#2563eb;color:white;}
    .btn-primary:hover{background:#1d4ed8;}
    .btn-green{background:#16a34a;color:white;}
    .btn-green:hover{background:#15803d;}
    .btn-sm{padding:6px 12px;font-size:12px;}
    .btn-yellow{background:#f59e0b;color:white;}
    .btn-yellow:hover{background:#d97706;}
    table{width:100%;border-collapse:collapse;font-size:13px;}
    th{background:#f7fafc;padding:10px 12px;text-align:left;font-size:11px;font-weight:800;text-transform:uppercase;color:#64748b;border-bottom:1px solid #e4eaf0;}
    td{padding:10px 12px;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
    tr:last-child td{border-bottom:none;}
    .badge{display:inline-block;padding:2px 9px;border-radius:999px;font-size:11px;font-weight:700;}
    .badge-red{background:#fee2e2;color:#991b1b;}
    .badge-green{background:#dcfce7;color:#166534;}
    .badge-gray{background:#f1f5f9;color:#475569;}
    .result-list{margin-top:16px;}
    .result-item{padding:10px 14px;border-radius:8px;margin-bottom:8px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:10px;}
    .result-ok{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;}
    .result-fail{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;}
    .manual-form{display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-top:6px;}
    .manual-form input{padding:6px 10px;border:1px solid #e4eaf0;border-radius:6px;font-family:inherit;font-size:12px;width:120px;}
    .loader{display:none;} .loading .loader{display:inline-block;}
    .loading .btn-text{display:none;}
    .access-form{background:white;border:1px solid #e4eaf0;border-radius:14px;padding:32px;max-width:400px;margin:60px auto;text-align:center;}
    .access-form input{width:100%;padding:12px;border:1.5px solid #e4eaf0;border-radius:8px;font-family:inherit;font-size:14px;margin:16px 0;outline:none;}
    .access-form input:focus{border-color:#2563eb;}
    .tip{background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:12px 14px;font-size:12px;color:#1e40af;margin-top:12px;line-height:1.6;}
  </style>
</head>
<body>
<div class="container">

<?php if (!$authOk): ?>
  <div class="access-form">
    <i class="fa-solid fa-key" style="font-size:32px;color:#2563eb;margin-bottom:12px;display:block;"></i>
    <h1>Geocode Fix Tool</h1>
    <p style="font-size:13px;color:#64748b;margin-top:6px;">Masukkan password untuk mengakses</p>
    <form>
      <input type="password" name="key" placeholder="Password..." required>
      <button type="submit" class="btn btn-primary" style="width:100%;">Masuk</button>
    </form>
    <div class="tip">
      <strong>Default password:</strong> <code>sma5fix2024</code><br>
      Ganti di baris <code>define('FIX_PASSWORD', ...)</code> jika perlu.
    </div>
  </div>

<?php else: ?>

  <h1><i class="fa-solid fa-map-location-dot" style="color:#2563eb;"></i> Geocode Fix Tool</h1>
  <p class="subtitle">Akses: <code>http://localhost/sma5/php/admin/geocode_fix.php?key=sma5fix2024</code></p>

  <div class="warning">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <span>Hapus file ini setelah selesai digunakan! Jangan simpan di server produksi.</span>
  </div>

  <!-- Stats -->
  <div class="stats">
    <div class="stat red">
      <div class="val"><?= count($noCoord) ?></div>
      <div class="lbl">Belum Ada Koordinat</div>
    </div>
    <div class="stat green">
      <div class="val"><?= count($hasCoord) ?></div>
      <div class="lbl">Sudah Ada Koordinat</div>
    </div>
    <div class="stat">
      <div class="val"><?= count($univList) ?></div>
      <div class="lbl">Total Universitas</div>
    </div>
    <div class="stat">
      <div class="val" style="font-size:18px;"><?= ini_get('allow_url_fopen') ? '✅ ON' : '❌ OFF' ?></div>
      <div class="lbl">allow_url_fopen</div>
    </div>
  </div>

  <?php if (!ini_get('allow_url_fopen')): ?>
  <div class="warning" style="background:#fef2f2;border-color:#fca5a5;color:#991b1b;">
    <i class="fa-solid fa-circle-xmark"></i>
    <div>
      <strong>allow_url_fopen = OFF</strong> — Nominatim tidak bisa diakses!<br>
      Buka <code>php.ini</code> Laragon dan set: <code>allow_url_fopen = On</code>, lalu restart Apache.
      Atau gunakan input koordinat manual di bawah.
    </div>
  </div>
  <?php endif; ?>

  <!-- Hasil aksi -->
  <?php if ($doAction && !empty($results)): ?>
  <div class="card">
    <h2><i class="fa-solid fa-list-check" style="color:#2563eb;"></i> Hasil</h2>
    <div class="result-list">
      <?php foreach ($results as $r): ?>
      <div class="result-item <?= $r['status']==='ok' ? 'result-ok' : 'result-fail' ?>">
        <i class="fa-solid fa-<?= $r['status']==='ok' ? 'circle-check' : 'circle-xmark' ?>"></i>
        <?php if ($r['status']==='ok'): ?>
          <strong><?= htmlspecialchars($r['nama']) ?></strong> —
          <?= $r['lat'] ?>, <?= $r['lng'] ?>
          <span style="font-size:11px;opacity:.7;">(<?= htmlspecialchars($r['source']) ?>)</span>
        <?php else: ?>
          <strong><?= htmlspecialchars($r['nama']) ?></strong> — <?= htmlspecialchars($r['msg']) ?>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- Geocode All -->
  <?php if (count($noCoord) > 0): ?>
  <div class="card">
    <h2><i class="fa-solid fa-wand-magic-sparkles" style="color:#16a34a;"></i> Geocode Semua Otomatis</h2>
    <p style="font-size:13px;color:#64748b;margin-bottom:14px;">
      Akan mencari koordinat untuk <strong><?= count($noCoord) ?> universitas</strong> yang belum ada koordinatnya.
      Estimasi waktu: ±<?= count($noCoord) * 2 ?> detik (karena delay 1 detik per kampus agar tidak diblokir Nominatim).
    </p>
    <form method="POST" onsubmit="startLoading(this)">
      <input type="hidden" name="action" value="geocode_all">
      <button type="submit" class="btn btn-green" id="btnAll">
        <i class="fa-solid fa-circle-notch fa-spin loader"></i>
        <span class="btn-text"><i class="fa-solid fa-map-location-dot"></i> Geocode <?= count($noCoord) ?> Kampus Sekarang</span>
      </button>
    </form>
    <div class="tip" style="margin-top:14px;">
      <strong>Proses ini berjalan di server.</strong> Jangan tutup browser. Refresh halaman jika terlalu lama (> 3 menit).
    </div>
  </div>
  <?php endif; ?>

  <!-- Tabel Universitas Belum Ada Koordinat -->
  <?php if (count($noCoord) > 0): ?>
  <div class="card">
    <h2><i class="fa-solid fa-location-xmark" style="color:#ef4444;"></i> Belum Ada Koordinat (<?= count($noCoord) ?>)</h2>
    <table>
      <thead>
        <tr><th>#</th><th>Universitas</th><th>Kota</th><th>Alumni</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php foreach (array_values($noCoord) as $i => $u): ?>
        <tr>
          <td style="color:#94a3b8;font-size:12px;"><?= $i+1 ?></td>
          <td><strong><?= htmlspecialchars($u['nama']) ?></strong></td>
          <td><?= htmlspecialchars($u['kota'] ?: '—') ?></td>
          <td><span class="badge <?= $u['jml_alumni'] > 0 ? 'badge-green' : 'badge-gray' ?>"><?= $u['jml_alumni'] ?> alumni</span></td>
          <td>
            <!-- Geocode otomatis satu -->
            <?php if ($u['kota']): ?>
            <form method="POST" style="display:inline;" onsubmit="startLoading(this)">
              <input type="hidden" name="action" value="geocode_one">
              <input type="hidden" name="univ_id" value="<?= $u['id'] ?>">
              <button type="submit" class="btn btn-yellow btn-sm">
                <i class="fa-solid fa-circle-notch fa-spin loader"></i>
                <span class="btn-text"><i class="fa-solid fa-location-crosshairs"></i> Cari</span>
              </button>
            </form>
            <?php endif; ?>

            <!-- Input manual -->
            <form method="POST" style="display:inline;" onsubmit="return validateManual(this)">
              <input type="hidden" name="action" value="set_manual">
              <input type="hidden" name="univ_id" value="<?= $u['id'] ?>">
              <div class="manual-form">
                <input type="text" name="lat" placeholder="Lat (-6.xxx)"
                  title="Cari di Google Maps, klik kanan → salin koordinat">
                <input type="text" name="lng" placeholder="Lng (106.xxx)">
                <button type="submit" class="btn btn-primary btn-sm">
                  <i class="fa-solid fa-check"></i> Set Manual
                </button>
              </div>
            </form>
            <div style="font-size:11px;color:#94a3b8;margin-top:4px;">
              <a href="https://www.google.com/maps/search/<?= urlencode($u['nama'] . ' ' . $u['kota']) ?>"
                target="_blank" style="color:#2563eb;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Cari di Google Maps
              </a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>

  <!-- Tabel Sudah Ada Koordinat -->
  <?php if (count($hasCoord) > 0): ?>
  <div class="card">
    <h2><i class="fa-solid fa-location-dot" style="color:#16a34a;"></i> Sudah Ada Koordinat (<?= count($hasCoord) ?>)</h2>
    <table>
      <thead>
        <tr><th>#</th><th>Universitas</th><th>Kota</th><th>Koordinat</th><th>Pulau</th><th>Alumni</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php foreach (array_values($hasCoord) as $i => $u): ?>
        <tr>
          <td style="color:#94a3b8;font-size:12px;"><?= $i+1 ?></td>
          <td><strong><?= htmlspecialchars($u['nama']) ?></strong></td>
          <td><?= htmlspecialchars($u['kota'] ?: '—') ?></td>
          <td style="font-size:12px;font-family:monospace;color:#475569;">
            <?= number_format((float)$u['lat'],5) ?>, <?= number_format((float)$u['lng'],5) ?>
          </td>
          <td><span class="badge badge-gray"><?= htmlspecialchars($u['pulau'] ?: '—') ?></span></td>
          <td><span class="badge <?= $u['jml_alumni'] > 0 ? 'badge-green' : 'badge-gray' ?>"><?= $u['jml_alumni'] ?></span></td>
          <td>
            <form method="POST" style="display:inline;" onsubmit="startLoading(this)">
              <input type="hidden" name="action" value="geocode_one">
              <input type="hidden" name="univ_id" value="<?= $u['id'] ?>">
              <button type="submit" class="btn btn-yellow btn-sm" title="Update koordinat">
                <i class="fa-solid fa-circle-notch fa-spin loader"></i>
                <span class="btn-text"><i class="fa-solid fa-rotate"></i> Update</span>
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>

  <div style="text-align:center;padding:20px;font-size:13px;color:#94a3b8;">
    <i class="fa-solid fa-shield-halved"></i>
    Setelah selesai, hapus file <code>geocode_fix.php</code> dari server Anda.
  </div>

<?php endif; ?>
</div>

<script>
function startLoading(form) {
  const btn = form.querySelector('button[type=submit]');
  if (btn) btn.classList.add('loading');
}
function validateManual(form) {
  const lat = parseFloat(form.lat.value);
  const lng = parseFloat(form.lng.value);
  if (isNaN(lat) || isNaN(lng)) {
    alert('Masukkan koordinat yang valid!\nContoh Lat: -3.5896, Lng: 98.6738');
    return false;
  }
  if (lat < -11 || lat > 6) { alert('Latitude Indonesia biasanya antara -11 sampai 6'); return false; }
  if (lng < 95 || lng > 141) { alert('Longitude Indonesia biasanya antara 95 sampai 141'); return false; }
  return true;
}
</script>
</body>
</html>