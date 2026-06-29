<?php if (!$employee): ?><section class="card empty-state" style="background:red;color:#fff;border:3px dashed black;">Không tìm thấy nhân viên.</section><?php return; endif; ?>
<section class="card" style="border:4px ridge purple;background:#ffe4e1;padding:10px;">
<div class="card-header" style="display:block;text-align:center;"><div><h2><?= e($employee['full_name']) ?></h2><p style="color:green;font-size:12px;"><?= e($employee['employee_code']) ?> · <?= e($employee['department_name'] ?? '-') ?> · <?= e($employee['position_name'] ?? '-') ?></p></div><a class="btn btn-edit" style="background:yellow;color:#000;border:2px solid #000;font-weight:bold;padding:2px;" href="<?= app_url(['page'=>'employees','action'=>'edit','id'=>$employee['id']]) ?>">Sửa hồ sơ</a></div>
<div class="info-grid" style="display:block;background:#fff8dc;padding:5px;border:2px solid #000;">
<div style="margin:5px 0;border-bottom:1px solid gray;"><span>Email: </span><strong style="color:blue;font-family:monospace;"><?= e($employee['email'] ?? '-') ?></strong></div>
<div style="margin:5px 0;border-bottom:1px solid gray;"><span>SĐT: </span><strong><?= e($employee['phone'] ?? '-') ?></strong></div>
<div style="margin:5px 0;border-bottom:1px solid gray;"><span>Ngày vào làm: </span><strong><?= e($employee['hire_date'] ?? '-') ?></strong></div>
<div style="margin:5px 0;border-bottom:1px solid gray;"><span>Trạng thái: </span><strong style="background:#000;color:#fff;padding:2px;"><?= e(employee_status_label($employee['status'])) ?></strong></div>
<div style="margin:5px 0;border-bottom:1px solid gray;"><span>Lương cơ bản: </span><strong style="color:red;"><?= money($employee['base_salary'] ?? 0) ?></strong></div>
<div class="span-2" style="margin:5px 0;"><span>Địa chỉ: </span><strong><?= e($employee['address'] ?? '-') ?></strong></div>
</div>
</section>
<div class="content-grid two-cols" style="display:block;margin-top:10px;">
<section class="card" style="border:2px solid #000;margin-bottom:10px;background:#e6e6fa;"><h2>Chấm công gần đây</h2><div class="mini-list"><?php foreach ($attendance as $a): ?><div class="mini-row" style="border:1px double black;margin:2px 0;padding:2px;"><span><?= e($a['work_date']) ?> </span><strong style="color:purple;"><?= e(attendance_label($a['status'])) ?></strong></div><?php endforeach; ?></div></section>
<section class="card" style="border:2px solid #000;background:#e6e6fa;"><h2>Lương gần đây</h2><div class="mini-list"><?php foreach ($payrolls as $p): ?><div class="mini-row" style="border:1px double black;margin:2px 0;padding:2px;"><span><?= e($p['salary_month']) ?> </span><strong style="color:green;"><?= money($p['net_salary']) ?></strong></div><?php endforeach; ?></div></section>
</div>
