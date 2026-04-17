<?php
// ============================================================
//  Admin: Kelola Data Alumni (CRUD)
//  php/admin/alumni.php
// ============================================================
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$db      = getDB();
$adminId = $_SESSION['admin_id'];
$msg     = '';
$msgType = '';

// ── Aksi ─────────────────────────────────────────────────────
$aksi = $_GET['action'] ?? 'list';

// HAPUS
if ($aksi === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $db->prepare('UPDATE alumni SET status = ? WHERE id = ?')->execute(['nonaktif', $id]);
    $msg = 'Data alumni berhasil dihapus.'; $msgType = 'success'; $aksi = 'list';
}

// SIMPAN (Tambah / Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = (int)($_POST['id'] ?? 0);
    $nama    = clean($_POST['nama']    ?? '');
    $univId  = (int)($_POST['universitas_id'] ?? 0);
    $prodi   = clean($_POST['prodi']   ?? '');
    $jalur   = $_POST['jalur']   ?? '';
    $angkatan = (int)($_POST['angkatan'] ?? 0);
    $nisn    = clean($_POST['nisn'] ?? '');

    $jalurOk = in_array($jalur, ['SNBP','SNBT','Mandiri','Kedinasan']);
    if (!$nama || !$univId || !$prodi || !$jalurOk || !$angkatan) {
        $msg = 'Harap lengkapi semua field.'; $msgType = 'error';
    } else {
        $univRow = $db->prepare('SELECT nama FROM universitas WHERE id=?');
        $univRow->execute([$univId]); $univNama = $univRow->fetchColumn();

        if ($id) {
            // Update
            $db->prepare('
                UPDATE alumni SET nama=?,universitas_id=?,universitas_nama=?,prodi=?,jalur=?,angkatan=?,nisn=?
                WHERE id=?
            ')->execute([$nama,$univId,$univNama,$prodi,$jalur,$angkatan,$nisn?:null,$id]);
            $msg = 'Data alumni berhasil diperbarui.'; $msgType = 'success';
        } else {
            // Insert
            $db->prepare('
                INSERT INTO alumni (nama,nisn,universitas_id,universitas_nama,prodi,jalur,angkatan,input_oleh)
                VALUES (?,?,?,?,?,?,?,\'admin\')
            ')->execute([$nama,$nisn?:null,$univId,$univNama,$prodi,$jalur,$angkatan]);
            $msg = 'Alumni baru berhasil ditambahkan.'; $msgType = 'success';
        }
        $aksi = 'list';
    }
}

// Edit – ambil data
$editRow = null;
if ($aksi === 'edit' && isset($_GET['id'])) {
    $editRow = $db->prepare('SELECT * FROM alumni WHERE id=?');
    $editRow->execute([(int)$_GET['id']]);
    $editRow = $editRow->fetch();
}

// Universitas untuk dropdown
$univList = $db->query("SELECT id, nama, kota FROM universitas ORDER BY nama")->fetchAll();

// ── List Alumni ───────────────────────────────────────────────
$search   = clean($_GET['q'] ?? '');
$jalurF   = clean($_GET['jalur'] ?? '');
$angkatanF = (int)($_GET['angkatan'] ?? 0);
$page     = max(1, (int)($_GET['page'] ?? 1));
$perPage  = 15;

$where  = ["a.status = 'aktif'"];
$params = [];
if ($search) { $where[] = "(a.nama LIKE ? OR a.universitas_nama LIKE ? OR a.prodi LIKE ?)"; $params = array_merge($params, ["%$search%","%$search%","%$search%"]); }
if ($jalurF) { $where[] = "a.jalur = ?"; $params[] = $jalurF; }
if ($angkatanF) { $where[] = "a.angkatan = ?"; $params[] = $angkatanF; }

$whereStr = 'WHERE ' . implode(' AND ', $where);
$total    = $db->prepare("SELECT COUNT(*) FROM alumni a $whereStr"); $total->execute($params); $total = $total->fetchColumn();
$pages    = max(1, ceil($total / $perPage));
$offset   = ($page - 1) * $perPage;

$list = $db->prepare("SELECT a.*, u.kota FROM alumni a JOIN universitas u ON a.universitas_id=u.id $whereStr ORDER BY a.created_at DESC LIMIT $perPage OFFSET $offset");
$list->execute($params);
$alumni = $list->fetchAll();

// Angkatan list untuk filter
$angkatanList = $db->query("SELECT DISTINCT angkatan FROM alumni WHERE status='aktif' ORDER BY angkatan DESC")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Alumni – Admin Portal Alumni</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../../css/admin.css">
  <style>
    .alert-msg { display:flex; align-items:center; gap:10px; padding:12px 16px; border-radius:var(--r-sm); font-size:13px; font-weight:600; margin-bottom:18px; }
    .alert-msg.success { background:var(--green-bg); color:var(--green-text); border:1px solid #86efac; }
    .alert-msg.error   { background:var(--red-bg);   color:var(--red-text);   border:1px solid #fca5a5; }

    .filter-row { display:flex; gap:10px; flex-wrap:wrap; align-items:center; margin-bottom:16px; }
    .search-admin { display:flex; align-items:center; gap:8px; background:var(--bg); border:1px solid var(--border); border-radius:var(--r-sm); padding:9px 14px; flex:1; max-width:320px; }
    .search-admin:focus-within { border-color:var(--accent); }
    .search-admin input { border:none; outline:none; background:none; font-family:var(--font); font-size:13px; width:100%; color:var(--text); }
    .select-filter { padding:9px 32px 9px 12px; border:1px solid var(--border); border-radius:var(--r-sm); font-family:var(--font); font-size:13px; background:var(--surface); color:var(--text); cursor:pointer; appearance:none; background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); background-repeat:no-repeat; background-position:right 10px center; background-size:14px; }
    .pagination-admin { display:flex; align-items:center; gap:8px; padding:14px 18px; border-top:1px solid var(--border); }
    .pg-btn { width:34px; height:34px; border:1px solid var(--border); border-radius:var(--r-xs); font-size:13px; font-weight:700; color:var(--text-soft); background:var(--surface); cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .12s; text-decoration:none; }
    .pg-btn:hover:not([disabled]) { border-color:var(--accent); color:var(--accent); }
    .pg-btn.active { background:var(--accent); border-color:var(--accent); color:white; }
    .pg-btn[disabled] { opacity:.4; pointer-events:none; }
    .pg-info { font-size:12px; color:var(--muted); margin-left:auto; }

    /* Form panel */
    .form-panel { background:var(--surface); border:1px solid var(--border); border-radius:var(--r-md); box-shadow:var(--shadow-md); padding:24px; margin-bottom:20px; }
    .form-panel h3 { font-size:16px; font-weight:800; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .form-grid-3 { grid-template-columns:1fr 1fr 1fr; }
    @media(max-width:720px) { .form-grid, .form-grid-3 { grid-template-columns:1fr; } }
    .f-group { display:flex; flex-direction:column; gap:6px; }
    .f-group label { font-size:12.5px; font-weight:700; color:var(--text-soft); }
    .f-group input, .f-group select {
      padding:10px 14px; border:1.5px solid var(--border); border-radius:var(--r-sm);
      font-family:var(--font); font-size:13.5px; color:var(--text);
      background:var(--bg); outline:none; transition:border-color .2s, box-shadow .2s;
      -webkit-appearance:none;
    }
    .f-group input:focus, .f-group select:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(59,108,244,.1); background:#fff; }
    .form-actions { display:flex; gap:10px; margin-top:8px; }
    .btn-save { padding:10px 22px; background:var(--accent); color:white; border:none; border-radius:var(--r-sm); font-family:var(--font); font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:7px; transition:background .15s; }
    .btn-save:hover { background:var(--accent-dark); }
    .btn-cancel { padding:10px 18px; background:var(--bg); color:var(--text-soft); border:1px solid var(--border); border-radius:var(--r-sm); font-family:var(--font); font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; display:flex; align-items:center; gap:7px; }
  </style>
</head>
<body class="admin-page" id="adminPage">

<?php include 'sidebar.php'; ?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="main-wrap">
  <header class="topbar">
    <div class="topbar-left">
      <button class="hamburger" id="hamburger"><i class="fa-solid fa-bars"></i></button>
      <div class="breadcrumb">
        <span class="breadcrumb-home"><i class="fa-solid fa-house"></i></span>
        <span class="sep"><i class="fa-solid fa-chevron-right"></i></span>
        <span class="breadcrumb-active">Data Alumni</span>
      </div>
    </div>
    <div class="topbar-right"><?php include 'topbar-avatar.php'; ?></div>
  </header>

  <main class="content">
    <div class="page-title">
      <div>
        <h2>Data Alumni</h2>
        <p>Kelola seluruh data alumni SMAN 5 Samarinda (<?= $total ?> entri aktif)</p>
      </div>
      <a href="?action=tambah" class="btn-primary-sm"><i class="fa-solid fa-plus"></i> Tambah Alumni</a>
    </div>

    <!-- Alert -->
    <?php if ($msg): ?>
    <div class="alert-msg <?= $msgType ?>" id="alertMsg">
      <i class="fa-solid fa-circle-check"></i> <?= $msg ?>
    </div>
    <?php endif; ?>

    <!-- Form Panel: Tambah / Edit -->
    <?php if ($aksi === 'tambah' || $aksi === 'edit'): ?>
    <div class="form-panel">
      <h3>
        <i class="fa-solid fa-<?= $aksi==='edit' ? 'pen' : 'plus' ?>" style="color:var(--accent);"></i>
        <?= $aksi==='edit' ? 'Edit Data Alumni' : 'Tambah Alumni Baru' ?>
      </h3>
      <form method="POST" action="alumni.php">
        <?php if ($editRow): ?><input type="hidden" name="id" value="<?= $editRow['id'] ?>"><?php endif; ?>

        <div class="form-grid" style="margin-bottom:16px;">
          <div class="f-group">
            <label>Nama Lengkap *</label>
            <input type="text" name="nama" placeholder="Nama alumni" required
              value="<?= $editRow ? htmlspecialchars($editRow['nama']) : '' ?>">
          </div>
          <div class="f-group">
            <label>NISN (opsional)</label>
            <input type="text" name="nisn" placeholder="10 digit NISN" maxlength="10"
              value="<?= $editRow ? htmlspecialchars($editRow['nisn']??'') : '' ?>">
          </div>
        </div>

        <div class="form-grid" style="margin-bottom:16px;">
          <div class="f-group" style="grid-column:span 2;">
            <label>Universitas / Perguruan Tinggi *</label>
            <select name="universitas_id" required>
              <option value="">— Pilih Universitas —</option>
              <?php foreach ($univList as $u): ?>
              <option value="<?= $u['id'] ?>" <?= ($editRow && $editRow['universitas_id']==$u['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($u['nama'] . ($u['kota'] ? ' — '.$u['kota'] : '')) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-grid form-grid-3" style="margin-bottom:20px;">
          <div class="f-group" style="grid-column:span 1;">
            <label>Program Studi *</label>
            <input type="text" name="prodi" placeholder="Contoh: Teknik Informatika" required
              value="<?= $editRow ? htmlspecialchars($editRow['prodi']) : '' ?>">
          </div>
          <div class="f-group">
            <label>Jalur Masuk *</label>
            <select name="jalur" required>
              <?php foreach (['SNBP','SNBT','Mandiri','Kedinasan'] as $j): ?>
              <option value="<?= $j ?>" <?= ($editRow && $editRow['jalur']===$j) ? 'selected' : '' ?>><?= $j ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="f-group">
            <label>Tahun Lulus *</label>
            <select name="angkatan" required>
              <?php for ($y=(int)date('Y'); $y>=2015; $y--): ?>
              <option value="<?= $y ?>" <?= ($editRow && $editRow['angkatan']==$y) ? 'selected' : '' ?>><?= $y ?></option>
              <?php endfor; ?>
            </select>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-save">
            <i class="fa-solid fa-floppy-disk"></i> <?= $aksi==='edit' ? 'Simpan Perubahan' : 'Tambah Alumni' ?>
          </button>
          <a href="alumni.php" class="btn-cancel"><i class="fa-solid fa-xmark"></i> Batal</a>
        </div>
      </form>
    </div>
    <?php endif; ?>

    <!-- Filter & Search -->
    <form method="GET" action="alumni.php">
      <div class="filter-row">
        <div class="search-admin">
          <i class="fa-solid fa-magnifying-glass" style="color:var(--muted);font-size:13px;"></i>
          <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama, kampus, prodi...">
        </div>
        <select name="jalur" class="select-filter" onchange="this.form.submit()">
          <option value="">Semua Jalur</option>
          <?php foreach (['SNBP','SNBT','Mandiri','Kedinasan'] as $j): ?>
          <option value="<?= $j ?>" <?= $jalurF===$j ? 'selected' : '' ?>><?= $j ?></option>
          <?php endforeach; ?>
        </select>
        <select name="angkatan" class="select-filter" onchange="this.form.submit()">
          <option value="">Semua Tahun</option>
          <?php foreach ($angkatanList as $y): ?>
          <option value="<?= $y ?>" <?= $angkatanF==$y ? 'selected' : '' ?>><?= $y ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-primary-sm" style="padding:9px 14px;">
          <i class="fa-solid fa-filter"></i> Filter
        </button>
        <?php if ($search || $jalurF || $angkatanF): ?>
        <a href="alumni.php" class="btn-cancel" style="padding:9px 14px;border-radius:var(--r-sm);display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--muted);text-decoration:none;background:var(--bg);border:1px solid var(--border);">
          <i class="fa-solid fa-xmark"></i> Reset
        </a>
        <?php endif; ?>
      </div>
    </form>

    <!-- Table -->
    <div class="card">
      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th><th>Nama Alumni</th><th>Universitas</th><th>Program Studi</th>
              <th>Jalur</th><th>Angkatan</th><th>Sumber</th><th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($alumni)): ?>
            <tr><td colspan="8">
              <div class="empty-state"><i class="fa-solid fa-user-graduate"></i><p>Tidak ada data alumni ditemukan.</p></div>
            </td></tr>
            <?php else: ?>
            <?php
            $colors = ['#e0f2fe','#dcfce7','#ede9fe','#fef9c3','#fee2e2','#e0e7ff'];
            $texts  = ['#0369a1','#166534','#5b21b6','#92400e','#991b1b','#3730a3'];
            foreach ($alumni as $i => $a):
              $ci   = $i % count($colors);
              $init = strtoupper(mb_substr($a['nama'], 0, 1));
              $num  = ($page-1)*$perPage + $i + 1;
            ?>
            <tr>
              <td style="color:var(--muted);font-size:12px;"><?= $num ?></td>
              <td>
                <div class="user-cell">
                  <div class="user-ava" style="background:<?= $colors[$ci] ?>;color:<?= $texts[$ci] ?>;"><?= $init ?></div>
                  <div>
                    <div style="font-weight:700;"><?= htmlspecialchars($a['nama']) ?></div>
                    <?php if ($a['nisn']): ?><div style="font-size:11px;color:var(--muted);">NISN: <?= htmlspecialchars($a['nisn']) ?></div><?php endif; ?>
                  </div>
                </div>
              </td>
              <td>
                <div style="font-weight:600;font-size:13px;"><?= htmlspecialchars($a['universitas_nama']) ?></div>
                <?php if ($a['kota']): ?><div style="font-size:11px;color:var(--muted);"><?= htmlspecialchars($a['kota']) ?></div><?php endif; ?>
              </td>
              <td style="color:var(--text-soft);"><?= htmlspecialchars($a['prodi']) ?></td>
              <td>
                <span class="pill <?= $a['jalur']==='SNBP' ? 'pill-green' : ($a['jalur']==='SNBT' ? 'pill-blue' : ($a['jalur']==='Kedinasan' ? 'pill-yellow' : 'pill-red')) ?>">
                  <?= htmlspecialchars($a['jalur']) ?>
                </span>
              </td>
              <td><?= $a['angkatan'] ?></td>
              <td>
                <span class="pill <?= $a['input_oleh']==='admin' ? 'pill-blue' : 'pill-green' ?>">
                  <i class="fa-solid fa-<?= $a['input_oleh']==='admin' ? 'user-shield' : 'user' ?>" style="font-size:9px;"></i>
                  <?= $a['input_oleh']==='admin' ? 'Admin' : 'Siswa' ?>
                </span>
              </td>
              <td>
                <div class="action-btns">
                  <a href="?action=edit&id=<?= $a['id'] ?>" class="btn-icon blue-icon" title="Edit"><i class="fa-solid fa-pen"></i></a>
                  <a href="?action=delete&id=<?= $a['id'] ?>" class="btn-icon red-icon" title="Hapus"
                    onclick="return confirm('Yakin hapus data <?= addslashes($a['nama']) ?>?')">
                    <i class="fa-solid fa-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if ($pages > 1): ?>
      <div class="pagination-admin">
        <?php
        $qs = http_build_query(['q'=>$search,'jalur'=>$jalurF,'angkatan'=>$angkatanF]);
        ?>
        <a href="?<?= $qs ?>&page=<?= max(1,$page-1) ?>" class="pg-btn" <?= $page<=1?'disabled':'' ?>>
          <i class="fa-solid fa-chevron-left" style="font-size:10px;"></i>
        </a>
        <?php for ($p=1;$p<=$pages;$p++): ?>
        <a href="?<?= $qs ?>&page=<?= $p ?>" class="pg-btn <?= $p===$page?'active':'' ?>"><?= $p ?></a>
        <?php endfor; ?>
        <a href="?<?= $qs ?>&page=<?= min($pages,$page+1) ?>" class="pg-btn" <?= $page>=$pages?'disabled':'' ?>>
          <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
        </a>
        <span class="pg-info">Hal. <?= $page ?> dari <?= $pages ?> · <?= $total ?> total</span>
      </div>
      <?php endif; ?>
    </div>

  </main>
</div>

<script src="../../js/admin.js"></script>
<script>
  setTimeout(() => {
    const a = document.getElementById('alertMsg');
    if (a) { a.style.opacity='0'; a.style.transition='opacity .4s'; setTimeout(()=>a.remove(),400); }
  }, 4000);
</script>
</body>
</html>
