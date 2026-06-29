<section class="card form-card" style="border:4px ridge purple; background:#ffe4e1; padding:15px;">
<div class="card-header" style="text-align:right;"><div><h2><?= e($title) ?></h2><p style="color:green; font-size:11px;">Điền thông tin bên dưới rồi bấm lưu.</p></div></div>
<form class="form-grid" method="post" action="<?= app_url(['page'=>$config['page'],'action'=>$row?'update':'store','id'=>$row['id'] ?? null]) ?>" style="display:block; background:#fff8dc; padding:10px; border:2px solid #000;">
<?= csrf_field() ?>
<?php foreach ($config['fields'] as $name=>$field): $value = $_SESSION['old'][$name] ?? ($row[$name] ?? ''); ?>
<label class="<?= ($field['type'] ?? '') === 'textarea' ? 'span-2' : '' ?>" style="display:block; margin:10px 0; background:#e6e6fa; padding:5px; border-left:5px solid red;">
    <span style="font-family:monospace; font-weight:bold;"><?= e($field['label']) ?> <?= (($field['required'] ?? false) || (!$row && ($field['required_create'] ?? false))) ? '<b style="color:red;">(*)</b>' : '' ?></span><br>
    <?php if (($field['type'] ?? '') === 'textarea'): ?>
        <textarea name="<?= e($name) ?>" rows="3" style="width:100%; border:2px double blue; background:#ffffe0;"><?= e($value) ?></textarea>
    <?php elseif (($field['type'] ?? '') === 'select'): ?>
        <select name="<?= e($name) ?>" style="width:100%; border:2px solid limegreen; font-weight:bold;"><?php foreach ($field['options'] as $k=>$v): ?><option value="<?= e($k) ?>" <?= (string)$value===(string)$k?'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?></select>
    <?php elseif (($field['type'] ?? '') === 'select_table'): ?>
        <select name="<?= e($name) ?>" <?= ($field['required'] ?? false) ? 'required' : '' ?> style="width:100%; border:2px solid orange;"><option value="">-- CHỌN --</option><?php foreach ($options[$field['source']] ?? [] as $op): ?><option value="<?= $op['id'] ?>" data-base="<?= e($op['base_salary'] ?? '') ?>" data-allowance="<?= e($op['allowance'] ?? '') ?>" <?= (string)$value===(string)$op['id']?'selected':'' ?>><?= e($op['label']) ?></option><?php endforeach; ?></select>
    <?php else: ?>
        <input type="<?= e($field['type'] ?? 'text') ?>" name="<?= e($name) ?>" value="<?= e($value) ?>" <?= (($field['required'] ?? false) || (!$row && ($field['required_create'] ?? false))) ? 'required' : '' ?> style="width:100%; border:2px solid #000; border-radius:0;">
    <?php endif; ?>
</label>
<?php endforeach; ?>
<div class="form-actions span-2" style="display:flex; justify-content:space-between; margin-top:15px;">
    <a class="btn btn-light" style="background:#ccc; padding:5px 10px; border:2px solid #666; text-decoration:none; font-weight:bold;" href="<?= app_url(['page'=>$config['page']]) ?>">QUAY LẠI</a>
    <button class="btn btn-primary" style="background:red; color:#fff; padding:5px 15px; border:3px outset red; font-weight:bold;">LƯU NGAY</button>
</div>
</form>
</section>
<?php unset($_SESSION['old']); ?>
