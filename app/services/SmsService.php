<?php

/**
 * 📱 SERVICIO DE SMS
 * 
 * Envía mensajes de texto usando Twilio
 * Requerimientos: composer require twilio/sdk
 */

use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

class SmsService {
    
    private static $client;
    
    /**
     * Inicializa el cliente de Twilio
     */
    private static function inicializar() {
        if (!$client) {
            self::$client = new Client(TWILIO_SID, TWILIO_AUTH_TOKEN);
        }
    }
    
    /**
     * Envía confirmación de reserva por SMS
     * 
     * @param string $numero Número con formato: +5491234567890
     * @param string $nombre Nombre del cliente
     * @param array $reserva Detalles de la reserva
     * @return bool|string
     */
    public static function enviarConfirmacionReserva($numero, $nombre, $reserva) {
        self::inicializar();
        
        try {
            $fecha = date('d/m/Y', strtotime($reserva['fecha_evento']));
            $mensaje = "✅ Hola {$nombre}, tu reserva para '{$reserva['titulo']}' el {$fecha} a las {$reserva['hora_inicio']} ha sido recibida. Estado: Pendiente. " . APP_NAME;
            
            $message = self::$client->messages->create(
                $numero,
                [
                    "from" => TWILIO_SMS_FROM,
                    "body" => substr($mensaje, 0, 160) // SMS máximo 160 caracteres
                ]
            );
            
            error_log("✅ SMS enviado a {$numero} - SID: " . $message->sid);
            return true;
            
        } catch (TwilioException $e) {
            error_log("❌ Error SMS: " . $e->getMessage());
            return "Error al enviar SMS: " . $e->getMessage();
        }
    }
    
    /**
     * Aprobación de reserva por SMS
     * 
     * @param string $numero
     * @param string $nombre
     * @param array $reserva
     * @return bool
     */
    public static function enviarAprobacionReserva($numero, $nombre, $reserva) {
        self::inicializar();
        
        try {
            $fecha = date('d/m/Y', strtotime($reserva['fecha_evento']));
            $mensaje = "✅ ¡{$nombre}, tu reserva fue APROBADA! '{$reserva['titulo']}' el {$fecha} {$reserva['hora_inicio']}. ¡Nos vemos! " . APP_NAME;
            
            $message = self::$client->messages->create(
                $numero,
                [
                    "from" => TWILIO_SMS_FROM,
                    "body" => substr($mensaje, 0, 160)
                ]
            );
            
            return true;
            
        } catch (TwilioException $e) {
            error_log("❌ Error SMS aprobación: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Rechazo de reserva por SMS
     * 
     * @param string $numero
     * @param string $nombre
     * @param array $reserva
     * @return bool
     */
    public static function enviarRechazoReserva($numero, $nombre, $reserva) {
        self::inicializar();
        
        try {
            $mensaje = "❌ Hola {$nombre}, lamentablemente no pudimos aprobar tu reserva para '{$reserva['titulo']}'. Intenta con otra fecha.";
            
            $message = self::$client->messages->create(
                $numero,
                [
                    "from" => TWILIO_SMS_FROM,
                    "body" => substr($mensaje, 0, 160)
                ]
            );
            
            return true;
            
        } catch (TwilioException $e) {
            error_log("❌ Error SMS rechazo: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Recordatorio de evento
     * 
     * @param string $numero
     * @param string $nombre
     * @param array $reserva
     * @return bool
     */
    public static function recordatorioEvento($numero, $nombre, $reserva) {
        self::inicializar();
        
        try {
            $mensaje = "🎉 ¡{$nombre}! Tu evento '{$reserva['titulo']}' es mañana a las {$reserva['hora_inicio']}. ¡Nos vemos! " . APP_NAME;
            
            $message = self::$client->messages->create(
                $numero,
                [
                    "from" => TWILIO_SMS_FROM,
                    "body" => substr($mensaje, 0, 160)
                ]
            );
            
            return true;
            
        } catch (TwilioException $e) {
            error_log("❌ Error SMS recordatorio: " . $e->getMessage());
            return false;
        }
    }
}

?>
