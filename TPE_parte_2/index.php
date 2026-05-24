<?php
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/controller/ProductoController.php';
require_once __DIR__ . '/app/controller/CategoriaController.php';
require_once __DIR__ . '/app/controller/AuthController.php';

// ─── Router ───────────────────────────────────────────────────────────────────
// Obtener la ruta desde el query string generado por .htaccess
$ruta = $_GET['ruta'] ?? '/';
$ruta = '/' . trim($ruta, '/');

$productoCtrl = new ProductoController();
$categoriaCtrl = new CategoriaController();
$authCtrl = new AuthController();

// Rutas públicas
$rutas = [
  '/' => fn() => $productoCtrl->index(),
  '/productos' => fn() => $productoCtrl->index(),
  '/productos/detalle' => fn() => $productoCtrl->detalle($_GET['id'] ?? null),
  '/categorias' => fn() => $categoriaCtrl->index(),
  '/categorias/productos' => fn() => $categoriaCtrl->productosPorCategoria($_GET['id'] ?? null),

  // Auth
  '/login' => fn() => $authCtrl->loginForm(),
  '/login/procesar' => fn() => $authCtrl->login(),
  '/logout' => fn() => $authCtrl->logout(),

  // Admin — productos
  '/admin/productos' => fn() => $productoCtrl->adminIndex(),
  '/admin/productos/nuevo' => fn() => $productoCtrl->nuevo(),
  '/admin/productos/guardar' => fn() => $productoCtrl->guardar(),
  '/admin/productos/editar' => fn() => $productoCtrl->editar($_GET['id'] ?? null),
  '/admin/productos/actualizar' => fn() => $productoCtrl->actualizar(),
  '/admin/productos/eliminar' => fn() => $productoCtrl->eliminar($_GET['id'] ?? null),

  // Admin — categorías
  '/admin/categorias' => fn() => $categoriaCtrl->adminIndex(),
  '/admin/categorias/nueva' => fn() => $categoriaCtrl->nueva(),
  '/admin/categorias/guardar' => fn() => $categoriaCtrl->guardar(),
  '/admin/categorias/editar' => fn() => $categoriaCtrl->editar($_GET['id'] ?? null),
  '/admin/categorias/actualizar' => fn() => $categoriaCtrl->actualizar(),
  '/admin/categorias/eliminar' => fn() => $categoriaCtrl->eliminar($_GET['id'] ?? null),
];

if (array_key_exists($ruta, $rutas)) {
  $rutas[$ruta]();
} else {
  http_response_code(404);
  echo "<h1>404 - Página no encontrada</h1><a href='" . BASE_URL . "/'>Volver al inicio</a>";
}
