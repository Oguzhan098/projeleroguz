<?php
require 'includes/config.php';
require 'includes/functions.php';

$tweets = PDO::query("
    SELECT tweets.content, tweets.created_at, users.username
    FROM tweets
    JOIN users ON tweets.user_id = users.id
    ORDER BY tweets.created_at DESC
")->fetchAll();
?>

<?php if (isLoggedIn()): ?>
    <form action="post_tweet.php" method="post">
        <textarea name="tweet" maxlength="180" placeholder="Ne düşünüyorsun?"></textarea>
        <button type="submit">Tweetle</button>
    </form>
    <a href="logout.php">Çıkış</a>
<?php else: ?>
    <a href="login.php">Giriş</a> | <a href="register.php">Kayıt</a>
<?php endif; ?>

<h2>Tweetler</h2>

<?php foreach ($tweets as $tweet): ?>

    <p><strong><?= htmlspecialchars($tweet['username']) ?>
        </strong>: <?= htmlspecialchars($tweet['content']) ?> <br>
        <small><?= $tweet['created_at'] ?></small></p>
<?php endforeach; ?>
