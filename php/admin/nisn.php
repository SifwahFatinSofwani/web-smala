<?php
// ============================================================
//  Admin: Kelola NISN
//  php/admin/nisn.php
// ============================================================
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$db  = getDB();
$msg = ''; $msgType = '';

// ── Aksi ─────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';

    // Tambah/Edit satu NISN
    if ($aksi === 'save') {
        $id      = (int)($_POST['id'] ?? 0);
        $nisn    = trim($_POST['nisn'] ?? '');
        $nama    = clean($_POST['nama_siswa'] ?? '');
        $angkatan = (int)($_POST['angkatan'] ?? 0);

        if (!preg_match('/^\d{10}$/', $nisn) || !$nama || !$angkatan) {
            $msg = 'Data tidak valid. Pastikan NISN 10 digit.'; $msgType = 'error';
        } else {
            try {
                if ($id) {
                    $db->prepare('UPDATE nisn_list SET nisn=?,nama_siswa=?,angkatan=? WHERE id=?')
                       ->execute([$nisn,$nama,$angkatan,$id]);
                    $msg = 'Data NISN berhasil diperbarui.'; $msgType = 'success';
                } else {
                    $db->prepare('INSERT INTO nisn_list (nisn,nama_siswa,angkatan) VALUES (?,?,?)')
                       ->execute([$nisn,$nama,$angkatan]);
                    $msg = 'NISN baru berhasil ditambahkan.'; $msgType = 'success';
                }
            } catch (PDOException $e) {
                $msg = 'NISN sudah terdaftar atau terjadi kesalahan.'; $msgType = 'error';
            }
        }
    }

    // Import bulk CSV
    if ($aksi === 'import' && isset($_FILES['csv_file'])) {
        $file = $_FILES['csv_file']['tmp_name'];
        if ($file && is_uploaded_file($file)) {
            $imported = 0; $skipped = 0;
            if (($fh = fopen($file, 'r')) !== false) {
                fgetcsv($fh); // Skip header
                while (($row = fgetcsv($fh)) !== false) {
                    if (count($row) >= 3) {
                        $nisn = trim($row[0]);
                        $nama = trim($row[1]);
                        $thn  = (int) trim($row[2]);
                        if (preg_match('/^\d{10}$/', $nisn) && $nama && $thn >= 2015) {
                            try {
                                $db->prepare('INSERT IGNORE INTO nisn_list (nisn,nama_siswa,angkatan) VALUES (?,?,?)')
                                   ->execute([$nisn,$nama,$thn]);
                                $imported++;
                            } catch (Exception $e) { $skipped++; }
                        } else { $skipped++; }
                    }
                }
                fclose($fh);
            }
            $msg = "Import selesai: $imported NISN ditambahkan, $skipped dilewati.";
            $msgType = $imported > 0 ? 'success' : 'warning';
        }
    }

    // Reset sudah_lapor
    if ($aksi === 'reset_lapor' && isset($_POST['nisn_id'])) {
        $db->prepare('UPDATE nisn_list SET sudah_lapor=0 WHERE id=?')->execute([(int)$_POST['nisn_id']]);
        $msg = 'Flag sudah_lapor berhasil di-reset.'; $msgType = 'success';
    }

    // Hapus
    if ($aksi === 'delete' && isset($_POST['nisn_id'])) {
        $db->prepare('DELETE FROM nisn_list WHERE id=?')->execute([(int)$_POST['nisn_id']]);
        $msg = 'Data NISN berhasil dihapus.'; $msgType = 'success';
    }
}

// Edit
$editRow = null;
if (isset($_GET['edit'])) {
    $editRow = $db->prepare('SELECT * FROM nisn_list WHERE id=?');
    $editRow->execute([(int)$_GET['edit']]);
    $editRow = $editRow->fetch();
}

// List
$search   = clean($_GET['q'] ?? '');
$angkatanF = (int)($_GET['angkatan'] ?? 0);
$page     = max(1, (int)($_GET['page'] ?? 1));
$perPage  = 20;

$where = []; $params = [];
if ($search) { $where[] = "(nisn LIKE ? OR nama_siswa LIKE ?)"; $params = ["%$search%","%$search%"]; }
if ($angkatanF) { $where[] = "angkatan = ?"; $params[] = $angkatanF; }
$whereStr = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$total = $db->prepare("SELECT COUNT(*) FROM nisn_list $whereStr"); $total->execute($params); $total = $total->fetchColumn();
$pages = max(1, ceil($total / $perPage));
$offset = ($page-1)*$perPage;

