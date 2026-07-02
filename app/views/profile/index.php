<?php
$user = $user ?? [];
$employee = $employee ?? null;
$attendance = $attendance ?? [];
$payrolls = $payrolls ?? [];
?>
<section class="card">
<div class="card-header"><div><h2><?= e($user['full_name'] ?? '-') ?></h2><p><?= e(role_label($user['role'] ?? null)) ?> · <?= e($user['email'] ?? '-') ?></p></div><div class="actions"><a class="btn btn-edit" href="<?= app_url(['page'=>'profile','action'=>'edit']) ?>">Sửa hồ sơ</a><a class="btn btn-light" href="<?= app_url(['page'=>'profile','action'=>'password']) ?>">Đổi mật khẩu</a></div></div>
<?php if ($employee): ?>
<div class="info-grid">
<div><span>Mã nhân viên</span><strong><?= e($employee['employee_code'] ?? '-') ?></strong></div>
<div><span>Phòng ban</span><strong><?= e($employee['department_name'] ?? '-') ?></strong></div>
<div><span>Chức vụ</span><strong><?= e($employee['position_name'] ?? '-') ?></strong></div>
<div><span>Số điện thoại</span><strong><?= e($employee['phone'] ?? '-') ?></strong></div>
<div><span>Ngày vào làm</span><strong><?= e($employee['hire_date'] ?? '-') ?></strong></div>
<div><span>Trạng thái</span><strong><?= e(employee_status_label($employee['status'] ?? null)) ?></strong></div>
<div class="span-2"><span>Địa chỉ</span><strong><?= e($employee['address'] ?? '-') ?></strong></div>
</div>
<?php else: ?><div class="empty-state">Tài khoản chưa liên kết hồ sơ nhân viên.</div><?php endif; ?>
</section>
<div class="content-grid two-cols">
<section class="card"><h2>Chấm công gần đây</h2><div class="mini-list"><?php foreach ($attendance as $a): ?><div class="mini-row"><span><?= e($a['work_date'] ?? '-') ?></span><strong><?= e(attendance_label($a['status'] ?? null)) ?></strong></div><?php endforeach; ?></div></section>
<section class="card"><h2>Lương gần đây</h2><div class="mini-list"><?php foreach ($payrolls as $p): ?><div class="mini-row"><span><?= e($p['salary_month'] ?? '-') ?></span><strong><?= money($p['net_salary'] ?? 0) ?></strong></div><?php endforeach; ?></div></section>
</div>
