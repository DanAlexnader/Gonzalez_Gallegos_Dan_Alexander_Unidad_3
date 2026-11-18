<?php
// Configuración local opcional (no subir a GitHub).
$local=is_file(__DIR__.'/local.php')?require __DIR__.'/local.php':[];
foreach (['BASE_URL'=>'/Trabajos9no_cuatri/TechStore_completo','DB_HOST'=>'127.0.0.1','DB_PORT'=>'3306','DB_NAME'=>'techstore_u3','DB_USER'=>'root','DB_PASS'=>''] as $key=>$default) {
    $env=getenv($key); define($key,$local[$key]??($env!==false?$env:$default));
}
