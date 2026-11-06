# TechStore — Unidad 3

Proyecto de demostración en PHP 8+, MySQL y Bootstrap 5. Registro, inicio de sesión, catálogo, carrito con sesiones, pedidos de prueba, administración de productos y API de lectura.

## Instalación en XAMPP

1. Descomprime la carpeta **TechStore_completo** dentro de `C:\xampp\htdocs\Trabajos9no_cuatri\` sin reemplazar tu `DWI u3` original.
2. Inicia Apache y MySQL en XAMPP.
3. En `http://localhost/phpmyadmin`, usa **Importar** y selecciona `sql/techstore.sql` para crear la base y cuatro productos.
4. Revisa `config/app.php`: `DB_PORT` (`3306` o `3307`), `DB_USER`, `DB_PASS` y `BASE_URL`. Si copias el contenido dentro de tu carpeta `DWI u3`, cambia `BASE_URL` a `/Trabajos9no_cuatri/DWI%20u3`.
5. Visita `http://localhost/Trabajos9no_cuatri/TechStore_completo/`.
6. Registra un usuario desde la web y prueba el carrito y los pedidos.

## Acceso administrador (solo local)

Primero registra tu usuario en la web; después ejecuta en phpMyAdmin, cambiando el correo por el tuyo:

```sql
UPDATE usuarios SET rol='admin' WHERE email='tu_correo@ejemplo.com';
```

Cierra sesión e inicia de nuevo. El enlace **Admin** permitirá crear o editar productos. No existe un registro público de administradores.

## Rutas principales

- `/index.php`: inicio
- `/productos/index.php`: catálogo y búsqueda
- `/productos/detalle.php?id=1`: detalle
- `/auth/registro.php`, `/auth/login.php`, `/auth/logout.php`: cuentas
- `/carrito.php`, `/checkout.php`, `/pedidos.php`: compra de prueba
- `/admin/index.php`: administración
- `/api/productos.php`: JSON público de productos activos

## Seguridad y límites

Usa consultas preparadas PDO, hash de contraseñas, tokens CSRF, salida HTML escapada, regeneración de sesión y transacciones al crear pedidos. Es una práctica académica: no procesa pagos ni envíos y requiere configuración adicional antes de publicarse en Internet. El bloqueo de intentos de login se basa en sesión y no sustituye un rate limit de servidor.
