<?php
require_once __DIR__ . '/../includes/funciones.php';
if (usuario()) ir('index.php');
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verificar_csrf();
    $email=strtolower(trim((string)($_POST['email'] ?? '')));
    $password=(string)($_POST['password'] ?? '');
    $bloqueo=$_SESSION['login_bloqueo'] ?? 0;
    if (time() < $bloqueo) $error='Demasiados intentos. Intenta de nuevo en 15 minutos.';
    else {
        $q=db()->prepare('SELECT id,nombre,password_hash,rol FROM usuarios WHERE email=?');
        $q->execute([$email]); $u=$q->fetch();
        if (!$u || !password_verify($password,$u['password_hash'])) {
            $intentos=(int)($_SESSION['login_intentos'] ?? 0)+1;
            $_SESSION['login_intentos']=$intentos;
            if ($intentos>=5) { $_SESSION['login_bloqueo']=time()+900; $_SESSION['login_intentos']=0; }
            $error='Correo o contraseña incorrectos.';
        } else {
            unset($_SESSION['login_intentos'],$_SESSION['login_bloqueo']);
            session_regenerate_id(true);
            $_SESSION['usuario']=['id'=>(int)$u['id'],'nombre'=>$u['nombre'],'rol'=>$u['rol']];
            ir('index.php');
        }
    }
}
$titulo='Iniciar sesión | TechStore';require __DIR__ . '/../includes/header.php';
?>
<main class="container my-5" style="max-width:540px"><h1>Iniciar sesión</h1>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="card p-4"><input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>">
<label class="form-label">Correo<input type="email" name="email" required class="form-control"></label>
<label class="form-label">Contraseña<input type="password" name="password" required class="form-control"></label>
<button class="btn btn-dark mt-2">Entrar</button></form></main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
