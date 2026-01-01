<?php
// Configurações de conexão (fornecidas)
$host = 'localhost';
$port = 5432;
$db   = 'DIMOB';
$user = 'postgres';
$pass = 'pegasus123';
$dsn  = "pgsql:host=$host;port=$port;dbname=$db";

/**
 * Retorna uma conexão PDO com o PostgreSQL.
 * Lance uma exceção em caso de erro.
 * Em produção, prefira carregar credenciais via variáveis de ambiente.
 */
function get_pdo()
{
    global $dsn, $user, $pass;
    try {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        error_log('DB connection error: ' . $e->getMessage());
        throw $e;
    }
}

?>
