<?php
// ============================================================
//  Admin Auth: Login & Logout
//  php/admin/auth.php
// ============================================================

require_once __DIR__ . '/../config/db.php';

$action = $_GET['action'] ?? 'login';

// ── LOGOUT ──────────────────────────────────────────────────
if ($action === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

// ── LOGIN (POST) ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    header('Location: index.php?error=empty');
    exit;
}

try {
    $db   = getDB();
    $stmt = $db->prepare('SELECT id, username, password, nama FROM admin_users WHERE username = ?');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($password, $admin['password'])) {
        // Tambah delay buat brute-force protection
        sleep(1);
        header('Location: index.php?error=invalid');
        exit;
    }

    // Set session
    $_SESSION['admin_id']   = $admin['id'];
    $_SESSION['admin_nama'] = $admin['nama'];
    $_SESSION['admin_user'] = $admin['username'];
    $_SESSION['login_time'] = time();

    header('Location: dashboard.php');
    exit;

} catch (PDOException $e) {
    header('Location: index.php?error=server');
    exit;
}
