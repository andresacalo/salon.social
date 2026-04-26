<?php
require_once __DIR__ . '/Conexion.php';

class Usuario
{
    public static function seedAdmin(): void
    {
        $db = Conexion::get();
        $email = 'admin@gmail.com';
        $stmt = $db->prepare('SELECT COUNT(*) c FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $has = $stmt->get_result()->fetch_assoc()['c'] ?? 0;
        if ($has) return;

        $stmt = $db->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?,?,?,"admin")');
        $name = 'Administrador';
        $pass = password_hash('admin123', PASSWORD_BCRYPT);
        $stmt->bind_param('sss', $name, $email, $pass);
        $stmt->execute();
    }

    public static function findByEmail(string $email): ?array
    {
        $db = Conexion::get();
        $stmt = $db->prepare('SELECT * FROM users WHERE email=? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ?: null;
    }

    public static function findById(int $id): ?array
    {
        $db = Conexion::get();
        $stmt = $db->prepare('SELECT * FROM users WHERE id=? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ?: null;
    }

    public static function getAdmins(): ?array
    {
        $db = Conexion::get();
        $stmt = $db->prepare('SELECT * FROM users WHERE role=? AND active=1 LIMIT 1');
        $role = 'admin';
        $stmt->bind_param('s', $role);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ?: null;
    }

    public static function create(string $name, string $email, string $role, string $password): bool
    {
        $db = Conexion::get();
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $db->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?,?,?,?)');
        $stmt->bind_param('ssss', $name, $email, $hash, $role);
        return $stmt->execute();
    }

    public static function toggle(int $id, int $active): void
    {
        $db = Conexion::get();
        $stmt = $db->prepare('UPDATE users SET active=? WHERE id=?');
        $stmt->bind_param('ii', $active, $id);
        $stmt->execute();
    }

    public static function all(): array
    {
        return Conexion::get()->query('SELECT * FROM users ORDER BY created_at DESC')->fetch_all(MYSQLI_ASSOC);
    }
}
?>
