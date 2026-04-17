<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lapor Data Masuk PTN — Portal Alumni SMAN 5 Samarinda</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <style>
    /* ── Lapor Page Specific Styles ── */
    .lapor-page {
      min-height: 100vh;
      background: linear-gradient(160deg, #f0f9ff 0%, #e0f2fe 40%, #ede9fe 100%);
      padding: 40px 0 80px;
    }

    .lapor-container {
      max-width: 640px;
      margin: 0 auto;
      padding: 0 20px;
    }

    /* Back link */
    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      font-weight: 700;
      color: var(--text-muted);
      text-decoration: none;
      margin-bottom: 28px;
      transition: color .2s, gap .2s;
    }
    .back-link:hover { color: var(--primary); gap: 12px; }

    /* Progress Steps */
    .progress-steps {
      display: flex;
      align-items: center;
      gap: 0;
      margin-bottom: 32px;
    }
    .step {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
      flex: 1;
      position: relative;
    }
    .step-circle {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      font-weight: 800;
      transition: all .3s;
      position: relative;
      z-index: 2;
    }
    .step-circle.inactive { background: #e2e8f0; color: #94a3b8; }
    .step-circle.active   { background: var(--primary); color: white; box-shadow: 0 0 0 4px rgba(37,99,235,.15); }
    .step-circle.done     { background: #16a34a; color: white; }
    .step-label {
      font-size: 11px;
      font-weight: 700;
      color: var(--text-light);
      white-space: nowrap;
      text-align: center;
    }
    .step-label.active { color: var(--primary); }
    .step-label.done   { color: #16a34a; }
    .step-line {
      flex: 1;
      height: 2px;
      background: #e2e8f0;
      margin-bottom: 20px;
      transition: background .3s;
    }
    .step-line.done { background: #16a34a; }

    /* Card */
    .lapor-card {
      background: white;
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(37,99,235,.10), 0 2px 8px rgba(0,0,0,.05);
      border: 1px solid rgba(255,255,255,.9);
      overflow: hidden;
    }

    .card-header-lapor {
      padding: 28px 32px 24px;
      border-bottom: 1px solid #f1f5f9;
      background: linear-gradient(135deg, #f8faff, #f0f4ff);
    }
    .card-header-lapor h2 {
      font-size: 20px;
      font-weight: 800;
      margin-bottom: 4px;
    }
    .card-header-lapor p {
      font-size: 13px;
      color: var(--text-muted);
    }
    .header-icon {
      width: 44px;
      height: 44px;
      background: linear-gradient(135deg, var(--primary), #6d4af8);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 18px;
      margin-bottom: 16px;
      box-shadow: 0 4px 14px rgba(37,99,235,.3);
    }

    .card-body { padding: 28px 32px; }

    /* Form Fields */
    .form-row { margin-bottom: 20px; }
    .form-row label {
      display: block;
      font-size: 13px;
      font-weight: 700;
      color: var(--text-main);
      margin-bottom: 8px;
    }
    .form-row label span { color: #ef4444; margin-left: 2px; }

    .field-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }
    .field-icon {
      position: absolute;
      left: 14px;
      color: var(--text-light);
      font-size: 14px;
      z-index: 1;
      pointer-events: none;
    }
    .field-wrap input,
    .field-wrap select,
    .field-wrap textarea {
      width: 100%;
      padding: 13px 16px 13px 42px;
      border: 1.5px solid #e2e8f0;
      border-radius: 10px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 14px;
      font-weight: 500;
      color: var(--text-main);
      background: #f8fafc;
      outline: none;
      transition: border-color .2s, box-shadow .2s, background .2s;
      -webkit-appearance: none;
    }
    .field-wrap input:focus,
    .field-wrap select:focus,
    .field-wrap textarea:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(37,99,235,.08);
      background: white;
    }
    .field-wrap input.success { border-color: #16a34a; background: #f0fdf4; }
    .field-wrap input.error   { border-color: #ef4444; background: #fef2f2; }
    .field-wrap textarea      { padding-top: 13px; resize: vertical; min-height: 80px; }

    .field-suffix {
      position: absolute;
      right: 14px;
      font-size: 13px;
    }

    .field-hint {
      font-size: 12px;
      color: var(--text-light);
      margin-top: 5px;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .field-hint.error   { color: #ef4444; }
    .field-hint.success { color: #16a34a; }

    /* NISN Verify Button */
    .nisn-row {
      display: flex;
      gap: 10px;
      align-items: flex-start;
    }
    .nisn-row .field-wrap { flex: 1; }
    .btn-verify {
      flex-shrink: 0;
      padding: 13px 18px;
      background: var(--primary);
      color: white;
      border: none;
      border-radius: 10px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      transition: background .15s, transform .12s, box-shadow .2s;
      white-space: nowrap;
      box-shadow: 0 2px 8px rgba(37,99,235,.25);
      display: flex;
      align-items: center;
      gap: 7px;
    }
    .btn-verify:hover:not(:disabled) { background: #1d4ed8; box-shadow: 0 4px 14px rgba(37,99,235,.4); transform: translateY(-1px); }
    .btn-verify:disabled { opacity: .6; cursor: not-allowed; transform: none; }

    /* Verified badge */
    .verified-badge {
      display: none;
      align-items: center;
      gap: 10px;
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      border-radius: 10px;
      padding: 12px 16px;
      margin-bottom: 20px;
    }
    .verified-badge.show { display: flex; animation: fadeIn .3s ease; }
    .verified-badge i { color: #16a34a; font-size: 18px; flex-shrink: 0; }
    .verified-badge-text strong { display: block; font-size: 14px; font-weight: 800; color: #166534; }
    .verified-badge-text span   { font-size: 12px; color: #15803d; font-weight: 600; }

    /* Form Section */
    .form-section {
      display: none;
      animation: fadeIn .35s ease;
    }
    .form-section.show { display: block; }
    @keyframes fadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }

    .form-divider {
      border: none;
      border-top: 1px dashed #e2e8f0;
      margin: 24px 0;
    }
    .form-section-title {
      font-size: 13px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .8px;
      color: var(--text-muted);
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .form-section-title::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #e2e8f0;
    }

    /* Submit Button */
    .btn-submit {
      width: 100%;
      padding: 15px;
      background: linear-gradient(135deg, #1d4ed8, #2563eb);
      color: white;
      border: none;
      border-radius: 12px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 15px;
      font-weight: 800;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: transform .12s, box-shadow .2s, opacity .2s;
      box-shadow: 0 4px 18px rgba(37,99,235,.35);
      margin-top: 8px;
      letter-spacing: .3px;
    }
    .btn-submit:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(37,99,235,.45); }
    .btn-submit:disabled { opacity:.6; cursor:not-allowed; transform:none; }

    /* Success State */
    .success-state {
      display: none;
      text-align: center;
      padding: 48px 32px;
      animation: fadeIn .4s ease;
    }
    .success-state.show { display: block; }
    .success-anim {
      width: 80px;
      height: 80px;
      background: #f0fdf4;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 36px;
      color: #16a34a;
      margin: 0 auto 20px;
      border: 3px solid #bbf7d0;
      animation: successPop .5s cubic-bezier(.34,1.56,.64,1) .1s both;
    }
    @keyframes successPop {
      from { transform: scale(0); opacity:0; }
      to   { transform: scale(1); opacity:1; }
    }
    .success-state h3  { font-size: 22px; font-weight: 800; margin-bottom: 8px; color: #166534; }
    .success-state p   { font-size: 14px; color: var(--text-muted); line-height: 1.7; max-width: 360px; margin: 0 auto 28px; }
    .btn-home {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 24px;
      background: var(--primary);
      color: white;
      border-radius: 10px;
      font-weight: 700;
      font-size: 14px;
      text-decoration: none;
      transition: background .15s, transform .12s;
    }
    .btn-home:hover { background: #1d4ed8; transform: translateY(-1px); }

    /* Info box */
    .info-box {
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      border-radius: 10px;
      padding: 14px 16px;
      font-size: 13px;
      color: #1e40af;
      display: flex;
      gap: 10px;
      align-items: flex-start;
      margin-bottom: 20px;
      line-height: 1.6;
    }
    .info-box i { flex-shrink: 0; margin-top: 1px; }

    /* Grid 2-col fields */
    .fields-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }
    @media (max-width: 520px) {
      .fields-grid { grid-template-columns: 1fr; }
      .card-body, .card-header-lapor { padding: 20px 20px; }
    }
  </style>
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar" id="navbar">
    <div class="container nav-inner">
      <a href="index.php" class="nav-brand">
        <img src="../image/logosma5.png" alt="Logo SMAN 5 Samarinda" class="nav-logo"
          onerror="this.src='https://via.placeholder.com/38x38/2563eb/fff?text=S5'; this.style.borderRadius='50%';">
        <span>SMAN 5 Samarinda</span>
      </a>
      <div class="nav-menu">
        <a href="index.php">Beranda</a>
        <a href="alumni.php">Data Alumni</a>
      </div>
      <button class="menu-btn" id="menuBtn" aria-label="Buka menu">
        <i class="fa-solid fa-bars" id="menuIcon"></i>
      </button>
    </div>
  </nav>

  <div class="mobile-menu" id="mobileMenu">
    <a href="index.php">Beranda</a>
    <a href="alumni.php">Data Alumni</a>
  </div>

  <!-- MAIN -->
  <div class="lapor-page">
    <div class="lapor-container">

      <a href="index.php" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
      </a>

      <!-- Progress Steps -->
      <div class="progress-steps">
        <div class="step">
          <div class="step-circle active" id="step1Circle">1</div>
          <div class="step-label active" id="step1Label">Verifikasi</div>
        </div>
        <div class="step-line" id="line1"></div>
        <div class="step">
          <div class="step-circle inactive" id="step2Circle">2</div>
          <div class="step-label" id="step2Label">Isi Data</div>
        </div>
        <div class="step-line" id="line2"></div>
        <div class="step">
          <div class="step-circle inactive" id="step3Circle">3</div>
          <div class="step-label" id="step3Label">Selesai</div>
        </div>
      </div>

      <!-- Card -->
      <div class="lapor-card">

        <!-- Header -->
        <div class="card-header-lapor">
          <div class="header-icon"><i class="fa-solid fa-graduation-cap"></i></div>
          <h2>Lapor Data Masuk PTN</h2>
          <p>Beritahu kami kampus dan jurusan yang kamu masuki agar adik kelas bisa melihat inspirasimu!</p>
        </div>

        <!-- Body -->
        <div class="card-body">

          <!-- ── STEP 1: NISN ── -->
          <div id="sectionNISN">

            <div class="info-box">
              <i class="fa-solid fa-circle-info"></i>
              <div>Masukkan <strong>NISN (Nomor Induk Siswa Nasional)</strong> kamu yang terdaftar di SMAN 5 Samarinda untuk memverifikasi identitasmu. NISN terdiri dari <strong>10 digit angka</strong>.</div>
            </div>

            <div class="form-row">
              <label>NISN (Nomor Induk Siswa Nasional) <span>*</span></label>
              <div class="nisn-row">
                <div class="field-wrap">
                  <i class="fa-solid fa-id-card field-icon"></i>
                  <input
                    type="text"
                    id="nisnInput"
                    placeholder="Contoh: 1234567890"
                    maxlength="10"
                    inputmode="numeric"
                    pattern="[0-9]{10}"
                    autocomplete="off"
                  >
                  <span class="field-suffix" id="nisnCheck" style="display:none;"></span>
                </div>
                <button class="btn-verify" id="btnVerify" onclick="verifyNISN()" disabled>
                  <i class="fa-solid fa-magnifying-glass" id="verifyIcon"></i>
                  <span id="verifyText">Verifikasi</span>
                </button>
              </div>
              <div class="field-hint" id="nisnHint">
                <i class="fa-solid fa-circle-info"></i>
                NISN bisa ditemukan di ijazah, rapor, atau kartu pelajar kamu.
              </div>
            </div>
          </div>

          <!-- ── Verified Badge ── -->
          <div class="verified-badge" id="verifiedBadge">
            <i class="fa-solid fa-circle-check"></i>
            <div class="verified-badge-text">
              <strong id="verifiedName">–</strong>
              <span id="verifiedAngkatan">–</span>
            </div>
          </div>

          <!-- ── STEP 2: Data Alumni ── -->
          <div class="form-section" id="sectionData">

            <div class="form-section-title">
              <i class="fa-solid fa-building-columns" style="color:var(--primary);font-size:12px;"></i>
              Data Perguruan Tinggi
            </div>

            <!-- Universitas -->
            <div class="form-row">
              <label>Universitas / Perguruan Tinggi <span>*</span></label>
              <div class="field-wrap">
                <i class="fa-solid fa-building-columns field-icon"></i>
                <select id="univSelect">
                  <option value="">— Pilih Universitas —</option>
                  <?php
                  // Isi dari database
                  require_once __DIR__ . '/config/db.php';
                  try {
                    $db   = getDB();
                    $rows = $db->query("SELECT id, nama, kota FROM universitas ORDER BY nama")->fetchAll();
                    foreach ($rows as $r) {
                      $label = $r['nama'] . ($r['kota'] ? ' — ' . $r['kota'] : '');
                      echo '<option value="' . $r['id'] . '">' . htmlspecialchars($label) . '</option>';
                    }
                  } catch (Exception $e) {
                    // Fallback jika DB belum tersedia
                    $fallback = [
                      [1,'Universitas Mulawarman','Samarinda'],
                      [2,'Universitas Brawijaya','Malang'],
                      [3,'Universitas Gadjah Mada','Yogyakarta'],
                      [4,'Universitas Diponegoro','Semarang'],
                      [5,'Institut Teknologi Kalimantan','Balikpapan'],
                      [6,'Institut Teknologi Bandung','Bandung'],
                      [7,'Universitas Lambung Mangkurat','Banjarmasin'],
                      [8,'Universitas Tanjungpura','Pontianak'],
                      [9,'Universitas Hasanuddin','Makassar'],
                      [10,'Universitas Indonesia','Depok'],
                      [11,'Universitas Palangka Raya','Palangka Raya'],
                      [12,'Institut Teknologi Sepuluh Nopember','Surabaya'],
                      [13,'Akademi Kepolisian','Semarang'],
                      [15,'Universitas Lainnya',''],
                    ];
                    foreach ($fallback as $r) {
                      $label = $r[1] . ($r[2] ? ' — ' . $r[2] : '');
                      echo '<option value="' . $r[0] . '">' . htmlspecialchars($label) . '</option>';
                    }
                  }
                  ?>
                </select>
              </div>
            </div>

            <!-- Program Studi -->
            <div class="form-row">
              <label>Program Studi / Jurusan <span>*</span></label>
              <div class="field-wrap">
                <i class="fa-solid fa-book-open field-icon"></i>
                <input type="text" id="prodiInput" placeholder="Contoh: Teknik Informatika" autocomplete="off">
              </div>
              <div class="field-hint"><i class="fa-solid fa-lightbulb"></i> Tuliskan nama lengkap program studi sesuai yang tertera di surat penerimaan.</div>
            </div>

            <!-- Jalur & Angkatan -->
            <div class="fields-grid">
              <div class="form-row">
                <label>Jalur Masuk <span>*</span></label>
                <div class="field-wrap">
                  <i class="fa-solid fa-route field-icon"></i>
                  <select id="jalurSelect">
                    <option value="">— Pilih Jalur —</option>
                    <option value="SNBP">SNBP (Undangan)</option>
                    <option value="SNBT">SNBT (UTBK)</option>
                    <option value="Mandiri">Mandiri</option>
                    <option value="Kedinasan">Kedinasan</option>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <label>Tahun Lulus <span>*</span></label>
                <div class="field-wrap">
                  <i class="fa-solid fa-calendar field-icon"></i>
                  <select id="angkatanSelect">
                    <option value="">— Pilih Tahun —</option>
                    <?php
                    $yr = (int) date('Y');
                    for ($y = $yr; $y >= 2015; $y--) {
                        echo '<option value="' . $y . '">' . $y . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>

            <hr class="form-divider">

            <!-- Catatan Opsional -->
            <div class="form-row">
              <label>Catatan / Pesan (Opsional)</label>
              <div class="field-wrap">
                <i class="fa-solid fa-comment field-icon" style="top:15px;"></i>
                <textarea id="catatanInput" placeholder="Contoh: Diterima melalui jalur KIP-K, atau ingin berbagi tips untuk adik kelas..."></textarea>
              </div>
            </div>

            <!-- Submit -->
            <button class="btn-submit" id="btnSubmit" onclick="submitLaporan()">
              <i class="fa-solid fa-paper-plane" id="submitIcon"></i>
              <span id="submitText">Kirim Laporan Data</span>
            </button>

            <p style="text-align:center;font-size:12px;color:var(--text-light);margin-top:12px;">
              <i class="fa-solid fa-shield-halved" style="color:#16a34a;"></i>
              Data akan diverifikasi admin sebelum ditampilkan publik. Maks. 3 hari kerja.
            </p>

          </div>

          <!-- ── STEP 3: Success ── -->
          <div class="success-state" id="sectionSuccess">
            <div class="success-anim"><i class="fa-solid fa-check"></i></div>
            <h3>Laporan Terkirim!</h3>
            <p>Terima kasih! Data kamu sedang diverifikasi oleh admin sekolah. Setelah disetujui, namamu akan muncul di halaman Data Alumni dan menginspirasi adik kelas.</p>
            <a href="alumni.php" class="btn-home">
              <i class="fa-solid fa-arrow-right"></i> Lihat Data Alumni
            </a>
          </div>

        </div><!-- /card-body -->
      </div><!-- /lapor-card -->

    </div>
  </div>

  <script>
  // ── State ──
  let verifiedNISN  = null;
  let verifiedNama  = null;

  // ── NISN: hanya izinkan angka ──
  document.getElementById('nisnInput').addEventListener('input', function() {
    this.value = this.value.replace(/\D/g,'');
    document.getElementById('btnVerify').disabled = this.value.length !== 10;
    resetNISNState();
  });
  document.getElementById('nisnInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') verifyNISN();
  });

  function resetNISNState() {
    const inp = document.getElementById('nisnInput');
    inp.classList.remove('success','error');
    document.getElementById('verifiedBadge').classList.remove('show');
    document.getElementById('sectionData').classList.remove('show');
    document.getElementById('nisnHint').className = 'field-hint';
    document.getElementById('nisnHint').innerHTML = '<i class="fa-solid fa-circle-info"></i> NISN bisa ditemukan di ijazah, rapor, atau kartu pelajar kamu.';
    document.getElementById('nisnCheck').style.display = 'none';
    verifiedNISN = null;
    updateSteps(1);
  }

  // ── Verify NISN ──
  async function verifyNISN() {
    const nisn   = document.getElementById('nisnInput').value.trim();
    const btn    = document.getElementById('btnVerify');
    const inp    = document.getElementById('nisnInput');
    const hint   = document.getElementById('nisnHint');
    const chk    = document.getElementById('nisnCheck');

    if (nisn.length !== 10) return;

    // Loading state
    btn.disabled = true;
    document.getElementById('verifyIcon').className = 'fa-solid fa-circle-notch fa-spin';
    document.getElementById('verifyText').textContent = 'Memeriksa...';

    try {
      const res  = await fetch('../api/verify-nisn.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nisn })
      });
      const data = await res.json();

      if (data.success) {
        // ✅ Valid
        inp.classList.add('success');
        inp.classList.remove('error');
        inp.readOnly = true;
        chk.innerHTML = '<i class="fa-solid fa-circle-check" style="color:#16a34a;"></i>';
        chk.style.display = 'block';

        hint.className = 'field-hint success';
        hint.innerHTML = '<i class="fa-solid fa-circle-check"></i> NISN terverifikasi!';

        verifiedNISN = nisn;
        verifiedNama = data.nama;

        // Tampilkan badge
        document.getElementById('verifiedName').textContent    = data.nama;
        document.getElementById('verifiedAngkatan').textContent = 'Angkatan ' + data.angkatan + ' · SMAN 5 Samarinda';
        document.getElementById('verifiedBadge').classList.add('show');

        // Tampilkan form data
        document.getElementById('sectionData').classList.add('show');
        updateSteps(2);

        // Scroll ke form
        setTimeout(() => {
          document.getElementById('sectionData').scrollIntoView({ behavior:'smooth', block:'start' });
        }, 200);

        btn.textContent = '✓ Terverifikasi';
        btn.style.background = '#16a34a';
        btn.disabled = true;

      } else {
        // ❌ Invalid
        inp.classList.add('error');
        inp.classList.remove('success');
        chk.innerHTML = '<i class="fa-solid fa-circle-xmark" style="color:#ef4444;"></i>';
        chk.style.display = 'block';

        hint.className = 'field-hint error';
        hint.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> ' + data.message;

        btn.disabled = false;
        document.getElementById('verifyIcon').className = 'fa-solid fa-magnifying-glass';
        document.getElementById('verifyText').textContent = 'Coba Lagi';
      }

    } catch(e) {
      hint.className = 'field-hint error';
      hint.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Gagal terhubung ke server. Cek koneksi internet.';
      btn.disabled = false;
      document.getElementById('verifyIcon').className = 'fa-solid fa-magnifying-glass';
      document.getElementById('verifyText').textContent = 'Coba Lagi';
    }
  }

  // ── Submit Laporan ──
  async function submitLaporan() {
    if (!verifiedNISN) return;

    const univId   = document.getElementById('univSelect').value;
    const prodi    = document.getElementById('prodiInput').value.trim();
    const jalur    = document.getElementById('jalurSelect').value;
    const angkatan = document.getElementById('angkatanSelect').value;
    const catatan  = document.getElementById('catatanInput').value.trim();

    // Validasi
    if (!univId || !prodi || !jalur || !angkatan) {
      showToast('Harap lengkapi semua field yang wajib diisi!', 'error');
      return;
    }
    if (prodi.length < 3) {
      showToast('Nama program studi terlalu pendek.', 'error');
      return;
    }

    const btn  = document.getElementById('btnSubmit');
    btn.disabled = true;
    document.getElementById('submitIcon').className  = 'fa-solid fa-circle-notch fa-spin';
    document.getElementById('submitText').textContent = 'Mengirim...';

    try {
      const res  = await fetch('../api/submit-laporan.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nisn:verifiedNISN, universitas_id:univId, prodi, jalur, angkatan, catatan })
      });
      const data = await res.json();

      if (data.success) {
        // Tampilkan sukses
        document.getElementById('sectionNISN').style.display  = 'none';
        document.getElementById('verifiedBadge').style.display = 'none';
        document.getElementById('sectionData').style.display  = 'none';
        document.getElementById('sectionSuccess').classList.add('show');
        updateSteps(3);
        window.scrollTo({ top: 0, behavior:'smooth' });
      } else {
        showToast(data.message, 'error');
        btn.disabled = false;
        document.getElementById('submitIcon').className  = 'fa-solid fa-paper-plane';
        document.getElementById('submitText').textContent = 'Kirim Laporan Data';
      }

    } catch(e) {
      showToast('Gagal mengirim. Periksa koneksi internet.', 'error');
      btn.disabled = false;
      document.getElementById('submitIcon').className  = 'fa-solid fa-paper-plane';
      document.getElementById('submitText').textContent = 'Kirim Laporan Data';
    }
  }

  // ── Update progress steps ──
  function updateSteps(active) {
    for (let i = 1; i <= 3; i++) {
      const circle = document.getElementById('step' + i + 'Circle');
      const label  = document.getElementById('step' + i + 'Label');
      if (i < active) {
        circle.className = 'step-circle done';
        circle.innerHTML = '<i class="fa-solid fa-check" style="font-size:12px;"></i>';
        label.className  = 'step-label done';
      } else if (i === active) {
        circle.className = 'step-circle active';
        circle.textContent = i;
        label.className  = 'step-label active';
      } else {
        circle.className = 'step-circle inactive';
        circle.textContent = i;
        label.className  = 'step-label';
      }
    }
    for (let i = 1; i <= 2; i++) {
      document.getElementById('line' + i).className = 'step-line' + (i < active ? ' done' : '');
    }
  }

  // ── Toast ──
  function showToast(msg, type) {
    const existing = document.querySelector('.toast-lapor');
    if (existing) existing.remove();
    const t = document.createElement('div');
    t.className = 'toast-lapor';
    t.innerHTML = (type === 'error'
      ? '<i class="fa-solid fa-circle-xmark"></i> '
      : '<i class="fa-solid fa-circle-check"></i> ') + msg;
    Object.assign(t.style, {
      position:'fixed', bottom:'24px', right:'24px',
      background: type === 'error' ? '#dc2626' : '#16a34a',
      color:'white', padding:'12px 18px', borderRadius:'10px',
      fontSize:'13px', fontWeight:'600',
      fontFamily:"'Plus Jakarta Sans',sans-serif",
      boxShadow:'0 4px 20px rgba(0,0,0,.15)',
      zIndex:'9999', display:'flex', alignItems:'center', gap:'8px',
      opacity:'0', transform:'translateY(12px)',
      transition:'all .3s', maxWidth:'340px', lineHeight:'1.5'
    });
    document.body.appendChild(t);
    requestAnimationFrame(() => requestAnimationFrame(() => {
      t.style.opacity = '1'; t.style.transform = 'translateY(0)';
    }));
    setTimeout(() => {
      t.style.opacity='0'; t.style.transform='translateY(12px)';
      setTimeout(() => t.remove(), 320);
    }, 4000);
  }

  // ── Navbar & Hamburger ──
  (function() {
    const nb   = document.getElementById('navbar');
    const btn  = document.getElementById('menuBtn');
    const menu = document.getElementById('mobileMenu');
    const icon = document.getElementById('menuIcon');
    let open=false, lastY=0;
    window.addEventListener('scroll', () => {
      const y = window.scrollY;
      if (y > lastY && y > 80) nb.classList.add('hidden');
      else nb.classList.remove('hidden');
      nb.classList.toggle('scrolled', y > 10);
      lastY = y <= 0 ? 0 : y;
    }, { passive:true });
    btn.addEventListener('click', () => {
      open = !open;
      menu.classList.toggle('open', open);
      icon.className = open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
    });
  })();
  </script>

</body>
</html>
