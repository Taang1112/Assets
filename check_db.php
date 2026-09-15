<?php
$passwords = ['', 'root', 'admin', 'password', '123456', '12345678'];
$ports = [3306, 3307];

foreach ($ports as $port) {
    foreach ($passwords as $pwd) {
        try {
            $pdo = new PDO("mysql:host=127.0.0.1;port={$port}", 'root', $pwd);
            echo "SUCCESS: port {$port}, pass: '{$pwd}'\n";
            $stmt = $pdo->query("SHOW DATABASES");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo " - DB: " . $row['Database'] . "\n";
            }
        } catch (Exception $e) {
            // failed
        }
    }
}
