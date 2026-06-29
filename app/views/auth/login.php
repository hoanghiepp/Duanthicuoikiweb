<section class="auth-page">
<form class="auth-card" method="post" action="<?= app_url(['page'=>'login']) ?>">
    <?= csrf_field() ?>
    <div class="brand big"><span class="brand-icon">HR</span><span><strong>HR Management</strong><small>Đăng nhập</small></span></div>
    <h1>Chào mừng trở lại</h1>
    <p>Đăng nhập để quản lý nhân sự.</p>
    <?php foreach (['success','danger','warning'] as $type): if ($msg = flash($type)): ?><div class="alert alert-<?= $type ?>"><?= $msg ?></div><?php endif; endforeach; ?>
    <label>Tên đăng nhập hoặc email<input name="username" required placeholder="admin"></label>
    <label>Mật khẩu<input type="password" name="password" required placeholder="admin123"></label>
    <button class="btn btn-primary full">Đăng nhập</button>
    <div class="demo-box"><b>Tài khoản mẫu</b><br>Admin: <code>admin/admin123</code><br>HR: <code>hr/hr123</code><br>Nhân viên: <code>employee/employee123</code></div>
</form>
</section>
