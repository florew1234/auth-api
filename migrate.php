<?php

$host = '127.0.0.1';
$db = 'auth_api';            // Nom de ta base
$user = 'florew';              // Ton utilisateur MySQL
$pass = 'florette54@';                  // Mot de passe MySQL
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "✅ Connexion MySQL établie avec succès\n";
} catch (PDOException $e) {
    exit("❌ Connexion échouée : " . $e->getMessage() . "\n");
}

// Crée la table des migrations si elle n'existe pas
$pdo->exec("
    CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE,
        migrated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

$migrationsPath = __DIR__ . '/migrations';
$files = glob($migrationsPath . '/*.php');

foreach ($files as $file) {
    $name = basename($file);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE name = ?");
    $stmt->execute([$name]);
    $alreadyRun = $stmt->fetchColumn();

    if ($alreadyRun) {
        echo "⏩ Migration déjà exécutée : $name\n";
        continue;
    }

    require_once $file;

    if (!function_exists('up')) {
        echo "❌ Fonction 'up' non trouvée dans $name\n";
        continue;
    }

    try {
        up($pdo);
        $stmt = $pdo->prepare("INSERT INTO migrations (name) VALUES (?)");
        $stmt->execute([$name]);
        echo "✅ Migration exécutée avec succès : $name\n";
    } catch (Exception $e) {
        echo "❌ Échec de la migration $name : " . $e->getMessage() . "\n";
    }
}
