<?php
class LegacyController {
    // Fallback to render any legacy view by path: ?c=Legacy&a=show&path=folder/file.php
    public function show() {
        $path = isset($_GET['path']) ? $_GET['path'] : 'index.php';
        $candidate = VIEW_PATH . '/legacy/' . $path;
        if (!preg_match('/^[A-Za-z0-9_\/\.-]+$/', $path)) {
            header("HTTP/1.0 400"); echo "Invalid path"; return;
        }
        if (!file_exists($candidate)) {
            header("HTTP/1.0 404"); echo "Legacy view not found"; return;
        }
        include $candidate;
    }
}
