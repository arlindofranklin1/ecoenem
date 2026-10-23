
<?php
/*
 * EcoIA - endpoint de chat conectado ao Supabase Edge Function.
 * Mantém compatibilidade com o frontend existente.
 * A chave da OpenRouter fica somente no Supabase.
 */

header("Content-Type: application/json; charset=utf-8");

$modoDebug = true;

/*
 * CONFIGURAÇÃO
 *
 * ECOIA_GATEWAY_SECRET deve ser configurado como variável
 * de ambiente no servidor PHP, com o mesmo valor cadastrado
 * nos Secrets do Supabase.
 *
 * Não coloque o segredo diretamente neste arquivo.
 */

$url = "https://phjkdovpmunpuehvjucu.supabase.co/functions/v1/ecoia";
$gatewaySecret = getenv("ECOIA_GATEWAY_SECRET");

/* =========================
   FUNÇÕES DE SAÍDA
========================= */

function responder(string $texto): void
{
    echo json_encode(
        ["resposta" => $texto],
        JSON_UNESCAPED_UNICODE
    );
    exit;
}

function falhar(string $texto, $debug = null, int $status = 200): void
{
    global $modoDebug;

    http_response_code($status);

    $saida = [
        "resposta" => $texto,
        "erro" => true
    ];

    if ($modoDebug && $debug !== null) {
        $saida["debug"] = $debug;
    }

    echo json_encode($saida, JSON_UNESCAPED_UNICODE);
    exit;
}

/* =========================
   VALIDAÇÃO DA CONFIGURAÇÃO
========================= */

if (!is_string($gatewaySecret) || trim($gatewaySecret) === "") {
    falhar(
        "Erro de configuração: o segredo de conexão com o Supabase não foi configurado no servidor PHP.",
        null,
        500
    );
}

if (!function_exists("curl_init")) {
    falhar(
        "Erro de configuração: a extensão cURL do PHP está desativada.",
        null,
        500
    );
}

/* =========================
   MENSAGEM ATUAL
========================= */

$mensagem = trim($_POST["mensagem"] ?? "");

if (strlen($mensagem) > 10000) {
    falhar("A mensagem é muito longa. Tente resumir sua pergunta.", null, 400);
}

/* =========================
   HISTÓRICO DO FRONTEND
========================= */

$historico = json_decode($_POST["mensagens"] ?? "[]", true);

if (!is_array($historico)) {
    $historico = [];
}

$mensagensValidas = [];

foreach ($historico as $msg) {
    if (!is_array($msg)) {
        continue;
    }

    $role = $msg["role"] ?? "";
    $content = $msg["content"] ?? "";

    if ($role !== "user" && $role !== "assistant") {
        continue;
    }

    if (!is_string($content) || trim($content) === "") {
        continue;
    }

    if (strlen($content) > 10000) {
        continue;
    }

    $mensagensValidas[] = [
        "role" => $role,
        "content" => $content
    ];
}

/*
 * Mantém apenas as últimas 10 mensagens do histórico,
 * reduzindo o tamanho das requisições.
 */
$mensagensValidas = array_slice($mensagensValidas, -10);

/* =========================
   EVITAR DUPLICAÇÃO
========================= */

if ($mensagem !== "") {
    $ultima = !empty($mensagensValidas)
        ? end($mensagensValidas)
        : null;

    $jaIncluida = is_array($ultima)
        && ($ultima["role"] ?? "") === "user"
        && trim($ultima["content"] ?? "") === $mensagem;

    if (!$jaIncluida) {
        $mensagensValidas[] = [
            "role" => "user",
            "content" => $mensagem
        ];
    }
}

if (empty($mensagensValidas)) {
    falhar("Digite uma mensagem para a EcoIA.", null, 400);
}

/* =========================
   REQUISIÇÃO AO SUPABASE
========================= */

$dados = [
    "mensagem" => $mensagem,
    "mensagens" => $mensagensValidas
];

$jsonDados = json_encode(
    $dados,
    JSON_UNESCAPED_UNICODE
);

if ($jsonDados === false) {
    falhar("Não foi possível preparar a mensagem para envio.", null, 500);
}

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_CONNECTTIMEOUT => 15,
    CURLOPT_TIMEOUT => 100,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "x-ecoia-secret: " . trim($gatewaySecret)
    ],
    CURLOPT_POSTFIELDS => $jsonDados
]);

$response = curl_exec($ch);
$erroNumero = curl_errno($ch);
$erroTexto = curl_error($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

/* =========================
   ERROS DE CONEXÃO
========================= */

if ($erroNumero !== 0) {
    error_log("EcoIA - erro cURL $erroNumero: $erroTexto");

    $dicas = [
        6 => "Não foi possível encontrar o servidor do Supabase.",
        7 => "Não foi possível conectar ao Supabase.",
        28 => "A IA demorou demais para responder. Tente novamente.",
        35 => "Falha na conexão segura com o servidor.",
        60 => "Não foi possível validar o certificado SSL."
    ];

    $texto = $dicas[$erroNumero] ?? "Não foi possível conectar ao serviço de IA.";

    falhar(
        $texto,
        ["codigo_curl" => $erroNumero],
        502
    );
}

/* =========================
   INTERPRETAR RESPOSTA
========================= */

$resultado = json_decode((string) $response, true);

if (!is_array($resultado)) {
    error_log("EcoIA - resposta inválida do Supabase. HTTP $status");

    falhar(
        "O serviço de IA retornou uma resposta inválida.",
        ["http" => $status],
        502
    );
}

/* =========================
   ERROS DEVOLVIDOS PELA FUNÇÃO
========================= */

if ($status < 200 || $status >= 300 || !empty($resultado["erro"])) {
    $texto = $resultado["resposta"]
        ?? "Não foi possível obter uma resposta da EcoIA.";

    $debug = $resultado["debug"] ?? null;

    /*
     * O frontend continua recebendo o campo "erro",
     * para não adicionar respostas de erro ao histórico.
     */
    falhar(
        $texto,
        $debug,
        ($status >= 400 && $status <= 599) ? $status : 502
    );
}

/* =========================
   RESPOSTA FINAL
========================= */

$conteudo = $resultado["resposta"] ?? null;

if (!is_string($conteudo) || trim($conteudo) === "") {
    falhar(
        "A IA não retornou texto desta vez. Tente novamente.",
        null,
        502
    );
}

responder($conteudo);
