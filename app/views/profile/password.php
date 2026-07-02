<section class="card form-card"><div class="card-header"><div><h2>Đổi mật khẩu</h2><p>Mật khẩu mới tối thiểu 6 ký tự.</p></div></div>
<form class="form-grid" method="post" action="<?= app_url(['page'=>'profile','action'=>'update-password']) ?>">
<?= csrf_field() ?>
<label>Mật khẩu hiện tại <input type="password" name="current_password" required></label>
<label>Mật khẩu mới <input type="password" name="new_password" required></label>
<label>Xác nhận mật khẩu mới <input type="password" name="confirm_password" required></label>
<div class="form-actions span-2"><a class="btn btn-light" href="<?= app_url(['page'=>'profile']) ?>">Quay lại</a><button class="btn btn-primary">Đổi mật khẩu</button></div>
</form></section>
