<?php
session_start();

require_once __DIR__ . '/config/dataBase.php';
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
  '/producto' => fn() => $productoCtrl->index(),
  '/producto/detalle' => fn() => $productoCtrl->detalle($_GET['id'] ?? null),
  '/categoria' => fn() => $categoriaCtrl->index(),
  '/categoria/producto' => fn() => $categoriaCtrl->productosPorCategoria($_GET['id'] ?? null),

  // Auth
  '/login' => fn() => $authCtrl->loginForm(),
  '/login/procesar' => fn() => $authCtrl->login(),
  '/logout' => fn() => $authCtrl->logout(),

  // Admin — productos
  '/admin/producto' => fn() => $productoCtrl->adminIndex(),
  '/admin/producto/nuevo' => fn() => $productoCtrl->nuevo(),
  '/admin/producto/guardar' => fn() => $productoCtrl->guardar(),
  '/admin/producto/editar' => fn() => $productoCtrl->editar($_GET['id'] ?? null),
  '/admin/producto/actualizar' => fn() => $productoCtrl->actualizar(),
  '/admin/producto/eliminar' => fn() => $productoCtrl->eliminar($_GET['id'] ?? null),

  // Admin — categorías
  '/admin/categoria' => fn() => $categoriaCtrl->adminIndex(),
  '/admin/categoria/nueva' => fn() => $categoriaCtrl->nueva(),
  '/admin/categoria/guardar' => fn() => $categoriaCtrl->guardar(),
  '/admin/categoria/editar' => fn() => $categoriaCtrl->editar($_GET['id'] ?? null),
  '/admin/categoria/actualizar' => fn() => $categoriaCtrl->actualizar(),
  '/admin/categoria/eliminar' => fn() => $categoriaCtrl->eliminar($_GET['id'] ?? null),
];

if (array_key_exists($ruta, $rutas)) {
  $rutas[$ruta]();
} else {
  http_response_code(404);
  echo "<h1>404 - Página no encontrada</h1><a href='" . BASE_URL . "/'>Volver al inicio</a>";
}
