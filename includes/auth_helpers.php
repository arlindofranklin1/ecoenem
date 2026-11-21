<?php
require_once __DIR__ . '/supabase_config.php';
function ecoenem_cookie_secure(): bool {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}
function ecoenem_definir_cookies(string $access, string $refresh): void {
    $opcoes=['path'=>'/','secure'=>ecoenem_cookie_secure(),'httponly'=>true,'samesite'=>'Lax'];
    setcookie('ecoenem_access_token',$access,$opcoes+['expires'=>time()+60*60*24*7]);
    setcookie('ecoenem_refresh_token',$refresh,$opcoes+['expires'=>time()+60*60*24*30]);
    $_COOKIE['ecoenem_access_token']=$access;$_COOKIE['ecoenem_refresh_token']=$refresh;
}
function ecoenem_validar_token(string $token): ?array {
    if(!$token||!supabase_url()||!supabase_anon_key()||!function_exists('curl_init'))return null;
    $ch=curl_init(supabase_url().'/auth/v1/user');
    curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>8,CURLOPT_HTTPHEADER=>['apikey: '.supabase_anon_key(),'Authorization: Bearer '.$token]]);
    $body=curl_exec($ch);$status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
    if($status!==200||!$body)return null;$user=json_decode($body,true);
    return is_array($user)&&!empty($user['id'])?$user:null;
}
