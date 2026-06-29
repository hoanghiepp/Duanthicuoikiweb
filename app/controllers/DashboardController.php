<?php
declare(strict_types=1);

class DashboardController
{
    public function index(): void
    {
        Auth::requireRoles(['admin','hr']);
        $stats = [
            'employees' => (int)(Database::fetch('SELECT COUNT(*) total FROM employees')['total'] ?? 0),
            'departments' => (int)(Database::fetch('SELECT COUNT(*) total FROM departments')['total'] ?? 0),
            'positions' => (int)(Database::fetch('SELECT COUNT(*) total FROM positions')['total'] ?? 0),
            'today_present' => (int)(Database::fetch('SELECT COUNT(*) total FROM attendance WHERE work_date = CURDATE() AND status IN ("present","late")')['total'] ?? 0),
        ];
        $recent = Database::all('SELECT e.*, d.name department_name, p.name position_name FROM employees e LEFT JOIN departments d ON d.id=e.department_id LEFT JOIN positions p ON p.id=e.position_id ORDER BY e.id DESC LIMIT 6');
        $deptStats = Database::all('SELECT d.name, COUNT(e.id) total FROM departments d LEFT JOIN employees e ON e.department_id=d.id GROUP BY d.id,d.name ORDER BY total DESC');
        view('dashboard/index', ['title'=>'Dashboard nhân sự', 'stats'=>$stats, 'recent'=>$recent, 'deptStats'=>$deptStats]);
    }
}
