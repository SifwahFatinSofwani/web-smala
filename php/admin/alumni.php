<?php
// ============================================================
//  Admin: Kelola Data Alumni (CRUD + Bulk Import)
//  php/admin/alumni.php
// ============================================================
require_once __DIR__ . '/db.php';
requireAdmin();

$db      = getDB();
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
    $id       = (int)($_POST['id']      ?? 0);
    $nama     = clean($_POST['nama']    ?? '');
    $univId   = (int)($_POST['universitas_id'] ?? 0);
    $prodi    = clean($_POST['prodi']   ?? '');
    $jalur    = $_POST['jalur']         ?? '';
    $angkatan = (int)($_POST['angkatan'] ?? 0);
    $nisn     = clean($_POST['nisn']    ?? '');

    $jalurOk = in_array($jalur, ['SNBP','SNBT','Mandiri','Kedinasan']);
    if (!$nama || !$univId || !$prodi || !$jalurOk || !$angkatan) {
        $msg = 'Harap lengkapi semua field.'; $msgType = 'error';
    } else {
        $univRow = $db->prepare('SELECT nama FROM universitas WHERE id=?');
        $univRow->execute([$univId]); $univNama = $univRow->fetchColumn();

        if ($id) {
            $db->prepare('UPDATE alumni SET nama=?,universitas_id=?,universitas_nama=?,prodi=?,jalur=?,angkatan=?,nisn=? WHERE id=?')
               ->execute([$nama,$univId,$univNama,$prodi,$jalur,$angkatan,$nisn?:null,$id]);
            $msg = 'Data alumni berhasil diperbarui.'; $msgType = 'success';
        } else {
            $db->prepare("INSERT INTO alumni (nama,nisn,universitas_id,universitas_nama,prodi,jalur,angkatan,input_oleh) VALUES (?,?,?,?,?,?,?,'admin')")
               ->execute([$nama,$nisn?:null,$univId,$univNama,$prodi,$jalur,$angkatan]);
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
$search    = clean($_GET['q']        ?? '');
$jalurF    = clean($_GET['jalur']    ?? '');
$angkatanF = (int)($_GET['angkatan'] ?? 0);
$page      = max(1, (int)($_GET['page'] ?? 1));
$perPage   = 15;

$where  = ["a.status = 'aktif'"];
$params = [];
if ($search)    { $where[] = "(a.nama LIKE ? OR a.universitas_nama LIKE ? OR a.prodi LIKE ?)"; $params = array_merge($params, ["%$search%","%$search%","%$search%"]); }
if ($jalurF)    { $where[] = "a.jalur = ?"; $params[] = $jalurF; }
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
    /* ── Alert ── */
    .alert-msg{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:600;margin-bottom:18px;}
    .alert-msg.success{background:var(--green-bg);color:var(--green-text);border:1px solid #86efac;}
    .alert-msg.error  {background:var(--red-bg);color:var(--red-text);border:1px solid #fca5a5;}

    /* ── Table ── */
    .data-table td{word-break:break-word;color:var(--text)!important;}
    .data-table td,.data-table th{white-space:normal!important;}

    /* ── Filter ── */
    .filter-row{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:16px;}
    .search-admin{display:flex;align-items:center;gap:8px;background:var(--bg);border:1px solid var(--border);border-radius:var(--r-sm);padding:9px 14px;flex:1;max-width:320px;}
    .search-admin:focus-within{border-color:var(--accent);}
    .search-admin input{border:none;outline:none;background:none;font-family:var(--font);font-size:13px;width:100%;color:var(--text);}
    .select-filter{padding:9px 32px 9px 12px;border:1px solid var(--border);border-radius:var(--r-sm);font-family:var(--font);font-size:13px;background:var(--surface);color:var(--text);cursor:pointer;appearance:none;background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");background-repeat:no-repeat;background-position:right 10px center;background-size:14px;}

    /* ── Pagination ── */
    .pagination-admin{display:flex;align-items:center;gap:8px;padding:14px 18px;border-top:1px solid var(--border);}
    .pg-btn{width:34px;height:34px;border:1px solid var(--border);border-radius:var(--r-xs);font-size:13px;font-weight:700;color:var(--text-soft);background:var(--surface);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .12s;text-decoration:none;}
    .pg-btn:hover:not([disabled]){border-color:var(--accent);color:var(--accent);}
    .pg-btn.active{background:var(--accent);border-color:var(--accent);color:white;}
    .pg-btn[disabled]{opacity:.4;pointer-events:none;}
    .pg-info{font-size:12px;color:var(--muted);margin-left:auto;}

    /* ── Form Panel (Tambah/Edit) ── */
    .form-panel{background:var(--surface);border:1px solid var(--border);border-radius:var(--r-md);box-shadow:var(--shadow-md);padding:24px;margin-bottom:20px;}
    .form-panel h3{font-size:16px;font-weight:800;margin-bottom:20px;display:flex;align-items:center;gap:8px;}
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
    .form-grid-3{grid-template-columns:1fr 1fr 1fr;}
    @media(max-width:720px){.form-grid,.form-grid-3{grid-template-columns:1fr;}}
    .f-group{display:flex;flex-direction:column;gap:6px;}
    .f-group label{font-size:12.5px;font-weight:700;color:var(--text-soft);}
    .f-group input,.f-group select{padding:10px 14px;border:1.5px solid var(--border);border-radius:var(--r-sm);font-family:var(--font);font-size:13.5px;color:var(--text);background:var(--bg);outline:none;transition:border-color .2s,box-shadow .2s;-webkit-appearance:none;}
    .f-group input:focus,.f-group select:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(59,108,244,.1);background:#fff;}
    .form-actions{display:flex;gap:10px;margin-top:8px;}
    .btn-save{padding:10px 22px;background:var(--accent);color:white;border:none;border-radius:var(--r-sm);font-family:var(--font);font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:7px;transition:background .15s;}
    .btn-save:hover{background:var(--accent-dark);}
    .btn-cancel{padding:10px 18px;background:var(--bg);color:var(--text-soft);border:1px solid var(--border);border-radius:var(--r-sm);font-family:var(--font);font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;display:flex;align-items:center;gap:7px;}

    /* ══════════════════════════════════════════
       BULK IMPORT PANEL
    ══════════════════════════════════════════ */
    .import-panel {
      background: var(--surface);
      border: 1.5px dashed var(--accent);
      border-radius: var(--r-md);
      margin-bottom: 20px;
      overflow: hidden;
      transition: border-color .2s;
    }
    .import-panel-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 20px;
      cursor: pointer;
      user-select: none;
      gap: 12px;
    }
    .import-panel-header:hover { background: var(--accent-light); }
    .import-panel-title {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      font-weight: 700;
      color: var(--accent);
    }
    .import-panel-title i { font-size: 16px; }
    .import-panel-chevron {
      color: var(--accent);
      font-size: 12px;
      transition: transform .25s;
    }
    .import-panel.open .import-panel-chevron { transform: rotate(180deg); }
    .import-panel-body {
      display: none;
      padding: 0 20px 20px;
      border-top: 1px solid var(--border);
    }
    .import-panel.open .import-panel-body { display: block; }

    /* Step labels */
    .import-steps {
      display: flex;
      gap: 0;
      margin: 18px 0 20px;
      flex-wrap: wrap;
    }
    .import-step {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 12.5px;
      font-weight: 700;
      color: var(--muted);
    }
    .import-step .step-num {
      width: 22px;
      height: 22px;
      border-radius: 50%;
      background: var(--border);
      color: var(--muted);
      font-size: 11px;
      font-weight: 800;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .import-step.active .step-num { background: var(--accent); color: white; }
    .import-step.active { color: var(--accent); }
    .import-step.done .step-num   { background: var(--green); color: white; }
    .import-step.done { color: var(--green-text); }
    .import-step-sep { width: 28px; height: 1px; background: var(--border); margin: 0 6px; flex-shrink: 0; }

    /* Drop zone */
    .drop-zone {
      border: 2px dashed var(--border);
      border-radius: var(--r-sm);
      padding: 28px 20px;
      text-align: center;
      background: var(--bg);
      cursor: pointer;
      transition: border-color .2s, background .2s;
      position: relative;
    }
    .drop-zone:hover,
    .drop-zone.drag-over { border-color: var(--accent); background: var(--accent-light); }
    .drop-zone input[type=file] {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
      width: 100%;
      height: 100%;
    }
    .drop-zone i { font-size: 28px; color: var(--muted); margin-bottom: 8px; display: block; }
    .drop-zone p { font-size: 13px; font-weight: 600; color: var(--text-soft); margin: 0; }
    .drop-zone span { font-size: 12px; color: var(--muted); }

    /* Template row */
    .template-row {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-top: 12px;
      font-size: 12.5px;
      color: var(--muted);
      flex-wrap: wrap;
    }
    .btn-template {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border: 1px solid var(--border);
      border-radius: 20px;
      font-size: 12px;
      font-weight: 700;
      color: var(--text-soft);
      background: var(--surface);
      cursor: pointer;
      text-decoration: none;
      transition: all .15s;
    }
    .btn-template:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }

    /* Format hint */
    .format-hint {
      background: #f0f9ff;
      border: 1px solid #bae6fd;
      border-radius: var(--r-sm);
      padding: 12px 16px;
      margin-top: 14px;
      font-size: 12.5px;
      line-height: 1.7;
      color: #075985;
    }
    .format-hint code {
      background: #e0f2fe;
      padding: 1px 5px;
      border-radius: 4px;
      font-family: monospace;
      font-size: 12px;
      font-weight: 700;
    }

    /* Preview table */
    .preview-wrap {
      margin-top: 18px;
      display: none;
    }
    .preview-wrap.show { display: block; }
    .preview-meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 10px;
      flex-wrap: wrap;
      gap: 8px;
    }
    .preview-meta span { font-size: 13px; font-weight: 700; color: var(--text-soft); }
    .preview-badge {
      font-size: 11px;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 999px;
    }
    .preview-badge.ok  { background: var(--green-bg); color: var(--green-text); }
    .preview-badge.err { background: var(--red-bg);   color: var(--red-text); }

    .preview-table-wrap {
      max-height: 260px;
      overflow: auto;
      border: 1px solid var(--border);
      border-radius: var(--r-sm);
    }
    .preview-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12.5px;
      white-space: nowrap;
    }
    .preview-table th {
      position: sticky;
      top: 0;
      background: #f7fafc;
      padding: 8px 12px;
      text-align: left;
      font-weight: 700;
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: .4px;
      color: var(--muted);
      border-bottom: 1px solid var(--border);
    }
    .preview-table td {
      padding: 8px 12px;
      border-bottom: 1px solid #f1f6fa;
      vertical-align: middle;
    }
    .preview-table tr.row-error { background: #fff5f5; }
    .preview-table tr.row-error td { color: #dc2626; }
    .row-status-ok  { color: var(--green-text); font-size: 11px; font-weight: 700; }
    .row-status-err { color: var(--red-text);   font-size: 11px; font-weight: 700; }

    /* Import action bar */
    .import-action-bar {
      display: none;
      align-items: center;
      gap: 12px;
      margin-top: 14px;
      padding-top: 14px;
      border-top: 1px solid var(--border);
      flex-wrap: wrap;
    }
    .import-action-bar.show { display: flex; }
    .btn-import-now {
      padding: 10px 22px;
      background: var(--green);
      color: white;
      border: none;
      border-radius: var(--r-sm);
      font-family: var(--font);
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: background .15s, transform .12s;
      box-shadow: 0 2px 10px rgba(34,197,94,.3);
    }
    .btn-import-now:hover:not(:disabled) { background: #16a34a; transform: translateY(-1px); }
    .btn-import-now:disabled { opacity: .6; cursor: not-allowed; transform: none; }
    .btn-import-reset {
      padding: 10px 16px;
      background: var(--bg);
      color: var(--text-soft);
      border: 1px solid var(--border);
      border-radius: var(--r-sm);
      font-family: var(--font);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .import-progress-text { font-size: 12.5px; color: var(--muted); font-weight: 600; }

    /* Result card */
    .import-result {
      display: none;
      margin-top: 14px;
      border-radius: var(--r-sm);
      padding: 14px 16px;
    }
    .import-result.show { display: block; }
    .import-result.success-result { background: var(--green-bg); border: 1px solid #86efac; }
    .import-result.error-result   { background: var(--red-bg);   border: 1px solid #fca5a5; }
    .import-result-title {
      font-size: 14px;
      font-weight: 800;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .import-result ul {
      margin: 8px 0 0 18px;
      padding: 0;
      font-size: 12px;
      line-height: 1.8;
      color: var(--red-text);
    }
  </style>
</head>
<body class="admin-page" id="adminPage">

<?php include 'sidebar.php'; ?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="main-wrap">
  <!-- Topbar -->
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
    <!-- Page Title -->
    <div class="page-title">
      <div>
        <h2>Data Alumni</h2>
        <p>Kelola seluruh data alumni SMAN 5 Samarinda (<?= $total ?> entri aktif)</p>
      </div>
      <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        <button class="btn-primary-sm" style="background:var(--green);box-shadow:0 2px 10px rgba(34,197,94,.3);"
          onclick="toggleImportPanel()">
          <i class="fa-solid fa-file-arrow-up"></i> Import CSV / XLSX
        </button>
        <a href="?action=tambah" class="btn-primary-sm">
          <i class="fa-solid fa-plus"></i> Tambah Alumni
        </a>
      </div>
    </div>

    <!-- Alert -->
    <?php if ($msg): ?>
    <div class="alert-msg <?= $msgType ?>" id="alertMsg">
      <i class="fa-solid fa-circle-check"></i> <?= $msg ?>
    </div>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════
         BULK IMPORT PANEL
    ══════════════════════════════════════════ -->
    <div class="import-panel" id="importPanel">
      <div class="import-panel-header" onclick="toggleImportPanel()">
        <div class="import-panel-title">
          <i class="fa-solid fa-file-arrow-up"></i>
          Import Massal – CSV / XLSX
        </div>
        <i class="fa-solid fa-chevron-down import-panel-chevron"></i>
      </div>
      <div class="import-panel-body">

        <!-- Steps indicator -->
        <div class="import-steps">
          <div class="import-step active" id="istep1">
            <span class="step-num">1</span> Pilih File
          </div>
          <div class="import-step-sep"></div>
          <div class="import-step" id="istep2">
            <span class="step-num">2</span> Preview Data
          </div>
          <div class="import-step-sep"></div>
          <div class="import-step" id="istep3">
            <span class="step-num">3</span> Import
          </div>
        </div>

        <!-- Drop zone -->
        <div class="drop-zone" id="dropZone">
          <input type="file" id="importFile" accept=".csv,.xlsx,.xls">
          <i class="fa-solid fa-cloud-arrow-up"></i>
          <p>Seret & lepas file di sini, atau <strong>klik untuk memilih</strong></p>
          <span>Mendukung .csv dan .xlsx — maks. 5 MB</span>
        </div>

        <!-- Template & format hint -->
        <div class="template-row">
          <span>Belum punya template?</span>
          <a class="btn-template" id="btnDownloadCSV" onclick="downloadTemplate('csv')">
            <i class="fa-solid fa-file-csv"></i> Template CSV
          </a>
          <a class="btn-template" id="btnDownloadXLSX" onclick="downloadTemplate('xlsx')">
            <i class="fa-regular fa-file-excel"></i> Template XLSX
          </a>
        </div>

        <div class="format-hint">
          <strong>Format kolom (urutan bebas, nama kolom harus sama):</strong><br>
          <code>nama</code> · <code>nisn</code> (opsional) · <code>universitas_nama</code> ·
          <code>prodi</code> · <code>jalur</code> · <code>angkatan</code><br>
          Nilai <code>jalur</code> yang valid: <code>SNBP</code> / <code>SNBT</code> / <code>Mandiri</code> / <code>Kedinasan</code> ·
          Jika nama universitas tidak ada di database, akan otomatis ditambahkan.
        </div>

        <!-- Preview area -->
        <div class="preview-wrap" id="previewWrap">
          <div class="preview-meta">
            <span id="previewMeta">—</span>
            <div style="display:flex;gap:6px;">
              <span class="preview-badge ok" id="badgeOk">0 valid</span>
              <span class="preview-badge err" id="badgeErr">0 error</span>
            </div>
          </div>
          <div class="preview-table-wrap">
            <table class="preview-table">
              <thead id="previewThead"></thead>
              <tbody id="previewTbody"></tbody>
            </table>
          </div>
        </div>

        <!-- Action bar -->
        <div class="import-action-bar" id="importActionBar">
          <button class="btn-import-now" id="btnImportNow" onclick="doImport()">
            <i class="fa-solid fa-cloud-arrow-up" id="importIcon"></i>
            <span id="importBtnText">Import Data</span>
          </button>
          <button class="btn-import-reset" onclick="resetImport()">
            <i class="fa-solid fa-rotate-left"></i> Ganti File
          </button>
          <span class="import-progress-text" id="importProgressText"></span>
        </div>

        <!-- Result -->
        <div class="import-result" id="importResult">
          <div class="import-result-title" id="importResultTitle"></div>
          <div id="importResultBody"></div>
        </div>

      </div><!-- /import-panel-body -->
    </div><!-- /import-panel -->

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
      <div class="table-wrap" style="overflow-x:unset;">
        <table class="data-table" style="width:100%;table-layout:fixed;">
          <colgroup>
            <col style="width:5%"><col style="width:25%"><col style="width:20%">
            <col style="width:15%"><col style="width:10%"><col style="width:10%">
            <col style="width:10%"><col style="width:5%">
          </colgroup>
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
            <?php foreach ($alumni as $i => $a):
              $init = strtoupper(mb_substr($a['nama'], 0, 1));
              $num  = ($page-1)*$perPage + $i + 1;
            ?>
            <tr>
              <td style="color:var(--muted);font-size:12px;"><?= $num ?></td>
              <td>
                <div class="user-cell">
                  <div class="user-ava" style="background:#f3f4f6;color:#4b5563;"><?= $init ?></div>
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
                <span class="pill <?= $a['jalur']==='SNBP'?'pill-green':($a['jalur']==='SNBT'?'pill-blue':($a['jalur']==='Kedinasan'?'pill-yellow':'pill-red')) ?>">
                  <?= htmlspecialchars($a['jalur']) ?>
                </span>
              </td>
              <td><?= $a['angkatan'] ?></td>
              <td>
                <span class="pill <?= $a['input_oleh']==='admin'?'pill-blue':'pill-green' ?>">
                  <i class="fa-solid fa-<?= $a['input_oleh']==='admin'?'user-shield':'user' ?>" style="font-size:9px;"></i>
                  <?= $a['input_oleh']==='admin'?'Admin':'Siswa' ?>
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
        <?php $qs = http_build_query(['q'=>$search,'jalur'=>$jalurF,'angkatan'=>$angkatanF]); ?>
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
</div><!-- /main-wrap -->

<!-- SheetJS untuk parsing XLSX di browser -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="../../js/admin.js"></script>
<script>
// ════════════════════════════════════════════════════════════
//  BULK IMPORT LOGIC
// ════════════════════════════════════════════════════════════

const REQUIRED_COLS = ['nama','universitas_nama','prodi','jalur','angkatan'];
const JALUR_VALID   = ['SNBP','SNBT','Mandiri','Kedinasan'];
const YEAR_NOW      = new Date().getFullYear();

let parsedRows  = [];   // semua baris dari file
let validRows   = [];   // baris yang lolos validasi klien
let importDone  = false;

// ── Toggle panel ───────────────────────────────────────────
function toggleImportPanel() {
  const panel = document.getElementById('importPanel');
  panel.classList.toggle('open');
  if (panel.classList.contains('open')) {
    panel.scrollIntoView({ behavior:'smooth', block:'start' });
  }
}

// ── Drag & drop events ─────────────────────────────────────
const dropZone   = document.getElementById('dropZone');
const fileInput  = document.getElementById('importFile');

dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
dropZone.addEventListener('dragleave', ()  => dropZone.classList.remove('drag-over'));
dropZone.addEventListener('drop', e => {
  e.preventDefault();
  dropZone.classList.remove('drag-over');
  const file = e.dataTransfer.files[0];
  if (file) handleFile(file);
});
fileInput.addEventListener('change', e => {
  if (e.target.files[0]) handleFile(e.target.files[0]);
});

// ── Pilih & parse file ─────────────────────────────────────
function handleFile(file) {
  resetImport();
  const ext = file.name.split('.').pop().toLowerCase();

  if (!['csv','xlsx','xls'].includes(ext)) {
    alert('Format tidak didukung. Gunakan .csv atau .xlsx');
    return;
  }
  if (file.size > 5 * 1024 * 1024) {
    alert('File terlalu besar. Maks. 5 MB.');
    return;
  }

  const reader = new FileReader();

  if (ext === 'csv') {
    reader.onload = e => {
      const text = e.target.result;
      const rows = parseCSV(text);
      buildPreview(rows, file.name);
    };
    reader.readAsText(file, 'UTF-8');
  } else {
    reader.onload = e => {
      const data  = new Uint8Array(e.target.result);
      const wb    = XLSX.read(data, { type:'array' });
      const ws    = wb.Sheets[wb.SheetNames[0]];
      const rows  = XLSX.utils.sheet_to_json(ws, { defval:'' });
      buildPreview(rows, file.name);
    };
    reader.readAsArrayBuffer(file);
  }
}

// ── Parse CSV manual ───────────────────────────────────────
function parseCSV(text) {
  const lines = text.split(/\r?\n/).filter(l => l.trim());
  if (lines.length < 2) return [];

  const headers = lines[0].split(',').map(h => h.trim().replace(/^"|"$/g, '').toLowerCase());
  const rows = [];
  for (let i = 1; i < lines.length; i++) {
    const vals = splitCSVLine(lines[i]);
    if (vals.every(v => !v.trim())) continue;
    const obj = {};
    headers.forEach((h, idx) => { obj[h] = (vals[idx] || '').trim().replace(/^"|"$/g, ''); });
    rows.push(obj);
  }
  return rows;
}

function splitCSVLine(line) {
  const result = [];
  let current = '', inQuotes = false;
  for (let i = 0; i < line.length; i++) {
    const ch = line[i];
    if (ch === '"') { inQuotes = !inQuotes; }
    else if (ch === ',' && !inQuotes) { result.push(current); current = ''; }
    else { current += ch; }
  }
  result.push(current);
  return result;
}

// ── Build preview tabel ────────────────────────────────────
function buildPreview(rows, fileName) {
  if (!rows || rows.length === 0) {
    alert('File kosong atau format tidak terbaca.');
    return;
  }

  // Normalisasi key (lowercase & trim)
  const normalizedRows = rows.map(r => {
    const n = {};
    Object.keys(r).forEach(k => { n[k.toLowerCase().trim()] = String(r[k]).trim(); });
    return n;
  });

  parsedRows = normalizedRows;
  validRows  = [];

  const COLS_DISPLAY = ['nama','nisn','universitas_nama','prodi','jalur','angkatan','status'];

  // Build thead
  let thead = '<tr>';
  ['#','Nama','NISN','Universitas','Prodi','Jalur','Angkatan','Status'].forEach(h => {
    thead += `<th>${h}</th>`;
  });
  thead += '</tr>';
  document.getElementById('previewThead').innerHTML = thead;

  // Validate & build rows
  let okCount = 0, errCount = 0;
  let tbody = '';

  normalizedRows.forEach((row, idx) => {
    const errs = validateRow(row);
    const isOk = errs.length === 0;
    if (isOk) { validRows.push(row); okCount++; }
    else errCount++;

    const cls = isOk ? '' : 'row-error';
    tbody += `<tr class="${cls}">
      <td style="color:var(--muted);font-size:11px;">${idx+2}</td>
      <td>${esc(row.nama||'')}</td>
      <td style="font-size:11px;color:var(--muted);">${esc(row.nisn||'—')}</td>
      <td>${esc(row.universitas_nama||'')}</td>
      <td>${esc(row.prodi||'')}</td>
      <td>${esc(row.jalur||'')}</td>
      <td>${esc(row.angkatan||'')}</td>
      <td class="${isOk?'row-status-ok':'row-status-err'}">
        ${isOk ? '✓ OK' : '✗ ' + errs[0]}
      </td>
    </tr>`;
  });

  document.getElementById('previewTbody').innerHTML = tbody;

  // Meta
  document.getElementById('previewMeta').textContent =
    `📄 ${fileName} · ${normalizedRows.length} baris ditemukan`;
  document.getElementById('badgeOk').textContent  = `${okCount} valid`;
  document.getElementById('badgeErr').textContent = `${errCount} error`;

  document.getElementById('previewWrap').classList.add('show');
  document.getElementById('importActionBar').classList.add('show');
  document.getElementById('btnImportNow').disabled = (okCount === 0);
  document.getElementById('importBtnText').textContent =
    `Import ${okCount} Alumni`;

  // Perbarui step indicator
  setStep(2);
}

// ── Validasi satu baris ────────────────────────────────────
function validateRow(row) {
  const errs = [];
  if (!row.nama)             errs.push('nama kosong');
  if (!row.universitas_nama) errs.push('universitas_nama kosong');
  if (!row.prodi)            errs.push('prodi kosong');
  if (!JALUR_VALID.includes(row.jalur)) errs.push(`jalur '${row.jalur}' tidak valid`);
  const thn = parseInt(row.angkatan);
  if (isNaN(thn) || thn < 2015 || thn > YEAR_NOW + 1) errs.push('angkatan tidak valid');
  return errs;
}

// ── Kirim ke server ────────────────────────────────────────
async function doImport() {
  if (validRows.length === 0 || importDone) return;

  const btn  = document.getElementById('btnImportNow');
  const icon = document.getElementById('importIcon');
  const txt  = document.getElementById('importBtnText');
  const prog = document.getElementById('importProgressText');

  btn.disabled = true;
  icon.className = 'fa-solid fa-circle-notch fa-spin';
  txt.textContent = 'Mengimpor…';
  prog.textContent = `Mengirim ${validRows.length} baris ke server…`;

  try {
    const res  = await fetch('bulk_import.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ rows: validRows }),
    });
    const data = await res.json();

    // Tampilkan hasil
    const resultEl = document.getElementById('importResult');
    const titleEl  = document.getElementById('importResultTitle');
    const bodyEl   = document.getElementById('importResultBody');

    if (data.inserted > 0) {
      resultEl.className = 'import-result show success-result';
      titleEl.innerHTML  = `<i class="fa-solid fa-circle-check" style="color:var(--green);"></i>
        ${data.inserted} alumni berhasil diimpor!
        ${data.skipped ? `<span style="font-size:12px;color:var(--muted);"> · ${data.skipped} dilewati</span>` : ''}`;
    } else {
      resultEl.className = 'import-result show error-result';
      titleEl.innerHTML  = `<i class="fa-solid fa-circle-xmark" style="color:var(--red);"></i>
        Tidak ada data yang berhasil diimpor.`;
    }

    if (data.errors && data.errors.length) {
      bodyEl.innerHTML = '<ul>' + data.errors.map(e => `<li>${esc(e)}</li>`).join('') + '</ul>';
    } else {
      bodyEl.innerHTML = '';
    }

    prog.textContent = '';
    icon.className   = 'fa-solid fa-check';
    txt.textContent  = 'Selesai!';
    importDone = true;
    setStep(3);

    // Reload tabel setelah 2 detik
    if (data.inserted > 0) {
      setTimeout(() => { window.location.href = 'alumni.php'; }, 2200);
    }

  } catch (err) {
    prog.textContent   = 'Gagal terhubung ke server.';
    btn.disabled       = false;
    icon.className     = 'fa-solid fa-cloud-arrow-up';
    txt.textContent    = 'Coba Lagi';
  }
}

// ── Reset panel ────────────────────────────────────────────
function resetImport() {
  parsedRows = []; validRows = []; importDone = false;
  document.getElementById('importFile').value = '';
  document.getElementById('previewWrap').classList.remove('show');
  document.getElementById('importActionBar').classList.remove('show');
  document.getElementById('importResult').classList.remove('show');
  document.getElementById('previewThead').innerHTML = '';
  document.getElementById('previewTbody').innerHTML = '';
  document.getElementById('importProgressText').textContent = '';
  document.getElementById('importBtnText').textContent = 'Import Data';
  document.getElementById('importIcon').className = 'fa-solid fa-cloud-arrow-up';
  document.getElementById('btnImportNow').disabled = false;
  setStep(1);
}

// ── Step indicator ─────────────────────────────────────────
function setStep(n) {
  [1,2,3].forEach(i => {
    const el = document.getElementById('istep' + i);
    el.className = 'import-step' +
      (i < n ? ' done' : i === n ? ' active' : '');
    const num = el.querySelector('.step-num');
    num.innerHTML = i < n ? '<i class="fa-solid fa-check" style="font-size:9px;"></i>' : i;
  });
}

// ── Download template ──────────────────────────────────────
function downloadTemplate(type) {
  const header = ['nama','nisn','universitas_nama','prodi','jalur','angkatan'];
  const sample = [
    ['Andi Firmansyah','1234567890','Universitas Mulawarman','Teknik Informatika','SNBT','2024'],
    ['Siti Rahayu','','Universitas Gadjah Mada','Kedokteran','SNBP','2024'],
    ['Dimas Prasetyo','','Institut Teknologi Bandung','Teknik Sipil','SNBT','2024'],
  ];

  if (type === 'csv') {
    const csv = [header, ...sample].map(r => r.join(',')).join('\n');
    const blob = new Blob(['\uFEFF' + csv], { type:'text/csv;charset=utf-8;' });
    triggerDownload(blob, 'template_alumni.csv');
  } else {
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet([header, ...sample]);
    // Lebar kolom
    ws['!cols'] = header.map((h,i) => ({ wch: [20,15,30,25,12,12][i] }));
    XLSX.utils.book_append_sheet(wb, ws, 'Alumni');
    XLSX.writeFile(wb, 'template_alumni.xlsx');
  }
}

function triggerDownload(blob, name) {
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = name;
  a.click();
  URL.revokeObjectURL(a.href);
}

// ── Helper escape HTML ─────────────────────────────────────
function esc(str) {
  return String(str)
    .replace(/&/g,'&amp;').replace(/</g,'&lt;')
    .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Auto dismiss alert ─────────────────────────────────────
setTimeout(() => {
  const a = document.getElementById('alertMsg');
  if (a) { a.style.opacity='0'; a.style.transition='opacity .4s'; setTimeout(()=>a.remove(),400); }
}, 4000);
</script>
</body>
</html>