<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Flash;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        // Flash mesajı tek seferlik çek
        $flash = Flash::get();
        $this->view('auth/login', [
            'flash' => $flash,
        ]);
    }

    public function login(): void
    {
        $id  = (string)($_POST['id'] ?? '');
        $pwd = (string)($_POST['password'] ?? '');

        if (Auth::attempt($id, $pwd)) {
            $this->redirect('/'); // başarılı
            return;
        }

        // Başarısız: flash ayarla ve login sayfasına dön
        Flash::set('error', 'Kullanıcı Adı veya şifre hatalı.');
        $this->redirect('/login');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/login');
    }
}
