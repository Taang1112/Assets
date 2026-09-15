<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3306", 'root', 'root');
    $stmt = $pdo->query("SHOW DATABASES");
    echo "DATABASES ON 3306 (pass root):\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo " - " . $row['Database'] . "\n";
    }
} catch (Exception $e) {
    echo "Error 3306 root: " . $e->getMessage() . "\n";
}

try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3306", 'root', '');
    $stmt = $pdo->query("SHOW DATABASES");
    echo "DATABASES ON 3306 (empty pass):\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo " - " . $row['Database'] . "\n";
    }
} catch (Exception $e) {
    echo "Error 3306 empty: " . $e->getMessage() . "\n";
}
