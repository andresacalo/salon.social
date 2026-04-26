<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController
{
    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $pass = $_POST['password'] ?? '';
        $user = Usuario::findByEmail($email);
        if ($user && $user['active'] && password_verify($pass, $user['password_hash'])) {
            $_SESSION['user'] = $user;
            header('Location: ' . route_to('dashboard'));
            return;
        }
        $_SESSION['flash'] = ['type'=>'error','message'=>'Credenciales inválidas'];
        header('Location: ' . route_to('login'));
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: ' . route_to('login'));
    }
}
?>
