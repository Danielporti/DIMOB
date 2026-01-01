<?php
require __DIR__ . '/db.php';

try {
    $pdo = get_pdo();
    $stmt = $pdo->query('SELECT version()');
    $version = $stmt->fetchColumn();
    echo "Conectado ao PostgreSQL: " . htmlspecialchars($version);
} catch (Exception $e) {
    echo "Falha na conexão: " . htmlspecialchars($e->getMessage());
}

?>
