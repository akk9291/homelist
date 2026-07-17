<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    
    $dbs = [];
    foreach ($pdo->query('SHOW DATABASES') as $row) {
        $dbName = $row[0];
        if (in_array($dbName, ['information_schema', 'mysql', 'performance_schema', 'sys'])) {
            continue;
        }
        $dbs[] = $dbName;
    }
    
    foreach ($dbs as $db) {
        try {
            $pdo->exec("USE `$db`");
            // Check if wp_posts exists
            $stmt = $pdo->query("SHOW TABLES LIKE 'wp_posts'");
            if ($stmt->fetch()) {
                $stmtCount = $pdo->query("SELECT COUNT(*) FROM `wp_posts`");
                $count = $stmtCount->fetchColumn();
                echo "Database `$db` has table `wp_posts` with $count rows.\n";
            }
        } catch (\Throwable $e) {
            // ignore access errors on other DBs
        }
    }
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}
