<?php
require_once __DIR__ . '/includes/funciones.php'; requiere_login();
$q=db()->prepare('SELECT id,total,estado,creado_en FROM pedidos WHERE usuario_id=? ORDER BY id DESC');
$q->execute([usuario()['id']]);$pedidos=$q->fetchAll();
$detalles=db()->prepare('SELECT nombre_producto,cantidad,precio_unitario FROM pedido_detalles WHERE pedido_id=? ORDER BY id');
$titulo='Mis pedidos | TechStore';require __DIR__ . '/includes/header.php';
?>
<main class="container my-5"><h1>Mis pedidos</h1>
<?php if (isset($_GET['creado'])): ?><div class="alert alert-success">Pedido registrado correctamente.</div><?php endif; ?>
<?php if (!$pedidos): ?><p>No tienes pedidos todavía.</p><?php endif; ?>
<?php foreach ($pedidos as $p): ?><div class="card p-3 mb-3"><h5>Pedido #<?= (int)$p['id'] ?> — <?= pesos($p['total']) ?></h5>
<p class="text-muted mb-2">Estado: <?= e($p['estado']) ?> · <?= e($p['creado_en']) ?></p>
<?php $detalles->execute([$p['id']]);foreach ($detalles->fetchAll() as $d): ?><div><?= e($d['nombre_producto']) ?> × <?= (int)$d['cantidad'] ?> — <?= pesos($d['precio_unitario']) ?> c/u</div><?php endforeach; ?>
</div><?php endforeach; ?></main>
<?php require __DIR__ . '/includes/footer.php'; ?>
