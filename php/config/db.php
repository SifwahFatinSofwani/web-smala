<?php
// ============================================================
//  KONFIGURASI DATABASE
//  php/config/db.php
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // Ganti sesuai user MySQL Anda
define('DB_PASS', '');              // Ganti sesuai password MySQL Anda
define('DB_NAME', 'portal_alumni');
define('DB_CHAR', 'utf8mb4');

// Fungsi koneksi PDO (lebih aman dari mysqli)
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHAR;
        $opts = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $opts);
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['success' => false, 'message' => 'Koneksi database gagal.']));
        }
    }
    return $pdo;
}

// Helper: sanitize input
function clean(string $str): string {
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

// Helper: JSON response
function jsonResponse(bool $success, string $message, array $data = []): void {
    header('Content-Type: application/json');
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $data));
    exit;
}

// Helper: cek session admin
function requireAdmin(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['admin_id'])) {
        header('Location: index.php?error=session');
        exit;
    }
}

// Aktifkan session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
