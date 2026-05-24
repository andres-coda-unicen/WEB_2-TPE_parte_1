<?php
// =====================================================
// Configuración de la base de datos
// Modificar estos valores según el entorno
// =====================================================

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'tpe_web_2');
define('DB_USER', 'root');
define('DB_PASS', 'root');

// URL base del sitio, por si en algun momento cambia la carpeta raiz
define('BASE_URL', '');

// Directorio de uploads
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/');
define('UPLOAD_URL', BASE_URL . '/public/uploads/');