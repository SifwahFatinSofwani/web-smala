<?php
// ============================================================
//  PUBLIC: Data Alumni
//  php/alumni.php  (menggantikan alumni.html)
// ============================================================
require_once __DIR__ . '/config/db.php';

$db = getDB();

// Stats
$totalAlumni = $db->query("SELECT COUNT(*) FROM alumni WHERE status='aktif'")->fetchColumn();
$totalUniv   = $db->query("SELECT COUNT(DISTINCT universitas_id) FROM alumni WHERE status='aktif'")->fetchColumn();
$totalProdi  = $db->query("SELECT COUNT(DISTINCT prodi) FROM alumni WHERE status='aktif'")->fetchColumn();
$angkatanMax = $db->query("SELECT MAX(angkatan) FROM alumni WHERE status='aktif'")->fetchColumn();

// Filter & pagination
$q         = clean($_GET['q'] ?? '');
$jalurF    = clean($_GET['jalur'] ?? '');
$angkatanF = (int)($_GET['angkatan'] ?? 0);
$sort      = in_array($_GET['sort']??'', ['nama','kampus','angkatan']) ? $_GET['sort'] : 'nama';
$page      = max(1, (int)($_GET['page'] ?? 1));
$perPage   = 10;

$where  = ["a.status='aktif'"]; $params = [];
if ($q) { $where[] = "(a.nama LIKE ? OR a.universitas_nama LIKE ? OR a.prodi LIKE ?)"; $params = array_merge($params,["%$q%","%$q%","%$q%"]); }
if ($jalurF) { $where[] = "a.jalur = ?"; $params[] = $jalurF; }
if ($angkatanF) { $where[] = "a.angkatan = ?"; $params[] = $angkatanF; }
$whereStr = 'WHERE ' . implode(' AND ', $where);

$orderMap = ['nama'=>'a.nama ASC','kampus'=>'a.universitas_nama ASC','angkatan'=>'a.angkatan DESC'];
$orderStr = $orderMap[$sort];

$total = $db->prepare("SELECT COUNT(*) FROM alumni a $whereStr"); $total->execute($params); $total = (int)$total->fetchColumn();
$pages = max(1, ceil($total / $perPage));
if ($page > $pages) $page = $pages;
$offset = ($page-1)*$perPage;

$list = $db->prepare("SELECT a.* FROM alumni a $whereStr ORDER BY $orderStr LIMIT $perPage OFFSET $offset");
$list->execute($params);
$alumni = $list->fetchAll();

