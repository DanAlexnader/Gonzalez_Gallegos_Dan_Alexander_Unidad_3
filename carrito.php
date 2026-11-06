<?php
require_once __DIR__ . '/includes/funciones.php';
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verificar_csrf();
    $accion=(string)($_POST['accion'] ?? ''); $id=filter_var($_POST['id'] ?? null,FILTER_VALIDATE_INT);
    if ($accion==='vaciar') { $_SESSION['carrito']=[]; ir('carrito.php'); }
    if ($id && $id>0) {
        if ($accion==='quitar') { unset($_SESSION['carrito'][$id]); ir('carrito.php'); }
        $q=db()->prepare('SELECT stock FROM productos WHERE id=? AND activo=1');$q->execute([$id]);$p=$q->fetch();
        if ($p) {
            $actual=(int)($_SESSION['carrito'][$id] ?? 0);
            if ($accion==='agregar') $cantidad=$actual+1;
            elseif ($accion==='actualizar') $cantidad=filter_var($_POST['cantidad'] ?? null,FILTER_VALIDATE_INT);
            else $cantidad=false;
            if ($cantidad !== false && $cantidad !== null && $cantidad>=0 && $cantidad<=99) {
                $cantidad=min($cantidad,(int)$p['stock']);
                if ($cantidad) $_SESSION['carrito'][$id]=$cantidad;
                else unset($_SESSION['carrito'][$id]);
                ir('carrito.php');
            }
        }
    }
    $error='No fue posible modificar el carrito. Comprueba disponibilidad y cantidad.';
}
$items=productos_carrito();$total=array_sum(array_column($items,'subtotal'));
$titulo='Carrito | TechStore';require __DIR__ . '/includes/header.php';
?>
<main class="container my-5"><h1>Mi carrito</h1>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if (!$items): ?><p class="alert alert-info">Tu carrito está vacío. <a href="<?= e(url('productos/index.php')) ?>">Ver productos</a></p>
<?php else: ?><div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th></th></tr></thead><tbody>
<?php foreach ($items as $p): ?><tr><td><?= e($p['icono']) ?> <?= e($p['nombre']) ?></td><td><?= pesos($p['precio']) ?></td><td>
<form method="post" class="d-flex gap-2"><input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>"><input type="hidden" name="accion" value="actualizar"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><input type="number" aria-label="Cantidad" name="cantidad" class="form-control" style="width:90px" min="0" max="<?= min(99,(int)$p['stock']) ?>" value="<?= (int)$p['cantidad'] ?>"><button class="btn btn-outline-dark btn-sm">Actualizar</button></form>
</td><td><?= pesos($p['subtotal']) ?></td><td><form method="post"><input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>"><input type="hidden" name="accion" value="quitar"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="btn btn-outline-danger btn-sm">Quitar</button></form></td></tr><?php endforeach; ?>
</tbody></table></div><h3 class="text-end">Total: <?= pesos($total) ?></h3>
<div class="d-flex justify-content-end flex-wrap gap-2"><form method="post"><input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>"><input type="hidden" name="accion" value="vaciar"><button class="btn btn-outline-danger">Vaciar carrito</button></form><a class="btn btn-success" href="<?= e(url('checkout.php')) ?>">Continuar pedido</a></div>
<?php endif; ?></main>
<?php require __DIR__ . '/includes/footer.php'; ?>
