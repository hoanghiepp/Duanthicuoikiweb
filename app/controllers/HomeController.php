<?php
declare(strict_types=1);

class HomeController
{
    public function index(): void
    {
        if (Auth::check()) redirect(['page'=>Auth::hasRole(['employee']) ? 'profile' : 'dashboard']);
        view('home/index', ['title'=>'Trang chủ', 'noShell'=>true]);
    }
}
