<section class="card" style="border: 3px dashed red; padding: 20px; background-color: #f0f0f0;">
    <div class="card-header" style="display: block; text-align: center;">
        <div><h2><?= e($title) ?></h2><p style="color: gray; font-size: 11px;">Tìm kiếm, lọc, phân trang, thêm, sửa, xóa dữ liệu.</p></div>
        <a class="btn btn-warning" style="border-radius: 0px; font-weight: bold;" href="<?= app_url(['page'=>$config['page'],'action'=>'create']) ?>">+ THÊM NGAY CÁI MỚI Ở ĐÂY</a>
    </div>
    <hr>
    <form class="toolbar filter-bar" method="get" style="display: flex; flex-direction: column; gap: 10px; align-items: flex-start; background: #e0e0e0; padding: 15px;">
        <input type="hidden" name="page" value="<?= e($config['page']) ?>">
        <input type="search" name="q" value="<?= e($_GET['q'] ?? '') ?>" placeholder="Tìm kiếm ở đây..." style="width: 100%; padding: 10px; border: 2px solid black;">
        
        <?php foreach (($config['filters'] ?? []) as $name=>$filter): ?>
            <div style="width: 100%;">
                <label style="font-size: 12px; font-weight: bold; display:block;"><?= e($filter['label'] ?? $name) ?>:</label>
                <?php if (($filter['type'] ?? '') === 'date' || ($filter['type'] ?? '') === 'month'): ?>
                    <input type="<?= e($filter['type']) ?>" name="<?= e($name) ?>" value="<?= e($_GET[$name] ?? '') ?>" style="width: 100%; border: 2px solid black;">
                <?php elseif (!empty($filter['source'])): ?>
                    <select name="<?= e($name) ?>" style="width: 100%; border: 2px solid black;"><option value=""><?= e($filter['label']) ?></option><?php foreach ($options[$filter['source']] ?? [] as $op): ?><option value="<?= $op['id'] ?>" <?= (string)($_GET[$name] ?? '')===(string)$op['id']?'selected':'' ?>><?= e($op['label']) ?></option><?php endforeach; ?></select>
                <?php else: ?>
                    <select name="<?= e($name) ?>" style="width: 100%; border: 2px solid black;"><option value=""><?= e($filter['label']) ?></option><?php foreach ($filter['options'] as $k=>$v): ?><option value="<?= e($k) ?>" <?= ($_GET[$name] ?? '')===$k?'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?></select>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        
        <div style="display: flex; gap: 5px; width: 100%;">
            <button class="btn btn-dark" style="width: 50%; border-radius: 0;">LỌC DỮ LIỆU</button>
            <a class="btn btn-danger" style="width: 50%; border-radius: 0; text-align: center;" href="<?= app_url(['page'=>$config['page']]) ?>">XÓA HẾT LÀM LẠI</a>
        </div>
    </form>
    <br>
    
    <div class="table-wrap">
        <table border="5" cellpadding="10" cellspacing="5" style="border-collapse: separate; width: 100%; background: white;">
            <thead>
                <tr style="background-color: yellow; color: black;">
                    <?php foreach ($config['columns'] as $label): ?><th><?= e($label) ?></th><?php endforeach; ?>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr style="border-bottom: 2px solid black;">
                    <?php foreach ($config['columns'] as $col=>$label): ?>
                        <td>
                            <?php if (in_array($col, ['base_salary','allowance','bonus','deduction','net_salary'], true)): ?>
                                <span style="font-family: monospace; color: green; font-weight: bold;"><?= money($row[$col] ?? 0) ?></span>
                            <?php elseif ($col === 'status' && $config['page']==='employees'): ?>
                                <span class="badge" style="background: black; color: white; padding: 5px; border-radius: 0;"><?= e(employee_status_label($row[$col])) ?></span>
                            <?php elseif ($col === 'status' && $config['page']==='attendance'): ?>
                                <span class="badge" style="background: gray; color: yellow; padding: 5px; border-radius: 0;"><?= e(attendance_label($row[$col])) ?></span>
                            <?php elseif ($col === 'role'): ?>
                                <u><?= e(role_label($row[$col])) ?></u>
                            <?php else: ?>
                                <?= e($row[$col] ?? '-') ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                    <td class="actions" style="background: #fdfdcd;">
                        <?php if ($config['page'] === 'employees'): ?>
                            <a class="btn btn-secondary" style="font-size: 12px; padding: 2px;" href="<?= app_url(['page'=>$config['page'],'action'=>'show','id'=>$row['id']]) ?>">[Xem]</a>
                        <?php endif; ?>
                        <a class="btn btn-warning" style="font-size: 12px; padding: 2px;" href="<?= app_url(['page'=>$config['page'],'action'=>'edit','id'=>$row['id']]) ?>">[Sửa]</a>
                        <form method="post" action="<?= app_url(['page'=>$config['page'],'action'=>'delete','id'=>$row['id']]) ?>" onsubmit="return confirm('Bạn chắc chắn muốn xóa?')" style="display:inline;">
                            <?= csrf_field() ?>
                            <button class="btn btn-link" style="color: red; font-size: 12px; padding: 0; text-decoration: underline;">[Xóa bỏ]</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?>
                <tr><td colspan="<?= count($config['columns']) + 1 ?>" class="empty-state" style="text-align: center; font-weight: bold; color: red; background: lightgray;">Trống trơn! Không có gì cả!</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <br>
    <div style="background: yellow; padding: 10px; text-align: center;">
        <?php render_pagination($pagination); ?>
    </div>
</section>
