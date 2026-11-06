<?php
require_once __DIR__ . '/../includes/funciones.php'; requiere_admin();
$productos=db()->query('SELECT * FROM productos ORDER BY id DESC')->fetchAll();
$titulo='Administración | TechStore';require __DIR__ . '/../includes/header.php';
?>
<main class="container my-5"><h1>Administración de productos</h1><p><a class="btn btn-success" href="<?= e(url('admin/producto.php')) ?>">+ Nuevo producto</a></p>
<div class="table-responsive"><table class="table table-striped"><thead><tr><th>ID</th><th>Producto</th><th>Precio</th><th>Stock</th><th>Activo</th><th></th></tr></thead><tbody>
<?php foreach ($productos as $p): ?><tr><td><?= (int)$p['id'] ?></td><td><?= e($p['icono']) ?> <?= e($p['nombre']) ?></td><td><?= pesos($p['precio']) ?></td><td><?= (int)$p['stock'] ?></td><td><?= (int)$p['activo'] ? 'Sí' : 'No' ?></td><td><a class="btn btn-sm btn-outline-dark" href="<?= e(url('admin/producto.php?id='.(int)$p['id'])) ?>">Editar</a></td></tr><?php endforeach; ?>
</tbody></table></div></main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
