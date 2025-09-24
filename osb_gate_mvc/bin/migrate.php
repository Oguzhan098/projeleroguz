#!/usr/bin/env php
<?php
declare(strict_types=1);

require __DIR__ . '/../app/bootstrap.php';

use App\Core\DB;

$pdo = DB::pdo();
$pdo->exec("CREATE TABLE IF NOT EXISTS schema_migrations (version text primary key, applied_at timestamptz not null default now())");

$dir = __DIR__ . '/../database/migrations';
$files = glob($dir . '/*.sql');
sort($files);

$appliedStmt = $pdo->query("SELECT version FROM schema_migrations");
$applied = $appliedStmt->fetchAll(PDO::FETCH_COLUMN);
$applied = array_flip($applied);

foreach ($files as $file) {
    $version = basename($file);
    if (isset($applied[$version])) {
        echo "SKIP  $version\n";
        continue;
    }
    $sql = file_get_contents($file);
    echo "APPLY $version ... ";
    $pdo->beginTransaction();
    try {
        $pdo->exec($sql);
        $stmt = $pdo->prepare("INSERT INTO schema_migrations(version) VALUES(:v)");
        $stmt->execute([':v'=>$version]);
        $pdo->commit();
        echo "OK\n";
    } catch (Throwable $e) {
        $pdo->rollBack();
        echo "FAIL\n";
        fwrite(STDERR, $e->getMessage() . PHP_EOL);
        exit(1);
    }
}
echo "Done.\n";
