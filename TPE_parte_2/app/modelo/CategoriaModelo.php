<?php
require_once __DIR__ . '/../../config/database.php';

class CategoriaModelo
{
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }

  /** Todas las categorías */
  public function obtenerTodas(): array
  {
    $stmt = $this->db->query("
            SELECT c.*, COUNT(p.id_producto) AS cantidad_productos
            FROM categoria c
            LEFT JOIN producto p ON c.id_categoria = p.id_categoria
            GROUP BY c.id_categoria
            ORDER BY c.nombre
        ");
    return $stmt->fetchAll();
  }

  /** Una categoría por ID */
  public function obtenerPorId(int $id): array|false
  {
    $stmt = $this->db->prepare("SELECT * FROM categoria WHERE id_categoria = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
  }

  /** Insertar nueva categoría */
  public function insertar(array $datos): int
  {
    $stmt = $this->db->prepare("
            INSERT INTO categoria (nombre, descripcion, imagen_url)
            VALUES (?, ?, ?)
        ");
    $stmt->execute([
      $datos['nombre'],
      $datos['descripcion'] ?? null,
      $datos['imagen_url'] ?? null,
    ]);
    return (int) $this->db->lastInsertId();
  }

  /** Actualizar categoría existente */
  public function actualizar(int $id, array $datos): void
  {
    $stmt = $this->db->prepare("
            UPDATE categoria
            SET nombre = ?, descripcion = ?, imagen_url = ?
            WHERE id_categoria = ?
        ");
    $stmt->execute([
      $datos['nombre'],
      $datos['descripcion'] ?? null,
      $datos['imagen_url'] ?? null,
      $id,
    ]);
  }

  /** Eliminar categoría */
  public function eliminar(int $id): void
  {
    $stmt = $this->db->prepare("DELETE FROM categoria WHERE id_categoria = ?");
    $stmt->execute([$id]);
  }
}
