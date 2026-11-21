<?php
require_once __DIR__ . '/../includes/supabase_config.php';
require_once __DIR__ . '/../includes/auth_helpers.php';
header('Content-Type: application/json; charset=utf-8');
$input=json_decode(file_get_contents('php://input'),true)?:[];
$access=(string)($input['access_token']??'');$refresh=(string)($input['refresh_token']??'');
if($access===''||$refresh===''){
  $opts=['expires'=>time()-3600,'path'=>'/','secure'=>ecoenem_cookie_secure(),'httponly'=>true,'samesite'=>'Lax'];
  setcookie('ecoenem_access_token','',$opts);setcookie('ecoenem_refresh_token','',$opts);
  echo json_encode(['ok'=>true]);exit;
}
$user=ecoenem_validar_token($access);
if(!$user){http_response_code(401);echo json_encode(['erro'=>'Sessão inválida.']);exit;}
ecoenem_definir_cookies($access,$refresh);
echo json_encode(['ok'=>true]);
