<?php
require_once __DIR__ . '/../config/conexion.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
function url(string $ruta = ''): string { return BASE_URL . ($ruta !== '' ? '/' . ltrim($ruta, '/') : '/'); }
function e($texto): string { return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8'); }
function pesos($monto): string { return '$' . number_format((float)$monto, 2) . ' MXN'; }
function ir(string $ruta): void { header('Location: ' . url($ruta)); exit; }
function token_csrf(): string {
    if (!isset($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verificar_csrf(): void {
    if (!hash_equals(token_csrf(), (string)($_POST['csrf'] ?? ''))) {
        http_response_code(403); exit('Solicitud no válida (CSRF).');
    }
}
function usuario(): ?array { return $_SESSION['usuario'] ?? null; }
function requiere_login(): void { if (!usuario()) ir('auth/login.php'); }
function requiere_admin(): void {
    requiere_login();
    if ((usuario()['rol'] ?? '') !== 'admin') { http_response_code(403); exit('Acceso exclusivo para administradores.'); }
}
function productos_carrito(): array {
    $cantidades = $_SESSION['carrito'] ?? [];
    if (!$cantidades) return [];
    $ids = array_values(array_filter(array_map('intval', array_keys($cantidades)), fn($id) => $id > 0));
    if (!$ids) return [];
    $marcas = implode(',', array_fill(0, count($ids), '?'));
    $consulta = db()->prepare("SELECT id,nombre,precio,stock,icono FROM productos WHERE activo=1 AND id IN ($marcas)");
    $consulta->execute($ids);
    $resultado = [];
    foreach ($consulta->fetchAll() as $p) {
        $p['cantidad'] = max(1, min(99, (int)($cantidades[$p['id']] ?? 1)));
        $p['subtotal'] = (float)$p['precio'] * $p['cantidad'];
        $resultado[] = $p;
    }
    return $resultado;
}
