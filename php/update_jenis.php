<?php
require_once __DIR__ . '/config/db.php';
$db = getDB();

// Update PTS
$db->exec("UPDATE universitas SET jenis = 'PTS' WHERE nama LIKE '%Muhammadiyah%' OR nama LIKE '%Swasta%'");

// Update Kedinasan
$db->exec("UPDATE universitas SET jenis = 'Kedinasan' WHERE nama LIKE '%Akademi%' OR nama LIKE '%Sekolah Tinggi%' OR nama LIKE '%Politeknik%' OR nama LIKE '%Kedinasan%'");

echo "Database updated!";
