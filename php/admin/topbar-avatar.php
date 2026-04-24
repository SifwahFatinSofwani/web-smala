<!-- Topbar Avatar Include – php/admin/topbar-avatar.php -->
<button class="topbar-icon" aria-label="Notifikasi">
  <i class="fa-regular fa-bell"></i>
  <?php
  try {
    $nb = getDB()->query("SELECT COUNT(*) FROM laporan_masuk WHERE status='pending'")->fetchColumn();
    if ($nb > 0) echo '<span class="notif-dot"></span>';
  } catch (Exception $e) {}
  ?>
</button>
<div class="admin-avatar" id="avatarBtn">
  <div class="avatar-circle"><?= strtoupper(mb_substr($_SESSION['admin_nama'] ?? 'A', 0, 1)) ?></div>
  <div class="avatar-info">
    <p class="avatar-name"><?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Admin') ?></p>
    <p class="avatar-role">Super Admin</p>
  </div>
  <i class="fa-solid fa-chevron-down av-arrow"></i>
  <div class="avatar-dropdown" id="avatarDropdown">
    <a href="profil.php"><i class="fa-regular fa-user"></i> Profil Saya</a>
    <a href="pengaturan.php"><i class="fa-solid fa-gear"></i> Pengaturan</a>
    <hr>
    <a href="auth.php?action=logout" class="logout-link"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
  </div>
</div>
