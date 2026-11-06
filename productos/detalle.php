<?php
require_once __DIR__ . '/../includes/funciones.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$q = db()->prepare('SELECT * FROM productos WHERE id=? AND activo=1');
$q->execute([$id ?: 0]); $p = $q->fetch();
if (!$p) { http_response_code(404); exit('Producto no encontrado.'); }
$titulo = $p['nombre'] . ' | TechStore';
require __DIR__ . '/../includes/header.php';
?>
<main class="container my-5"><a href="<?= e(url('productos/index.php')) ?>">← Volver al catálogo</a>
<div class="card p-4 mt-3"><div class="row align-items-center"><div class="col-md-4 text-center icono-producto"><?= e($p['icono']) ?></div>
<div class="col-md-8"><h1><?= e($p['nombre']) ?></h1><p><?= e($p['descripcion']) ?></p><p class="precio"><?= pesos($p['precio']) ?></p><p>Existencias: <?= (int)$p['stock'] ?></p>
<form action="<?= e(url('carrito.php')) ?>" method="post">
<input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>"><input type="hidden" name="accion" value="agregar"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
<button class="btn btn-dark" <?= (int)$p['stock'] < 1 ? 'disabled' : '' ?>>Agregar al carrito</button>
</form></div></div></div></main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
