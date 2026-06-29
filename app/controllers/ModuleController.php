<?php
declare(strict_types=1);

class ModuleController
{
    private string $name;
    private array $config;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->config = ModuleConfig::get($name);
    }

    public function handle(string $action): void
    {
        Auth::requireRoles($this->config['role']);
        match($action) {
            'create' => $this->form(),
            'store' => $this->store(),
            'edit' => $this->form((int)($_GET['id'] ?? 0)),
            'update' => $this->update((int)($_GET['id'] ?? 0)),
            'delete' => $this->delete((int)($_GET['id'] ?? 0)),
            'show' => $this->show((int)($_GET['id'] ?? 0)),
            default => $this->index(),
        };
    }

    public function index(): void
    {
        [$where, $params] = $this->where();
        $from = $this->config['from'] ?? $this->config['table'] . ' t';
        $select = $this->config['select'] ?? 't.*';
        $total = (int)(Database::fetch("SELECT COUNT(*) total FROM {$from} {$where}", $params)['total'] ?? 0);
        $pg = paginate($total, page_number(), per_page());
        $limit = (int)$pg['per_page']; $offset = (int)$pg['offset'];
        $rows = Database::all("SELECT {$select} FROM {$from} {$where} ORDER BY " . ($this->config['alias'] ?? 't') . ".id DESC LIMIT {$limit} OFFSET {$offset}", $params);
        view('modules/index', ['title'=>'Quản lý ' . $this->config['title'], 'config'=>$this->config, 'rows'=>$rows, 'pagination'=>$pg, 'options'=>$this->allOptions()]);
    }

    public function form(int $id = 0): void
    {
        $row = null;
        if ($id > 0) {
            $row = Database::fetch("SELECT * FROM {$this->config['table']} WHERE id = ?", [$id]);
            if (!$row) { flash('danger', 'Không tìm thấy dữ liệu.'); redirect(['page'=>$this->name]); }
        }
        view('modules/form', ['title'=>($row ? 'Cập nhật ' : 'Thêm ') . $this->config['title'], 'config'=>$this->config, 'row'=>$row, 'options'=>$this->allOptions()]);
    }

    public function show(int $id): void
    {
        if ($this->name !== 'employees') redirect(['page'=>$this->name]);
        $employee = Database::fetch('SELECT e.*, d.name department_name, p.name position_name, p.base_salary, p.allowance FROM employees e LEFT JOIN departments d ON d.id=e.department_id LEFT JOIN positions p ON p.id=e.position_id WHERE e.id=?', [$id]);
        if (!$employee) { flash('danger', 'Không tìm thấy nhân viên.'); redirect(['page'=>'employees']); }
        $attendance = Database::all('SELECT * FROM attendance WHERE employee_id=? ORDER BY work_date DESC LIMIT 8', [$id]);
        $payrolls = Database::all('SELECT * FROM payrolls WHERE employee_id=? ORDER BY salary_month DESC LIMIT 8', [$id]);
        view('modules/show_employee', ['title'=>'Chi tiết nhân viên', 'employee'=>$employee, 'attendance'=>$attendance, 'payrolls'=>$payrolls]);
    }

    public function store(): void
    {
        verify_csrf();
        $data = $this->payload(false);
        $errors = $this->validate($data, 0, false);
        if ($errors) { $_SESSION['old']=$data; flash('danger', implode('<br>', $errors)); redirect(['page'=>$this->name,'action'=>'create']); }
        $this->beforeSave($data, false);
        $cols = array_keys($data);
        $placeholders = implode(',', array_fill(0, count($cols), '?'));
        Database::query("INSERT INTO {$this->config['table']} (" . implode(',', $cols) . ") VALUES ({$placeholders})", array_values($data));
        unset($_SESSION['old']);
        flash('success', 'Thêm dữ liệu thành công.');
        redirect(['page'=>$this->name]);
    }

    public function update(int $id): void
    {
        verify_csrf();
        $data = $this->payload(true);
        $errors = $this->validate($data, $id, true);
        if ($errors) { $_SESSION['old']=$data; flash('danger', implode('<br>', $errors)); redirect(['page'=>$this->name,'action'=>'edit','id'=>$id]); }
        $this->beforeSave($data, true);
        if ($this->name === 'users' && ($data['password'] ?? '') === '') unset($data['password']);
        $sets = implode(',', array_map(fn($c)=>"$c=?", array_keys($data)));
        $params = array_values($data); $params[] = $id;
        Database::query("UPDATE {$this->config['table']} SET {$sets} WHERE id=?", $params);
        unset($_SESSION['old']);
        flash('success', 'Cập nhật dữ liệu thành công.');
        redirect(['page'=>$this->name]);
    }

    public function delete(int $id): void
    {
        verify_csrf();
        if ($this->name === 'users' && $id === (int)($_SESSION['user_id'] ?? 0)) {
            flash('danger', 'Không thể xóa chính tài khoản đang đăng nhập.');
            redirect(['page'=>'users']);
        }
        Database::query("DELETE FROM {$this->config['table']} WHERE id=?", [$id]);
        flash('success', 'Đã xóa dữ liệu.');
        redirect(['page'=>$this->name]);
    }

    private function payload(bool $isUpdate): array
    {
        $data = [];
        foreach ($this->config['fields'] as $name => $field) {
            $v = trim((string)($_POST[$name] ?? ''));
            if ($isUpdate && $this->name === 'users' && $name === 'password' && $v === '') { $data[$name] = ''; continue; }
            $data[$name] = $v === '' ? null : $v;
        }
        return $data;
    }

    private function validate(array $data, int $id, bool $isUpdate): array
    {
        $errors = [];
        foreach ($this->config['fields'] as $name => $field) {
            $required = ($field['required'] ?? false) || (!$isUpdate && ($field['required_create'] ?? false));
            if ($required && ($data[$name] === null || $data[$name] === '')) $errors[] = $field['label'] . ' không được để trống.';
            if (($field['type'] ?? '') === 'email' && !empty($data[$name]) && !filter_var($data[$name], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ.';
            if (($field['unique'] ?? false) && !empty($data[$name])) {
                $exists = Database::fetch("SELECT id FROM {$this->config['table']} WHERE {$name}=? AND id<>? LIMIT 1", [$data[$name], $id]);
                if ($exists) $errors[] = $field['label'] . ' đã tồn tại.';
            }
        }
        if ($this->name === 'payrolls') {
            foreach (['base_salary','allowance','bonus','deduction'] as $f) if ((float)($data[$f] ?? 0) < 0) $errors[] = 'Số tiền không hợp lệ.';
            $exists = Database::fetch('SELECT id FROM payrolls WHERE employee_id=? AND salary_month=? AND id<>? LIMIT 1', [(int)$data['employee_id'], $data['salary_month'], $id]);
            if ($exists) $errors[] = 'Nhân viên đã có bảng lương trong tháng này.';
        }
        if ($this->name === 'attendance') {
            $exists = Database::fetch('SELECT id FROM attendance WHERE employee_id=? AND work_date=? AND id<>? LIMIT 1', [(int)$data['employee_id'], $data['work_date'], $id]);
            if ($exists) $errors[] = 'Nhân viên đã có chấm công trong ngày này.';
        }
        return $errors;
    }

    private function beforeSave(array &$data, bool $isUpdate): void
    {
        foreach ($this->config['fields'] as $name => $field) {
            if (($field['type'] ?? '') === 'number' && ($data[$name] === null || $data[$name] === '')) {
                $data[$name] = 0;
            }
        }
        if ($this->name === 'users' && !empty($data['password'])) $data['password'] = password_hash((string)$data['password'], PASSWORD_DEFAULT);
        if ($this->name === 'payrolls') {
            foreach (['base_salary','allowance','bonus','deduction'] as $f) {
                $data[$f] = (float)($data[$f] ?? 0);
            }
            $data['net_salary'] = max(0, $data['base_salary'] + $data['allowance'] + $data['bonus'] - $data['deduction']);
        }
    }

    private function where(): array
    {
        $where = []; $params = []; $alias = $this->config['alias'] ?? 't';
        $q = trim($_GET['q'] ?? '');
        if ($q !== '' && !empty($this->config['search'])) {
            $where[] = '(' . implode(' OR ', array_map(fn($c)=>"$c LIKE ?", $this->config['search'])) . ')';
            foreach ($this->config['search'] as $_) $params[] = "%{$q}%";
        }
        foreach (($this->config['filters'] ?? []) as $name => $filter) {
            if (($_GET[$name] ?? '') !== '') {
                $where[] = "{$alias}.{$name} = ?";
                $params[] = $_GET[$name];
            }
        }
        return [$where ? 'WHERE ' . implode(' AND ', $where) : '', $params];
    }

    private function allOptions(): array
    {
        return [
            'departments' => Database::all('SELECT id, name label FROM departments ORDER BY name'),
            'positions' => Database::all('SELECT id, name label FROM positions ORDER BY name'),
            'employees' => Database::all("SELECT id, CONCAT(employee_code, ' - ', full_name) label FROM employees ORDER BY full_name"),
            'employees_salary' => Database::all("SELECT e.id, CONCAT(e.employee_code, ' - ', e.full_name) label, COALESCE(p.base_salary,0) base_salary, COALESCE(p.allowance,0) allowance FROM employees e LEFT JOIN positions p ON p.id=e.position_id ORDER BY e.full_name"),
        ];
    }
}
