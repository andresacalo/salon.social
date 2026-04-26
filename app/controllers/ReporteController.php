<?php
require_once __DIR__ . '/../models/Reserva.php';

class ReporteController
{
    public function csvReservas(): void
    {
        $this->requireRole(['admin','supervisor']);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="reservas.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID','Usuario','Titulo','Fecha','Inicio','Fin','Estado','Creado']);
        $rows = Conexion::get()->query("SELECT r.id,u.name,r.titulo,r.fecha_evento,r.hora_inicio,r.hora_fin,r.estado,r.creado_el FROM reservas r JOIN users u ON u.id=r.usuario_id ORDER BY r.fecha_evento DESC");
        while ($row = $rows->fetch_assoc()) { fputcsv($out, $row); }
        exit;
    }

    private function requireRole(array $roles): void
    {
        $u = $_SESSION['user'] ?? null;
        if (!$u || !in_array($u['role'], $roles, true)) { http_response_code(403); exit('No autorizado'); }
    }
}
?>
