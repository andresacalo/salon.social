<?php
require_once __DIR__ . '/../models/Conexion.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../../config/api_keys.php';
require_once __DIR__ . '/../services/Notificaciones.php';

class ReservaController
{
    public function crear(): void
    {
        $this->requireLogin();
        $data = [
            'fecha_evento' => trim($_POST['fecha_evento'] ?? ''),
            'hora_inicio' => trim($_POST['hora_inicio'] ?? ''),
            'hora_fin' => trim($_POST['hora_fin'] ?? ''),
            'titulo' => trim($_POST['titulo'] ?? ''),
            'asistentes' => (int)($_POST['asistentes'] ?? 0),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'notas' => trim($_POST['notas'] ?? ''),
        ];
        try {
            Reserva::create($_SESSION['user']['id'], $data);
            $_SESSION['flash'] = ['type'=>'success','message'=>'✅ Reserva creada'];
            unset($_SESSION['form_data']);
            
            // Enviar notificación de confirmación
            $usuario = Usuario::findById($_SESSION['user']['id']);
            if ($usuario) {
                @Notificaciones::crear($data, $usuario);
            }
            
        } catch (RuntimeException $e) {
            $_SESSION['flash'] = ['type'=>'error','message'=>$e->getMessage()];
            $_SESSION['form_data'] = $data;
        }
        header('Location: ' . route_to('reservas'));
    }

    public function cambiarEstado(): void
    {
        $this->requireRole(['admin']);
        
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        
        Reserva::changeStatus($id, $status);
        $_SESSION['flash'] = ['type'=>'success','message'=>'Estado actualizado'];
        
        // Obtener datos y enviar notificación
        try {
            $db = Conexion::get();
            $stmt = $db->prepare('SELECT r.*, u.email, u.name FROM reservas r JOIN users u ON r.usuario_id = u.id WHERE r.id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $reserva = $stmt->get_result()->fetch_assoc();
            
            if ($reserva) {
                $usuario = ['email' => $reserva['email'], 'name' => $reserva['name']];
                
                if ($status === 'aprobada') {
                    @Notificaciones::aprobar($reserva, $usuario);
                } elseif ($status === 'rechazada') {
                    @Notificaciones::rechazar($reserva, $usuario);
                }
            }
        } catch (Exception $e) {
            error_log("Error en notificación: " . $e->getMessage());
        }
        
        header('Location: ' . route_to('admin_reservas'));
    }

    private function requireLogin(): void
    {
        if (!isset($_SESSION['user'])) { header('Location: ' . route_to('login')); exit; }
    }
    private function requireRole(array $roles): void
    {
        $u = $_SESSION['user'] ?? null;
        if (!$u || !in_array($u['role'], $roles, true)) { http_response_code(403); exit('No autorizado'); }
    }
}
?>
