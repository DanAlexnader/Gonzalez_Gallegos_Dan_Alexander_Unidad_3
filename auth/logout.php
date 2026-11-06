<?php
require_once __DIR__ . '/../includes/funciones.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Método no permitido.'); }
verificar_csrf();
$_SESSION=[];
session_regenerate_id(true);
ir('index.php');
