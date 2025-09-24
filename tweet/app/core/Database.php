<?php

class Database
{
    private $host = 'localhost';
    private $db = 'tweet_app';
    private $user = 'postgres';
    private $pass = 'postgres'; // kendi şifreni yaz
    public $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO("pgsql:host=$this->host;dbname=$this->db",
                $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Veritabanı bağlantı hatası: " . $e->getMessage());
        }
    }
}
