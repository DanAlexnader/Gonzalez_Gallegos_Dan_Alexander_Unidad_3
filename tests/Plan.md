# Plan de pruebas manuales (pendientes de ejecutar en XAMPP)
| Caso | Acción | Resultado esperado |
|---|---|---|
| Registro | Crear cuenta ficticia válida | Cuenta cliente, contraseña hash |
| Login | Cinco claves erróneas; abrir otra ventana privada | Sexto intento bloqueado por IP durante 15 minutos |
| Roles | Cliente entra a admin/index.php | HTTP 403 |
| CSRF | POST a API sin X-CSRF-Token como admin | HTTP 403 |
| API pública | GET api/productos.php | HTTP 200 y JSON |
| API detalle | GET ?id=99999999 | HTTP 404 |
| API crear | Admin usa POST en panel de pruebas | HTTP 201 y nuevo ID |
| API editar | PUT con ID creado y JSON completo | HTTP 200 y datos actualizados |
| API borrar | DELETE con ID creado | HTTP 200 y desaparece del catálogo |
| API inválida | POST con precio negativo | HTTP 400 |
| API no autenticada | POST desde sesión anónima | HTTP 401 |
| API cliente | POST desde sesión cliente | HTTP 403 |
| Método inválido | PATCH | HTTP 405 |
| Moneda | Abrir catálogo con Internet y api/moneda.php | USD, fecha, tasa y HTTP 200 |
| Moneda sin red | Sesión nueva sin acceso Internet | Aviso, catálogo utilizable en MXN |
| XSS | Crear producto con nombre <script>alert(1)</script> | Texto literal, no ejecución |
| SQLi | Buscar comilla simple y texto SQL | Sin error SQL ni acceso indebido |
| Pedido | Comprar producto de prueba | Pedido registrado y stock reducido |

Registrar resultado observado, fecha y captura para cada caso. No confundir resultado esperado con prueba realizada.
