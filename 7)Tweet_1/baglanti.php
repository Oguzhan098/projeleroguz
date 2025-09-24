<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=tweet_app;charset=utf8mb4", "root", "root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Veritabanına başarıyla bağlanıldı!";

    session_start(); // Bağlantı başarılıysa session başlatılır
} catch (PDOException $e) {
    echo "❌ Bağlantı hatası: " . $e->getMessage();
}

