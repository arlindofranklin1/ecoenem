<?php
// Protege páginas PHP e renova a sessão com o refresh token quando necessário.
require_once __DIR__ . '/auth_helpers.php';
$ecoenem_access=$_COOKIE['ecoenem_access_token']??'';
$ecoenem_usuario=ecoenem_validar_token($ecoenem_access);
if(!$ecoenem_usuario){
    $refresh=$_COOKIE['ecoenem_refresh_token']??'';
    if($refresh&&supabase_url()&&supabase_anon_key()&&function_exists('curl_init')){
        $ch=curl_init(supabase_url().'/auth/v1/token?grant_type=refresh_token');
        curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>8,CURLOPT_POSTFIELDS=>json_encode(['refresh_token'=>$refresh]),CURLOPT_HTTPHEADER=>['Content-Type: application/json','apikey: '.supabase_anon_key()]]);
        $body=curl_exec($ch);$status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);$dados=json_decode($body?:'',true);
        if($status===200&&!empty($dados['access_token'])&&!empty($dados['refresh_token'])){
            ecoenem_definir_cookies($dados['access_token'],$dados['refresh_token']);
            $ecoenem_usuario=ecoenem_validar_token($dados['access_token']);
        }
    }
}
if(!$ecoenem_usuario){
    $opts=['expires'=>time()-3600,'path'=>'/','secure'=>ecoenem_cookie_secure(),'httponly'=>true,'samesite'=>'Lax'];
    setcookie('ecoenem_access_token','',$opts);setcookie('ecoenem_refresh_token','',$opts);
    $destino=basename($_SERVER['PHP_SELF']??'index.php');header('Location: /login.php?next='.urlencode($destino));exit;
}

// Disponibiliza a sessão validada ao SDK no navegador para manter refresh tokens sincronizados.
$ecoenem_sessao_servidor = ['access_token' => ($_COOKIE['ecoenem_access_token'] ?? ''), 'refresh_token' => ($_COOKIE['ecoenem_refresh_token'] ?? '')];
