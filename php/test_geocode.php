<?php
function geocodeUniversitas(string $nama, string $kota): array {
    $queries = [
        $nama . ', ' . $kota . ', Indonesia',
        $kota . ', Indonesia',
    ];

    foreach ($queries as $q) {
        $url = 'https://nominatim.openstreetmap.org/search?q='
            . urlencode($q)
            . '&format=json&limit=1&countrycodes=id';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Nominatim mewajibkan User-Agent yang valid
        curl_setopt($ch, CURLOPT_USERAGENT, 'PortalAlumniSMAN5Samarinda/1.0 (contact@sman5samarinda.sch.id)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        // Hindari masalah sertifikat SSL di local (Laragon/XAMPP)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $raw = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($raw === false) {
             echo 'Curl error: ' . curl_error($ch) . "\n";
        }
        curl_close($ch);

        if ($raw && $httpCode === 200) {
            $data = json_decode($raw, true);
            if (!empty($data[0]['lat']) && !empty($data[0]['lon'])) {
                return [
                    'lat' => (float) $data[0]['lat'],
                    'lng' => (float) $data[0]['lon'],
                ];
            }
        }
        sleep(1);
    }

    return ['lat' => null, 'lng' => null];
}

$res = geocodeUniversitas('Universitas Mulawarman', 'Samarinda');
print_r($res);
