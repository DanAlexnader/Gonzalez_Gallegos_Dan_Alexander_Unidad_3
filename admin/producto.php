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
    $nombre=trim((string)($_POST['nombre'] ?? ''));$descripcion=trim((string)($_POST['descripcion'] ?? ''));
    $precio=filter_var($_POST['precio'] ?? null,FILTER_VALIDATE_FLOAT);
    $stock=filter_var($_POST['stock'] ?? null,FILTER_VALIDATE_INT);
    $icono=trim((string)($_POST['icono'] ?? '📦'));
    $activo=isset($_POST['activo']) ? 1 : 0;
    $p=compact('nombre','descripcion','precio','stock','icono','activo');
    if ($nombre==='' || mb_strlen($nombre)>150 || mb_strlen($descripcion)>1000 || mb_strlen($icono)>15 || $precio===false || $precio<0 || $precio>9999999 || $stock===false || $stock<0 || $stock>1000000) {
        $error='Revisa nombre, descripción, precio, stock e ícono.';
    } else {
        if ($id) {
            $q=db()->prepare('UPDATE productos SET nombre=?,descripcion=?,precio=?,stock=?,icono=?,activo=? WHERE id=?');
            $q->execute([$nombre,$descripcion,$precio,$stock,$icono,$activo,$id]);
        } else {
            $q=db()->prepare('INSERT INTO productos (nombre,descripcion,precio,stock,icono,activo) VALUES (?,?,?,?,?,?)');
            $q->execute([$nombre,$descripcion,$precio,$stock,$icono,$activo]);
        }
        ir('admin/index.php');
    }
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
