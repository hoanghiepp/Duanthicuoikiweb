<?php
declare(strict_types=1);

class ProfileController
{
    public function handle(string $action): void
    {
        Auth::requireLogin();
        match($action) {
            'edit' => $this->edit(),
            'update' => $this->update(),
            'password' => $this->password(),
            'update-password' => $this->updatePassword(),
            'attendance' => $this->attendance(),
            'payrolls' => $this->payrolls(),
            default => $this->index(),
        };
    }

    public function index(): void
    {
        $user = Auth::user();
        $employee = $this->employee($user);
        view('profile/index', [
            'title'=>'Hồ sơ cá nhân',
            'user'=>$user,
            'employee'=>$employee,
            'attendance'=>$employee ? Database::all('SELECT * FROM attendance WHERE employee_id=? ORDER BY work_date DESC LIMIT 5', [$employee['id']]) : [],
            'payrolls'=>$employee ? Database::all('SELECT * FROM payrolls WHERE employee_id=? ORDER BY salary_month DESC LIMIT 5', [$employee['id']]) : [],
        ]);
    }

    public function attendance(): void
    {
        $user = Auth::user();
        $employee = $this->employee($user);
        $rows = $employee ? Database::all('SELECT * FROM attendance WHERE employee_id=? ORDER BY work_date DESC LIMIT 100', [$employee['id']]) : [];
        view('profile/attendance', ['title'=>'Chấm công của tôi','rows'=>$rows]);
    }

    public function payrolls(): void
    {
        $user = Auth::user();
        $employee = $this->employee($user);
        $rows = $employee ? Database::all('SELECT * FROM payrolls WHERE employee_id=? ORDER BY salary_month DESC LIMIT 100', [$employee['id']]) : [];
        view('profile/payrolls', ['title'=>'Lương của tôi','rows'=>$rows]);
    }

    public function edit(): void
    {
        $user = Auth::user();
        view('profile/edit', ['title'=>'Cập nhật hồ sơ', 'user'=>$user, 'employee'=>$this->employee($user)]);
    }

    public function update(): void
    {
        verify_csrf();
        $user = Auth::user();
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '') ?: null;
        $phone = trim($_POST['phone'] ?? '') ?: null;
        $address = trim($_POST['address'] ?? '') ?: null;
        if ($fullName === '') { flash('danger','Họ tên không được để trống.'); redirect(['page'=>'profile','action'=>'edit']); }
        Database::query('UPDATE users SET full_name=?, email=? WHERE id=?', [$fullName, $email, $user['id']]);
        if (!empty($user['employee_id'])) Database::query('UPDATE employees SET full_name=?, email=?, phone=?, address=? WHERE id=?', [$fullName, $email, $phone, $address, $user['employee_id']]);
        flash('success', 'Cập nhật hồ sơ thành công.');
        redirect(['page'=>'profile']);
    }

    public function password(): void { view('profile/password', ['title'=>'Đổi mật khẩu']); }

    public function updatePassword(): void
    {
        verify_csrf();
        $user = Auth::user();
        $row = Database::fetch('SELECT password FROM users WHERE id=?', [$user['id']]);
        $current = (string)($_POST['current_password'] ?? '');
        $new = (string)($_POST['new_password'] ?? '');
        $confirm = (string)($_POST['confirm_password'] ?? '');
        if (!$row || !password_verify($current, $row['password'])) { flash('danger','Mật khẩu hiện tại không đúng.'); redirect(['page'=>'profile','action'=>'password']); }
        if (strlen($new) < 6 || $new !== $confirm) { flash('danger','Mật khẩu mới không hợp lệ hoặc xác nhận không khớp.'); redirect(['page'=>'profile','action'=>'password']); }
        Database::query('UPDATE users SET password=? WHERE id=?', [password_hash($new, PASSWORD_DEFAULT), $user['id']]);
        flash('success', 'Đổi mật khẩu thành công.');
        redirect(['page'=>'profile']);
    }

    private function employee(?array $user): ?array
    {
        if (empty($user['employee_id'])) return null;
        return Database::fetch('SELECT e.*, d.name department_name, p.name position_name FROM employees e LEFT JOIN departments d ON d.id=e.department_id LEFT JOIN positions p ON p.id=e.position_id WHERE e.id=?', [$user['employee_id']]);
    }
}
