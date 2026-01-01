<?php
/**
 * Configuração de conexão para PostgreSQL.
 *
 * Prioriza a variável de ambiente `DATABASE_URL` (formatos como
 * postgres://user:pass@host:port/dbname) — usada por muitos hosts como Render —
 * e faz fallback para variáveis individuais (`DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`).
 * Em produção, defina credenciais seguras via variáveis de ambiente no painel do Render.
 */

function get_db_config()
{
    $dbUrl = getenv('DATABASE_URL') ?: getenv('POSTGRES_URL');
    if ($dbUrl) {
        $parts = parse_url($dbUrl);
        if ($parts === false) {
            throw new Exception('DATABASE_URL inválida');
        }
        $host = $parts['host'] ?? 'localhost';
        $port = $parts['port'] ?? 5432;
        $db   = isset($parts['path']) ? ltrim($parts['path'], '/') : '';
        $user = $parts['user'] ?? '';
        $pass = $parts['pass'] ?? '';
    } else {
        $host = getenv('DB_HOST') ?: 'localhost';
        $port = getenv('DB_PORT') ?: 5432;
        $db   = getenv('DB_NAME') ?: 'DIMOB';
        $user = getenv('DB_USER') ?: 'postgres';
        $pass = getenv('DB_PASS') ?: '';
    }

    return [
        'host' => $host,
        'port' => $port,
        'db'   => $db,
        'user' => $user,
        'pass' => $pass,
    ];
}

function get_pdo()
{
    $cfg = get_db_config();
    $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', $cfg['host'], $cfg['port'], $cfg['db']);
    try {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, $cfg['user'], $cfg['pass'], $options);
    } catch (PDOException $e) {
        error_log('DB connection error: ' . $e->getMessage());
        throw $e;
    }
}

?>
