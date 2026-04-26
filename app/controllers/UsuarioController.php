<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController
{
    public function crear(): void
    {
        $this->requireRole(['admin']);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'residente';
        $pass = $_POST['password'] ?? '';
        if (!$name || !$email || !$pass) {
            $_SESSION['flash'] = ['type'=>'error','message'=>'Datos incompletos'];
            header('Location: ' . route_to('usuarios'));
            return;
        }
        if (Usuario::create($name, $email, $role, $pass)) {
            $_SESSION['flash'] = ['type'=>'success','message'=>'Usuario creado'];
        } else {
            $_SESSION['flash'] = ['type'=>'error','message'=>'Correo ya existe'];
        }
        header('Location: ' . route_to('usuarios'));
    }

    public function toggle(): void
    {
        $this->requireRole(['admin']);
        Usuario::toggle((int)$_POST['id'], (int)$_POST['active']);
        $_SESSION['flash'] = ['type'=>'success','message'=>'Usuario actualizado'];
        header('Location: ' . route_to('usuarios'));
    }

    private function requireRole(array $roles): void
    {
        $u = $_SESSION['user'] ?? null;
        if (!$u || !in_array($u['role'], $roles, true)) { http_response_code(403); exit('No autorizado'); }
    }
}
?>
