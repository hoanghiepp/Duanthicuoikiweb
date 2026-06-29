<?php
declare(strict_types=1);

class App
{
    private array $modules = ['employees', 'departments', 'positions', 'attendance', 'payrolls', 'users'];

    public function run(): void
    {
        $page = $_GET['page'] ?? 'home';
        $action = $_GET['action'] ?? 'index';

        try {
            if ($page === 'home') (new HomeController())->index();
            elseif ($page === 'login') is_post() ? (new AuthController())->login() : (new AuthController())->showLogin();
            elseif ($page === 'logout') (new AuthController())->logout();
            elseif ($page === 'dashboard') (new DashboardController())->index();
            elseif ($page === 'profile') (new ProfileController())->handle($action);
            elseif (in_array($page, $this->modules, true)) (new ModuleController($page))->handle($action);
            else view('errors/404', ['title'=>'Không tìm thấy trang']);
        } catch (PDOException $e) {
            view('errors/500', ['title'=>'Lỗi hệ thống', 'message'=>$e->getMessage()]);
        }
    }
}
