<?php
// ============================================================
//  Admin: Kelola Universitas
//  php/admin/universitas.php
// ============================================================
require_once __DIR__ . '/db.php';
requireAdmin();

$db  = getDB();
$msg = ''; $msgType = '';

// Auto-migrate schema (tambah kolom logo jika belum ada)
try {
    $db->exec("ALTER TABLE universitas ADD COLUMN logo VARCHAR(255) DEFAULT NULL");
} catch(Exception $e) { /* Abaikan jika sudah ada */ }

// Pastikan folder uploads/logos tersedia
$uploadDir = __DIR__ . '/../../uploads/logos/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// ── Aksi ─────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';

    if ($aksi === 'save') {
        $id   = (int)($_POST['id'] ?? 0);
        $nama = clean($_POST['nama'] ?? '');
        $kota = clean($_POST['kota'] ?? '');

        if (!$nama) {
            $msg = 'Masukan nama universitas.'; $msgType = 'error';
        } else {
            // Handle Upload Logo
            $logoPath = null;
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','webp'];
                if (in_array($ext, $allowed)) {
                    $fileName = 'logo_' . time() . '_' . rand(100,999) . '.' . $ext;
                    if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $fileName)) {
                        $logoPath = $fileName;
                    }
                } else {
                    $msg = 'Format logo tidak didukung. Gunakan JPG/PNG/WEBP.'; $msgType = 'warning';
                }
            }

            try {
                if ($id) {
                    // Cek apakah ada logo lama saat upload logo baru
                    if ($logoPath) {
                        $old = $db->prepare('SELECT logo FROM universitas WHERE id=?');
                        $old->execute([$id]);
                        $oldFile = $old->fetchColumn();
                        if ($oldFile && file_exists($uploadDir . $oldFile)) {
                            unlink($uploadDir . $oldFile);
                        }
                        $db->prepare('UPDATE universitas SET nama=?, kota=?, logo=? WHERE id=?')
                           ->execute([$nama,$kota,$logoPath,$id]);
                    } else {
                        // Update tanpa ganti logo
                        $db->prepare('UPDATE universitas SET nama=?, kota=? WHERE id=?')
                           ->execute([$nama,$kota,$id]);
                    }
                    $msg = $msg ?: 'Data universitas berhasil diperbarui.'; $msgType = $msgType ?: 'success';
                } else {
                    $db->prepare('INSERT INTO universitas (nama,kota,logo) VALUES (?,?,?)')
                       ->execute([$nama,$kota,$logoPath]);
                    $msg = $msg ?: 'Universitas baru berhasil ditambahkan.'; $msgType = $msgType ?: 'success';
                }
            } catch (PDOException $e) {
                $msg = 'Terjadi kesalahan pada database.'; $msgType = 'error';
            }
        }
    }

    if ($aksi === 'delete' && isset($_POST['univ_id'])) {
        $univ_id = (int)$_POST['univ_id'];
        try {
            $old = $db->prepare('SELECT logo FROM universitas WHERE id=?');
            $old->execute([$univ_id]);
            $oldFile = $old->fetchColumn();

            $db->prepare('DELETE FROM universitas WHERE id=?')->execute([$univ_id]);
            
            if ($oldFile && file_exists($uploadDir . $oldFile)) {
                unlink($uploadDir . $oldFile);
            }
            $msg = 'Data universitas berhasil dihapus.'; $msgType = 'success';
        } catch (PDOException $e) {
            $msg = 'Universitas gagal dihapus. Mungkin masih digunakan oleh data alumni.'; 
            $msgType = 'error';
        }
    }
}

// Edit
$editRow = null;
if (isset($_GET['edit'])) {
    $editRow = $db->prepare('SELECT * FROM universitas WHERE id=?');
    $editRow->execute([(int)$_GET['edit']]);
    $editRow = $editRow->fetch();
}

// List
$search   = clean($_GET['q'] ?? '');
$page     = max(1, (int)($_GET['page'] ?? 1));
$perPage  = 20;

