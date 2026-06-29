<section class="card"><div class="card-header"><div><h2>Lương của tôi</h2><p>Lịch sử bảng lương cá nhân.</p></div></div>
<div class="table-wrap"><table><thead><tr><th>Tháng</th><th>Lương CB</th><th>Phụ cấp</th><th>Thưởng</th><th>Khấu trừ</th><th>Thực nhận</th><th>Ghi chú</th></tr></thead><tbody>
<?php foreach ($rows as $r): ?><tr><td><?= e($r['salary_month']) ?></td><td><?= money($r['base_salary']) ?></td><td><?= money($r['allowance']) ?></td><td><?= money($r['bonus']) ?></td><td><?= money($r['deduction']) ?></td><td><strong><?= money($r['net_salary']) ?></strong></td><td><?= e($r['note'] ?? '-') ?></td></tr><?php endforeach; ?>
<?php if (!$rows): ?><tr><td colspan="7" class="empty-state">Chưa có dữ liệu.</td></tr><?php endif; ?>
</tbody></table></div></section>
