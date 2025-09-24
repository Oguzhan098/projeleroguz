<?php
global $pdo;
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    $tweet = substr(trim($_POST['tweet']), 0, 180);
    $stmt = $pdo->prepare("INSERT INTO tweets (user_id, content) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user']['id'], $tweet]);
    header("Location: index.php");
    exit;
}

$stmt = $pdo->query("SELECT tweets.*, users.username FROM tweets 
                     JOIN users ON tweets.user_id = users.id 
                     ORDER BY tweets.created_at DESC");
$tweets = $stmt->fetchAll();
?>

<h2>Tweet Uygulaması</h2>

<?php if (isset($_SESSION['user'])): ?>
    <p>Merhaba, <?= htmlspecialchars($_SESSION['user']['username']) ?> |
        <a href="logout.php">Çıkış</a></p>

    <form method="post">
        <label>
            <textarea name="tweet" maxlength="180" rows="3" cols="50" placeholder="Ne düşünüyorsun?"></textarea>
        </label><br>
        <button type="submit">Tweetle</button>
    </form>
<?php else: ?>
    <p><a href="login.php">Giriş Yap</a> veya <a href="register.php">Kayıt Ol</a></p>
<?php endif; ?>

<hr>

<h3>Tweetler</h3>
<?php foreach ($tweets as $tweet): ?>
    <div style="border:1px solid #ccc; margin-bottom:10px; padding:10px;">
        <strong><?= htmlspecialchars($tweet['username']) ?></strong><br>
        <?= nl2br(htmlspecialchars($tweet['content'])) ?><br>
        <small><?= $tweet['created_at'] ?></small>
    </div>
<?php endforeach; ?>
