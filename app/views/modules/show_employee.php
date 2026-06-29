<?php if (!$employee): ?><section class="card empty-state">Không tìm thấy nhân viên.</section><?php return; endif; ?>
<section class="card">
<div class="card-header"><div><h2><?= e($employee['full_name']) ?></h2><p><?= e($employee['employee_code']) ?> · <?= e($employee['department_name'] ?? '-') ?> · <?= e($employee['position_name'] ?? '-') ?></p></div><a class="btn btn-edit" href="<?= app_url(['page'=>'employees','action'=>'edit','id'=>$employee['id']]) ?>">Sửa hồ sơ</a></div>
<div class="info-grid">
<div><span>Email</span><strong><?= e($employee['email'] ?? '-') ?></strong></div>
<div><span>SĐT</span><strong><?= e($employee['phone'] ?? '-') ?></strong></div>
<div><span>Ngày vào làm</span><strong><?= e($employee['hire_date'] ?? '-') ?></strong></div>
<div><span>Trạng thái</span><strong><?= e(employee_status_label($employee['status'])) ?></strong></div>
<div><span>Lương cơ bản</span><strong><?= money($employee['base_salary'] ?? 0) ?></strong></div>
<div class="span-2"><span>Địa chỉ</span><strong><?= e($employee['address'] ?? '-') ?></strong></div>
</div>
</section>
<div class="content-grid two-cols">
<section class="card"><h2>Chấm công gần đây</h2><div class="mini-list"><?php foreach ($attendance as $a): ?><div class="mini-row"><span><?= e($a['work_date']) ?></span><strong><?= e(attendance_label($a['status'])) ?></strong></div><?php endforeach; ?></div></section>
<section class="card"><h2>Lương gần đây</h2><div class="mini-list"><?php foreach ($payrolls as $p): ?><div class="mini-row"><span><?= e($p['salary_month']) ?></span><strong><?= money($p['net_salary']) ?></strong></div><?php endforeach; ?></div></section>
</div>
