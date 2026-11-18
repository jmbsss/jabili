<?php
require __DIR__ . '/config.php';
if (current_user()) redirect('index.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $stmt = db()->prepare('SELECT id,name,username,password_hash,role FROM users WHERE username=?');
    $stmt->execute([trim($_POST['username'] ?? '')]);
    $user = $stmt->fetch();
    if ($user && password_verify($_POST['password'] ?? '', $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id'=>$user['id'],'name'=>$user['name'],'username'=>$user['username'],'role'=>$user['role']];
        redirect('index.php');
    }
    $error = 'Incorrect username or password.';
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sign in · Jobili</title><link rel="stylesheet" href="assets/style.css"></head><body class="login-bg"><main class="login-card"><div class="brand-mark">J</div><h1>Welcome to Jobili</h1><p class="muted">Store Management System · BSCS</p><?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label>Username</label><input name="username" autocomplete="username" required autofocus><label>Password</label><input type="password" name="password" autocomplete="current-password" required><button class="btn primary full">Sign in</button></form><p class="small muted">First time? Import install.sql, then open <a href="setup.php">setup.php</a>.</p></main></body></html>
