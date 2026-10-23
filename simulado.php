<?php

/*
 * ============================================================
 *  SIMULADÃO ECOENEM
 * ============================================================
 *  1. Recebe os dados do formulário via $_POST;
 *  2. Protege contra HTML/JavaScript Injection com valida_dados();
 *  3. Valida os campos com empty(), preg_match() e filter_var();
 *  4. Se não houver erros, processa os dados: sorteia as questões,
 *     organiza o simulado conforme o objetivo e calcula o tempo
 *     sugerido por questão;
 *  5. Guarda o simulado na sessão para o arquivo gerar_simulado.php
 *     montar o PDF.
 * ============================================================
 */

session_start(); // Inicia a sessão (usada para enviar o simulado ao gerador de PDF)

date_default_timezone_set("America/Recife"); // Fuso horário usado na data do simulado

include "valida_dados.php";     // Importa a função valida_dados()
include "questoes_simulado.php"; // Importa o array $bancoQuestoes

// Inicializar as variáveis
$nome = $email = $quantidade = $tempo = $objetivo = "";
$nome_erro = $email_erro = $quantidade_erro = $tempo_erro = $objetivo_erro = "";
$simulado_gerado = false;
$tempo_por_questao = 0;

// Definição de Padrões do preg_match
// Nome: apenas letras (com acento) e espaços em branco — padrão do roteiro de estudos.
// O modificador "u" faz a expressão tratar o texto como UTF-8 (letras acentuadas).
define('PADRAO_VALIDA_NOME', '/^[a-zA-ZÀ-ú\s]+$/u');

