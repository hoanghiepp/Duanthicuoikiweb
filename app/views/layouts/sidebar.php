<?php $user = Auth::user(); $page = current_page(); ?>
<aside class="sidebar" id="sidebar">
    <a class="brand" href="<?= app_url(['page'=>Auth::hasRole(['employee']) ? 'profile' : 'dashboard']) ?>">
        <span class="brand-icon">HR</span><span><strong>HR Manager</strong><small>Quản lý nhân sự</small></span>
    </a>
    <nav class="sidebar-nav">
        <?php if (Auth::hasRole(['admin','hr'])): ?>
        <div class="nav-label">Quản trị</div>
        <?php foreach ([
            'dashboard'=>'📊 Dashboard','employees'=>'👥 Nhân viên','departments'=>'🏢 Phòng ban',
            'positions'=>'💼 Chức vụ','attendance'=>'🕘 Chấm công','payrolls'=>'💰 Bảng lương'
        ] as $p=>$label): ?>
            <a class="nav-link <?= $page===$p?'active':'' ?>" href="<?= app_url(['page'=>$p]) ?>"><?= $label ?></a>
        <?php endforeach; endif; ?>
        <?php if (Auth::hasRole(['admin'])): ?>
            <div class="nav-label">Hệ thống</div>
            <a class="nav-link <?= $page==='users'?'active':'' ?>" href="<?= app_url(['page'=>'users']) ?>">🔐 Tài khoản</a>
        <?php endif; ?>
        <div class="nav-label">Cá nhân</div>
        <a class="nav-link <?= $page==='profile' && current_action()==='index'?'active':'' ?>" href="<?= app_url(['page'=>'profile']) ?>">🙋 Hồ sơ của tôi</a>
        <a class="nav-link <?= current_action()==='attendance'?'active':'' ?>" href="<?= app_url(['page'=>'profile','action'=>'attendance']) ?>">📅 Chấm công của tôi</a>
        <a class="nav-link <?= current_action()==='payrolls'?'active':'' ?>" href="<?= app_url(['page'=>'profile','action'=>'payrolls']) ?>">💵 Lương của tôi</a>
    </nav>
    <div class="sidebar-footer">
        <div class="mini-user"><span class="avatar"><?= e(mb_substr($user['full_name'] ?? 'U', 0, 1)) ?></span><div><strong><?= e($user['full_name'] ?? '') ?></strong><small><?= e(role_label($user['role'] ?? '')) ?></small></div></div>
        <a class="btn btn-ghost full" href="<?= app_url(['page'=>'logout']) ?>">Đăng xuất</a>
    </div>
</aside>
