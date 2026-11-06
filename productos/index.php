<?php
require_once __DIR__ . '/../includes/funciones.php';
$busqueda = trim((string)($_GET['q'] ?? ''));
if ($busqueda !== '') {
    $q = db()->prepare('SELECT * FROM productos WHERE activo=1 AND nombre LIKE ? ORDER BY id DESC');
    $q->execute(['%' . $busqueda . '%']);
    $productos = $q->fetchAll();
} else { $productos = db()->query('SELECT * FROM productos WHERE activo=1 ORDER BY id DESC')->fetchAll(); }
$titulo = 'Catálogo | TechStore';
require __DIR__ . '/../includes/header.php';
?>
<main class="container my-5"><h1 class="mb-4">Catálogo de productos</h1>
<form method="get" class="row g-2 mb-4"><div class="col"><input class="form-control" type="search" name="q" placeholder="Buscar producto" value="<?= e($busqueda) ?>"></div><div class="col-auto"><button class="btn btn-dark">Buscar</button></div></form>
<?php require __DIR__ . '/../includes/tarjetas_productos.php'; ?>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
