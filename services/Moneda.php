<?php
// Servicio externo de Frankfurter. No necesita clave; no envía datos personales.
function tipo_cambio(): ?array {
    $cache=$_SESSION['tipo_cambio']??null;
    if ($cache && time()-$cache['consultado']<3600) return $cache;
    if (isset($_SESSION['moneda_error']) && time()-$_SESSION['moneda_error']<60) return null;
    $url='https://api.frankfurter.dev/v1/latest?base=MXN&symbols=USD';
    $ctx=stream_context_create(['http'=>['timeout'=>4,'follow_location'=>0,'header'=>"Accept: application/json\r\n"],'ssl'=>['verify_peer'=>true,'verify_peer_name'=>true]]);
    $raw=@file_get_contents($url,false,$ctx,0,16384);
    $d=$raw===false?null:json_decode($raw,true);
    if (!is_array($d)||($d['base']??'')!=='MXN'||!is_numeric($d['rates']['USD']??null)||$d['rates']['USD']<=0||!preg_match('/^\d{4}-\d{2}-\d{2}$/D',$d['date']??'')) { $_SESSION['moneda_error']=time(); return null; }
    return $_SESSION['tipo_cambio']=['tasa'=>(float)$d['rates']['USD'],'fecha'=>$d['date'],'consultado'=>time(),'fuente'=>'Frankfurter'];
}
