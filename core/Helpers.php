<?php
declare(strict_types=1);

function e(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function app_url(array $params = []): string
{
    $query = http_build_query(array_filter($params, fn($v) => $v !== null && $v !== ''));
    return 'index.php' . ($query ? '?' . $query : '');
}

function asset(string $path): string { return 'assets/' . ltrim($path, '/'); }

function redirect(array|string $target): never
{
    header('Location: ' . (is_array($target) ? app_url($target) : $target));
    exit;
}

function is_post(): bool { return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'; }
function current_page(): string { return $_GET['page'] ?? 'home'; }
function current_action(): string { return $_GET['action'] ?? 'index'; }

function flash(string $type, ?string $message = null): ?string
{
    if ($message !== null) { $_SESSION['flash'][$type] = $message; return null; }
    if (!empty($_SESSION['flash'][$type])) {
        $msg = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $msg;
    }
    return null;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        http_response_code(419);
        exit('CSRF token không hợp lệ.');
    }
}

function view(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $viewPath = BASE_PATH . '/app/views/' . $view . '.php';
    if (!file_exists($viewPath)) exit('Không tìm thấy view: ' . e($view));
    ob_start();
    require $viewPath;
    $content = ob_get_clean();
    require BASE_PATH . '/app/views/layouts/main.php';
}

function money(mixed $value): string { return number_format((float)$value, 0, ',', '.') . ' đ'; }

function role_label(?string $role): string
{
    return match($role) {
        'admin' => 'Admin',
        'hr' => 'Nhân sự',
        'employee' => 'Nhân viên',
        default => $role ?: '-',
    };
}

function employee_status_label(?string $status): string
{
    return match($status) {
        'working' => 'Đang làm',
        'probation' => 'Thử việc',
        'resigned' => 'Đã nghỉ',
        default => $status ?: '-',
    };
}

function attendance_label(?string $status): string
{
    return match($status) {
        'present' => 'Có mặt',
        'late' => 'Đi muộn',
        'absent' => 'Vắng',
        'leave' => 'Nghỉ phép',
        default => $status ?: '-',
    };
}

function page_number(): int { return max(1, (int)($_GET['p'] ?? 1)); }
function per_page(): int { return in_array((int)($_GET['per_page'] ?? 10), [5,10,20,50], true) ? (int)($_GET['per_page'] ?? 10) : 10; }

function paginate(int $total, int $page, int $perPage): array
{
    $pages = max(1, (int)ceil($total / $perPage));
    $page = min(max(1, $page), $pages);
    return ['total'=>$total,'page'=>$page,'per_page'=>$perPage,'total_pages'=>$pages,'offset'=>($page-1)*$perPage];
}

function render_pagination(array $pg): void
{
    if (($pg['total_pages'] ?? 1) <= 1) return;
    echo '<div class="pagination"><span>Trang ' . $pg['page'] . '/' . $pg['total_pages'] . ' · ' . $pg['total'] . ' dòng</span><div>';
    for ($i=max(1,$pg['page']-2); $i<=min($pg['total_pages'],$pg['page']+2); $i++) {
        $params = array_merge($_GET, ['p'=>$i]);
        $class = $i === $pg['page'] ? 'active' : '';
        echo '<a class="' . $class . '" href="' . e(app_url($params)) . '">' . $i . '</a>';
    }
    echo '</div></div>';
}
