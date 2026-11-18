<?php
require_once __DIR__ . '/../includes/funciones.php'; requiere_admin();
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT) ?: 0;
$p=['nombre'=>'','descripcion'=>'','precio'=>'','stock'=>0,'icono'=>'📦','activo'=>1];
if ($id) {
    $q=db()->prepare('SELECT * FROM productos WHERE id=?');$q->execute([$id]);$p=$q->fetch();
    if (!$p) { http_response_code(404); exit('Producto no encontrado.'); }
}
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verificar_csrf();
    require_once __DIR__.'/../models/Producto.php';
    $p=$_POST; $p['activo']=isset($_POST['activo'])?1:0;
    try { $data=validar_producto($p); (new Producto(db()))->guardar($data,$id?:null); ir('admin/index.php'); }
    catch (InvalidArgumentException $ex) { $error=$ex->getMessage(); }

}
$titulo=($id?'Editar':'Nuevo').' producto | TechStore';require __DIR__ . '/../includes/header.php';
?>
<main class="container my-5" style="max-width:700px"><h1><?= $id?'Editar':'Nuevo' ?> producto</h1>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="card p-4"><input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>">
<label class="form-label">Nombre<input required maxlength="150" name="nombre" class="form-control" value="<?= e($p['nombre']) ?>"></label>
<label class="form-label">Descripción<textarea maxlength="1000" name="descripcion" class="form-control"><?= e($p['descripcion']) ?></textarea></label>
<label class="form-label">Precio<input required type="number" min="0" max="9999999" step="0.01" name="precio" class="form-control" value="<?= e($p['precio']) ?>"></label>
<label class="form-label">Existencias<input required type="number" min="0" max="1000000" name="stock" class="form-control" value="<?= e($p['stock']) ?>"></label>
<label class="form-label">Ícono (emoji)<input name="icono" maxlength="15" class="form-control" value="<?= e($p['icono']) ?>"></label>
<label class="form-check my-3"><input type="checkbox" name="activo" class="form-check-input" <?= $p['activo']?'checked':'' ?>> Producto activo</label>
<div class="d-flex gap-2"><button class="btn btn-success">Guardar</button><a class="btn btn-outline-secondary" href="<?= e(url('admin/index.php')) ?>">Cancelar</a></div>
</form></main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
