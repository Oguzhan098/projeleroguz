<?php
declare(strict_types=1);
ini_set('display_errors','1'); error_reporting(E_ALL);
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

$BASE = '/ucus_tam_proje';

$pdo = require __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    try {
        $stmt = $pdo->prepare('DELETE FROM person WHERE id = ?');
        $stmt->execute([$id]);
        $_SESSION['flash'] = 'Kişi silindi.';
    } catch (PDOException $e) {
        $_SESSION['flash'] = 'Kişi silinemedi. (Muhtemelen bir uçuşa atanmış.)';
    }
    header("Location: {$BASE}/app/views/people/index.php");
    exit;
}


$people = $pdo->query("SELECT * FROM person ORDER BY id")->fetchAll();

require __DIR__ . '/../layout/header.php';
?>
<h1>Kişiler</h1>

<?php if (!empty($_SESSION['flash'])): ?>
    <p class="flash"><?= htmlspecialchars($_SESSION['flash'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php $_SESSION['flash'] = null; ?>
<?php endif; ?>

<div class="actions">
    <a class="btn" href="<?= $BASE ?>/app/views/people/new.php">Yeni Kişi</a>
</div>

<?php if (empty($people)): ?>
    <p class="small">Kayıt yok.</p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Ad</th>
            <th>Soyad</th>
            <th>Cinsiyet</th>
            <th>Yaş</th>
            <th style="width:120px;">Aksiyon</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($people as $pe): ?>
            <tr>
                <td><?= (int)$pe['id'] ?></td>
                <td><?= htmlspecialchars($pe['first_name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($pe['last_name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($pe['gender'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= (int)$pe['age'] ?></td>
                <td class="actions" style="display:flex; gap:6px;">
                    <form method="post"
                          action="<?= $BASE ?>/app/views/people/index.php"
                          onsubmit="return confirm('Bu kişi silinsin mi?')">
                        <input type="hidden" name="delete_id" value="<?= (int)$pe['id'] ?>">
                        <button class="btn danger" type="submit">Sil</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>
