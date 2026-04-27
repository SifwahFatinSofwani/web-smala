<?php
require_once __DIR__ . '/config/db.php';
$db = getDB();
$stmt = $db->query("
    SELECT u.nama, u.jenis, COUNT(a.id) as alumni_count
    FROM universitas u
    LEFT JOIN alumni a ON a.universitas_id = u.id AND a.status = 'aktif'
    GROUP BY u.id
");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($data);
