<?php
require_once __DIR__ . '/claves.php';

class Database
{
  private static ?PDO $connection = null;

  public static function getConnection(): PDO
  {
    if (self::$connection === null) {
      self::$connection = self::connect();
    }
    return self::$connection;
  }

  private static function connect(): PDO
  {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    try {
      $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
      self::autoDeploy($pdo);
      return $pdo;
    } catch (PDOException $e) {
      // Si la BD no existe, crearla primero y reconectar
      if ($e->getCode() == 1049) {
        self::crearBaseDeDatos();
        return self::connect();
      }
      die("Error de conexión a la base de datos: " . $e->getMessage());
    }
  }

  private static function crearBaseDeDatos(): void
  {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
  }

  private static function autoDeploy(PDO $pdo): void
  {
    $stmt = $pdo->query(
      "SELECT COUNT(*) FROM information_schema.tables 
        WHERE table_schema = '" . DB_NAME . "' AND table_name = 'categoria'"
    );
    $exists = (int) $stmt->fetchColumn();

    if ($exists === 0) {
      self::createSchema($pdo);
      self::seedData($pdo);
    }
  }

  private static function createSchema(PDO $pdo): void
  {
    $pdo->exec("
      CREATE TABLE IF NOT EXISTS categoria (
        id_categoria INT AUTO_INCREMENT PRIMARY KEY,
        nombre       VARCHAR(100) NOT NULL,
        descripcion  VARCHAR(255),
        imagen_url   VARCHAR(500)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

      CREATE TABLE IF NOT EXISTS producto (
        id_producto  INT AUTO_INCREMENT PRIMARY KEY,
        nombre       VARCHAR(100) NOT NULL,
        descripcion  TEXT,
        precio       DECIMAL(10,2) NOT NULL,
        stock        INT NOT NULL DEFAULT 0,
        imagen_url   VARCHAR(500),
        id_categoria INT,
        FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
            ON UPDATE CASCADE ON DELETE SET NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

      CREATE TABLE IF NOT EXISTS usuario (
        id_usuario INT AUTO_INCREMENT PRIMARY KEY,
        email      VARCHAR(100) NOT NULL UNIQUE,
        password   VARCHAR(255) NOT NULL,
        es_admin   TINYINT(1) DEFAULT 1,
        telefono   VARCHAR(20)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  ");
  }

  private static function seedData(PDO $pdo): void
  {
    $hashEjemplo = password_hash('Ejemplo1.', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
      INSERT INTO usuario (email, password, es_admin, telefono)
      VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
      'ej@ej.com',
      $hashEjemplo,
      true,
      '2244571825'
    ]);

    $pdo->exec("
      INSERT INTO categoria (nombre, descripcion, imagen_url) VALUES
      ('Electrónica',  'Dispositivos electrónicos y accesorios tecnológicos', 'https://i.pinimg.com/originals/3c/2e/f4/3c2ef45475e0dbd5980acd0627bf0bcc.jpg'),
      ('Ropa',         'Indumentaria y accesorios de moda', 'https://i.pinimg.com/1200x/fa/5c/bd/fa5cbd0d6b4c762ddd0ceed71fa72331.jpg'),
      ('Hogar',        'Artículos para el hogar y decoración', 'https://i.pinimg.com/736x/72/a2/d7/72a2d747e25acdad18b10196fcc03e97.jpg'),
      ('Deportes',     'Equipamiento y ropa deportiva', 'https://i.pinimg.com/1200x/59/cf/d6/59cfd6f365aca650970fe832ff2fefcd.jpg'),
      ('Libros',       'Libros, revistas y material educativo','https://i.pinimg.com/736x/98/10/d3/9810d3cbc3c5fa2e968d3595b53ec6ad.jpg');
    ");

    $pdo->exec("
      INSERT INTO producto (nombre, descripcion, precio, stock, imagen_url, id_categoria) VALUES
      ('Auriculares Bluetooth', 'Auriculares inalámbricos con cancelación de ruido activa', 15999.99, 25, 'https://i.pinimg.com/736x/4b/7f/14/4b7f146db7814fc52483d6fe14993108.jpg', 1),
      ('Cargador USB-C 65W',    'Cargador rápido compatible con notebooks y celulares',     3499.00,  50, 'https://i.pinimg.com/1200x/bd/35/18/bd35186a51aba22ec270d1eb73da4428.jpg', 1),
      ('Remera básica algodón', 'Remera de algodón peinado, disponible en varios colores',  2990.00, 100, 'https://i.pinimg.com/1200x/79/9b/6e/799b6e179fcbf5afa6b404df55f92543.jpg', 2),
      ('Campera impermeable',   'Campera cortaviento e impermeable para exteriores',        18500.00, 15, 'https://i.pinimg.com/1200x/42/59/6c/42596c014118964358be19c1671ec93e.jpg', 2),
      ('Set de sábanas',        'Set de sábanas 100% algodón 200 hilos, 2 plazas',          8750.00, 30, 'https://i.pinimg.com/1200x/d8/13/cc/d813cc86421f24f32ef37b79948e616b.jpg', 3),
      ('Pelota de fútbol',      'Pelota oficial tamaño 5, del mundial 2026',                   4200.00, 40, 'https://i.pinimg.com/736x/73/49/ed/7349ed5b808e357d7da1b356b3c62d60.jpg', 4),
      ('Codigo limpio',            'Libro de Robert C. Martin sobre buenas prácticas',         6500.00, 20, 'https://i.pinimg.com/736x/8e/b4/2a/8eb42af248e45067eff294d69c8c6419.jpg', 5);
    ");

  }
}
