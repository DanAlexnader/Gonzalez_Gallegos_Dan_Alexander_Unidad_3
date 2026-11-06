<?php
require_once __DIR__ . '/../includes/funciones.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
try {
    $productos=db()->query('SELECT id,nombre,descripcion,precio,stock,icono FROM productos WHERE activo=1 ORDER BY id DESC')->fetchAll();
    echo json_encode(['ok'=>true,'productos'=>$productos],JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>'No fue posible consultar los productos.']);
}
