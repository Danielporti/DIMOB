<?php
// backend/meta_status.php
header('Content-Type: application/json');
$path = __DIR__ . '/meta_token.json';
if (!file_exists($path)) {
    echo json_encode(['connected' => false]);
    exit;
}
$data = json_decode(file_get_contents($path), true);
if (!$data || !isset($data['access_token'])) {
    echo json_encode(['connected' => false]);
    exit;
}

echo json_encode([
    'connected' => true,
    'obtained_at' => $data['obtained_at'] ?? null,
]);