$where = []; $params = [];
if ($search) { $where[] = "(nama LIKE ? OR kota LIKE ?)"; $params = ["%$search%","%$search%"]; }
$whereStr = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$total = $db->prepare("SELECT COUNT(*) FROM universitas $whereStr"); $total->execute($params); $total = $total->fetchColumn();
$pages = max(1, ceil($total / $perPage));
$offset = ($page-1)*$perPage;

$list = $db->prepare("SELECT * FROM universitas $whereStr ORDER BY nama ASC LIMIT $perPage OFFSET $offset");
$list->execute($params);
$univRows = $list->fetchAll();

$totalUniv = $db->query("SELECT COUNT(*) FROM universitas")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Universitas – Admin Portal Alumni</title>
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
    .form-row-inline{display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;}
    .f-group{display:flex;flex-direction:column;gap:6px;}
    .f-group label{font-size:12.5px;font-weight:700;color:var(--text-soft);}
    .f-group input{padding:9px 13px;border:1.5px solid var(--border);border-radius:var(--r-sm);font-family:var(--font);font-size:13px;color:var(--text);background:var(--bg);outline:none;transition:border-color .2s;}
    .f-group input[type="file"]{padding:6px 13px; font-size:12px;}
    .f-group input:focus{border-color:var(--accent);background:#fff;}
    .btn-save{padding:9px 20px;background:var(--accent);color:white;border:none;border-radius:var(--r-sm);font-family:var(--font);font-size:13px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:7px;transition:background .15s;align-self:flex-end;}
    .btn-save:hover{background:var(--accent-dark);}

    .filter-row{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:16px;}
    .search-admin{display:flex;align-items:center;gap:8px;background:var(--bg);border:1px solid var(--border);border-radius:var(--r-sm);padding:9px 14px;flex:1;max-width:280px;}
    .search-admin:focus-within{border-color:var(--accent);}
    .search-admin input{border:none;outline:none;background:none;font-family:var(--font);font-size:13px;width:100%;color:var(--text);}

    .pg-wrap{display:flex;align-items:center;gap:8px;padding:14px 18px;border-top:1px solid var(--border);}
    .pg-btn{width:34px;height:34px;border:1px solid var(--border);border-radius:var(--r-xs);font-size:13px;font-weight:700;color:var(--text-soft);background:var(--surface);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .12s;text-decoration:none;}
    .pg-btn:hover:not([disabled]){border-color:var(--accent);color:var(--accent);}
    .pg-btn.active{background:var(--accent);border-color:var(--accent);color:white;}
    .pg-btn[disabled]{opacity:.4;pointer-events:none;}
    
    .logo-thumb { width:32px; height:32px; border-radius:4px; object-fit:contain; background:#fff; border:1px solid var(--border); }
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
        <span class="breadcrumb-active">Universitas</span>
      </div>
    </div>
    <div class="topbar-right"><?php include 'topbar-avatar.php'; ?></div>
  </header>

  <main class="content">
    <div class="page-title">
      <div>
        <h2>Kelola Universitas</h2>
        <p>Manajemen daftar kampus dan perguruan tinggi yang tersedia</p>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid" style="grid-template-columns:repeat(1, 400px);">
      <div class="stat-card stat-card-sm">
        <div class="stat-icon-wrap"><i class="fa-solid fa-building-columns" style="color:#3b6cf4;"></i></div>
        <div class="stat-body"><p class="stat-label">Total Universitas</p><h3 class="stat-value"><?= $totalUniv ?></h3></div>
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
        <?= $editRow ? 'Edit Universitas' : 'Tambah Kampus Baru' ?>
      </h3>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="aksi" value="save">
        <?php if ($editRow): ?><input type="hidden" name="id" value="<?= $editRow['id'] ?>"><?php endif; ?>
        <div class="form-row-inline">
          <div class="f-group" style="flex:1;min-width:180px;">
            <label>Nama Universitas *</label>
            <input type="text" name="nama" placeholder="Contoh: Universitas Mulawarman" required
              value="<?= $editRow ? htmlspecialchars($editRow['nama']) : '' ?>">
          </div>
          <div class="f-group" style="flex:1;min-width:140px;">
            <label>Kota (opsional)</label>
            <input type="text" name="kota" placeholder="Contoh: Samarinda"
              value="<?= $editRow ? htmlspecialchars($editRow['kota']) : '' ?>">
          </div>
          <div class="f-group" style="flex:0 0 160px;">
            <label>Logo Kampus</label>
            <input type="file" name="logo" accept="image/png, image/jpeg, image/webp">
          </div>
          <button type="submit" class="btn-save" style="margin-bottom: 2px;">
            <i class="fa-solid fa-floppy-disk"></i> <?= $editRow ? 'Update' : 'Tambah' ?>
          </button>
          <?php if ($editRow): ?>
          <a href="universitas.php" style="align-self:flex-end;margin-bottom: 2px;padding:9px 14px;background:var(--bg);border:1px solid var(--border);border-radius:var(--r-sm);font-size:13px;font-weight:600;color:var(--text-soft);text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
            <i class="fa-solid fa-xmark"></i> Batal
          </a>
          <?php endif; ?>
        </div>
      </form>
    </div>

    <!-- Filter -->
    <form method="GET">
      <div class="filter-row">
        <div class="search-admin">
          <i class="fa-solid fa-magnifying-glass" style="color:var(--muted);font-size:13px;"></i>
          <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari universitas atau kota...">
        </div>
        <button type="submit" class="btn-save" style="align-self:auto;">
          <i class="fa-solid fa-filter"></i> Cari
        </button>
      </div>
    </form>

    <!-- Table -->
    <div class="card">
      <div class="card-header">
        <h3><i class="fa-solid fa-building-columns"></i> Daftar Universitas</h3>
        <span class="badge-pill"><?= $total ?> kampus</span>
      </div>
      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr><th>#</th><th>Logo</th><th>Nama Universitas</th><th>Kota / Lokasi</th><th style="width: 80px;">Aksi</th></tr>
          </thead>
          <tbody>
            <?php if (empty($univRows)): ?>
            <tr><td colspan="5"><div class="empty-state"><i class="fa-solid fa-building-columns"></i><p>Tidak ada data universitas.</p></div></td></tr>
            <?php else: ?>
            <?php foreach ($univRows as $i => $u): $num = ($page-1)*$perPage + $i + 1; ?>
            <tr>
              <td style="color:var(--muted);font-size:12px;"><?= $num ?></td>
              <td>
                <?php if (!empty($u['logo'])): ?>
                <img src="../../uploads/logos/<?= htmlspecialchars($u['logo']) ?>" class="logo-thumb" alt="Logo">
                <?php else: ?>
                <div class="logo-thumb" style="display:flex;align-items:center;justify-content:center;background:#9cb2b3;color:#ffffff;border-color:#9cb2b3;">
                  <i class="fa-solid fa-building-columns" style="font-size:12px;"></i>
                </div>
                <?php endif; ?>
              </td>
              <td style="font-weight:700;"><?= htmlspecialchars($u['nama']) ?></td>
              <td style="color:var(--text-soft);"><?= htmlspecialchars($u['kota'] ?? '-') ?></td>
              <td>
                <div class="action-btns" style="justify-content: flex-start;">
                  <a href="?edit=<?= $u['id'] ?>" class="btn-icon blue-icon" title="Edit"><i class="fa-solid fa-pen"></i></a>
                  <form method="POST" style="display:contents;" onsubmit="return confirm('Hapus kampus <?= addslashes($u['nama']) ?>?')">
                    <input type="hidden" name="aksi" value="delete">
                    <input type="hidden" name="univ_id" value="<?= $u['id'] ?>">
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
        <?php $qs = http_build_query(['q'=>$search]); ?>
        <a href="?<?= $qs ?>&page=<?= max(1,$page-1) ?>" class="pg-btn" <?= $page<=1?'disabled':'' ?>>‹</a>
        <?php for ($p=1;$p<=$pages;$p++): ?>
        <a href="?<?= $qs ?>&page=<?= $p ?>" class="pg-btn <?= $p===$page?'active':'' ?>"><?= $p ?></a>
        <?php endfor; ?>
        <a href="?<?= $qs ?>&page=<?= min($pages,$page+1) ?>" class="pg-btn" <?= $page>=$pages?'disabled':'' ?>>›</a>
        <span style="margin-left:auto;font-size:12px;color:var(--muted);"><?= $total ?> total universitas</span>
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
