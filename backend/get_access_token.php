<?php
// backend/get_access_token.php
// Substitua pelo seu Client Secret
$client_id = '4187669924066683';
$client_secret = '2943973110'; // Cole aqui o seu Client Secret

$ch = curl_init('https://api.mercadopago.com/oauth/token');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'grant_type' => 'client_credentials',
    'client_id' => $client_id,
    'client_secret' => $client_secret
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
echo $data['access_token'];
?>
