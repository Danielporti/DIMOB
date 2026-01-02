<?php
// backend/meta_connect.php
// Start or complete OAuth flow to connect a Meta (Facebook) app for Pages/WhatsApp/Ads access.
session_start();
$config = include __DIR__ . '/oauth_config.php';
if (!isset($config['meta'])) {
    http_response_code(500);
    echo "Meta configuration not found. Configure backend/oauth_config.php.";
    exit;
}
$cfg = $config['meta'];

// generate simple state
if (!isset($_SESSION['meta_oauth_state'])) {
    $_SESSION['meta_oauth_state'] = bin2hex(random_bytes(16));
}
$state = $_SESSION['meta_oauth_state'];

// If no code => start authorization
if (!isset($_GET['code'])) {
    $auth = 'https://www.facebook.com/v12.0/dialog/oauth';
    $scopes = [
        'pages_show_list',
        'pages_read_engagement',
        'pages_manage_metadata',
        'pages_messaging',
        'ads_management',
        'business_management',
        'instagram_basic',
        'instagram_manage_messages',
        'whatsapp_business_messaging'
    ];
    $params = [
        'client_id' => $cfg['app_id'],
        'redirect_uri' => $cfg['redirect_uri'],
        'state' => $state,
        'scope' => implode(',', $scopes),
    ];
    header('Location: ' . $auth . '?' . http_build_query($params));
    exit;
}

// callback: verify state
if (!isset($_GET['state']) || $_GET['state'] !== $state) {
    echo "State inválido";
    exit;
}

$code = $_GET['code'];

// exchange code for access token
$tokenUrl = 'https://graph.facebook.com/v12.0/oauth/access_token';
$params = [
    'client_id' => $cfg['app_id'],
    'redirect_uri' => $cfg['redirect_uri'],
    'client_secret' => $cfg['app_secret'],
    'code' => $code,
];
$ch = curl_init($tokenUrl . '?' . http_build_query($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$resp = curl_exec($ch);
curl_close($ch);
$data = json_decode($resp, true);
if (!isset($data['access_token'])) {
    echo "Erro ao obter token Meta: " . htmlspecialchars($resp);
    exit;
}

$access_token = $data['access_token'];

// save token to a simple file (for demo) — in production use secure storage
$save = [
    'access_token' => $access_token,
    'obtained_at' => time(),
    'raw' => $data,
];
file_put_contents(__DIR__ . '/meta_token.json', json_encode($save, JSON_PRETTY_PRINT));

header('Location: ../DIMOB/meta_admin.html');
exit;
