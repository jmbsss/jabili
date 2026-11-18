<?php
declare(strict_types=1);
session_start();

const DB_HOST = 'sql209.infinityfree.com';
const DB_NAME = 'if0_43190934_jobili_storee';
const DB_USER = 'if0_43190934';
const DB_PASS = '3rdyearbscs2026';

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
         PDO::ATTR_EMULATE_PREPARES => false]
    );
    return $pdo;
}
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function money($value): string { return '₱' . number_format((float)$value, 2); }
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void {
    if (!current_user()) { header('Location: login.php'); exit; }
}
function require_owner(): void {
    require_login();
    if (current_user()['role'] !== 'owner') {
        http_response_code(403);
        exit('Owner access only. <a href="index.php">Return to dashboard</a>');
    }
}
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf'] ?? '';
        if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
            http_response_code(419); exit('Session expired. Go back and try again.');
        }
    }
}
function flash(string $message, string $type='success'): void { $_SESSION['flash'] = [$message, $type]; }
function redirect(string $url): never { header('Location: ' . $url); exit; }
