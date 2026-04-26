<?php
function base_path(): string {
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $dir = rtrim(str_replace('\\','/', dirname($script)), '/');
    // Si el front controller vive en /public, recorta ese segmento para no exponerlo en las URLs
    if (substr($dir, -7) === '/public') {
        $dir = rtrim(substr($dir, 0, -7), '/');
    }
    return ($dir === '' || $dir === '/') ? '' : $dir;
}

function route_to(string $name): string {
    $base = base_path();
    return ($base ? $base : '') . '/?r=' . $name;
}

function asset(string $path): string {
    $base = base_path();
    return ($base ? $base : '') . '/' . ltrim($path, '/');
}

function view(string $path, array $data = []) {
    extract($data);
    include __DIR__ . '/views/layouts/header.php';
    include __DIR__ . '/views/' . $path . '.php';
    include __DIR__ . '/views/layouts/footer.php';
}

function flash(?string $type = null, ?string $message = null) {
    if ($type !== null) {
        $_SESSION['flash'] = ['type'=>$type,'message'=>$message];
    }
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

function current_user() {
    return $_SESSION['user'] ?? null;
}
?>