// Opções permitidas em cada campo de escolha (arrays usados na validação e no HTML)
$opcoes_quantidade = [5, 10, 15];
$opcoes_tempo = [30, 60, 120];
$opcoes_objetivo = [
    "revisar"  => "Revisar conteúdos",
    "praticar" => "Praticar para o ENEM",
    "testar"   => "Testar meus conhecimentos"
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Coleta os dados do formulário e valida contra HTML injection.
    // O operador ?? evita aviso caso o campo não seja enviado (ex.: radio sem marcar).
    $nome = valida_dados($_POST["nome"] ?? "");
    $email = valida_dados($_POST["email"] ?? "");
    $quantidade = valida_dados($_POST["quantidade"] ?? "");
    $tempo = valida_dados($_POST["tempo"] ?? "");
    $objetivo = valida_dados($_POST["objetivo"] ?? "");

    ## Validação do campo Nome ##
    if (empty($nome)) {
        $nome_erro = "O nome é obrigatório!";
    } elseif (strlen($nome) < 3 || strlen($nome) > 60) {
        $nome_erro = "O nome deve ter entre 3 e 60 caracteres.";
    } elseif (!preg_match(PADRAO_VALIDA_NOME, $nome)) {
        $nome_erro = "Apenas letras e espaços em branco são permitidos!";
    }

    ## Validação do campo E-mail ##
    if (empty($email)) {
        $email_erro = "O e-mail é obrigatório!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_erro = "Por favor, insira um e-mail válido.";
    }

    ## Validação do campo Quantidade de questões ##
    // filter_var() com FILTER_VALIDATE_INT garante que o valor é um número inteiro
    // entre 5 e 15; in_array() garante que é uma das opções do formulário (5, 10 ou 15).
    if (empty($quantidade)) {
        $quantidade_erro = "Escolha a quantidade de questões!";
    } elseif (
        filter_var($quantidade, FILTER_VALIDATE_INT, ["options" => ["min_range" => 5, "max_range" => 15]]) === false
        || !in_array((int) $quantidade, $opcoes_quantidade)
    ) {
        $quantidade_erro = "Quantidade inválida. Escolha 5, 10 ou 15 questões.";
    }

    ## Validação do campo Tempo disponível ##
    if (empty($tempo)) {
        $tempo_erro = "Escolha o tempo disponível!";
    } elseif (
        filter_var($tempo, FILTER_VALIDATE_INT) === false
        || !in_array((int) $tempo, $opcoes_tempo)
    ) {
        $tempo_erro = "Tempo inválido. Escolha 30, 60 ou 120 minutos.";
    }

    ## Validação do campo Objetivo ##
    if (empty($objetivo)) {
        $objetivo_erro = "Escolha o objetivo do simulado!";
    } elseif (!array_key_exists($objetivo, $opcoes_objetivo)) {
        $objetivo_erro = "Objetivo inválido.";
    }

    // Processamento do simulado se não houver erros de validação
    if (empty($nome_erro) && empty($email_erro) && empty($quantidade_erro) && empty($tempo_erro) && empty($objetivo_erro)) {

        $quantidade = (int) $quantidade;
        $tempo = (int) $tempo;

        // Embaralha o banco de questões para gerar um simulado diferente a cada vez
        $questoes_sorteadas = $bancoQuestoes;
        shuffle($questoes_sorteadas);

        switch ($objetivo) {
            case 'praticar':
                // Praticar para o ENEM: prioriza as questões com o texto original da prova
                usort($questoes_sorteadas, function ($a, $b) {
                    return $a["adaptada"] <=> $b["adaptada"];
                });
                $questoes_sorteadas = array_slice($questoes_sorteadas, 0, $quantidade);
                shuffle($questoes_sorteadas);
                break;
            case 'revisar':
                // Revisar conteúdos: sorteia e depois agrupa as questões por tema
                $questoes_sorteadas = array_slice($questoes_sorteadas, 0, $quantidade);
                usort($questoes_sorteadas, function ($a, $b) {
                    return strcmp($a["tema"], $b["tema"]);
                });
                break;
            default:
                // Testar meus conhecimentos: questões em ordem totalmente aleatória
                $questoes_sorteadas = array_slice($questoes_sorteadas, 0, $quantidade);
                break;
        }

        // Guarda apenas os ids das questões escolhidas
        $ids_questoes = [];
        foreach ($questoes_sorteadas as $questao) {
            $ids_questoes[] = $questao["id"];
        }

        // Calcula o tempo sugerido para cada questão (em minutos)
        $tempo_por_questao = floor($tempo / $quantidade);

        // Guarda o simulado na sessão para o gerar_simulado.php montar o PDF
        $_SESSION["simulado"] = [
            "nome" => $nome,
            "email" => $email,
            "quantidade" => $quantidade,
            "tempo" => $tempo,
            "tempo_por_questao" => $tempo_por_questao,
            "objetivo" => $objetivo,
            "objetivo_texto" => $opcoes_objetivo[$objetivo],
            "questoes" => $ids_questoes,
            "codigo" => "ECO-" . strtoupper(substr(md5(uniqid()), 0, 6)),
            "data" => date("d/m/Y")
        ];

        $simulado_gerado = true;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simuladão</title>
    <link rel="shortcut icon" href="imgs/ecoenemicon.png" type="image/x-icon">

    <link rel="stylesheet" href="css/simulado.css">
</head>

<body>

    <header>
        <div class="logo">
            <a href="index.php">EcoENEM</a>
        </div>

        <nav>
            <a href="ecoia.php">EcoIA</a>
            <a href="conteudo.php">Conteúdo</a>
            <a href="questoes.php">Questões</a>
            <a href="simulado.php">Simuladão</a>
            <a href="sobre.php">Sobre</a>
        </nav>
    </header>

    <div class="container">

        <section class="titulo">
            <h1>Simuladão EcoEnem</h1>
            <p>
                Preencha seus dados e gere um simulado personalizado de Ecologia em PDF, com questões do ENEM.
            </p>
        </section>

        <div class="simulado-box">

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>

                <div class="campo">
                    <label for="nome">Nome do estudante*</label>
                    <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" value="<?php echo $nome; ?>">
                    <span class="erro"><?php echo $nome_erro; ?></span>
                </div>

                <div class="campo">
                    <label for="email">E-mail do estudante*</label>
                    <input type="text" id="email" name="email" placeholder="seuemail@exemplo.com" value="<?php echo $email; ?>">
                    <span class="erro"><?php echo $email_erro; ?></span>
                </div>

                <div class="campo">
                    <label>Quantidade de questões*</label>
                    <div class="opcoes">
                        <?php foreach ($opcoes_quantidade as $opcao) : ?>
                            <label class="opcao">
                                <input type="radio" name="quantidade" value="<?php echo $opcao; ?>" <?php if ($quantidade == $opcao) echo "checked"; ?>>
                                <span><?php echo $opcao; ?> questões</span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <span class="erro"><?php echo $quantidade_erro; ?></span>
                </div>

                <div class="campo">
                    <label>Tempo disponível*</label>
                    <div class="opcoes">
                        <?php foreach ($opcoes_tempo as $opcao) : ?>
                            <label class="opcao">
                                <input type="radio" name="tempo" value="<?php echo $opcao; ?>" <?php if ($tempo == $opcao) echo "checked"; ?>>
                                <span><?php echo $opcao; ?> minutos</span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <span class="erro"><?php echo $tempo_erro; ?></span>
                </div>

                <div class="campo">
                    <label for="objetivo">Objetivo do simulado*</label>
                    <select id="objetivo" name="objetivo">
                        <option value="">Selecione...</option>
                        <?php foreach ($opcoes_objetivo as $valor => $texto) : ?>
                            <option value="<?php echo $valor; ?>" <?php if ($objetivo == $valor) echo "selected"; ?>><?php echo $texto; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="erro"><?php echo $objetivo_erro; ?></span>
                </div>

                <button type="submit" class="gerar-btn">Gerar simulado</button>

            </form>

            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !$simulado_gerado) : ?>
                <div class="feedback-errado">
                    Não foi possível gerar o simulado. Corrija os campos destacados e tente novamente.
                </div>
            <?php endif; ?>

            <?php if ($simulado_gerado) : ?>
                <div class="resultado">
                    <h2>Simulado gerado com sucesso!</h2>
                    <p><strong>Estudante:</strong> <?php echo $nome; ?></p>
                    <p><strong>E-mail:</strong> <?php echo $email; ?></p>
                    <p><strong>Quantidade de questões:</strong> <?php echo $quantidade; ?></p>
                    <p><strong>Tempo disponível:</strong> <?php echo $tempo; ?> minutos (cerca de <?php echo $tempo_por_questao; ?> min por questão)</p>
                    <p><strong>Objetivo:</strong> <?php echo $opcoes_objetivo[$objetivo]; ?></p>
                    <p><strong>Código do simulado:</strong> <?php echo $_SESSION["simulado"]["codigo"]; ?></p>

                    <a class="gerar-btn pdf-btn" href="gerar_simulado.php" target="_blank">Baixar simulado em PDF</a>
                </div>
            <?php endif; ?>

        </div>

    </div>

    <footer>

        <p>
            © 2026 EcoENEM. Todos os direitos reservados.
        </p>

    </footer>

</body>

</html>
