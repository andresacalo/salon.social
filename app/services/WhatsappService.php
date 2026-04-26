<?php

/**
 * 💬 SERVICIO DE WHATSAPP
 * 
 * Envía mensajes a través de WhatsApp usando Twilio API (con CURL, sin dependencias)
 */

class WhatsappService {
    
    /**
     * Envía confirmación de reserva por WhatsApp
     */
    public static function enviarConfirmacionReserva($numero, $nombre, $reserva) {
        $mensaje = "✅ *¡Reserva Recibida, {$nombre}!*\n\n";
        $mensaje .= "Evento: " . $reserva['titulo'] . "\n";
        $mensaje .= "Fecha: " . (isset($reserva['fecha_evento']) ? date('d/m/Y', strtotime($reserva['fecha_evento'])) : '') . "\n";
        $mensaje .= "Hora: " . $reserva['hora_inicio'] . " - " . $reserva['hora_fin'] . "\n";
        $mensaje .= "Asistentes: " . $reserva['asistentes'] . "\n\n";
        $mensaje .= "⏳ Estado: Pendiente de aprobación\n";
        $mensaje .= "Te notificaremos pronto.\n\n";
        $mensaje .= (defined('APP_NAME') ? APP_NAME : 'Salón Social') . " 🎉";
        
        return self::enviarMensaje($numero, $mensaje);
    }
        self::inicializar();
        
        try {
            $mensaje = "✅ *¡Reserva Recibida, {$nombre}!*\n\n";
            $mensaje .= "Evento: {$reserva['titulo']}\n";
            $mensaje .= "Fecha: " . date('d/m/Y', strtotime($reserva['fecha_evento'])) . "\n";
            $mensaje .= "Hora: {$reserva['hora_inicio']} - {$reserva['hora_fin']}\n";
            $mensaje .= "Asistentes: {$reserva['asistentes']}\n\n";
            $mensaje .= "⏳ Estado: Pendiente de aprobación\n";
            $mensaje .= "Te notificaremos pronto.\n\n";
            $mensaje .= APP_NAME . " 🎉";
            
            $message = self::$client->messages->create(
                "whatsapp:{$numero}",
                [
                    "from" => TWILIO_WHATSAPP_FROM,
                    "body" => $mensaje
                ]
            );
            
            error_log("✅ WhatsApp enviado a {$numero} - SID: " . $message->sid);
            return true;
            
        } catch (TwilioException $e) {
            error_log("❌ Error WhatsApp: " . $e->getMessage());
            return "Error al enviar WhatsApp: " . $e->getMessage();
        }
    }
    
    /**
     * Notifica al admin sobre nueva reserva
     * 
     * @param string $numero Número del admin
     * @param string $nombreCliente
     * @param array $reserva
     * @return bool
     */
    public static function notificarAdminNuevaReserva($numero, $nombreCliente, $reserva) {
        self::inicializar();
        
        try {
            $mensaje = "🔔 *NUEVA RESERVA PENDIENTE*\n\n";
            $mensaje .= "Cliente: {$nombreCliente}\n";
            $mensaje .= "Evento: {$reserva['titulo']}\n";
            $mensaje .= "Fecha: " . date('d/m/Y', strtotime($reserva['fecha_evento'])) . "\n";
            $mensaje .= "Asistentes: {$reserva['asistentes']}\n\n";
            $mensaje .= "👉 Accede a tu panel para aprobar o rechazar";
            
            $message = self::$client->messages->create(
                "whatsapp:{$numero}",
                [
                    "from" => TWILIO_WHATSAPP_FROM,
                    "body" => $mensaje
                ]
            );
            
            return true;
            
        } catch (TwilioException $e) {
            error_log("❌ Error notificación admin WhatsApp: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Notifica aprobación de reserva
     * 
     * @param string $numero
     * @param string $nombre
     * @param array $reserva
     * @return bool
     */
    public static function enviarAprobacionReserva($numero, $nombre, $reserva) {
        self::inicializar();
        
        try {
            $mensaje = "✅ *¡TU RESERVA FUE APROBADA!*\n\n";
            $mensaje .= "¡Hola {$nombre}!\n\n";
            $mensaje .= "Evento: {$reserva['titulo']}\n";
            $mensaje .= "Fecha: " . date('d/m/Y', strtotime($reserva['fecha_evento'])) . "\n";
            $mensaje .= "Hora: {$reserva['hora_inicio']} - {$reserva['hora_fin']}\n\n";
            $mensaje .= "¡Nos vemos en tu evento! 🎉\n";
            $mensaje .= APP_NAME;
            
            $message = self::$client->messages->create(
                "whatsapp:{$numero}",
                [
                    "from" => TWILIO_WHATSAPP_FROM,
                    "body" => $mensaje
                ]
            );
            
            return true;
            
        } catch (TwilioException $e) {
            error_log("❌ Error aprobación WhatsApp: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Notifica rechazo de reserva
     * 
     * @param string $numero
     * @param string $nombre
     * @param array $reserva
     * @return bool
     */
    public static function enviarRechazoReserva($numero, $nombre, $reserva) {
        self::inicializar();
        
        try {
            $mensaje = "❌ *Reserva No Disponible*\n\n";
            $mensaje .= "Hola {$nombre},\n\n";
            $mensaje .= "Lamentablemente, tu reserva para '{$reserva['titulo']}' ";
            $mensaje .= "no pudo ser aprobada debido a que el salón no está disponible.\n\n";
            $mensaje .= "Intenta con otra fecha. Gracias.";
            
            $message = self::$client->messages->create(
                "whatsapp:{$numero}",
                [
                    "from" => TWILIO_WHATSAPP_FROM,
                    "body" => $mensaje
                ]
            );
            
            return true;
            
        } catch (TwilioException $e) {
            error_log("❌ Error rechazo WhatsApp: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Recordatorio de evento (usar 24 horas antes)
     * 
     * @param string $numero
     * @param string $nombre
     * @param array $reserva
     * @return bool
     */
    public static function recordatorioEvento($numero, $nombre, $reserva) {
        self::inicializar();
        
        try {
            $mensaje = "🎉 *RECORDATORIO: Tu Evento es Mañana*\n\n";
            $mensaje .= "¡Hola {$nombre}!\n\n";
            $mensaje .= "Te recordamos que tu evento '{$reserva['titulo']}'\n";
            $mensaje .= "es mañana a las {$reserva['hora_inicio']}\n\n";
            $mensaje .= "¡Nos vemos! 👋\n";
            $mensaje .= APP_NAME;
            
            $message = self::$client->messages->create(
                "whatsapp:{$numero}",
                [
                    "from" => TWILIO_WHATSAPP_FROM,
                    "body" => $mensaje
                ]
            );
            
            return true;
            
        } catch (TwilioException $e) {
            error_log("❌ Error recordatorio WhatsApp: " . $e->getMessage());
            return false;
        }
    }
}

?>
