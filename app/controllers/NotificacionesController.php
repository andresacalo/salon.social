<?php

class NotificacionesController {
    
    public function index(): void {
        $this->requireRole(['admin']);
        
        $db = \Conexion::get();
        $resultado = $db->query("
            SELECT 
                r.id, r.titulo, r.fecha_evento, r.hora_inicio, 
                r.estado, u.name, u.email, u.phone, u.whatsapp
            FROM reservas r
            JOIN users u ON r.usuario_id = u.id
            ORDER BY r.fecha_evento DESC
        ");
        
        $reservas = $resultado->fetch_all(MYSQLI_ASSOC);
        
        include __DIR__ . '/../views/notificaciones/index.php';
    }
    
    public function enviar(): void {
        $this->requireRole(['admin']);
        
        $id = (int)($_POST['id'] ?? 0);
        $tipo = $_POST['tipo'] ?? '';
        $canal = $_POST['canal'] ?? 'email';
        
        try {
            $db = \Conexion::get();
            $stmt = $db->prepare('
                SELECT r.*, u.email, u.name, u.phone, u.whatsapp 
                FROM reservas r 
                JOIN users u ON r.usuario_id = u.id 
                WHERE r.id = ?
            ');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $reserva = $stmt->get_result()->fetch_assoc();
            
            if (!$reserva) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Reserva no encontrada'];
                header('Location: ' . route_to('notificaciones'));
                exit;
            }
            
            $usuario = ['email' => $reserva['email'], 'name' => $reserva['name']];
            $enviados = [];
            
            // Enviar según el canal seleccionado
            if ($canal === 'email') {
                if ($tipo === 'confirmacion') {
                    if (\Notificaciones::crear($reserva, $usuario)) $enviados[] = '📧 Email';
                } elseif ($tipo === 'aprobacion') {
                    if (\Notificaciones::aprobar($reserva, $usuario)) $enviados[] = '📧 Email';
                } elseif ($tipo === 'rechazo') {
                    if (\Notificaciones::rechazar($reserva, $usuario)) $enviados[] = '📧 Email';
                }
            } elseif ($canal === 'whatsapp' && !empty($reserva['phone'])) {
                $mensaje = $this->construirMensaje($tipo, $reserva);
                if (\Notificaciones::enviarWhatsapp($reserva['phone'], $mensaje)) {
                    $enviados[] = '💬 WhatsApp';
                }
            } elseif ($canal === 'sms' && !empty($reserva['phone'])) {
                $mensaje = $this->construirMensajeSMS($tipo, $reserva);
                if (\Notificaciones::enviarSMS($reserva['phone'], $mensaje)) {
                    $enviados[] = '📞 SMS';
                }
            }
            
            if (empty($enviados)) {
                throw new Exception('No se pudo enviar la notificación. Verifica que los datos de contacto sean válidos. El email se intentó enviar pero el servidor de correo podría no estar configurado.');
            }
            
            $mensaje = '✅ Notificaciones enviadas por: ' . implode(', ', $enviados);
            $_SESSION['flash'] = ['type' => 'success', 'message' => $mensaje];
            
        } catch (Exception $e) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()];
        }
        
        header('Location: ' . route_to('notificaciones'));
    }
    
    private function construirMensaje(string $tipo, array $reserva): string {
        $evento = htmlspecialchars_decode($reserva['titulo']);
        $fecha = date('d/m/Y', strtotime($reserva['fecha_evento']));
        $hora = $reserva['hora_inicio'];
        
        return match($tipo) {
            'confirmacion' => "¡Hola! Tu reserva para '{$evento}' el {$fecha} a las {$hora} ha sido recibida. Pronto te confirmaremos. 🎉",
            'aprobacion' => "¡Excelente! Tu reserva para '{$evento}' el {$fecha} a las {$hora} ha sido APROBADA. ✅",
            'rechazo' => "Lamentamos informarte que tu reserva para '{$evento}' el {$fecha} ha sido rechazada. Por favor contactanos. ❌",
            default => "Tu reserva ha sido procesada."
        };
    }
    
    private function construirMensajeSMS(string $tipo, array $reserva): string {
        $evento = htmlspecialchars_decode($reserva['titulo']);
        $fecha = date('d/m/Y', strtotime($reserva['fecha_evento']));
        
        return match($tipo) {
            'confirmacion' => "Reserva recibida: {$evento} - {$fecha}",
            'aprobacion' => "Reserva aprobada: {$evento} - {$fecha} ✅",
            'rechazo' => "Reserva rechazada: {$evento}",
            default => "Tu reserva ha sido procesada."
        };
    }
    
    private function requireRole(array $roles): void {
        $u = $_SESSION['user'] ?? null;
        if (!$u || !in_array($u['role'], $roles, true)) {
            http_response_code(403);
            exit('No autorizado');
        }
    }
}

?>
