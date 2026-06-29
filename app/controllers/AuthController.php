<?php
declare(strict_types=1);

class AuthController
{
    public function showLogin(): void
    {
        if (Auth::check()) redirect(['page'=>Auth::hasRole(['employee']) ? 'profile' : 'dashboard']);
        view('auth/login', ['title'=>'Đăng nhập', 'noShell'=>true]);
    }

    public function login(): void
    {
        verify_csrf();
        if (Auth::login(trim($_POST['username'] ?? ''), (string)($_POST['password'] ?? ''))) {
            flash('success', 'Đăng nhập thành công.');
            redirect(['page'=>Auth::hasRole(['employee']) ? 'profile' : 'dashboard']);
        }
        flash('danger', 'Tên đăng nhập hoặc mật khẩu không chính xác.');
        redirect(['page'=>'login']);
    }

    public function logout(): void
    {
        Auth::logout();
        session_start();
        flash('success', 'Bạn đã đăng xuất.');
        redirect(['page'=>'login']);
    }
}
