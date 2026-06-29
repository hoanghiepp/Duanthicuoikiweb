<div class="stats-grid">
    <div class="stat-card primary"><span>Tổng nhân viên</span><strong><?= $stats['employees'] ?></strong></div>
    <div class="stat-card success"><span>Phòng ban</span><strong><?= $stats['departments'] ?></strong></div>
    <div class="stat-card warning"><span>Chức vụ</span><strong><?= $stats['positions'] ?></strong></div>
    <div class="stat-card danger"><span>Có mặt hôm nay</span><strong><?= $stats['today_present'] ?></strong></div>
</div>
<div class="content-grid two-cols">
<section class="card">
    <div class="card-header"><div><h2>Nhân viên mới</h2><p>Danh sách nhân sự gần đây</p></div><a class="btn btn-primary" href="<?= app_url(['page'=>'employees','action'=>'create']) ?>">+ Thêm</a></div>
    <div class="table-wrap"><table><thead><tr><th>Mã</th><th>Họ tên</th><th>Phòng ban</th><th>Trạng thái</th></tr></thead><tbody>
        <?php foreach ($recent as $r): ?><tr><td><?= e($r['employee_code']) ?></td><td><?= e($r['full_name']) ?></td><td><?= e($r['department_name'] ?? '-') ?></td><td><span class="badge <?= e($r['status']) ?>"><?= e(employee_status_label($r['status'])) ?></span></td></tr><?php endforeach; ?>
    </tbody></table></div>
</section>
<section class="card">
    <div class="card-header"><div><h2>Thống kê phòng ban</h2><p>Số nhân viên theo từng phòng</p></div></div>
    <div class="mini-list"><?php foreach ($deptStats as $d): ?><div class="mini-row"><span><?= e($d['name']) ?></span><strong><?= (int)$d['total'] ?> nhân viên</strong></div><?php endforeach; ?></div>
</section>
</div>
