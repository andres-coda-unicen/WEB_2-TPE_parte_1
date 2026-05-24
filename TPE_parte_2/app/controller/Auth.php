<?php
class Auth
{
  public static function estaLogueado(): bool
  {
    return isset($_SESSION['usuario_id']) && $_SESSION['es_admin'] === true;
  }

  public static function requireAdmin(): void
  {
    if (!self::estaLogueado()) {
      header('Location: ' . BASE_URL . '/login');
      exit;
    }
  }
}
