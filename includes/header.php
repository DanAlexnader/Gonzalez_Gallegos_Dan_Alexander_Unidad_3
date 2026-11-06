<?php
require_once __DIR__ . '/funciones.php';
$titulo = $titulo ?? 'TechStore | Tienda en línea';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($titulo) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= e(url('assets/css/estilos.css')) ?>">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container flex-wrap gap-2">
    <a class="navbar-brand fw-bold" href="<?= e(url('index.php')) ?>">TechStore</a>
    <div class="d-flex flex-wrap align-items-center gap-3 ms-auto">
      <a class="nav-link text-white" href="<?= e(url('productos/index.php')) ?>">Productos</a>
      <?php if (usuario()): ?>
        <a class="nav-link text-white" href="<?= e(url('pedidos.php')) ?>">Mis pedidos</a>
        <?php if (usuario()['rol'] === 'admin'): ?><a class="nav-link text-warning" href="<?= e(url('admin/index.php')) ?>">Admin</a><?php endif; ?>
        <span class="text-white-50 small">Hola, <?= e(usuario()['nombre']) ?></span>
        <form action="<?= e(url('auth/logout.php')) ?>" method="post" class="m-0">
          <input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>">
          <button class="btn btn-outline-light btn-sm">Salir</button>
        </form>
      <?php else: ?>
        <a class="nav-link text-white" href="<?= e(url('auth/login.php')) ?>">Iniciar sesión</a>
        <a class="nav-link text-white" href="<?= e(url('auth/registro.php')) ?>">Registrarse</a>
      <?php endif; ?>
      <a class="btn btn-warning" href="<?= e(url('carrito.php')) ?>">🛒 Carrito (<?= array_sum($_SESSION['carrito'] ?? []) ?>)</a>
    </div>
  </div>
</nav>
