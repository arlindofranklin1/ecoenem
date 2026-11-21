<?php
// Configuração pública do Supabase. A anon/publishable key pode chegar ao navegador;
// nunca coloque a service_role aqui.
function supabase_url(): string { return rtrim((string)getenv('SUPABASE_URL'), '/'); }
function supabase_anon_key(): string { return (string)getenv('SUPABASE_ANON_KEY'); }
function supabase_config(): array {
    return ['url' => supabase_url(), 'anonKey' => supabase_anon_key()];
}
