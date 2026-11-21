<?php
require_once __DIR__ . '/../includes/supabase_config.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
if (!supabase_url() || !supabase_anon_key()) { http_response_code(503); echo json_encode(['erro'=>'Configure SUPABASE_URL e SUPABASE_ANON_KEY no Render.']); exit; }
echo json_encode(supabase_config(), JSON_UNESCAPED_SLASHES);
