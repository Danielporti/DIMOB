<?php
// oauth_config.php
// Preencha os valores com as credenciais geradas nos painéis dos provedores.
return [
    'google' => [
        'client_id' => '156833654094-4a6uds213c9to9nqesr3vkdnhek55jgn.apps.googleusercontent.com',
        'client_secret' => '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',
        // Redirect URI must match the one registered in Google Console
        // When running the built-in PHP server use port 8000 (http://localhost:8000)
        'redirect_uri' => 'http://localhost:8000/DIMOB/backend/auth.php?provider=google',
    ],
    // Meta (Facebook / Instagram / WhatsApp Business) app credentials
    'meta' => [
        'app_id' => 'META_APP_ID',
        'app_secret' => 'META_APP_SECRET',
        // Redirect used by meta_connect.php (include port if using built-in server)
        'redirect_uri' => 'http://localhost:8000/DIMOB/backend/meta_connect.php',
    ],
];
