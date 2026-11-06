<?php
require_once __DIR__ . '/includes/funciones.php';
$productos = db()->query('SELECT id,nombre,precio,stock,icono,descripcion FROM productos WHERE activo=1 ORDER BY id DESC LIMIT 8')->fetchAll();
require __DIR__ . '/includes/header.php';
?>
<header class="hero text-center"><div class="container">
<h1>Bienvenido a TechStore</h1><p>Encuentra accesorios tecnológicos a excelentes precios.</p>
<a href="#catalogo" class="btn btn-warning btn-lg">Ver productos</a>
</div></header>
<main class="container my-5" id="catalogo"><h2 class="text-center mb-4">Nuestros productos</h2>
<?php require __DIR__ . '/includes/tarjetas_productos.php'; ?>
<div class="text-center mt-4"><a href="<?= e(url('productos/index.php')) ?>" class="btn btn-outline-dark">Ver todo el catálogo</a></div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
