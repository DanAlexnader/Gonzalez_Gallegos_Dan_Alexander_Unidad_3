<?php
require_once __DIR__.'/../includes/funciones.php'; requiere_admin(); $titulo='Pruebas de API'; require __DIR__.'/../includes/header.php'; ?>
<main class="container my-5" id="api-demo" data-url="<?= e(url('api/productos.php')) ?>" data-csrf="<?= e(token_csrf()) ?>"><h1>Pruebas de API REST</h1><p>Usa productos de prueba. Las modificaciones afectan el catálogo real de la demostración.</p>
<label class="form-label">Método<select id="metodo" class="form-select"><option>GET</option><option>POST</option><option>PUT</option><option>DELETE</option></select></label>
<label class="form-label">ID (opcional para GET; obligatorio para PUT y DELETE)<input id="producto-id" type="number" min="1" class="form-control"></label>
<label class="form-label d-block">JSON para POST o PUT<textarea id="json" class="form-control" rows="8">{"nombre":"Producto de prueba","descripcion":"Prueba API","precio":99,"stock":5,"icono":"📦","activo":1}</textarea></label>
<button id="ejecutar" class="btn btn-primary">Ejecutar solicitud</button><pre id="resultado" class="bg-light border p-3 mt-3" aria-live="polite"></pre></main><script src="<?= e(url('assets/api-demo.js')) ?>" defer></script>
<?php require __DIR__.'/../includes/footer.php'; ?>
