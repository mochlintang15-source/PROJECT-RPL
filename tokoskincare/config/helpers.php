<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rupiah($number): string
{
    return 'Rp ' . number_format((float) $number, 0, ',', '.');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function indexUrl(string $query = ''): string
{
    $scriptDir = basename(dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $prefix = in_array($scriptDir, ['auth', 'process', 'admin'], true) ? '../' : '';
    return $prefix . 'index.php' . $query;
}

function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user']);
}

function isAdmin(): bool
{
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        redirect(indexUrl('?page=login&error=login_required')); 
    }
}

function requireAdmin(): void
{
    requireLogin();
    if (!isAdmin()) {
        redirect(indexUrl('?page=katalog&error=forbidden')); 
    }
}

function getParam(string $key, $default = '')
{
    return $_GET[$key] ?? $default;
}

function postParam(string $key, $default = '')
{
    return $_POST[$key] ?? $default;
}

function uploadProductImage(string $fieldName, string $oldImage = '-'): string
{
    if (empty($_FILES[$fieldName]['name']) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return $oldImage ?: '-';
    }

    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return $oldImage ?: '-';
    }

    $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
    $originalName = $_FILES[$fieldName]['name'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExt, true)) {
        return $oldImage ?: '-';
    }

    $targetDir = __DIR__ . '/../uploads/products/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0775, true);
    }

    $newName = 'produk_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $targetPath = $targetDir . $newName;

    if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetPath)) {
        if ($oldImage !== '-' && $oldImage !== '' && is_file($targetDir . $oldImage)) {
            @unlink($targetDir . $oldImage);
        }
        return $newName;
    }

    return $oldImage ?: '-';
}
