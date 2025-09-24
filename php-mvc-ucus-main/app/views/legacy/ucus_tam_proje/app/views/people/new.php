<?php
declare(strict_types=1);
ini_set('display_errors','1'); error_reporting(E_ALL);
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

$BASE = '/ucus_tam_proje';

$pdo = require __DIR__ . '/../../config/database.php';

if (!function_exists('e')) {
    function e($v): string { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
}

$errors = [];
$old = [
        'first_name' => '',
        'last_name'  => '',
        'gender'     => '',
        'age'        => '',
];

$ALLOWED_GENDERS = ['Erkek','Kadın'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Eski değerleri formda tutalım
    $old['first_name'] = trim((string)($_POST['first_name'] ?? ''));
    $old['last_name']  = trim((string)($_POST['last_name']  ?? ''));
    $old['gender']     = trim((string)($_POST['gender']     ?? ''));
    $ageRaw            = $_POST['age'] ?? '';

    // Doğrulamalar
    if ($old['first_name'] === '') { $errors[] = 'Ad zorunludur.'; }
    if ($old['last_name']  === '') { $errors[] = 'Soyad zorunludur.'; }
    if ($old['gender'] === '' || !in_array($old['gender'], $ALLOWED_GENDERS, true)) {
        $errors[] = 'Geçerli bir cinsiyet seçiniz.';
    }
    $age = filter_var($ageRaw, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
    if ($age === false) { $errors[] = 'Yaş 0 veya daha büyük bir tam sayı olmalıdır.'; }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                    "INSERT INTO person (first_name, last_name, gender, age)
                 VALUES (:f, :l, :g, :a)"
            );
            $stmt->execute([
                    ':f' => $old['first_name'],
                    ':l' => $old['last_name'],
                    ':g' => $old['gender'],
                    ':a' => (int)$age,
            ]);
            $_SESSION['flash'] = 'Kişi eklendi.';
            header("Location: {$BASE}/app/views/people/index.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Kayıt sırasında bir hata oluştu. (' . e($e->getMessage()) . ')';
        }
    }
}

require __DIR__ . '/../layout/header.php';
?>
<h1>Yeni Kişi</h1>

<?php if ($errors): ?>
    <div class="alert error">
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= $BASE ?>/app/views/people/new.php" class="form-grid">
    <div>
        <label for="first_name">Ad</label>
        <input id="first_name" type="text" name="first_name" value="<?= e($old['first_name']) ?>" required autofocus>
    </div>

    <div>
        <label for="last_name">Soyad</label>
        <input id="last_name" type="text" name="last_name" value="<?= e($old['last_name']) ?>" required>
    </div>

    <div>
        <label for="gender">Cinsiyet</label>
        <select id="gender" name="gender" required>
            <option value="">Seçiniz</option>
            <?php foreach ($ALLOWED_GENDERS as $g): ?>
                <option value="<?= e($g) ?>" <?= $old['gender'] === $g ? 'selected' : '' ?>><?= e($g) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="age">Yaş</label>
        <input id="age" type="number" name="age" min="0" value="<?= e($old['age'] === '' ? '' : (string)(int)$old['age']) ?>" required>
    </div>

    <div style="grid-column:1/-1">
        <button class="btn" type="submit">Kaydet</button>
        <a class="btn secondary" href="<?= $BASE ?>/app/views/people/index.php">İptal</a>
    </div>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>
