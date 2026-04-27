<?php
require 'config/db.php';
$db = getDB();
$sqlMap = "
    SELECT
        u.kode,
        u.nama,
        u.kota,
        u.jenis,
        COALESCE(u.pulau, 'Lainnya') AS pulau,
        u.lat,
        u.lng,
        a.angkatan,
        COUNT(a.id)                       AS jumlah,
        SUM(a.jalur = 'SNBP')             AS snbp,
        SUM(a.jalur = 'SNBT')             AS snbt,
        SUM(a.jalur = 'Mandiri')          AS mandiri,
        SUM(a.jalur = 'Kedinasan')        AS kedinasan
    FROM universitas u
    INNER JOIN alumni a ON a.universitas_id = u.id AND a.status = 'aktif'
    WHERE u.lat IS NOT NULL
      AND u.lng IS NOT NULL
      AND u.lat != 0
      AND u.lng != 0
    GROUP BY u.id, a.angkatan
    HAVING jumlah > 0
    ORDER BY jumlah DESC
    LIMIT 3
";
$stmt = $db->query($sqlMap);
$mapData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$univData = array_map(function($u) {
    return [
        'kode'    => $u['kode']    ?? '',
        'angkatan'=> (string)($u['angkatan'] ?? ''),
        'jumlah'  => (int)$u['jumlah'],
    ];
}, $mapData);
echo json_encode($univData, JSON_PRETTY_PRINT);
