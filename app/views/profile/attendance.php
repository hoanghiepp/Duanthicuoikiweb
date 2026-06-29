<section class="card"><div class="card-header"><div><h2>Chấm công của tôi</h2><p>Lịch sử chấm công cá nhân.</p></div></div>
<div class="table-wrap"><table><thead><tr><th>Ngày</th><th>Trạng thái</th><th>Giờ vào</th><th>Giờ ra</th><th>Ghi chú</th></tr></thead><tbody>
<?php foreach ($rows as $r): ?><tr><td><?= e($r['work_date']) ?></td><td><span class="badge <?= e($r['status']) ?>"><?= e(attendance_label($r['status'])) ?></span></td><td><?= e($r['check_in'] ?? '-') ?></td><td><?= e($r['check_out'] ?? '-') ?></td><td><?= e($r['note'] ?? '-') ?></td></tr><?php endforeach; ?>
<?php if (!$rows): ?><tr><td colspan="5" class="empty-state">Chưa có dữ liệu.</td></tr><?php endif; ?>
</tbody></table></div></section>
