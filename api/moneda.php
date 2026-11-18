<?php
require_once __DIR__.'/../includes/funciones.php'; require_once __DIR__.'/../services/Moneda.php';
header('Content-Type: application/json; charset=utf-8'); header('Cache-Control: no-store');
if ($_SERVER['REQUEST_METHOD']!=='GET') { header('Allow: GET'); http_response_code(405); exit('{"ok":false}'); }
$t=tipo_cambio(); if (!$t) http_response_code(503);
echo json_encode($t?['ok'=>true]+$t:['ok'=>false,'error'=>'Servicio externo no disponible']);
