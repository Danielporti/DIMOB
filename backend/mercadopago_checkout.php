<?php
// backend/mercadopago_checkout.php
header('Content-Type: application/json');

// Substitua pelo seu Access Token do Mercado Pago
$access_token = '4187669924066683';

$input = json_decode(file_get_contents('php://input'), true);
$plano = $input['plano'] ?? '';

$planos = [
    'basico' => [
        'title' => 'Assinatura Básica',
        'price' => 49.99
    ],
    'imobiliaria' => [
        'title' => 'Assinatura Imobiliária',
        'price' => 149.00
    ]
];

if (!isset($planos[$plano])) {
    echo json_encode(['error' => 'Plano inválido']);
    exit;
}

$item = [
    'title' => $planos[$plano]['title'],
    'quantity' => 1,
    'currency_id' => 'BRL',
    'unit_price' => $planos[$plano]['price']
];

$preference = [
    'items' => [$item],
    'payment_methods' => [
        'installments' => 1
    ],
    'back_urls' => [
        'success' => 'https://seusite.com/sucesso',
        'failure' => 'https://seusite.com/erro',
        'pending' => 'https://seusite.com/pendente'
    ],
    'auto_return' => 'approved'
];

$ch = curl_init('https://api.mercadopago.com/checkout/preferences');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token
]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preference));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode == 201) {
    $data = json_decode($response, true);
    echo json_encode(['init_point' => $data['init_point']]);
} else {
    echo json_encode(['error' => 'Erro ao criar preferência de pagamento']);
}
