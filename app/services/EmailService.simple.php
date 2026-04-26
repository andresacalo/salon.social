<?php

/**
 * 📧 SERVICIO DE EMAIL SIMPLIFICADO
 * 
 * Versión sin dependencias externas - Usa php mail() o SMTP opcional
 */

class EmailService {
    
    /**
     * Envía confirmación de reserva
     */
    public static function enviarConfirmacionReserva($email, $nombre, $reserva) {
        $asunto = "✅ Reserva Confirmada - " . (defined('APP_NAME') ? APP_NAME : 'Salón Social');
        
        $mensaje = "
        <html>
        <body style='font-family: Arial; background-color: #f5f5f5; padding: 20px;'>
            <div style='background-color: white; padding: 40px; border-radius: 10px; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #22d3ee;'>✅ ¡Reserva Confirmada!</h2>
                <p>Hola <strong>{$nombre}</strong>,</p>
                <p>Tu reserva ha sido <strong>recibida correctamente</strong>.</p>
                
                <div style='background-color: #f0f9ff; padding: 20px; border-left: 4px solid #22d3ee; margin: 20px 0;'>
                    <h3>Detalles:</h3>
                    <p><strong>Evento:</strong> " . $reserva['titulo'] . "</p>
                    <p><strong>Fecha:</strong> " . date('d/m/Y', strtotime($reserva['fecha_evento'])) . "</p>
                    <p><strong>Hora:</strong> " . $reserva['hora_inicio'] . " - " . $reserva['hora_fin'] . "</p>
                    <p><strong>Asistentes:</strong> " . $reserva['asistentes'] . "</p>
                    <p><strong>Estado:</strong> ⏳ Pendiente de aprobación</p>
                </div>
                
                <p>Te notificaremos cuando sea aprobada.</p>
                <p style='color: #999; font-size: 12px;'>Este es un email automático, no responder.</p>
            </div>
        </body>
        </html>";
        
        return self::enviar($email, $asunto, $mensaje);
    }
    
    /**
     * Notificación para el admin
     */
    public static function notificarAdminNuevaReserva($reserva, $nombreCliente) {
        $admin_email = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'admin@salonsocial.com';
        
        $asunto = "🔔 NUEVA RESERVA - " . (defined('APP_NAME') ? APP_NAME : 'Salón Social');
        
        $mensaje = "
        <html>
        <body>
            <h2>Nueva Reserva Pendiente</h2>
            <p><strong>Cliente:</strong> {$nombreCliente}</p>
            <p><strong>Evento:</strong> " . $reserva['titulo'] . "</p>
            <p><strong>Fecha:</strong> " . date('d/m/Y', strtotime($reserva['fecha_evento'])) . "</p>
            <p><strong>Asistentes:</strong> " . $reserva['asistentes'] . "</p>
            <p><a href='" . (defined('APP_URL') ? APP_URL : 'http://localhost/salon_social') . "'>👉 Ir a aprobar/rechazar</a></p>
        </body>
        </html>";
        
        return self::enviar($admin_email, $asunto, $mensaje);
    }
    
    /**
     * Notificación de aprobación
     */
    public static function enviarAprobacionReserva($email, $nombre, $reserva) {
        $asunto = "✅ ¡Tu Reserva Fue APROBADA!";
        
        $mensaje = "
        <html>
        <body style='font-family: Arial;'>
            <div style='background-color: #f0fdf4; padding: 40px; border-radius: 10px; max-width: 600px;'>
                <h2 style='color: #22c55e;'>✅ ¡Tu Reserva Fue APROBADA!</h2>
                <p>Hola {$nombre},</p>
                <p>¡Excelentes noticias! Tu reserva ha sido <strong>APROBADA</strong>.</p>
                
                <div style='background-color: #f0fdf4; padding: 20px; border-left: 4px solid #22c55e; margin: 20px 0;'>
                    <p><strong>Evento:</strong> " . $reserva['titulo'] . "</p>
                    <p><strong>Fecha:</strong> " . date('d/m/Y', strtotime($reserva['fecha_evento'])) . "</p>
                    <p><strong>Hora:</strong> " . $reserva['hora_inicio'] . "</p>
                </div>
                
                <p>¡Nos vemos en tu evento! 🎉</p>
            </div>
        </body>
        </html>";
        
        return self::enviar($email, $asunto, $mensaje);
    }
    
    /**
     * Notificación de rechazo
     */
    public static function enviarRechazoReserva($email, $nombre, $reserva) {
        $asunto = "❌ Reserva No Disponible";
        
        $mensaje = "
        <html>
        <body>
            <h2>Reserva No Disponible</h2>
            <p>Hola {$nombre},</p>
            <p>Lamentablemente, tu reserva para '<strong>" . $reserva['titulo'] . "</strong>' no pudo ser aprobada.</p>
            <p>Intenta con otra fecha.</p>
        </body>
        </html>";
        
        return self::enviar($email, $asunto, $mensaje);
    }
    
    /**
     * Envía email
     */
    private static function enviar($destinatario, $asunto, $cuerpo) {
        if (empty($destinatario)) {
            return false;
        }

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . (defined('MAIL_FROM_EMAIL') ? MAIL_FROM_EMAIL : 'salonSocial@example.com') . "\r\n";
        
        $result = @mail($destinatario, $asunto, $cuerpo, $headers);
        
        if ($result) {
            error_log("✅ Email enviado a: {$destinatario}");
        } else {
            error_log("❌ Error enviando email a: {$destinatario}");
        }
        
        return $result;
    }
}

?>