$list = $db->prepare("SELECT * FROM nisn_list $whereStr ORDER BY angkatan DESC, nama_siswa ASC LIMIT $perPage OFFSET $offset");
$list->execute($params);
$nisnRows = $list->fetchAll();

$angkatanOpts = $db->query("SELECT DISTINCT angkatan FROM nisn_list ORDER BY angkatan DESC")->fetchAll(PDO::FETCH_COLUMN);

$totalNISN   = $db->query("SELECT COUNT(*) FROM nisn_list")->fetchColumn();
$sudahLapor  = $db->query("SELECT COUNT(*) FROM nisn_list WHERE sudah_lapor=1")->fetchColumn();
$belumLapor  = $totalNISN - $sudahLapor;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola NISN – Admin Portal Alumni</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../../css/admin.css">
  <style>
    .alert-msg { display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:600;margin-bottom:18px; }
    .alert-msg.success{background:var(--green-bg);color:var(--green-text);border:1px solid #86efac;}
    .alert-msg.warning{background:var(--yellow-bg);color:var(--yellow-text);border:1px solid #fde047;}
    .alert-msg.error  {background:var(--red-bg);color:var(--red-text);border:1px solid #fca5a5;}

    .form-panel{background:var(--surface);border:1px solid var(--border);border-radius:var(--r-md);box-shadow:var(--shadow-md);padding:24px;margin-bottom:20px;}
    .form-panel h3{font-size:15px;font-weight:800;margin-bottom:18px;display:flex;align-items:center;gap:8px;}
    .form-row-inline{display:flex;gap:12px;flex-wrap:wrap;}
    .f-group{display:flex;flex-direction:column;gap:6px;}
    .f-group label{font-size:12.5px;font-weight:700;color:var(--text-soft);}
    .f-group input,.f-group select{padding:9px 13px;border:1.5px solid var(--border);border-radius:var(--r-sm);font-family:var(--font);font-size:13px;color:var(--text);background:var(--bg);outline:none;transition:border-color .2s;}
    .f-group input:focus,.f-group select:focus{border-color:var(--accent);background:#fff;}
    .btn-save{padding:9px 20px;background:var(--accent);color:white;border:none;border-radius:var(--r-sm);font-family:var(--font);font-size:13px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:7px;transition:background .15s;align-self:flex-end;}
    .btn-save:hover{background:var(--accent-dark);}

    .import-panel{background:var(--accent-light);border:1.5px dashed var(--accent);border-radius:var(--r-md);padding:20px 24px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;}
    .import-panel p{font-size:13px;font-weight:600;color:var(--accent-dark);flex:1;}
    .import-panel input[type=file]{font-size:13px;}
    .btn-import{padding:9px 18px;background:var(--accent);color:white;border:none;border-radius:var(--r-sm);font-family:var(--font);font-size:13px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:7px;white-space:nowrap;}

    .filter-row{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:16px;}
    .search-admin{display:flex;align-items:center;gap:8px;background:var(--bg);border:1px solid var(--border);border-radius:var(--r-sm);padding:9px 14px;flex:1;max-width:280px;}
    .search-admin:focus-within{border-color:var(--accent);}
    .search-admin input{border:none;outline:none;background:none;font-family:var(--font);font-size:13px;width:100%;color:var(--text);}
    .select-filter{padding:9px 32px 9px 12px;border:1px solid var(--border);border-radius:var(--r-sm);font-family:var(--font);font-size:13px;background:var(--surface);color:var(--text);cursor:pointer;appearance:none;background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");background-repeat:no-repeat;background-position:right 10px center;background-size:14px;}

    .nisn-chip{background:var(--bg);border:1px solid var(--border);padding:2px 9px;border-radius:5px;font-family:monospace;font-size:12.5px;font-weight:700;letter-spacing:.3px;}
    .lapor-yes{color:var(--green-text);background:var(--green-bg);padding:2px 9px;border-radius:999px;font-size:11px;font-weight:700;}
    .lapor-no {color:var(--muted);background:var(--bg);padding:2px 9px;border-radius:999px;font-size:11px;font-weight:700;}
    .pg-wrap{display:flex;align-items:center;gap:8px;padding:14px 18px;border-top:1px solid var(--border);}
    .pg-btn{width:34px;height:34px;border:1px solid var(--border);border-radius:var(--r-xs);font-size:13px;font-weight:700;color:var(--text-soft);background:var(--surface);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .12s;text-decoration:none;}
    .pg-btn:hover:not([disabled]){border-color:var(--accent);color:var(--accent);}
    .pg-btn.active{background:var(--accent);border-color:var(--accent);color:white;}
    .pg-btn[disabled]{opacity:.4;pointer-events:none;}
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
        <span class="breadcrumb-active">Kelola NISN</span>
      </div>
    </div>
    <div class="topbar-right"><?php include 'topbar-avatar.php'; ?></div>
  </header>

  <main class="content">
    <div class="page-title">
      <div>
        <h2>Kelola NISN</h2>
        <p>Daftar NISN valid untuk verifikasi laporan siswa</p>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
      <div class="stat-card">
        <div class="stat-icon-wrap"><i class="fa-solid fa-id-card" style="color:#3b6cf4;"></i></div>
        <div class="stat-body"><p class="stat-label">Total NISN</p><h3 class="stat-value"><?= $totalNISN ?></h3></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon-wrap"><i class="fa-solid fa-circle-check" style="color:#22c55e;"></i></div>
        <div class="stat-body"><p class="stat-label">Sudah Lapor</p><h3 class="stat-value"><?= $sudahLapor ?></h3></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon-wrap"><i class="fa-solid fa-clock" style="color:#f59e0b;"></i></div>
        <div class="stat-body"><p class="stat-label">Belum Lapor</p><h3 class="stat-value"><?= $belumLapor ?></h3></div>
      </div>
    </div>

    <!-- Alert -->
    <?php if ($msg): ?>
    <div class="alert-msg <?= $msgType ?>" id="alertMsg">
      <i class="fa-solid fa-circle-check"></i> <?= $msg ?>
    </div>
    <?php endif; ?>

    <!-- Form Tambah/Edit -->
    <div class="form-panel">
      <h3><i class="fa-solid fa-<?= $editRow ? 'pen' : 'plus' ?>" style="color:var(--accent);"></i>
        <?= $editRow ? 'Edit NISN' : 'Tambah NISN Baru' ?>
      </h3>
      <form method="POST">
        <input type="hidden" name="aksi" value="save">
        <?php if ($editRow): ?><input type="hidden" name="id" value="<?= $editRow['id'] ?>"><?php endif; ?>
        <div class="form-row-inline">
          <div class="f-group" style="flex:0 0 160px;">
            <label>NISN (10 digit) *</label>
            <input type="text" name="nisn" maxlength="10" inputmode="numeric"
              placeholder="0000000000" required
              value="<?= $editRow ? htmlspecialchars($editRow['nisn']) : '' ?>">
          </div>
          <div class="f-group" style="flex:1;min-width:180px;">
            <label>Nama Siswa *</label>
            <input type="text" name="nama_siswa" placeholder="Nama lengkap siswa" required
              value="<?= $editRow ? htmlspecialchars($editRow['nama_siswa']) : '' ?>">
          </div>
          <div class="f-group" style="flex:0 0 110px;">
            <label>Angkatan *</label>
            <select name="angkatan" required>
              <?php for ($y=(int)date('Y'); $y>=2015; $y--): ?>
              <option value="<?= $y ?>" <?= ($editRow && $editRow['angkatan']==$y) ? 'selected' : '' ?>><?= $y ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <button type="submit" class="btn-save">
            <i class="fa-solid fa-floppy-disk"></i> <?= $editRow ? 'Update' : 'Tambah' ?>
          </button>
          <?php if ($editRow): ?>
          <a href="nisn.php" style="align-self:flex-end;padding:9px 14px;background:var(--bg);border:1px solid var(--border);border-radius:var(--r-sm);font-size:13px;font-weight:600;color:var(--text-soft);text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
            <i class="fa-solid fa-xmark"></i> Batal
          </a>
          <?php endif; ?>
        </div>
      </form>
    </div>

    <!-- Import CSV -->
    <div class="form-panel" style="padding:20px 24px;">
      <h3><i class="fa-solid fa-file-csv" style="color:#16a34a;"></i> Import Massal via CSV</h3>
      <div class="import-panel">
        <p>
          <strong>Format CSV:</strong> kolom <code>nisn</code>, <code>nama_siswa</code>, <code>angkatan</code><br>
          <small style="opacity:.8;">Baris pertama = header. NISN duplikat akan dilewati otomatis.</small>
        </p>
        <form method="POST" enctype="multipart/form-data" style="display:flex;align-items:center;gap:10px;">
          <input type="hidden" name="aksi" value="import">
          <input type="file" name="csv_file" accept=".csv" required>
          <button type="submit" class="btn-import"><i class="fa-solid fa-upload"></i> Import</button>
        </form>
      </div>
      <p style="font-size:12px;color:var(--muted);margin-top:10px;">
        <i class="fa-solid fa-download" style="color:var(--accent);"></i>
        <a href="download-template-nisn.php" style="color:var(--accent);font-weight:700;">Download template CSV</a>
      </p>
    </div>

    <!-- Filter -->
    <form method="GET">
      <div class="filter-row">
        <div class="search-admin">
          <i class="fa-solid fa-magnifying-glass" style="color:var(--muted);font-size:13px;"></i>
          <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari NISN atau nama...">
        </div>
        <select name="angkatan" class="select-filter" onchange="this.form.submit()">
          <option value="">Semua Angkatan</option>
          <?php foreach ($angkatanOpts as $y): ?>
          <option value="<?= $y ?>" <?= $angkatanF==$y?'selected':'' ?>><?= $y ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-save" style="align-self:auto;">
          <i class="fa-solid fa-filter"></i> Filter
        </button>
      </div>
    </form>

    <!-- Table -->
    <div class="card">
      <div class="card-header">
        <h3><i class="fa-solid fa-id-card"></i> Daftar NISN</h3>
        <span class="badge-pill"><?= $total ?> data</span>
      </div>
      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr><th>#</th><th>NISN</th><th>Nama Siswa</th><th>Angkatan</th><th>Status Lapor</th><th></th></tr>
          </thead>
          <tbody>
            <?php if (empty($nisnRows)): ?>
            <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-id-card"></i><p>Tidak ada data.</p></div></td></tr>
            <?php else: ?>
            <?php foreach ($nisnRows as $i => $n): $num = ($page-1)*$perPage + $i + 1; ?>
            <tr>
              <td style="color:var(--muted);font-size:12px;"><?= $num ?></td>
              <td><span class="nisn-chip"><?= htmlspecialchars($n['nisn']) ?></span></td>
              <td style="font-weight:700;"><?= htmlspecialchars($n['nama_siswa']) ?></td>
              <td><?= $n['angkatan'] ?></td>
              <td>
                <?php if ($n['sudah_lapor']): ?>
                <span class="lapor-yes"><i class="fa-solid fa-check"></i> Sudah lapor</span>
                <?php else: ?>
                <span class="lapor-no">Belum lapor</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="action-btns">
                  <a href="?edit=<?= $n['id'] ?>" class="btn-icon blue-icon" title="Edit"><i class="fa-solid fa-pen"></i></a>
                  <?php if ($n['sudah_lapor']): ?>
                  <form method="POST" style="display:contents;" onsubmit="return confirm('Reset flag laporan NISN ini?')">
                    <input type="hidden" name="aksi" value="reset_lapor">
                    <input type="hidden" name="nisn_id" value="<?= $n['id'] ?>">
                    <button type="submit" class="btn-icon" style="background:var(--yellow-bg);color:var(--yellow-text);" title="Reset lapor">
                      <i class="fa-solid fa-rotate-left"></i>
                    </button>
                  </form>
                  <?php endif; ?>
                  <form method="POST" style="display:contents;" onsubmit="return confirm('Hapus NISN <?= addslashes($n['nisn']) ?>?')">
                    <input type="hidden" name="aksi" value="delete">
                    <input type="hidden" name="nisn_id" value="<?= $n['id'] ?>">
                    <button type="submit" class="btn-icon red-icon" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <?php if ($pages > 1): ?>
      <div class="pg-wrap">
        <?php $qs = http_build_query(['q'=>$search,'angkatan'=>$angkatanF]); ?>
        <a href="?<?= $qs ?>&page=<?= max(1,$page-1) ?>" class="pg-btn" <?= $page<=1?'disabled':'' ?>>‹</a>
        <?php for ($p=1;$p<=$pages;$p++): ?>
        <a href="?<?= $qs ?>&page=<?= $p ?>" class="pg-btn <?= $p===$page?'active':'' ?>"><?= $p ?></a>
        <?php endfor; ?>
        <a href="?<?= $qs ?>&page=<?= min($pages,$page+1) ?>" class="pg-btn" <?= $page>=$pages?'disabled':'' ?>>›</a>
        <span style="margin-left:auto;font-size:12px;color:var(--muted);"><?= $total ?> total NISN</span>
      </div>
      <?php endif; ?>
    </div>

  </main>
</div>

<script src="../../js/admin.js"></script>
<script>
  setTimeout(()=>{const a=document.getElementById('alertMsg');if(a){a.style.opacity='0';a.style.transition='opacity .4s';setTimeout(()=>a.remove(),400);}},4000);
</script>
</body>
</html>
