
<?php
$productos = [
    [
        "nombre" => "Audífonos inalámbricos",
        "precio" => 399,
        "icono" => "🎧",
        "descripcion" => "Disfruta tu música favorita."
    ],
    [
        "nombre" => "Teclado inalámbrico",
        "precio" => 299,
        "icono" => "⌨️",
        "descripcion" => "Ideal para estudiar y trabajar."
    ],
    [
        "nombre" => "Mouse inalámbrico",
        "precio" => 199,
        "icono" => "🖱️",
        "descripcion" => "Comodidad para tu computadora."
    ],
    [
        "nombre" => "Bocina Bluetooth",
        "precio" => 499,
        "icono" => "🔊",
        "descripcion" => "Lleva tu música a todas partes."
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore | Tienda en línea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">TechStore</a>
        <div class="ms-auto d-flex gap-3">
            <a class="nav-link text-white" href="login.php">Iniciar sesión</a>
            <a class="nav-link text-white" href="registro.php">Registrarse</a>
            <a class="btn btn-warning" href="carrito.php">🛒 Carrito</a>
        </div>
    </div>
</nav>

<header class="hero text-center">
    <div class="container">
        <h1>Bienvenido a TechStore</h1>
        <p>Encuentra accesorios tecnológicos a excelentes precios.</p>
        <a href="#catalogo" class="btn btn-warning btn-lg">Ver productos</a>
    </div>
</header>

<main class="container my-5" id="catalogo">
    <h2 class="text-center mb-4">Nuestros productos</h2>

    <div class="row g-4">
        <?php foreach ($productos as $producto): ?>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card producto h-100 text-center">
                    <div class="icono-producto">
                        <?= htmlspecialchars($producto["icono"]) ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <?= htmlspecialchars($producto["nombre"]) ?>
                        </h5>
                        <p class="card-text">
                            <?= htmlspecialchars($producto["descripcion"]) ?>
                        </p>
                        <p class="precio">
                            $<?= number_format($producto["precio"], 2) ?> MXN
                        </p>
                        <button class="btn btn-dark"
                                onclick="alert('En el siguiente módulo activaremos el carrito de compras.')">
                            Agregar al carrito
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<footer class="bg-dark text-white text-center p-4">
    <p class="mb-0">TechStore - Proyecto de Aplicación Web, Unidad 3</p>
</footer>

</body>
</html>
