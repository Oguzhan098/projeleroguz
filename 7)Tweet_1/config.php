<?php
$pdo = new PDO("mysql:host=localhost;dbname=tweet_app;charset=utf8mb4", "root", "root");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();
