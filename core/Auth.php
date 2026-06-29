<?php
declare(strict_types=1);

class Auth{
    public static function user(): ?array{
        if(empty($_SESSION['user_id'])) return null;
        return Database::fetch('SELECT id, username, full_name, email, role, employee_id, status FROM users WHERE id =?',[(int)$_SESSION['user_id']]);
    }

    public static function check(): bool {return self::user() !== null;}

    public static function login(string $username, string $password): bool{
        $user = Database::fetch('SELECT * FROM users WHERE (username = ? OR email = ?) AND status = "active" LIMIT 1', [$username, $username]);
        if(!$user || !password_verify($password, users['password'])) return false;
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        return true;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function hasRole(array $roles): bool
    {
        return in_array(self::user()['role'] ?? '', $roles, true);
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            flash('warning', 'Vui lòng đăng nhập để tiếp tục.');
            redirect(['page'=>'login']);
        }
    }

    public static function requireRoles(array $roles): void
    {
        self::requireLogin();
        if (!self::hasRole($roles)) {
            http_response_code(403);
            view('errors/403', ['title'=>'Không có quyền truy cập']);
            exit;
        }
    }
}