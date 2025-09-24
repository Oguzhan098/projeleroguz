<?php ob_start(); ?>
<?php if (User::isLoggedIn()): ?>
    <form method="POST">
        <textarea name="content" maxlength="180" required placeholder="Ne düşünüyorsun?"></textarea>
        <button type="submit">Tweetle</button>
    </form>
<?php endif; ?>

<h2>Tweetler</h2>
<?php foreach ($tweets as $tweet): ?>
    <div class="tweet">
        <strong>@<?= htmlspecialchars($tweet['username']) ?></strong><br>
        <?= htmlspecialchars($tweet['content']) ?><br>
        <small><?= $tweet['created_at'] ?></small>
    </div>
<?php endforeach; ?>
<?php $content = ob_get_clean(); include 'layout.php'; ?>