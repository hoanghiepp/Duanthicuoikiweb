<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? APP_TITLE) ?> - <?= e(APP_TITLE) ?></title>
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body>
<?php if (!empty($noShell)): ?>
    <?= $content ?>
<?php else: ?>
<div class="app-shell">
    <?php require BASE_PATH . '/app/views/layouts/sidebar.php'; ?>
    <main class="main-content">
        <?php require BASE_PATH . '/app/views/layouts/topbar.php'; ?>
        <section class="page-content">
            <?php foreach (['success','danger','warning'] as $type): if ($msg = flash($type)): ?>
                <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
            <?php endif; endforeach; ?>
            <?= $content ?>
        </section>
    </main>
</div>
<?php endif; ?>
<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
