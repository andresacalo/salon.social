<?php

/**
 * 🔔 NOTIFICACIONES UNIFICADAS
 * 
 * Una sola función para enviar notificaciones por:
 * - 📧 Email
 * - 💬 WhatsApp
 * - 📱 SMS
 * 
 * USO:
 * Notificaciones::crear($datosReserva, $usuario);
 * Notificaciones::aprobar($datosReserva, $usuario);
 * Notificaciones::rechazar($datosReserva, $usuario);
 */

class Notificaciones {
    
    // ============================================
    // 📧 ENVIAR EMAIL DE CONFIRMACIÓN
    // ============================================
    
    public static function crear($reserva, $usuario) {
        $email = $usuario['email'] ?? '';
        $nombre = $usuario['name'] ?? 'Usuario';
        
        if (empty($email)) return false;
        
        $asunto = "✅ Tu Reserva en " . (defined('APP_NAME') ? APP_NAME : 'Salón Social');
        
        $mensaje = "
        <html>
        <head><meta charset='UTF-8'></head>
        <body style='font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;'>
            <div style='background-color: white; padding: 40px; border-radius: 10px; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #22d3ee;'>✅ ¡Tu Reserva Fue Recibida!</h2>
                
                <p>Hola <strong>{$nombre}</strong>,</p>
                
                <p>Tu reserva ha sido <strong>recibida correctamente</strong> en " . (defined('APP_NAME') ? APP_NAME : 'Salón Social') . "</p>
                
                <div style='background-color: #f0f9ff; padding: 20px; border-left: 4px solid #22d3ee; margin: 20px 0; border-radius: 5px;'>
                    <h3 style='margin-top: 0;'>📋 Detalles de tu Evento:</h3>
                    <p><strong>Evento:</strong> {$reserva['titulo']}</p>
                    <p><strong>Fecha:</strong> " . date('d/m/Y', strtotime($reserva['fecha_evento'])) . "</p>
                    <p><strong>Hora:</strong> {$reserva['hora_inicio']} - {$reserva['hora_fin']}</p>
                    <p><strong>Asistentes:</strong> {$reserva['asistentes']}</p>
                    <p style='color: #f59e0b; font-weight: bold;'>Estado: ⏳ Pendiente de Aprobación</p>
                </div>
                
                <p>Te enviaremos una notificación cuando el administrador apruebe tu reserva.</p>
                
                <hr style='border: none; border-top: 1px solid #ddd; margin: 30px 0;'>
                <p style='color: #999; font-size: 12px;'>Este es un correo automático, por favor no responder.</p>
            </div>
        </body>
        </html>";
        
        return self::enviarEmail($email, $asunto, $mensaje);
    }
    
    // ============================================
    // ✅ ENVIAR EMAIL DE APROBACIÓN
    // ============================================
    
    public static function aprobar($reserva, $usuario) {
        $email = $usuario['email'] ?? '';
        $nombre = $usuario['name'] ?? 'Usuario';
        
        if (empty($email)) return false;
        
        $asunto = "✅ ¡Tu Reserva Fue APROBADA!";
        
        $mensaje = "
        <html>
        <head><meta charset='UTF-8'></head>
        <body style='font-family: Arial, sans-serif;'>
            <div style='background-color: #f0fdf4; padding: 40px; border-radius: 10px; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #22c55e;'>✅ ¡Tu Reserva Fue APROBADA!</h2>
                
                <p>¡Hola {$nombre}!</p>
                <p>¡Excelentes noticias! Tu reserva ha sido <strong>APROBADA</strong> ✨</p>
                
                <div style='background-color: white; padding: 20px; border-left: 4px solid #22c55e; margin: 20px 0; border-radius: 5px;'>
                    <p><strong>Evento:</strong> {$reserva['titulo']}</p>
                    <p><strong>Fecha:</strong> " . date('d/m/Y', strtotime($reserva['fecha_evento'])) . "</p>
                    <p><strong>Hora:</strong> {$reserva['hora_inicio']} - {$reserva['hora_fin']}</p>
                    <p><strong>Lugar:</strong> " . (defined('APP_NAME') ? APP_NAME : 'Salón Social') . "</p>
                </div>
                
                <p style='font-size: 16px;'>¡Nos vemos en tu evento! 🎉</p>
            </div>
        </body>
        </html>";
        
        return self::enviarEmail($email, $asunto, $mensaje);
    }
    
    // ============================================
    // ❌ ENVIAR EMAIL DE RECHAZO
    // ============================================
    
    public static function rechazar($reserva, $usuario) {
        $email = $usuario['email'] ?? '';
        $nombre = $usuario['name'] ?? 'Usuario';
        
        if (empty($email)) return false;
        
        $asunto = "❌ Reserva No Disponible";
        
        $mensaje = "
        <html>
        <head><meta charset='UTF-8'></head>
        <body style='font-family: Arial, sans-serif;'>
            <div style='background-color: #fef2f2; padding: 40px; border-radius: 10px; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #ef4444;'>❌ Reserva No Disponible</h2>
                
                <p>Hola {$nombre},</p>
                <p>Lamentablemente, tu reserva para <strong>'{$reserva['titulo']}'</strong> no pudo ser aprobada.</p>
                
                <div style='background-color: white; padding: 20px; margin: 20px 0; border-radius: 5px;'>
                    <p>Las razones pueden ser:</p>
                    <ul>
                        <li>El salón no está disponible en esa fecha/hora</li>
                        <li>Conflicto con otra reserva</li>
                    </ul>
                </div>
                
                <p>Por favor, intenta con otra fecha u horario.</p>
            </div>
        </body>
        </html>";
        
        return self::enviarEmail($email, $asunto, $mensaje);
    }
    
