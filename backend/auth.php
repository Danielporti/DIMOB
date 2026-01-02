<?php
session_start();
$config = include __DIR__ . '/oauth_config.php';

$provider = isset($_GET['provider']) ? $_GET['provider'] : null;
if (!$provider || !isset($config[$provider])) {
    http_response_code(400);
    echo "Provider inválido";
    exit;
}

$cfg = $config[$provider];

// simple state handling
if (!isset($_SESSION['oauth_state'])) {
    $_SESSION['oauth_state'] = bin2hex(random_bytes(16));
}
$state = $_SESSION['oauth_state'];

// If no code => start authorization
// use $_REQUEST so Apple (form_post) works as well
if (!isset($_REQUEST['code'])) {
    if ($provider === 'google') {
        $auth = 'https://accounts.google.com/o/oauth2/v2/auth';
        $params = [
            'response_type' => 'code',
            'client_id' => $cfg['client_id'],
            'redirect_uri' => $cfg['redirect_uri'],
            'scope' => 'openid email profile',
            'state' => $state,
            'access_type' => 'offline',
            'prompt' => 'consent'
        ];
        header('Location: ' . $auth . '?' . http_build_query($params));
        exit;
    }

    echo "Provider não implementado";
    exit;

    echo "Provider não implementado";
    exit;
}

// Callback handling: verify state
if (!isset($_REQUEST['state']) || $_REQUEST['state'] !== $state) {
    echo "State inválido";
    exit;
}

$code = $_REQUEST['code'];

if ($provider === 'google') {
    // Exchange code for tokens
    $tokenUrl = 'https://oauth2.googleapis.com/token';
    $post = [
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $cfg['redirect_uri'],
        'client_id' => $cfg['client_id'],
        'client_secret' => $cfg['client_secret'],
    ];

    $ch = curl_init($tokenUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($resp, true);
    if (!isset($data['access_token'])) {
        echo "Erro ao obter token: " . htmlspecialchars($resp);
        exit;
    }

    // get userinfo
    $userinfo = file_get_contents('https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . urlencode($data['access_token']));
    $user = json_decode($userinfo, true);
    $_SESSION['user'] = $user;
    // Redirect to app (adjust as needed)
    header('Location: ../DIMOB/index.html');
    exit;
}

echo "Fluxo não tratado.";
