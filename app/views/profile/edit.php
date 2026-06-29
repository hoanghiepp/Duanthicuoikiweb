<section class="card form-card"><div class="card-header"><div><h2>Cập nhật hồ sơ cá nhân</h2><p>Cập nhật thông tin liên hệ cơ bản.</p></div></div>
<form class="form-grid" method="post" action="<?= app_url(['page'=>'profile','action'=>'update']) ?>">
<?= csrf_field() ?>
<label>Họ tên <input name="full_name" required value="<?= e($user['full_name']) ?>"></label>
<label>Email <input type="email" name="email" value="<?= e($user['email'] ?? '') ?>"></label>
<label>Số điện thoại <input name="phone" value="<?= e($employee['phone'] ?? '') ?>"></label>
<label class="span-2">Địa chỉ <textarea name="address" rows="3"><?= e($employee['address'] ?? '') ?></textarea></label>
<div class="form-actions span-2"><a class="btn btn-light" href="<?= app_url(['page'=>'profile']) ?>">Quay lại</a><button class="btn btn-primary">Lưu hồ sơ</button></div>
</form></section>
