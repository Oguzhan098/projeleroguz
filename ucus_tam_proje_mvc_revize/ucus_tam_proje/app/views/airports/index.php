<?php
declare(strict_types=1);
ini_set('display_errors','1'); error_reporting(E_ALL);
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

$BASE = '/ucus_tam_proje';

$pdo = require __DIR__ . '/../../config/database.php';

if (!function_exists('e')) {
    function e($v): string {
        return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'], $_POST['csrf'])) {
    if (!hash_equals($_SESSION['csrf'], (string)$_POST['csrf'])) {
        http_response_code(403);
        echo 'Geçersiz istek (CSRF doğrulaması başarısız).';
        exit;
    }
    $aid = (int)$_POST['delete_id'];
    if ($aid > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM airport WHERE id = :id");
            $stmt->execute([':id' => $aid]);
            $_SESSION['flash'] = 'Havalimanı silindi.';
        } catch (Throwable $e) {
            $_SESSION['flash'] = 'Havalimanı silinemedi. İlişkili uçuşlar olabilir.';
        }
    }
    $self = strtok($_SERVER['REQUEST_URI'], '?');
    header('Location: ' . $self);
    exit;
}



$airports = $pdo->query("SELECT * FROM airport ORDER BY id")->fetchAll();

require __DIR__ . '/../layout/header.php';
?>
<h1>Havalimanları</h1>



<?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash"><?= e($_SESSION['flash']) ?></div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>



<div class="actions">
    <a class="btn" href="<?= $BASE ?>/app/views/airports/new.php">Yeni Havalimanı</a>
</div>


<?php if (empty($airports)): ?>
    <p class="small">Kayıtlı havalimanı bulunamadı.</p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Ad</th>
            <th>Pist</th>
            <th>Uçak Kapasitesi</th>
            <th>İşlemler</th>
        </tr></thead>
        <tbody>
        <?php foreach ($airports as $a): ?>
            <tr>
                <td><?= (int)$a['id'] ?></td>
                <td><?= e($a['name']) ?></td>
                <td><?= (int)$a['pist_sayisi'] ?></td>
                <td><?= (int)$a['ucak_kapasitesi'] ?></td>
                <td class="actions">

                    <form method="post" action=""
                          onsubmit="return confirm('<?= e($a['name']) ?> silinsin mi?');">
                        <input type="hidden" name="delete_id" value="<?= (int)$a['id'] ?>">
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
