<?php
require_once APP_ROOT . '/models/Database.php';

class DemoController {
    public function index() {
        $db = Database::getInstance();
        // Example safe query (replace with real table/columns)
        try {
            $rows = $db->query("SELECT 1 as ok")->fetchAll();
        } catch (Throwable $e) {
            $rows = [['ok' => 'DB bağlantısı yapılandırılmalı: app/config.php']];
        }
        $data = $rows;
        include VIEW_PATH . '/legacy/index.php';
    }
}
