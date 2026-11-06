<?php if (!$productos): ?><p class="alert alert-info">Aún no hay productos disponibles.</p><?php endif; ?>
<div class="row g-4">
<?php foreach ($productos as $producto): ?>
 <div class="col-12 col-sm-6 col-lg-3"><div class="card producto h-100 text-center">
   <div class="icono-producto"><?= e($producto['icono']) ?></div>
   <div class="card-body d-flex flex-column">
     <h5 class="card-title"><?= e($producto['nombre']) ?></h5>
     <p class="card-text"><?= e($producto['descripcion']) ?></p>
     <p class="precio mt-auto"><?= pesos($producto['precio']) ?></p>
     <p class="small text-muted">Disponibles: <?= (int)$producto['stock'] ?></p>
     <a class="btn btn-outline-secondary mb-2" href="<?= e(url('productos/detalle.php?id=' . (int)$producto['id'])) ?>">Ver detalle</a>
     <form action="<?= e(url('carrito.php')) ?>" method="post">
       <input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>">
       <input type="hidden" name="accion" value="agregar">
       <input type="hidden" name="id" value="<?= (int)$producto['id'] ?>">
       <button class="btn btn-dark w-100" <?= (int)$producto['stock'] < 1 ? 'disabled' : '' ?>>Agregar al carrito</button>
     </form>
   </div>
 </div></div>
<?php endforeach; ?>
</div>
