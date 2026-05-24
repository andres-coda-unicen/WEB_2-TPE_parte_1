<?php
require_once __DIR__ . '/Auth.php';
require_once __DIR__ . '/../modelo/ProductoModelo.php';
require_once __DIR__ . '/../modelo/CategoriaModelo.php';

class ProductoController
{
  private ProductoModelo $productoModelo;
  private CategoriaModelo $categoriaModelo;

  public function __construct()
  {
    $this->productoModelo = new ProductoModelo();
    $this->categoriaModelo = new CategoriaModelo();
  }

  // ── Públicas ──────────────────────────────────────────────────────────────

  /** GET /productos — Listado público de todos los productos */
  public function index(): void
  {
    $productos = $this->productoModelo->obtenerTodos();
    $categorias = $this->categoriaModelo->obtenerTodas();
    require __DIR__ . '/../paginas/productos/listado.phtml';
  }

  /** GET /productos/detalle?id=X — Detalle de un producto */
  public function detalle(?string $id): void
  {
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if (!$id) {
      $this->redirigir('/productos');
      return;
    }

    $producto = $this->productoModelo->obtenerPorId((int) $id);
    if (!$producto) {
      $this->redirigir('/productos');
      return;
    }

    require __DIR__ . '/../paginas/productos/detalle.phtml';
  }

  // ── Admin ─────────────────────────────────────────────────────────────────

  /** GET /admin/productos — Listado admin */
  public function adminIndex(): void
  {
    Auth::requireAdmin();
    $productos = $this->productoModelo->obtenerTodos();
    require __DIR__ . '/../paginas/productos/admin_listado.phtml';
  }

  /** GET /admin/productos/nuevo — Formulario de alta */
  public function nuevo(): void
  {
    Auth::requireAdmin();
    $categorias = $this->categoriaModelo->obtenerTodas();
    require __DIR__ . '/../paginas/productos/form.phtml';
  }

  /** POST /admin/productos/guardar — Procesar alta */
  public function guardar(): void
  {
    Auth::requireAdmin();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirigir('/admin/productos');
      return;
    }

    $datos = $this->sanitizarFormulario($_POST);
    if (!$this->validar($datos)) {
      $_SESSION['error'] = 'Todos los campos obligatorios deben completarse.';
      $this->redirigir('/admin/productos/nuevo');
      return;
    }

    $this->productoModelo->insertar($datos);
    $_SESSION['exito'] = 'Producto agregado correctamente.';
    $this->redirigir('/admin/productos');
  }

  /** GET /admin/productos/editar?id=X — Formulario de edición */
  public function editar(?string $id): void
  {
    Auth::requireAdmin();
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if (!$id) {
      $this->redirigir('/admin/productos');
      return;
    }

    $producto = $this->productoModelo->obtenerPorId((int) $id);
    $categorias = $this->categoriaModelo->obtenerTodas();
    if (!$producto) {
      $this->redirigir('/admin/productos');
      return;
    }

    require __DIR__ . '/../paginas/productos/form.phtml';
  }

  /** POST /admin/productos/actualizar — Procesar edición */
  public function actualizar(): void
  {
    Auth::requireAdmin();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirigir('/admin/productos');
      return;
    }

    $id = filter_var($_POST['id_producto'] ?? null, FILTER_VALIDATE_INT);
    $datos = $this->sanitizarFormulario($_POST);

    if (!$id || !$this->validar($datos)) {
      $_SESSION['error'] = 'Datos inválidos.';
      $this->redirigir('/admin/productos/editar?id=' . $id);
      return;
    }

    $this->productoModelo->actualizar((int) $id, $datos);
    $_SESSION['exito'] = 'Producto actualizado correctamente.';
    $this->redirigir('/admin/productos');
  }

  /** GET /admin/productos/eliminar?id=X — Eliminar producto */
  public function eliminar(?string $id): void
  {
    Auth::requireAdmin();
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if ($id) {
      $this->productoModelo->eliminar((int) $id);
      $_SESSION['exito'] = 'Producto eliminado.';
    }
    $this->redirigir('/admin/productos');
  }

  // ── Helpers ───────────────────────────────────────────────────────────────

  private function sanitizarFormulario(array $post): array
  {
    return [
      'nombre' => trim($post['nombre'] ?? ''),
      'descripcion' => trim($post['descripcion'] ?? ''),
      'precio' => filter_var($post['precio'] ?? 0, FILTER_VALIDATE_FLOAT),
      'stock' => filter_var($post['stock'] ?? 0, FILTER_VALIDATE_INT),
      'imagen_url' => trim($post['imagen_url'] ?? ''),
      'id_categoria' => filter_var($post['id_categoria'] ?? null, FILTER_VALIDATE_INT),
    ];
  }

  private function validar(array $datos): bool
  {
    return !empty($datos['nombre'])
      && $datos['precio'] !== false && $datos['precio'] > 0
      && $datos['stock'] !== false && $datos['stock'] >= 0
      && !empty($datos['id_categoria']);
  }

  private function redirigir(string $ruta): void
  {
    header('Location: ' . BASE_URL . $ruta);
    exit;
  }
}
