# WEB_2-TPE_parte_1
Trabajo practico entregable para Web_2 de la carrera de Tudai

### Integrantes del grupo ###
-- Andres Coda - acoda@alumnos.exa.unicen.edu.ar

### Tematica del Tpe ###
La idea del trabajo es tener una tienda online que muestre productos para su venta. Estos productos podran filtrarse por categoría.
Va a tener un administrador, quien se encargara de subir, editar, y eliminar productos.
La página podra accederse libremente, sin registrarse, y en un futuro se podrá gestionar compra de productos mediante whatsapp.

### Diagrama de relaciones de entidades ###
El proyecto en principio contara con 3 entidades (usuario, producto, categoria). La entidad usuario es solo para seguridad, va a tener un solo usuario, y va a ser harcodeado. Ahi van a estar los datos del usuario, para poder contactarlo para efectuar alguna compra, y más importante, los datos para verificar que es el administrador y que puede efectuar modificaciones.

Las entidades categoria y producto estan relacionadas de la siguiente manera

categoria (1 ----- N) producto

--> modifique la estructura de la base de datos, agregue imagen a producto y categoria. Y cambie el telefono de INT a VARCHAR(20).

# WEB 2 - TPE Parte 2
En esta segunda parte cree una pagina web dinamica en php que muestra los elementos cargados en base de datos, tanto de productos como de categorias.
También permite al administrador loguerase, cargar nuevos elementos, editar los existentes o eliminarlos.
El administrador es "ej@ej.com" y su pass es "Ejemplo1."


# Mapa del sitiu
El sitio esta estructurado de la siguiente manera:

HOME
 ├── Productos
 │     ├── Detalle
 │     ├── Crear
 │     ├── Editar
 │     └── Eliminar
 │
 ├── Categorías
 │     ├── Productos por categoría
 │     ├── Crear
 │     ├── Editar
 │     └── Eliminar
 │
 └── Login
       └── Logout

En Productos y Categorias, solo se puede acceder a la parte de crear, editar y elimnar siendo administrador. El resto lo puede acceder cualquiera

# Sugerencias de instalación

1-Clonar el repositorio:
  git clone <https://github.com/andres-coda-unicen/WEB_2-TPE_parte_1>

2-Colocar el proyecto (la carpeta TPE_parte_2) dentro de la carpeta htdocs de XAMPP.
  Ejemplo:
    C:\xampp\htdocs\TPE_parte_2

3-Iniciar Apache y MySQL desde el panel de XAMPP.

4-Configurar las credenciales de la base de datos en:
  config/claves.php
    Ejemplo:
      define('DB_HOST', 'localhost');
      define('DB_PORT', '3306');
      define('DB_NAME', 'tpe_web2');
      define('DB_USER', 'root');
      define('DB_PASS', '');

5-Abrir el proyecto en el navegador:
  http://localhost/TPE_parte_2



# Curiosidades extras

La applicación crea la base de datos con sus tablas y datos de ejemplos al ejecutarse por primera vez.