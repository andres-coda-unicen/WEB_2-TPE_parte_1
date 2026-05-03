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

