<?php
require __DIR__ . '/config.php';
$message = '';
$error = '';
try {
    // Import install.sql in phpMyAdmin first, then open this page to create initial accounts.
    $count = (int)db()->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf();
        if ($count > 0) throw new RuntimeException('Accounts already exist. Delete setup.php after installation.');
        $ownerName = trim($_POST['owner_name'] ?? 'BSCS');
        $ownerUser = trim($_POST['owner_username'] ?? 'owner');
        $ownerPass = $_POST['owner_password'] ?? '';
        $cashierName = trim($_POST['cashier_name'] ?? 'Cashier');
        $cashierUser = trim($_POST['cashier_username'] ?? 'cashier');
        $cashierPass = $_POST['cashier_password'] ?? '';
        if (strlen($ownerPass) < 8 || strlen($cashierPass) < 8) throw new RuntimeException('Both passwords must be at least 8 characters.');
        if (!$ownerUser || !$cashierUser || $ownerUser === $cashierUser) throw new RuntimeException('Use two different usernames.');
        $pdo = db();
        $stmt = $pdo->prepare('INSERT INTO users(name,username,password_hash,role) VALUES(?,?,?,?)');
        $stmt->execute([$ownerName,$ownerUser,password_hash($ownerPass,PASSWORD_DEFAULT),'owner']);
        $stmt->execute([$cashierName,$cashierUser,password_hash($cashierPass,PASSWORD_DEFAULT),'cashier']);
        $message = 'Accounts created. Sign in, then delete setup.php from your project folder.';
        $count = 2;
    }
} catch (Throwable $ex) { $error = $ex->getMessage(); }
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Jobili setup</title><link rel="stylesheet" href="assets/style.css"></head><body class="login-bg"><main class="login-card wide"><div class="brand-mark">J</div><h1>Set up Jobili</h1><p class="muted">Create your owner and cashier accounts.</p><?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?><?php if($message): ?><div class="alert success"><?=e($message)?> <a href="login.php">Go to login</a></div><?php elseif($count===0): ?><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><h3>Owner account</h3><label>Owner display name</label><input name="owner_name" value="BSCS" required><label>Username</label><input name="owner_username" value="owner" required><label>Password (8+ characters)</label><input type="password" name="owner_password" minlength="8" required><h3>Cashier account</h3><label>Cashier display name</label><input name="cashier_name" value="Cashier" required><label>Username</label><input name="cashier_username" value="cashier" required><label>Password (8+ characters)</label><input type="password" name="cashier_password" minlength="8" required><button class="btn primary full">Create accounts</button></form><?php else: ?><div class="alert success">Accounts already exist. <a href="login.php">Sign in</a></div><?php endif; ?></main></body></html>
