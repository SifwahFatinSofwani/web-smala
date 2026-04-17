<?php
// ============================================================
//  Admin: Kelola Laporan Masuk
//  php/admin/laporan.php
// ============================================================
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$db      = getDB();
$adminId = $_SESSION['admin_id'];
$msg     = '';
$msgType = '';

// ── Aksi Approve / Reject ────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi       = $_POST['aksi']       ?? '';
    $laporanId  = (int)($_POST['laporan_id'] ?? 0);

    if ($laporanId && in_array($aksi, ['approve','reject'])) {
        try {
            $laporan = $db->prepare('SELECT * FROM laporan_masuk WHERE id = ?');
            $laporan->execute([$laporanId]);
            $row = $laporan->fetch();

            if (!$row) { $msg = 'Laporan tidak ditemukan.'; $msgType = 'error'; }
            else {
                if ($aksi === 'approve') {
                    // Pindahkan ke tabel alumni
                    $ins = $db->prepare('
                        INSERT INTO alumni (nama, nisn, universitas_id, universitas_nama, prodi, jalur, angkatan, input_oleh)
                        VALUES (?,?,?,?,?,?,?,\'siswa\')
                    ');
                    $ins->execute([
                        $row['nama'], $row['nisn'], $row['universitas_id'],
                        $row['universitas_nama'], $row['prodi'], $row['jalur'], $row['angkatan']
                    ]);
                    $msg = '✅ Laporan <strong>' . htmlspecialchars($row['nama']) . '</strong> berhasil disetujui dan ditambahkan ke data alumni.';
                    $msgType = 'success';
                } else {
                    // Reject: reset flag sudah_lapor di nisn_list agar bisa lapor lagi
                    $db->prepare('UPDATE nisn_list SET sudah_lapor = 0 WHERE nisn = ?')->execute([$row['nisn']]);
                    $msg = 'Laporan <strong>' . htmlspecialchars($row['nama']) . '</strong> telah ditolak.';
                    $msgType = 'warning';
                }
                // Update status laporan
                $upd = $db->prepare('UPDATE laporan_masuk SET status = ?, reviewed_at = NOW(), reviewed_by = ? WHERE id = ?');
                $upd->execute([$aksi === 'approve' ? 'approved' : 'rejected', $adminId, $laporanId]);
            }
        } catch (PDOException $e) {
            $msg = 'Terjadi kesalahan: ' . $e->getMessage(); $msgType = 'error';
        }
    }
}

// ── Ambil data laporan ────────────────────────────────────────
$filter = $_GET['filter'] ?? 'pending';
$valid  = ['pending','approved','rejected'];
if (!in_array($filter, $valid)) $filter = 'pending';

$laporan = $db->prepare('
    SELECT l.*, u.nama AS univ_display
    FROM laporan_masuk l
    JOIN universitas u ON l.universitas_id = u.id
    WHERE l.status = ?
    ORDER BY l.submitted_at DESC
');
$laporan->execute([$filter]);
$rows = $laporan->fetchAll();

$counts = $db->query('SELECT status, COUNT(*) as cnt FROM laporan_masuk GROUP BY status')->fetchAll(PDO::FETCH_KEY_PAIR);
$pending  = $counts['pending']  ?? 0;
$approved = $counts['approved'] ?? 0;
$rejected = $counts['rejected'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Masuk – Admin Portal Alumni</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../../css/admin.css">
  <style>
    .filter-tabs { display:flex; gap:8px; margin-bottom:18px; flex-wrap:wrap; }
    .filter-tab {
      padding:8px 18px; border-radius:999px; font-size:13px; font-weight:700;
      border:1.5px solid var(--border); cursor:pointer; text-decoration:none;
      color:var(--text-soft); background:var(--surface); transition:all .15s;
      display:inline-flex; align-items:center; gap:7px;
    }
    .filter-tab:hover { border-color:var(--accent); color:var(--accent); }
    .filter-tab.active { background:var(--accent); border-color:var(--accent); color:white; }
    .filter-tab .cnt { font-size:11px; background:rgba(255,255,255,.25); padding:1px 7px; border-radius:999px; }
    .filter-tab:not(.active) .cnt { background:var(--bg); color:var(--text); }

    .alert-msg { display:flex; align-items:center; gap:10px; padding:12px 16px; border-radius:var(--r-sm); font-size:13px; font-weight:600; margin-bottom:18px; }
    .alert-msg.success { background:var(--green-bg); color:var(--green-text); border:1px solid #86efac; }
    .alert-msg.warning { background:var(--yellow-bg); color:var(--yellow-text); border:1px solid #fde047; }
    .alert-msg.error   { background:var(--red-bg);   color:var(--red-text);   border:1px solid #fca5a5; }

    .laporan-card-item {
      background:var(--surface); border:1px solid var(--border); border-radius:var(--r-md);
      padding:18px; display:flex; align-items:center; gap:16px;
      transition:box-shadow .15s; margin-bottom:10px;
    }
    .laporan-card-item:hover { box-shadow:var(--shadow-sm); }
    .laporan-av {
      width:44px; height:44px; border-radius:50%; display:flex; align-items:center;
      justify-content:center; font-size:16px; font-weight:800; flex-shrink:0;
    }
    .laporan-info { flex:1; min-width:0; }
    .laporan-info h4 { font-size:14px; font-weight:800; margin-bottom:3px; }
    .laporan-info .meta { font-size:12px; color:var(--muted); display:flex; gap:12px; flex-wrap:wrap; }
    .laporan-info .meta span { display:flex; align-items:center; gap:5px; }
    .laporan-actions { display:flex; gap:8px; flex-shrink:0; }
    .btn-approve { padding:8px 16px; background:var(--green-bg); color:var(--green-text); border:1px solid #86efac; border-radius:var(--r-sm); font-size:12px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; transition:all .15s; }
    .btn-approve:hover { background:#dcfce7; }
    .btn-reject  { padding:8px 16px; background:var(--red-bg); color:var(--red-text); border:1px solid #fca5a5; border-radius:var(--r-sm); font-size:12px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; transition:all .15s; }
    .btn-reject:hover  { background:#fee2e2; }
    .tanggal-sub { font-size:11px; color:var(--muted); margin-top:4px; }
    .catatan-box { background:var(--bg); border-radius:var(--r-xs); padding:8px 12px; font-size:12px; color:var(--text-soft); margin-top:8px; border-left:3px solid var(--accent-light); font-style:italic; }

    @media(max-width:680px) {
      .laporan-card-item { flex-direction:column; align-items:flex-start; }
      .laporan-actions { width:100%; }
    }
  </style>
</head>
<body class="admin-page" id="adminPage">

<?php include 'sidebar.php'; ?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="main-wrap" id="mainWrap">
  <header class="topbar">
    <div class="topbar-left">
      <button class="hamburger" id="hamburger"><i class="fa-solid fa-bars"></i></button>
      <div class="breadcrumb">
        <span class="breadcrumb-home"><i class="fa-solid fa-house"></i></span>
        <span class="sep"><i class="fa-solid fa-chevron-right"></i></span>
        <span class="breadcrumb-active">Laporan Masuk</span>
      </div>
    </div>
    <div class="topbar-right">
      <?php include 'topbar-avatar.php'; ?>
    </div>
  </header>

  <main class="content">
    <div class="page-title">
      <div>
        <h2>Laporan Masuk</h2>
        <p>Kelola laporan data alumni yang dikirim oleh siswa</p>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
      <div class="stat-card">
        <div class="stat-icon-wrap"><i class="fa-solid fa-inbox" style="color:#f59e0b;"></i></div>
        <div class="stat-body">
          <p class="stat-label">Menunggu</p>
          <h3 class="stat-value"><?= $pending ?></h3>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon-wrap"><i class="fa-solid fa-circle-check" style="color:#22c55e;"></i></div>
        <div class="stat-body">
          <p class="stat-label">Disetujui</p>
          <h3 class="stat-value"><?= $approved ?></h3>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon-wrap"><i class="fa-solid fa-circle-xmark" style="color:#ef4444;"></i></div>
        <div class="stat-body">
          <p class="stat-label">Ditolak</p>
          <h3 class="stat-value"><?= $rejected ?></h3>
        </div>
      </div>
    </div>

    <!-- Alert -->
    <?php if ($msg): ?>
    <div class="alert-msg <?= $msgType ?>" id="alertMsg">
      <i class="fa-solid fa-<?= $msgType === 'success' ? 'circle-check' : ($msgType === 'warning' ? 'triangle-exclamation' : 'circle-xmark') ?>"></i>
      <?= $msg ?>
    </div>
    <?php endif; ?>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
      <a href="?filter=pending"  class="filter-tab <?= $filter==='pending'  ? 'active' : '' ?>">
        <i class="fa-solid fa-clock"></i> Menunggu <span class="cnt"><?= $pending ?></span>
      </a>
      <a href="?filter=approved" class="filter-tab <?= $filter==='approved' ? 'active' : '' ?>">
        <i class="fa-solid fa-check"></i> Disetujui <span class="cnt"><?= $approved ?></span>
      </a>
      <a href="?filter=rejected" class="filter-tab <?= $filter==='rejected' ? 'active' : '' ?>">
        <i class="fa-solid fa-xmark"></i> Ditolak <span class="cnt"><?= $rejected ?></span>
      </a>
    </div>

    <!-- List -->
    <div class="card">
      <div class="card-header">
        <h3><i class="fa-solid fa-list-check"></i> <?= ucfirst($filter) ?></h3>
        <span class="badge-pill"><?= count($rows) ?> laporan</span>
      </div>
      <div style="padding:16px;">
        <?php if (empty($rows)): ?>
        <div class="empty-state">
          <i class="fa-solid fa-inbox"></i>
          <p>Tidak ada laporan <?= $filter ?> saat ini.</p>
        </div>
        <?php else: ?>
        <?php
        $colors = ['#e0f2fe','#dcfce7','#ede9fe','#fef9c3','#fee2e2','#e0e7ff','#fce7f3'];
        $texts  = ['#0369a1','#166534','#5b21b6','#92400e','#991b1b','#3730a3','#9d174d'];
        foreach ($rows as $i => $r):
          $ci   = $i % count($colors);
          $init = strtoupper(mb_substr($r['nama'], 0, 1));
        ?>
        <div class="laporan-card-item">
          <div class="laporan-av" style="background:<?= $colors[$ci] ?>;color:<?= $texts[$ci] ?>;"><?= $init ?></div>
          <div class="laporan-info">
            <h4><?= htmlspecialchars($r['nama']) ?> <span style="font-size:11px;background:var(--bg);padding:2px 8px;border-radius:999px;font-weight:600;color:var(--muted);">NISN: <?= htmlspecialchars($r['nisn']) ?></span></h4>
            <div class="meta">
              <span><i class="fa-solid fa-building-columns"></i><?= htmlspecialchars($r['universitas_nama']) ?></span>
              <span><i class="fa-solid fa-book-open"></i><?= htmlspecialchars($r['prodi']) ?></span>
              <span><i class="fa-solid fa-route"></i>
                <span class="pill <?= $r['jalur']==='SNBP' ? 'pill-green' : ($r['jalur']==='SNBT' ? 'pill-blue' : ($r['jalur']==='Kedinasan' ? 'pill-yellow' : 'pill-red')) ?>">
                  <?= htmlspecialchars($r['jalur']) ?>
                </span>
              </span>
              <span><i class="fa-solid fa-calendar"></i><?= $r['angkatan'] ?></span>
            </div>
            <?php if ($r['catatan']): ?>
            <div class="catatan-box"><i class="fa-solid fa-comment-dots" style="color:var(--accent);margin-right:6px;"></i><?= htmlspecialchars($r['catatan']) ?></div>
            <?php endif; ?>
            <p class="tanggal-sub"><i class="fa-regular fa-clock"></i> Dikirim: <?= date('d M Y, H:i', strtotime($r['submitted_at'])) ?></p>
          </div>
          <?php if ($filter === 'pending'): ?>
          <div class="laporan-actions">
            <form method="POST" style="display:contents;" onsubmit="return confirm('Setujui laporan <?= addslashes($r['nama']) ?>?')">
              <input type="hidden" name="laporan_id" value="<?= $r['id'] ?>">
              <input type="hidden" name="aksi" value="approve">
              <button type="submit" class="btn-approve"><i class="fa-solid fa-check"></i> Setujui</button>
            </form>
            <form method="POST" style="display:contents;" onsubmit="return confirm('Tolak laporan <?= addslashes($r['nama']) ?>?')">
              <input type="hidden" name="laporan_id" value="<?= $r['id'] ?>">
              <input type="hidden" name="aksi" value="reject">
              <button type="submit" class="btn-reject"><i class="fa-solid fa-xmark"></i> Tolak</button>
            </form>
          </div>
          <?php else: ?>
          <span class="pill <?= $filter==='approved' ? 'pill-green' : 'pill-red' ?>">
            <?= $filter==='approved' ? 'Disetujui' : 'Ditolak' ?>
          </span>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

  </main>
</div>

<script src="../../js/admin.js"></script>
<script>
  // Auto dismiss alert
  setTimeout(() => {
    const a = document.getElementById('alertMsg');
    if (a) { a.style.opacity='0'; a.style.transition='opacity .4s'; setTimeout(()=>a.remove(), 400); }
  }, 4000);
</script>
</body>
</html>
