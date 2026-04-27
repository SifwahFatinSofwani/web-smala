<?php
require_once __DIR__ . '/config/db.php';
$db = getDB();
$stmt = $db->query("SELECT nama, jenis, lat, lng FROM universitas");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($data);
