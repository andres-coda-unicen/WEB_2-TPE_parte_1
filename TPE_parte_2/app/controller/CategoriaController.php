<?php
require_once __DIR__ . '/Auth.php';
require_once __DIR__ . '/../modelo/CategoriaModelo.php';
require_once __DIR__ . '/../modelo/ProductoModelo.php';

class CategoriaController
{
  private CategoriaModelo $categoriaModelo;
  private ProductoModelo $productoModelo;

  public function __construct()
  {
    $this->categoriaModelo = new CategoriaModelo();
    $this->productoModelo = new ProductoModelo();
  }

  // ── Públicas ──────────────────────────────────────────────────────────────

  /** GET /categorias — Listado público de categorías */
  public function index(): void
  {
    $categorias = $this->categoriaModelo->obtenerTodas();
    require __DIR__ . '/../paginas/categoria/listado.phtml';
  }

  /** GET /categorias/productos?id=X — Productos de una categoría */
  public function productosPorCategoria(?string $id): void
  {
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if (!$id) {
      $this->redirigir('/categoria');
      return;
    }

    $categoria = $this->categoriaModelo->obtenerPorId((int) $id);
    if (!$categoria) {
      $this->redirigir('/categorias');
      return;
    }

    $productos = $this->productoModelo->obtenerPorCategoria((int) $id);
    require __DIR__ . '/../paginas/categoria/producto_categoria.phtml';
  }

  // ── Admin ─────────────────────────────────────────────────────────────────

  /** GET /admin/categorias */
  public function adminIndex(): void
  {
    Auth::requireAdmin();
    $categorias = $this->categoriaModelo->obtenerTodas();
    require __DIR__ . '/../paginas/categoria/admin_listado.phtml';
  }

  /** GET /admin/categorias/nueva */
  public function nueva(): void
  {
    Auth::requireAdmin();
    require __DIR__ . '/../paginas/categoria/form.phtml';
  }

  /** POST /admin/categorias/guardar */
  public function guardar(): void
  {
    Auth::requireAdmin();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirigir('/admin/categoria');
      return;
    }

    $datos = $this->sanitizarFormulario($_POST);
    if (empty($datos['nombre'])) {
      $_SESSION['error'] = 'El nombre de la categoría es obligatorio.';
      $this->redirigir('/admin/categoria/nueva');
      return;
    }

    $this->categoriaModelo->insertar($datos);
    $_SESSION['exito'] = 'Categoría agregada correctamente.';
    $this->redirigir('/admin/categoria');
  }

  /** GET /admin/categorias/editar?id=X */
  public function editar(?string $id): void
  {
    Auth::requireAdmin();
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if (!$id) {
      $this->redirigir('/admin/categorias');
      return;
    }

    $categoria = $this->categoriaModelo->obtenerPorId((int) $id);
    if (!$categoria) {
      $this->redirigir('/admin/categoria');
      return;
    }

    require __DIR__ . '/../paginas/categoria/form.phtml';
  }

  /** POST /admin/categorias/actualizar */
  public function actualizar(): void
  {
    Auth::requireAdmin();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirigir('/admin/categoria');
      return;
    }

    $id = filter_var($_POST['id_categoria'] ?? null, FILTER_VALIDATE_INT);
    $datos = $this->sanitizarFormulario($_POST);

    if (!$id || empty($datos['nombre'])) {
      $_SESSION['error'] = 'Datos inválidos.';
      $this->redirigir('/admin/categoria/editar?id=' . $id);
      return;
    }

    $this->categoriaModelo->actualizar((int) $id, $datos);
    $_SESSION['exito'] = 'Categoría actualizada correctamente.';
    $this->redirigir('/admin/categoria');
  }

  /** GET /admin/categorias/eliminar?id=X */
  public function eliminar(?string $id): void
  {
    Auth::requireAdmin();
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if ($id) {
      $this->categoriaModelo->eliminar((int) $id);
      $_SESSION['exito'] = 'Categoría eliminada.';
    }
    $this->redirigir('/admin/categoria');
  }

  // ── Helpers ───────────────────────────────────────────────────────────────

  private function sanitizarFormulario(array $post): array
  {
    return [
      'nombre' => trim($post['nombre'] ?? ''),
      'descripcion' => trim($post['descripcion'] ?? ''),
      'imagen_url' => trim($post['imagen_url'] ?? ''),
    ];
  }

  private function redirigir(string $ruta): void
  {
    header('Location: ' . BASE_URL . $ruta);
    exit;
  }
}
