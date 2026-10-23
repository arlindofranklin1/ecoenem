
<?php
/*
 * EcoIA - endpoint PHP conectado ao Supabase Edge Function.
 * A chave da OpenRouter fica somente no Supabase.
 */

header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store");

$modoDebug = false;

/*
 * CONFIGURAÇÃO
 *
 * Confirme que esta URL corresponde ao projeto Supabase
 * que contém a função Edge chamada "ecoia".
 */
$url = "https://phjkdovpmunpuehvjucu.supabase.co/functions/v1/ecoia";
$gatewaySecret = getenv("ECOIA_GATEWAY_SECRET");

/* =========================
   FUNÇÕES DE RESPOSTA
========================= */

function responder(string $texto, int $status = 200): void
{
    http_response_code($status);

    echo json_encode(
        ["resposta" => $texto],
        JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
    );
    exit;
}

function falhar(string $texto, int $status = 500, $debug = null): void
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

    echo json_encode(
        $saida,
        JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
    );
    exit;
}

/* =========================
   VALIDAR REQUISIÇÃO
========================= */

if (($_SERVER["REQUEST_METHOD"] ?? "") !== "POST") {
    falhar(
        "Envie uma mensagem para a EcoIA usando o método POST.",
        405
    );
}

if (!is_string($gatewaySecret) || trim($gatewaySecret) === "") {
    error_log("EcoIA: variável ECOIA_GATEWAY_SECRET não configurada.");

    falhar(
        "A EcoIA está temporariamente indisponível por uma configuração do servidor.",
        500
    );
}

if (!function_exists("curl_init")) {
    error_log("EcoIA: extensão cURL não está habilitada.");

    falhar(
        "O serviço de IA está temporariamente indisponível.",
        500
    );
}

/* =========================
   MENSAGEM ATUAL
========================= */

$mensagem = $_POST["mensagem"] ?? "";

if (!is_string($mensagem)) {
    falhar("A mensagem enviada é inválida.", 400);
}

$mensagem = trim($mensagem);

if (strlen($mensagem) > 10000) {
    falhar(
        "A mensagem é muito longa. Tente resumir sua pergunta.",
        400
    );
}

/* =========================
   HISTÓRICO
========================= */

$historicoBruto = $_POST["mensagens"] ?? "[]";

if (!is_string($historicoBruto)) {
    falhar("O histórico enviado é inválido.", 400);
}

$historico = json_decode($historicoBruto, true);

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

/* Mantém somente as últimas 10 mensagens. */
$mensagensValidas = array_slice($mensagensValidas, -10);

/* Evita adicionar a mensagem atual duas vezes. */
if ($mensagem !== "") {
    $ultima = !empty($mensagensValidas)
        ? $mensagensValidas[count($mensagensValidas) - 1]
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
    falhar("Digite uma mensagem para a EcoIA.", 400);
}

/* =========================
   PREPARAR REQUISIÇÃO
========================= */

$dados = [
    "mensagem" => $mensagem,
    "mensagens" => $mensagensValidas
];

$jsonDados = json_encode(
    $dados,
    JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
);

if ($jsonDados === false) {
    falhar("Não foi possível preparar a mensagem.", 500);
}

/* =========================
   CHAMAR SUPABASE
========================= */

$ch = curl_init($url);

if ($ch === false) {
    falhar("Não foi possível iniciar a conexão com a IA.", 500);
}

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_CONNECTTIMEOUT => 15,
    CURLOPT_TIMEOUT => 100,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Accept: application/json",
        "x-ecoia-secret: " . trim($gatewaySecret)
    ],
    CURLOPT_POSTFIELDS => $jsonDados
]);

$response = curl_exec($ch);
$erroNumero = curl_errno($ch);
$erroTexto = curl_error($ch);
$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

/* =========================
   ERROS DE CONEXÃO
========================= */

if ($erroNumero !== 0) {
    error_log(
        "EcoIA: erro cURL " . $erroNumero . ": " . $erroTexto
    );

    if ($erroNumero === 28) {
        responder(
            "A EcoIA demorou demais para responder. Tente novamente.",
            504
        );
    }

    responder(
        "Não foi possível conectar ao serviço de IA. Tente novamente.",
        502
    );
}

/* =========================
   INTERPRETAR RESPOSTA
========================= */

$resultado = json_decode((string) $response, true);

if (!is_array($resultado)) {
    error_log(
        "EcoIA: resposta inválida do Supabase. HTTP " . $status
    );

    responder(
        "O serviço de IA retornou uma resposta inválida.",
        502
    );
}

/* Trata erros HTTP ou erros enviados pela Edge Function. */
if (
    $status < 200 ||
    $status >= 300 ||
    !empty($resultado["erro"])
) {
    error_log(
        "EcoIA: Edge Function retornou HTTP " . $status
    );

    $texto = $resultado["resposta"]
        ?? "Não foi possível obter uma resposta da EcoIA.";

    responder(
        is_string($texto)
            ? $texto
            : "Não foi possível obter uma resposta da EcoIA.",
        ($status >= 400 && $status <= 599) ? $status : 502
    );
}

/* =========================
   RESPOSTA FINAL
========================= */

$conteudo = $resultado["resposta"] ?? null;

if (!is_string($conteudo) || trim($conteudo) === "") {
    responder(
        "A IA não retornou texto desta vez. Tente novamente.",
        502
    );
}

responder($conteudo);
