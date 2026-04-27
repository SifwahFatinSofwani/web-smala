<?php
require_once __DIR__ . '/config/db.php';
$db = getDB();

$id = 1; // Universitas Mulawarman
$nama = 'Universitas Mulawarman';
$kota = 'Balikpapan'; // Mengubah tempatnya

$existing = $db->prepare('SELECT lat, lng, pulau FROM universitas WHERE id=?');
$existing->execute([$id]);
$existingRow = $existing->fetch();
$existingLat   = $existingRow['lat']   ?? null;
$existingLng   = $existingRow['lng']   ?? null;
$existingPulau = $existingRow['pulau'] ?? null;

$needGeocode = ($existingLat === null || $existingLng === null) && $kota;
$geo = ['lat' => $existingLat, 'lng' => $existingLng];

echo "Need geocode: " . ($needGeocode ? 'Yes' : 'No') . "\n";

$pulau = 'Kalimantan';
$lat = $geo['lat'];
$lng = $geo['lng'];

try {
    $stmt = $db->prepare('UPDATE universitas SET nama=?, kota=?, lat=?, lng=?, pulau=?, jenis=? WHERE id=?');
    $stmt->execute([$nama, $kota, $lat, $lng, $pulau, 'PTN', $id]);
    echo "Success!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
