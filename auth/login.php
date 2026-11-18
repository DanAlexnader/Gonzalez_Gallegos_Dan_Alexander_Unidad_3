<?php
require_once __DIR__ . '/../includes/funciones.php';
if (usuario()) ir('index.php');
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verificar_csrf();
    $email=strtolower(trim((string)($_POST['email'] ?? '')));
    $password=(string)($_POST['password'] ?? '');
    $pdo=db();
    $clave=hash('sha256',$_SERVER['REMOTE_ADDR']??'local');
    $pdo->beginTransaction();
    try {
        $q=$pdo->prepare('INSERT IGNORE INTO login_limites (clave,intentos,bloqueo) VALUES (?,0,0)'); $q->execute([$clave]);
        $q=$pdo->prepare('SELECT intentos,bloqueo FROM login_limites WHERE clave=? FOR UPDATE'); $q->execute([$clave]); $lim=$q->fetch();
        if (time()<(int)$lim['bloqueo']) { $error='Demasiados intentos. Intenta de nuevo en 15 minutos.'; }
        else {
            $q=$pdo->prepare('SELECT id,nombre,password_hash,rol FROM usuarios WHERE email=?'); $q->execute([$email]); $u=$q->fetch();
            if (!$u || !password_verify($password,$u['password_hash'])) {
                $n=((int)$lim['bloqueo']>0?0:(int)$lim['intentos'])+1;
                $q=$pdo->prepare('UPDATE login_limites SET intentos=?,bloqueo=? WHERE clave=?'); $q->execute([$n>=5?0:$n,$n>=5?time()+900:0,$clave]);
                $error='Correo o contraseña incorrectos.';
            } else {
                $q=$pdo->prepare('DELETE FROM login_limites WHERE clave=?'); $q->execute([$clave]); $pdo->commit();
                session_regenerate_id(true); unset($_SESSION['csrf']);
                $_SESSION['usuario']=['id'=>(int)$u['id'],'nombre'=>$u['nombre'],'rol'=>$u['rol']]; ir('index.php');
            }
        }
        $pdo->commit();
    } catch(Throwable $ex) { if($pdo->inTransaction())$pdo->rollBack(); error_log($ex->getMessage()); $error='No se pudo iniciar sesión.'; }

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
