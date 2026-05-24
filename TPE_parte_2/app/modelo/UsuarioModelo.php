<?php
require_once __DIR__ . '/../../config/database.php';

class UsuarioModelo
{
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }

  /** Buscar usuario por email (o nombre de usuario) */
  public function obtenerPorEmail(string $email): array|false
  {
    $stmt = $this->db->prepare("SELECT * FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
  }
}
