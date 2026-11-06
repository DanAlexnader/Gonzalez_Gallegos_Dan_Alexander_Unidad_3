<?php
require_once __DIR__ . '/includes/funciones.php'; requiere_login();
$error='';
$items=productos_carrito();
if (!$items) ir('carrito.php');
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verificar_csrf();
    try {
        $pdo=db();$pdo->beginTransaction();
        $ids=array_column($items,'id');$marcas=implode(',',array_fill(0,count($ids),'?'));
        $q=$pdo->prepare("SELECT id,nombre,precio,stock FROM productos WHERE activo=1 AND id IN ($marcas) FOR UPDATE");
        $q->execute($ids);$catalogo=[];
        foreach ($q->fetchAll() as $p) $catalogo[(int)$p['id']]=$p;
        $total=0; $detalle=[];
        foreach ($items as $item) {
            $id=(int)$item['id'];$cantidad=(int)$item['cantidad'];
            if (!isset($catalogo[$id]) || $catalogo[$id]['stock']<$cantidad) throw new RuntimeException('Un producto ya no tiene existencias suficientes.');
            $p=$catalogo[$id];$subtotal=round((float)$p['precio']*$cantidad,2);
            $total+=$subtotal;$detalle[]=[$id,$p['nombre'],$cantidad,$p['precio'],$subtotal];
        }
        $q=$pdo->prepare("INSERT INTO pedidos (usuario_id,total,estado) VALUES (?,?,'pendiente')");
        $q->execute([usuario()['id'],$total]);$pedidoId=(int)$pdo->lastInsertId();
        $ins=$pdo->prepare('INSERT INTO pedido_detalles (pedido_id,producto_id,nombre_producto,cantidad,precio_unitario,subtotal) VALUES (?,?,?,?,?,?)');
        $stock=$pdo->prepare('UPDATE productos SET stock=stock-? WHERE id=? AND stock>=?');
        foreach ($detalle as [$id,$nombre,$cantidad,$precio,$subtotal]) {
            $stock->execute([$cantidad,$id,$cantidad]);
            if ($stock->rowCount()!==1) throw new RuntimeException('No hay existencias suficientes.');
            $ins->execute([$pedidoId,$id,$nombre,$cantidad,$precio,$subtotal]);
        }
        $pdo->commit();$_SESSION['carrito']=[];ir('pedidos.php?creado='.$pedidoId);
    } catch (Throwable $ex) {
        if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
        $error=$ex instanceof RuntimeException ? $ex->getMessage() : 'No se pudo registrar el pedido.';
    }
}
$total=array_sum(array_column($items,'subtotal'));
$titulo='Confirmar pedido | TechStore';require __DIR__ . '/includes/header.php';
?>
<main class="container my-5" style="max-width:750px"><h1>Confirmar pedido</h1>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<div class="card p-4"><p>Pedido a nombre de <strong><?= e(usuario()['nombre']) ?></strong>.</p>
<?php foreach ($items as $p): ?><p class="mb-1"><?= e($p['nombre']) ?> × <?= (int)$p['cantidad'] ?> — <?= pesos($p['subtotal']) ?></p><?php endforeach; ?>
<hr><h3>Total: <?= pesos($total) ?></h3><p class="text-muted">Esta es una compra de demostración: no se procesa ningún pago real.</p>
<form method="post"><input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>"><button class="btn btn-success">Confirmar pedido de prueba</button></form>
</div></main>
<?php require __DIR__ . '/includes/footer.php'; ?>
