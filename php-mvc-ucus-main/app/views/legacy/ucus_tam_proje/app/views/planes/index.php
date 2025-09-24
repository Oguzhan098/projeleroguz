<?php
declare(strict_types=1);
ini_set('display_errors','1'); error_reporting(E_ALL);
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

$BASE = $BASE ?? '/ucus_tam_proje';
$pdo  = require __DIR__ . '/../../config/database.php';

if (!function_exists('e')) {
    function e($v): string {
        return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'], $_POST['csrf'])) {
    if (!isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], (string)$_POST['csrf'])) {
        http_response_code(403);
        echo 'Geçersiz istek (CSRF doğrulaması başarısız).';
        exit;
    }
    $pid = (int)$_POST['delete_id'];
    if ($pid > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM plane WHERE id = :id");
            $stmt->execute([':id' => $pid]);
            $_SESSION['flash'] = 'Uçak silindi.';
        } catch (Throwable $e) {
            $_SESSION['flash'] = 'Uçak silinemedi. İlişkili uçuşlar olabilir.';
        }
    }
    $self = strtok($_SERVER['REQUEST_URI'], '?');
    header('Location: ' . $self);
    exit;
}



$planes = $pdo->query("SELECT * FROM plane ORDER BY id")->fetchAll();

require __DIR__ . '/../layout/header.php';
?>
<h1>Uçaklar</h1>

<?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash"><?= e($_SESSION['flash']) ?></div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="actions">
    <a class="btn" href="<?= $BASE ?>/app/views/planes/new.php">Yeni Uçak</a>
</div>

<?php if (empty($planes)): ?>
    <p class="small">Kayıt yok.</p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Marka</th>
            <th>Model</th>
            <th>Kapasite</th>
            <th>Yıl</th>
            <th>İşlemler</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($planes as $p): ?>
            <?php
            $id    = isset($p['id']) ? (int)$p['id'] : 0;
            $brand = $p['brand'] ?? '';
            $model = $p['model'] ?? '';
            $cap   = isset($p['capacity']) ? (int)$p['capacity'] : '';
            $year  = isset($p['year']) && $p['year'] !== null ? (int)$p['year'] : '';
            $label = trim(($brand ? $brand : '') . ' ' . ($model ? $model : ''));
            ?>
            <tr>
                <td><?= $id ?: '' ?></td>
                <td><?= e($brand) ?></td>
                <td><?= e($model) ?></td>
                <td><?= $cap ?></td>
                <td><?= $year ?></td>
                <td class="actions">
                    <form method="post" action=""
                          onsubmit="return confirm('<?= e($label ?: 'Bu uçak') ?> silinsin mi?');">
                        <input type="hidden" name="delete_id" value="<?= $id ?>">
                        <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
                        <button class="btn danger" type="submit">Sil</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>