    // ============================================
    // 📧 FUNCIÓN BASE PARA ENVIAR EMAIL
    // ============================================
    
    private static function enviarEmail($destinatario, $asunto, $cuerpo) {
        if (empty($destinatario)) {
            error_log("❌ Email vacío");
            return false;
        }

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . (defined('MAIL_FROM_EMAIL') ? MAIL_FROM_EMAIL : 'salonSocial@example.com') . "\r\n";
        $headers .= "Reply-To: " . (defined('MAIL_FROM_EMAIL') ? MAIL_FROM_EMAIL : 'salonSocial@example.com') . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        
        // Intentar enviar por mail()
        $result = mail($destinatario, $asunto, $cuerpo, $headers);
        
        if ($result) {
            error_log("✅ Email enviado a: {$destinatario} | Asunto: {$asunto}");
            self::registrarNotificacion($destinatario, $asunto, 'email', 'exitoso');
            return true;
        } else {
            error_log("⚠️  Mail() falló, guardando en carpeta local: {$destinatario}");
            // Si mail() falla, guarda en carpeta local para debug
            self::guardarLocalEmail($destinatario, $asunto, $cuerpo);
            self::registrarNotificacion($destinatario, $asunto, 'email', 'guardado_localmente');
            return true; // Se considera exitoso porque se guardó localmente
        }
    }
    
    private static function guardarLocalEmail($destinatario, $asunto, $cuerpo) {
        try {
            // Crear carpeta de emails si no existe
            $carpeta = __DIR__ . '/../../storage/emails';
            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            
            // Guardar con timestamp
            $timestamp = date('Y-m-d_H-i-s');
            $email_limpio = str_replace(['@', '.'], ['_', '_'], $destinatario);
            $archivo = $carpeta . "/" . $timestamp . "_" . $email_limpio . ".html";
            
            $contenido = "
            <div style='background: #f0f0f0; padding: 20px; margin: 10px; border-left: 4px solid #3b82f6;'>
                <h3>📧 Email Guardado Localmente</h3>
                <p><strong>De:</strong> salonSocial@example.com</p>
                <p><strong>Para:</strong> {$destinatario}</p>
                <p><strong>Asunto:</strong> {$asunto}</p>
                <p><strong>Fecha:</strong> {$timestamp}</p>
                <hr>
                <h4>Contenido del Email:</h4>
                {$cuerpo}
                <hr>
                <p style='color: #999; font-size: 12px;'><strong>Nota:</strong> Este email se guardó localmente porque el servidor de correo no está configurado. En producción, se enviaría automáticamente.</p>
            </div>";
            
            file_put_contents($archivo, $contenido);
            error_log("💾 Email guardado en: {$archivo}");
        } catch (Exception $e) {
            error_log("❌ Error guardando email localmente: " . $e->getMessage());
        }
    }
    
    private static function registrarNotificacion($destinatario, $asunto, $canal, $estado) {
        try {
            $db = \Conexion::get();
            $query = "INSERT INTO historial_notificaciones (destinatario, asunto, canal, estado, fecha_envio) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $db->prepare($query);
            if ($stmt) {
                $stmt->bind_param('ssss', $destinatario, $asunto, $canal, $estado);
                $stmt->execute();
            }
        } catch (Exception $e) {
            // Error ignorado, solo es para logging
        }
    }
    
    // ============================================
    // 💬 ENVIAR WHATSAPP (Opcional - Requiere Twilio)
    // ============================================
    
    public static function enviarWhatsapp($numero, $mensaje) {
        if (!defined('TWILIO_SID') || !TWILIO_SID || empty($numero)) {
            return false;
        }

        $url = "https://api.twilio.com/2010-04-01/Accounts/" . TWILIO_SID . "/Messages.json";
        
        $data = [
            'From' => defined('TWILIO_WHATSAPP_FROM') ? TWILIO_WHATSAPP_FROM : 'whatsapp:+14155238886',
            'To' => "whatsapp:{$numero}",
            'Body' => $mensaje
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_USERPWD, TWILIO_SID . ":" . TWILIO_AUTH_TOKEN);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpcode == 201;
    }
    
    // ============================================
    // 📱 ENVIAR SMS (Opcional - Requiere Twilio)
    // ============================================
    
    public static function enviarSMS($numero, $mensaje) {
        if (!defined('TWILIO_SID') || !TWILIO_SID || empty($numero)) {
            return false;
        }

        $url = "https://api.twilio.com/2010-04-01/Accounts/" . TWILIO_SID . "/Messages.json";
        
        $data = [
            'From' => defined('TWILIO_SMS_FROM') ? TWILIO_SMS_FROM : '+1234567890',
            'To' => $numero,
            'Body' => substr($mensaje, 0, 160) // SMS máximo 160 caracteres
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_USERPWD, TWILIO_SID . ":" . TWILIO_AUTH_TOKEN);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpcode == 201;
    }
}

?>
