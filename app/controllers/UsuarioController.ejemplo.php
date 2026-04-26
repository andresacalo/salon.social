<?php

/**
 * 👥 EJEMPLO: CONTROLLER DE USUARIOS CON SOPORTE DE PHONE Y WHATSAPP
 * 
 * Este es un ejemplo para que actualices tu UsuarioController.php
 * Copia las partes que necesites a tu archivo original
 */

class UsuarioController
{
    /**
     * Crear nuevo usuario
     * 
     * POST data esperada:
     * - name: string
     * - email: string
     * - password: string
     * - role: string ('admin', 'residente', 'supervisor')
     * - phone: string (opcional)
     * - whatsapp: string (opcional)
     */
    public function crear(): void
    {
        // Verificar que sea admin
        $this->requireRole(['admin']);
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = trim($_POST['role'] ?? 'residente');
        $phone = trim($_POST['phone'] ?? null);
        $whatsapp = trim($_POST['whatsapp'] ?? null);
        
        // Validar campos
        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Todos los campos son requeridos'];
            header('Location: ' . route_to('usuarios'));
            return;
        }
        
        // Validar email único
        if (Usuario::findByEmail($email)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Email ya registrado'];
            header('Location: ' . route_to('usuarios'));
            return;
        }
        
        try {
            // Crear usuario en la BD
            Usuario::create($name, $email, $role, $password, $phone, $whatsapp);
            
            $_SESSION['flash'] = ['type' => 'success', 'message' => "Usuario {$name} creado"];
            
            // 📧 Enviar email de bienvenida
            $this->enviarEmailBienvenida($email, $name);
            
        } catch (Exception $e) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Error al crear usuario'];
        }
        
        header('Location: ' . route_to('usuarios'));
    }
    
    /**
     * Editar usuario (ejemplo)
     */
    public function editar(): void
    {
        $this->requireRole(['admin']);
        
        $id = (int)($_POST['id'] ?? 0);
        $phone = trim($_POST['phone'] ?? null);
        $whatsapp = trim($_POST['whatsapp'] ?? null);
        
        // Aquí irían más campos según necesites
        // ...
        
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Usuario actualizado'];
        header('Location: ' . route_to('usuarios'));
    }
    
    private function requireRole(array $roles): void
    {
        $u = $_SESSION['user'] ?? null;
        if (!$u || !in_array($u['role'], $roles, true)) {
            http_response_code(403);
            exit('No autorizado');
        }
    }
    
    private function enviarEmailBienvenida($email, $name): void
    {
        // Ejemplo: Enviar email de bienvenida
        // require_once 'config/api_keys.php';
        // require_once 'app/services/EmailService.php';
        // EmailService::enviarBienvenida($email, $name);
    }
}

?>
