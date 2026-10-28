<?php
$hosts = ['127.0.0.1', 'localhost', '::1'];
foreach ($hosts as $host) {
    echo "Testing host: $host ...\n";
    $t0 = microtime(true);
    try {
        $pdo = new PDO("mysql:host=$host;port=3306;dbname=sig_sppg_cileunyi", "root", "", [
            PDO::ATTR_TIMEOUT => 2,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        echo "  SUCCESS in " . round(microtime(true) - $t0, 3) . "s\n";
        $stmt = $pdo->query("SHOW PROCESSLIST");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "  Active MySQL processes:\n";
        foreach ($rows as $r) {
            echo "    [ID: {$r['Id']}] User: {$r['User']} | Time: {$r['Time']} | State: {$r['State']} | Info: {$r['Info']}\n";
        }
    } catch (Exception $e) {
        echo "  FAILED in " . round(microtime(true) - $t0, 3) . "s: " . $e->getMessage() . "\n";
    }
}
