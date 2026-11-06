<?php
require_once __DIR__ . '/../includes/funciones.php';
if (usuario()) ir('index.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();
    $nombre = trim((string)($_POST['nombre'] ?? ''));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');
    if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 100) $error = 'Escribe un nombre de 2 a 100 caracteres.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) $error = 'Correo electrónico inválido.';
    elseif (strlen($password) < 8) $error = 'La contraseña necesita al menos 8 caracteres.';
    else {
        try {
            $q=db()->prepare("INSERT INTO usuarios (nombre,email,password_hash,rol) VALUES (?,?,?,'cliente')");
            $q->execute([$nombre,$email,password_hash($password,PASSWORD_DEFAULT)]);
            session_regenerate_id(true);
            $_SESSION['usuario']=['id'=>(int)db()->lastInsertId(),'nombre'=>$nombre,'rol'=>'cliente'];
            ir('index.php');
        } catch (PDOException $ex) {
            if ($ex->getCode() === '23000') $error='Ya existe una cuenta con ese correo.';
            else throw $ex;
        }
    }
}
$titulo='Registro | TechStore'; require __DIR__ . '/../includes/header.php';
?>
<main class="container my-5" style="max-width:540px"><h1>Crear cuenta</h1>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="card p-4"><input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>">
<label class="form-label">Nombre<input required maxlength="100" name="nombre" class="form-control" value="<?= e($_POST['nombre'] ?? '') ?>"></label>
<label class="form-label">Correo<input required type="email" maxlength="190" name="email" class="form-control" value="<?= e($_POST['email'] ?? '') ?>"></label>
<label class="form-label">Contraseña (mínimo 8 caracteres)<input required minlength="8" type="password" name="password" class="form-control"></label>
<button class="btn btn-dark mt-2">Registrarme</button></form></main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
