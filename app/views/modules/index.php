<section class="card">
    <div class="card-header">
        <div><h2><?= e($title) ?></h2><p>Tìm kiếm, lọc, phân trang, thêm, sửa, xóa dữ liệu.</p></div>
        <a class="btn btn-primary" href="<?= app_url(['page'=>$config['page'],'action'=>'create']) ?>">+ Thêm mới</a>
    </div>
    <form class="toolbar filter-bar" method="get">
        <input type="hidden" name="page" value="<?= e($config['page']) ?>">
        <input type="search" name="q" value="<?= e($_GET['q'] ?? '') ?>" placeholder="Tìm kiếm...">
        <?php foreach (($config['filters'] ?? []) as $name=>$filter): ?>
            <?php if (($filter['type'] ?? '') === 'date' || ($filter['type'] ?? '') === 'month'): ?>
                <input type="<?= e($filter['type']) ?>" name="<?= e($name) ?>" value="<?= e($_GET[$name] ?? '') ?>">
            <?php elseif (!empty($filter['source'])): ?>
                <select name="<?= e($name) ?>"><option value=""><?= e($filter['label']) ?></option><?php foreach ($options[$filter['source']] ?? [] as $op): ?><option value="<?= $op['id'] ?>" <?= (string)($_GET[$name] ?? '')===(string)$op['id']?'selected':'' ?>><?= e($op['label']) ?></option><?php endforeach; ?></select>
            <?php else: ?>
                <select name="<?= e($name) ?>"><option value=""><?= e($filter['label']) ?></option><?php foreach ($filter['options'] as $k=>$v): ?><option value="<?= e($k) ?>" <?= ($_GET[$name] ?? '')===$k?'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?></select>
            <?php endif; ?>
        <?php endforeach; ?>
        <button class="btn btn-primary">Lọc</button><a class="btn btn-light" href="<?= app_url(['page'=>$config['page']]) ?>">Làm mới</a>
    </form>
    <div class="table-wrap"><table><thead><tr><?php foreach ($config['columns'] as $label): ?><th><?= e($label) ?></th><?php endforeach; ?><th>Thao tác</th></tr></thead><tbody>
    <?php foreach ($rows as $row): ?><tr>
        <?php foreach ($config['columns'] as $col=>$label): ?>
            <td>
                <?php if (in_array($col, ['base_salary','allowance','bonus','deduction','net_salary'], true)): ?><?= money($row[$col] ?? 0) ?>
                <?php elseif ($col === 'status' && $config['page']==='employees'): ?><span class="badge <?= e($row[$col]) ?>"><?= e(employee_status_label($row[$col])) ?></span>
                <?php elseif ($col === 'status' && $config['page']==='attendance'): ?><span class="badge <?= e($row[$col]) ?>"><?= e(attendance_label($row[$col])) ?></span>
                <?php elseif ($col === 'role'): ?><?= e(role_label($row[$col])) ?>
                <?php else: ?><?= e($row[$col] ?? '-') ?><?php endif; ?>
            </td>
        <?php endforeach; ?>
        <td class="actions">
            <?php if ($config['page'] === 'employees'): ?><a class="btn btn-light" href="<?= app_url(['page'=>$config['page'],'action'=>'show','id'=>$row['id']]) ?>">Xem</a><?php endif; ?>
            <a class="btn btn-edit" href="<?= app_url(['page'=>$config['page'],'action'=>'edit','id'=>$row['id']]) ?>">Sửa</a>
            <form method="post" action="<?= app_url(['page'=>$config['page'],'action'=>'delete','id'=>$row['id']]) ?>" onsubmit="return confirm('Bạn chắc chắn muốn xóa?')"><?= csrf_field() ?><button class="btn btn-delete">Xóa</button></form>
        </td>
    </tr><?php endforeach; ?>
    <?php if (!$rows): ?><tr><td colspan="<?= count($config['columns']) + 1 ?>" class="empty-state">Chưa có dữ liệu.</td></tr><?php endif; ?>
    </tbody></table></div>
    <?php render_pagination($pagination); ?>
</section>
