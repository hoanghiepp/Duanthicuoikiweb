<section class="card form-card">
<div class="card-header"><div><h2><?= e($title) ?></h2><p>Điền thông tin bên dưới rồi bấm lưu.</p></div></div>
<form class="form-grid" method="post" action="<?= app_url(['page'=>$config['page'],'action'=>$row?'update':'store','id'=>$row['id'] ?? null]) ?>">
<?= csrf_field() ?>
<?php foreach ($config['fields'] as $name=>$field): $value = $_SESSION['old'][$name] ?? ($row[$name] ?? ''); ?>
<label class="<?= ($field['type'] ?? '') === 'textarea' ? 'span-2' : '' ?>"><?= e($field['label']) ?> <?= (($field['required'] ?? false) || (!$row && ($field['required_create'] ?? false))) ? '<b>*</b>' : '' ?>
    <?php if (($field['type'] ?? '') === 'textarea'): ?>
        <textarea name="<?= e($name) ?>" rows="3"><?= e($value) ?></textarea>
    <?php elseif (($field['type'] ?? '') === 'select'): ?>
        <select name="<?= e($name) ?>"><?php foreach ($field['options'] as $k=>$v): ?><option value="<?= e($k) ?>" <?= (string)$value===(string)$k?'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?></select>
    <?php elseif (($field['type'] ?? '') === 'select_table'): ?>
        <select name="<?= e($name) ?>" <?= ($field['required'] ?? false) ? 'required' : '' ?>><option value="">-- Chọn --</option><?php foreach ($options[$field['source']] ?? [] as $op): ?><option value="<?= $op['id'] ?>" data-base="<?= e($op['base_salary'] ?? '') ?>" data-allowance="<?= e($op['allowance'] ?? '') ?>" <?= (string)$value===(string)$op['id']?'selected':'' ?>><?= e($op['label']) ?></option><?php endforeach; ?></select>
    <?php else: ?>
        <input type="<?= e($field['type'] ?? 'text') ?>" name="<?= e($name) ?>" value="<?= e($value) ?>" <?= (($field['required'] ?? false) || (!$row && ($field['required_create'] ?? false))) ? 'required' : '' ?>>
    <?php endif; ?>
</label>
<?php endforeach; ?>
<div class="form-actions span-2"><a class="btn btn-light" href="<?= app_url(['page'=>$config['page']]) ?>">Quay lại</a><button class="btn btn-primary">Lưu dữ liệu</button></div>
</form>
</section>
<?php unset($_SESSION['old']); ?>
