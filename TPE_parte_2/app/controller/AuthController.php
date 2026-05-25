<?php
require_once __DIR__ . '/../modelo/UsuarioModelo.php';

class AuthController
{
  private UsuarioModelo $usuarioModelo;

  public function __construct()
  {
    $this->usuarioModelo = new UsuarioModelo();
  }

  /** GET /login — Formulario de login */
  public function loginForm(): void
  {
    if (Auth::estaLogueado()) {
      $this->redirigir('/admin/productos');
    }
    require __DIR__ . '/../paginas/usuario/login.phtml';
  }

  /** POST /login/procesar — Procesar credenciales */
  public function login(): void
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirigir('/login');
      return;
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $usuario = $this->usuarioModelo->obtenerPorEmail($email);

    if ($usuario && $usuario['es_admin'] && password_verify($password, $usuario['password'])) {
      $_SESSION['usuario_id'] = $usuario['id_usuario'];
      $_SESSION['usuario_email'] = $usuario['email'];
      $_SESSION['es_admin'] = true;
      $this->redirigir('/admin/producto');
    } else {
      $_SESSION['error'] = 'Usuario o contraseña incorrectos.';
      $this->redirigir('/login');
    }
  }

  /** GET /logout */
  public function logout(): void
  {
    session_destroy();
    header('Location: ' . BASE_URL . '/');
    exit;
  }

  private function redirigir(string $ruta): void
  {
    header('Location: ' . BASE_URL . $ruta);
    exit;
  }
}
