<?php
require_once __DIR__ . '/../../config/database.php';

class ProductoModelo
{
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }

  /** Todos los productos con nombre de categoría */
  public function obtenerTodos(): array
  {
    $stmt = $this->db->query("
            SELECT p.*, c.nombre AS categoria_nombre
            FROM producto p
            LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
            ORDER BY p.id_producto DESC
        ");
    return $stmt->fetchAll();
  }

  /** Un producto por ID con nombre de categoría */
  public function obtenerPorId(int $id): array|false
  {
    $stmt = $this->db->prepare("
            SELECT p.*, c.nombre AS categoria_nombre
            FROM producto p
            LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
            WHERE p.id_producto = ?
        ");
    $stmt->execute([$id]);
    return $stmt->fetch();
  }

  /** Productos filtrados por categoría */
  public function obtenerPorCategoria(int $idCategoria): array
  {
    $stmt = $this->db->prepare("
            SELECT p.*, c.nombre AS categoria_nombre
            FROM producto p
            LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
            WHERE p.id_categoria = ?
            ORDER BY p.id_producto DESC
        ");
    $stmt->execute([$idCategoria]);
    return $stmt->fetchAll();
  }

  /** Insertar nuevo producto */
  public function insertar(array $datos): int
  {
    $stmt = $this->db->prepare("
            INSERT INTO producto (nombre, descripcion, precio, stock, imagen_url, id_categoria)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
    $stmt->execute([
      $datos['nombre'],
      $datos['descripcion'],
      $datos['precio'],
      $datos['stock'],
      $datos['imagen_url'] ?? null,
      $datos['id_categoria'],
    ]);
    return (int) $this->db->lastInsertId();
  }

  /** Actualizar producto existente */
  public function actualizar(int $id, array $datos): void
  {
    $stmt = $this->db->prepare("
            UPDATE producto
            SET nombre = ?, descripcion = ?, precio = ?, stock = ?, imagen_url = ?, id_categoria = ?
            WHERE id_producto = ?
        ");
    $stmt->execute([
      $datos['nombre'],
      $datos['descripcion'],
      $datos['precio'],
      $datos['stock'],
      $datos['imagen_url'] ?? null,
      $datos['id_categoria'],
      $id,
    ]);
  }

  /** Eliminar producto */
  public function eliminar(int $id): void
  {
    $stmt = $this->db->prepare("DELETE FROM producto WHERE id_producto = ?");
    $stmt->execute([$id]);
  }
}