// Angkatan list
$angkatanOpts = $db->query("SELECT DISTINCT angkatan FROM alumni WHERE status='aktif' ORDER BY angkatan DESC")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Alumni — Portal Alumni SMAN 5 Samarinda</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-cross">

  <!-- NAVBAR -->
  <nav class="navbar" id="navbar">
    <div class="container nav-inner">
      <a href="index.php" class="nav-brand">
        <img src="../image/logosma5.png" alt="Logo SMAN 5" class="nav-logo"
          onerror="this.src='https://via.placeholder.com/38/2563eb/fff?text=S5';this.style.borderRadius='50%';">
        <span>SMAN 5 Samarinda</span>
      </a>
      <div class="nav-menu">
        <a href="index.php">Beranda</a>
        <a href="alumni.php" class="active">Data Alumni</a>
        <a href="lapor.php" style="background:#2563eb;color:white;padding:6px 16px;border-radius:999px;font-size:13px;">
          <i class="fa-solid fa-plus"></i> Lapor Data
        </a>
      </div>
      <button class="menu-btn" id="menuBtn"><i class="fa-solid fa-bars" id="menuIcon"></i></button>
    </div>
  </nav>

  <div class="mobile-menu" id="mobileMenu">
    <a href="index.php">Beranda</a>
    <a href="alumni.php" class="active">Data Alumni</a>
    <a href="lapor.php">Lapor Data Saya</a>
  </div>

  <!-- PAGE HERO -->
  <section class="page-hero-alt">
    <div class="container">
      <div class="breadcrumb">
        <a href="index.php">Beranda</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
        <span>Data Alumni</span>
      </div>
      <h1 class="hero-title" style="margin-bottom:16px;">
        Data Alumni <span style="background:linear-gradient(135deg,#1e293b,#475569);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">SMAN 5 Samarinda</span>
      </h1>
      <p style="font-size:15px;color:var(--text-muted);max-width:560px;margin:0 auto 20px;line-height:1.6;">
        Rekap lengkap lulusan berdasarkan kampus, jurusan, dan jalur masuk Perguruan Tinggi Negeri.
      </p>
      <a href="lapor.php" style="display:inline-flex;align-items:center;gap:8px;background:#2563eb;color:white;padding:11px 22px;border-radius:12px;font-weight:700;font-size:14px;text-decoration:none;box-shadow:0 4px 14px rgba(37,99,235,.3);transition:transform .15s,box-shadow .15s;"
        onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(37,99,235,.4)'"
        onmouseout="this.style.transform='';this.style.boxShadow='0 4px 14px rgba(37,99,235,.3)'">
        <i class="fa-solid fa-graduation-cap"></i> Lapor Data Masuk PTN Kamu!
      </a>
    </div>
  </section>

  <!-- CONTENT -->
  <div style="padding-bottom:80px;">
    <div class="container">

      <!-- Stats -->
      <div class="stats-strip">
        <div class="stat-card-alumni">
          <div class="stat-card-label"><i class="fa-solid fa-users"></i> Total Alumni</div>
          <div class="stat-card-val"><?= number_format($totalAlumni) ?></div>
          <div class="stat-card-sub">data terdaftar</div>
        </div>
        <div class="stat-card-alumni">
          <div class="stat-card-label"><i class="fa-solid fa-building-columns"></i> Universitas</div>
          <div class="stat-card-val"><?= $totalUniv ?></div>
          <div class="stat-card-sub">kampus berbeda</div>
        </div>
        <div class="stat-card-alumni">
          <div class="stat-card-label"><i class="fa-solid fa-book-open"></i> Program Studi</div>
          <div class="stat-card-val"><?= $totalProdi ?></div>
          <div class="stat-card-sub">jurusan berbeda</div>
        </div>
        <div class="stat-card-alumni">
          <div class="stat-card-label"><i class="fa-solid fa-calendar"></i> Angkatan Terbaru</div>
          <div class="stat-card-val"><?= $angkatanMax ?: '—' ?></div>
          <div class="stat-card-sub">tahun terbaru</div>
        </div>
      </div>

      <!-- Toolbar -->
      <form method="GET" action="alumni.php" id="filterForm">
        <div class="toolbar">
          <div class="search-field">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="q" id="tableSearch" value="<?= htmlspecialchars($q) ?>"
              placeholder="Cari nama, kampus, jurusan..." autocomplete="off">
          </div>

          <!-- Jalur -->
          <div class="filter-group">
            <span class="filter-label">Jalur:</span>
            <select name="jalur" class="custom-sort-trigger" style="border-radius:20px;padding:8px 34px 8px 14px;appearance:none;background-image:url(\"data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e\");background-repeat:no-repeat;background-position:right 10px center;background-size:14px;cursor:pointer;font-family:var(--font);font-size:13px;font-weight:700;" onchange="this.form.submit()">
              <option value="">Semua Jalur</option>
              <?php foreach (['SNBP','SNBT','Mandiri','Kedinasan'] as $j): ?>
              <option value="<?= $j ?>" <?= $jalurF===$j?'selected':'' ?>><?= $j ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Tahun -->
          <div class="filter-group">
            <span class="filter-label">Tahun:</span>
            <select name="angkatan" class="custom-sort-trigger" style="border-radius:20px;padding:8px 34px 8px 14px;appearance:none;background-image:url(\"data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e\");background-repeat:no-repeat;background-position:right 10px center;background-size:14px;cursor:pointer;font-family:var(--font);font-size:13px;font-weight:700;" onchange="this.form.submit()">
              <option value="">Semua Tahun</option>
              <?php foreach ($angkatanOpts as $y): ?>
              <option value="<?= $y ?>" <?= $angkatanF==$y?'selected':'' ?>><?= $y ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Urutkan -->
          <div class="filter-group">
            <span class="filter-label">Urutkan:</span>
            <select name="sort" class="custom-sort-trigger" style="border-radius:20px;padding:8px 34px 8px 14px;appearance:none;background-image:url(\"data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e\");background-repeat:no-repeat;background-position:right 10px center;background-size:14px;cursor:pointer;font-family:var(--font);font-size:13px;font-weight:700;" onchange="this.form.submit()">
              <option value="nama"     <?= $sort==='nama'    ?'selected':'' ?>>Nama A–Z</option>
              <option value="kampus"   <?= $sort==='kampus'  ?'selected':'' ?>>Kampus A–Z</option>
              <option value="angkatan" <?= $sort==='angkatan'?'selected':'' ?>>Angkatan Terbaru</option>
            </select>
          </div>

          <?php if ($q||$jalurF||$angkatanF): ?>
          <a href="alumni.php" style="font-size:12px;font-weight:700;color:var(--text-muted);text-decoration:none;white-space:nowrap;display:inline-flex;align-items:center;gap:5px;padding:8px 12px;border:1px solid var(--border);border-radius:20px;">
            <i class="fa-solid fa-xmark"></i> Reset
          </a>
          <?php endif; ?>

          <button type="submit" style="padding:8px 18px;background:var(--primary);color:white;border:none;border-radius:20px;font-family:var(--font);font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:7px;">
            <i class="fa-solid fa-magnifying-glass"></i> Cari
          </button>
        </div>
      </form>

      <!-- Table -->
      <div class="table-wrap">
        <div style="overflow-x:auto;">
          <table class="alumni-table">
            <thead>
              <tr>
                <th style="width:40px;">#</th>
                <th>Nama Alumni</th>
                <th>Kampus</th>
                <th>Program Studi</th>
                <th>Jalur</th>
                <th>Angkatan</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($alumni)): ?>
              <tr><td colspan="6">
                <div class="empty-state">
                  <i class="fa-solid fa-magnifying-glass"></i>
                  <p>Tidak ada data yang cocok dengan pencarian Anda.</p>
                </div>
              </td></tr>
              <?php else: ?>
              <?php foreach ($alumni as $i => $a):
                $num  = ($page-1)*$perPage + $i + 1;
                $jalurClass = 'badge-' . strtolower($a['jalur']);
              ?>
              <tr>
                <td style="color:var(--text-light);font-size:12px;font-weight:600;"><?= $num ?></td>
                <td><div class="alumni-name"><?= htmlspecialchars($a['nama']) ?></div></td>
                <td style="font-weight:600;"><?= htmlspecialchars($a['universitas_nama']) ?></td>
                <td style="color:var(--text-muted);"><?= htmlspecialchars($a['prodi']) ?></td>
                <td><span class="jalur-badge <?= $jalurClass ?>"><?= htmlspecialchars($a['jalur']) ?></span></td>
                <td><span class="angkatan-badge"><?= $a['angkatan'] ?></span></td>
              </tr>
              <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <?php if ($pages > 1 || $total > 0): ?>
        <div class="pagination">
          <span class="pagination-info">
            Menampilkan <?= ($page-1)*$perPage+1 ?>–<?= min($page*$perPage,$total) ?> dari <?= $total ?> alumni
          </span>
          <div class="pagination-btns">
            <?php $qs = http_build_query(['q'=>$q,'jalur'=>$jalurF,'angkatan'=>$angkatanF,'sort'=>$sort]); ?>
            <a href="?<?= $qs ?>&page=<?= max(1,$page-1) ?>" class="page-btn<?= $page<=1?' ':'' ?>"
              <?= $page<=1?'style="pointer-events:none;opacity:.4;"':'' ?>>
              <i class="fa-solid fa-chevron-left" style="font-size:11px;"></i>
            </a>
            <?php
            for ($p=1; $p<=$pages; $p++):
              if ($pages>7 && abs($p-$page)>2 && $p!==1 && $p!==$pages) { if (abs($p-$page)===3) echo '<button class="page-btn" disabled>…</button>'; continue; }
            ?>
            <a href="?<?= $qs ?>&page=<?= $p ?>" class="page-btn<?= $p===$page?' active':'' ?>"><?= $p ?></a>
            <?php endfor; ?>
            <a href="?<?= $qs ?>&page=<?= min($pages,$page+1) ?>" class="page-btn"
              <?= $page>=$pages?'style="pointer-events:none;opacity:.4;"':'' ?>>
              <i class="fa-solid fa-chevron-right" style="font-size:11px;"></i>
            </a>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- CTA Lapor -->
      <div style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);border-radius:20px;padding:32px;text-align:center;color:white;margin-top:32px;position:relative;overflow:hidden;">
        <div style="position:absolute;top:-40px;right:-40px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,.06);"></div>
        <h3 style="font-size:20px;font-weight:800;margin-bottom:8px;position:relative;z-index:1;">
          Kamu baru diterima di PTN? 🎉
        </h3>
        <p style="font-size:14px;opacity:.9;margin-bottom:20px;position:relative;z-index:1;">
          Bagikan kisah suksesmu! Lapor kampus yang kamu masuki dan inspirasi adik kelas.
        </p>
        <a href="lapor.php" style="display:inline-flex;align-items:center;gap:8px;background:white;color:#1d4ed8;padding:12px 24px;border-radius:12px;font-weight:800;font-size:14px;text-decoration:none;box-shadow:0 4px 12px rgba(0,0,0,.15);position:relative;z-index:1;">
          <i class="fa-solid fa-graduation-cap"></i> Lapor Data Sekarang
        </a>
      </div>

    </div>
  </div>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="container footer-grid">
      <div class="brand-col">
        <h3 class="footer-title">SMAN 5 SAMARINDA</h3>
        <p class="footer-desc">Destinasi pendidikan menengah atas unggulan di Kalimantan Timur.</p>
        <div class="footer-contact-item">
          <i class="fa-solid fa-location-dot"></i>
          <span>Jl. Ir. H. Juanda No. 1, Samarinda</span>
        </div>
      </div>
      <div class="link-col">
        <h3 class="footer-title">Jelajahi</h3>
        <ul class="footer-links">
          <li><a href="index.php"><i class="fa-solid fa-chevron-right"></i> Beranda</a></li>
          <li><a href="alumni.php"><i class="fa-solid fa-chevron-right"></i> Data Alumni</a></li>
          <li><a href="lapor.php"><i class="fa-solid fa-chevron-right"></i> Lapor Data</a></li>
        </ul>
      </div>
      <div class="contact-col">
        <h3 class="footer-title">Hubungi Kami</h3>
        <div class="footer-contact-list">
          <div class="footer-contact-item"><i class="fa-solid fa-envelope"></i><span>info@sman5samarinda.sch.id</span></div>
          <div class="footer-contact-item"><i class="fa-solid fa-phone"></i><span>(0541) 1234567</span></div>
        </div>
      </div>
    </div>
    <div class="footer-bottom"><div class="container"><p>&copy; <?= date('Y') ?> SMAN 5 Samarinda. All Rights Reserved.</p></div></div>
  </footer>

  <button class="scroll-top" id="scrollTop"><i class="fa-solid fa-chevron-up"></i></button>

  <script>
  (function(){
    const nb=document.getElementById('navbar'),btn=document.getElementById('menuBtn'),
          menu=document.getElementById('mobileMenu'),icon=document.getElementById('menuIcon');
    let open=false,lastY=0;
    window.addEventListener('scroll',()=>{
      const y=window.scrollY;
      if(y>lastY&&y>80)nb.classList.add('hidden');else nb.classList.remove('hidden');
      nb.classList.toggle('scrolled',y>10);lastY=y<=0?0:y;
    },{passive:true});
    btn.addEventListener('click',()=>{open=!open;menu.classList.toggle('open',open);icon.className=open?'fa-solid fa-xmark':'fa-solid fa-bars';});
    const st=document.getElementById('scrollTop');
    window.addEventListener('scroll',()=>st.classList.toggle('visible',window.scrollY>400),{passive:true});
    st.addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));
  })();
  </script>
</body>
</html>
