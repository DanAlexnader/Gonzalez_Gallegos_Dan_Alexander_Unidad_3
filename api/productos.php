<?php
require_once __DIR__.'/../includes/funciones.php';
require_once __DIR__.'/../models/Producto.php';
header('Content-Type: application/json; charset=utf-8'); header('Cache-Control: no-store');
function respuesta(int $status,array $data): never { http_response_code($status); echo json_encode($data,JSON_UNESCAPED_UNICODE); exit; }
$m=$_SERVER['REQUEST_METHOD'];
if (!in_array($m,['GET','POST','PUT','DELETE'],true)) { header('Allow: GET, POST, PUT, DELETE'); respuesta(405,['ok'=>false,'error'=>'Método no permitido']); }
$id=isset($_GET['id'])?filter_var($_GET['id'],FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]):null;
if ($id===false || (in_array($m,['PUT','DELETE'],true)&&$id===null) || ($m==='POST'&&$id!==null)) respuesta(400,['ok'=>false,'error'=>'ID inválido']);
if ($m!=='GET') {
    if (!usuario()) respuesta(401,['ok'=>false,'error'=>'Inicia sesión']);
    if (usuario()['rol']!=='admin') respuesta(403,['ok'=>false,'error'=>'Permiso de administrador requerido']);
    if (!hash_equals(token_csrf(),$_SERVER['HTTP_X_CSRF_TOKEN']??'')) respuesta(403,['ok'=>false,'error'=>'CSRF inválido']);
}
try {
    $repo=new Producto(db());
    if ($m==='GET') { $items=$repo->listar($id); if ($id!==null&&!$items) respuesta(404,['ok'=>false,'error'=>'No encontrado']); respuesta(200,['ok'=>true,'productos'=>$items]); }
    if ($id!==null&&!$repo->existe($id)) respuesta(404,['ok'=>false,'error'=>'No encontrado']);
    if ($m==='DELETE') { $repo->desactivar($id); respuesta(200,['ok'=>true,'id'=>$id]); }
    if (strtolower(trim(explode(';',$_SERVER['CONTENT_TYPE']??'')[0]))!=='application/json') respuesta(415,['ok'=>false,'error'=>'Usa application/json']);
    $raw=file_get_contents('php://input',false,null,0,16385); if(strlen($raw)>16384) respuesta(413,['ok'=>false,'error'=>'Solicitud demasiado grande']);
    $d=json_decode($raw,true,32,JSON_THROW_ON_ERROR); if(!is_array($d)) throw new InvalidArgumentException('JSON inválido');
    $id=$repo->guardar(validar_producto($d),$id); respuesta($m==='POST'?201:200,['ok'=>true,'id'=>$id]);
} catch (InvalidArgumentException|JsonException $ex) { respuesta(400,['ok'=>false,'error'=>'Datos de producto inválidos']); } catch(Throwable $ex) { error_log($ex->getMessage()); respuesta(500,['ok'=>false,'error'=>'Error interno']); }
