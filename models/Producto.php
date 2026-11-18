<?php
final class Producto {
    public function __construct(private PDO $pdo) {}
    public function listar(?int $id = null): array {
        $q=$this->pdo->prepare('SELECT id,nombre,descripcion,precio,stock,icono FROM productos WHERE activo=1'.($id===null?' ORDER BY id DESC':' AND id=?'));
        $q->execute($id===null?[]:[$id]); return $q->fetchAll();
    }
    public function guardar(array $p, ?int $id): int {
        $values=[$p['nombre'],$p['descripcion'],$p['precio'],$p['stock'],$p['icono'],$p['activo']];
        if ($id===null) {
            $q=$this->pdo->prepare('INSERT INTO productos(nombre,descripcion,precio,stock,icono,activo) VALUES (?,?,?,?,?,?)');
            $q->execute($values); return (int)$this->pdo->lastInsertId();
        }
        $q=$this->pdo->prepare('UPDATE productos SET nombre=?,descripcion=?,precio=?,stock=?,icono=?,activo=? WHERE id=?');
        $q->execute([...$values,$id]); return $id;
    }
    public function existe(int $id): bool { $q=$this->pdo->prepare('SELECT id FROM productos WHERE id=?'); $q->execute([$id]); return (bool)$q->fetchColumn(); }
    public function desactivar(int $id): void { $q=$this->pdo->prepare('UPDATE productos SET activo=0 WHERE id=?'); $q->execute([$id]); }
}
function validar_producto(array $d): array {
    foreach (['nombre','descripcion','icono'] as $k) if(isset($d[$k]) && !is_string($d[$k])) throw new InvalidArgumentException('Texto inválido.');
    $nombre=trim($d['nombre']??''); $descripcion=trim($d['descripcion']??''); $icono=trim($d['icono']??'📦');
    $precio=filter_var($d['precio']??null,FILTER_VALIDATE_FLOAT); $stock=filter_var($d['stock']??null,FILTER_VALIDATE_INT); $activo=$d['activo']??1;
    if ($nombre==='' || mb_strlen($nombre)>150 || mb_strlen($descripcion)>1000 || mb_strlen($icono)>15 || $precio===false || !is_finite($precio) || $precio<0 || $precio>9999999 || $stock===false || $stock<0 || $stock>1000000 || !in_array($activo,[0,1,true,false],true)) throw new InvalidArgumentException('Revisa los datos del producto.');
    return compact('nombre','descripcion','precio','stock','icono')+['activo'=>(int)$activo];
}
