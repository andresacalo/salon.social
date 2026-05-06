<?php
require_once __DIR__ . '/Conexion.php';

class Reserva
{
    public static function validate(array $data, mysqli $db): ?string
    {
        $eventDate = $data['fecha_evento'] ?? '';
        $start = $data['hora_inicio'] ?? '';
        $end = $data['hora_fin'] ?? '';
        if (!$eventDate || !$start || !$end) return 'Fecha y horarios son obligatorios';

        $now = new DateTime('now');
        $eventDateTime = new DateTime($eventDate . ' ' . $start);
        $diffHours = ($eventDateTime->getTimestamp() - $now->getTimestamp()) / 3600;
        if ($diffHours < 48) return 'Minimo 48 horas de anticipacion';
        if ($diffHours > 90 * 24) return 'Maximo 90 dias de anticipacion';
        if ($start < '12:00' || $end > '23:59' || $start >= $end) return 'Horario permitido 12:00-23:59';
        $dateEsc = $db->real_escape_string($eventDate);
        $existing = $db->query("SELECT id FROM reservas WHERE fecha_evento='{$dateEsc}' AND estado IN ('pendiente','aprobada')")->num_rows;
        if ($existing) return 'Ya existe una reserva para ese dia';
        return null;
    }

    public static function create(int $userId, array $data): void
    {
        $db = Conexion::get();
        if ($msg = self::validate($data, $db)) {
            throw new RuntimeException($msg);
        }
        // Guardar teléfono y correo del usuario
        $email = $data['email'] ?? '';
        $phone = $data['phone'] ?? '';
        if ($email || $phone) {
            $updateStmt = $db->prepare('UPDATE users SET email=?, phone=? WHERE id=?');
            $updateStmt->bind_param('ssi', $email, $phone, $userId);
            $updateStmt->execute();
        }
        $stmt = $db->prepare('INSERT INTO reservas (usuario_id, titulo, fecha_evento, hora_inicio, hora_fin, asistentes, notas) VALUES (?,?,?,?,?,?,?)');
        $stmt->bind_param('issssis', $userId, $data['titulo'], $data['fecha_evento'], $data['hora_inicio'], $data['hora_fin'], $data['asistentes'], $data['notas']);
        $stmt->execute();
    }

    public static function changeStatus(int $id, string $status): void
    {
        $db = Conexion::get();
        $stmt = $db->prepare('UPDATE reservas SET estado=? WHERE id=?');
        $stmt->bind_param('si', $status, $id);
        $stmt->execute();
    }

    public static function delete(int $id): void
    {
        $db = Conexion::get();
        $stmt = $db->prepare('DELETE FROM reservas WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public static function forUser(int $userId): array
    {
        $stmt = Conexion::get()->prepare('SELECT * FROM reservas WHERE usuario_id=? ORDER BY fecha_evento DESC');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function all(): array
    {
        return Conexion::get()->query('SELECT r.*, u.name AS user_name FROM reservas r JOIN users u ON u.id=r.usuario_id ORDER BY r.fecha_evento DESC')->fetch_all(MYSQLI_ASSOC);
    }

    public static function stats(): array
    {
        $db = Conexion::get();
        return [
            'pendientes' => $db->query("SELECT COUNT(*) c FROM reservas WHERE estado='pendiente'")->fetch_assoc()['c'] ?? 0,
            'aprobadas'  => $db->query("SELECT COUNT(*) c FROM reservas WHERE estado='aprobada'")->fetch_assoc()['c'] ?? 0,
            'rechazadas' => $db->query("SELECT COUNT(*) c FROM reservas WHERE estado='rechazada'")->fetch_assoc()['c'] ?? 0,
        ];
    }

    public static function upcoming(int $limit = 5): array
    {
        $db = Conexion::get();
        return $db->query("SELECT r.*, u.name AS user_name FROM reservas r JOIN users u ON u.id=r.usuario_id WHERE r.fecha_evento >= CURDATE() ORDER BY r.fecha_evento ASC LIMIT {$limit}")->fetch_all(MYSQLI_ASSOC);
    }
}
?>
