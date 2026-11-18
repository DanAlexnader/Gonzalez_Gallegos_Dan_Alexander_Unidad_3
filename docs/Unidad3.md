# Unidad 3 — TechStore
## Caso de estudio
Tienda académica de accesorios tecnológicos, con registro, catálogo, carrito, pedidos simulados y administración. No procesa pagos reales.
## Arquitectura y patrones
Arquitectura por capas con separación parcial tipo MVC: páginas PHP controlan solicitudes; includes y HTML presentan vistas; models/Producto.php encapsula persistencia de productos (Repository); config/conexion.php reutiliza una instancia PDO por solicitud (Singleton a nivel de función); services/Moneda.php encapsula la integración externa. Las páginas originales aún combinan controlador y vista, por lo que no es MVC estricto. Bootstrap 5 es el framework de interfaz.
## Metodología ágil propuesta (Scrum)
Backlog: catálogo, cuentas, pedidos, seguridad, REST, conversión, pruebas y reporte. Sprint 1: catálogo y cuentas; sprint 2: carrito, pedidos y permisos; sprint 3: servicios y evidencias. Registrar fechas reales, tareas completadas, revisión y retrospectiva. No afirmar que se efectuaron reuniones o sprints que no ocurrieron.
## Servicios
Frankfurter: GET https://api.frankfurter.dev/v1/latest?base=MXN&symbols=USD. El backend consulta por HTTPS, guarda temporalmente la tasa en sesión y devuelve JSON mediante api/moneda.php. La interfaz muestra dólares informativos; los pedidos siguen en MXN. Si falla, muestra aviso sin inventar tasas.
API propia: GET api/productos.php (lista); GET ?id=N (detalle); POST (alta); PUT ?id=N (edición completa); DELETE ?id=N (desactivación lógica). Modificaciones requieren sesión admin, cabecera X-CSRF-Token y JSON para POST/PUT. Usar panel Admin > Probar API REST para obtener evidencias.
## Seguridad
PDO preparado, hash de contraseña, escape HTML, CSRF, control de roles, cookie HttpOnly/SameSite, Secure bajo HTTPS, regeneración de sesión, límites persistentes por IP tras cinco errores, bloqueo de 15 minutos, transacciones, configuración local ignorada en Git. El límite por IP afecta equipos compartidos; no equivale a una protección distribuida de producción. En producción exigir HTTPS, configurar credenciales limitadas y completar aviso de privacidad.
## Evidencias a capturar
Inicio, catálogo con USD y fecha de tasa, JSON de moneda, registro, pedido, panel admin, API GET/POST/PUT/DELETE y códigos HTTP, rechazo de cliente, rechazo CSRF, bloqueo de login, hash en base de datos usando cuenta ficticia, historial Git. Ocultar correos reales, cookies, tokens y contraseñas. No fabricar capturas.
## Referencias para consultar y elaborar APA
- PHP: https://www.php.net/manual/es/
- Bootstrap: https://getbootstrap.com/docs/5.3/
- Frankfurter: https://frankfurter.dev/
- OWASP: https://owasp.org/www-project-top-ten/
- Git: https://git-scm.com/doc
- Scrum Guide: https://scrumguides.org/
