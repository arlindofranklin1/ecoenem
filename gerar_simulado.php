<?php

/*
 * ============================================================
 *  SIMULADÃO ECOENEM — GERAÇÃO DO PDF
 * ============================================================
 *  Lê o simulado guardado na sessão pelo simulado.php e monta o
 *  caderno em PDF com:
 *   1. identificação do estudante (nome e e-mail);
 *   2. instruções conforme o tempo e o objetivo escolhidos;
 *   3. as questões sorteadas, em duas colunas (padrão ENEM);
 *   4. folha de respostas;
 *   5. gabarito (comentado quando o objetivo é "Revisar conteúdos").
 * ============================================================
 */

session_start();

include "questoes_simulado.php"; // Importa o array $bancoQuestoes
include "pdf_simulado.php";      // Importa a classe PDFSimulado (FPDF)

// Se o estudante abrir esta página sem ter preenchido o formulário,
// volta para o formulário do Simuladão
if (empty($_SESSION["simulado"])) {
    header("Location: simulado.php");
    exit;
}

$simulado = $_SESSION["simulado"];

// Busca no banco as questões sorteadas, mantendo a ordem do simulado
$questoes = [];
foreach ($simulado["questoes"] as $id) {
    foreach ($bancoQuestoes as $questao) {
        if ($questao["id"] == $id) {
            $questoes[] = $questao;
        }
    }
}

// O nome e o e-mail foram guardados já passados pelo htmlspecialchars();
// aqui eles voltam ao texto normal para aparecerem corretamente no PDF
$nome = htmlspecialchars_decode($simulado["nome"]);
$email = htmlspecialchars_decode($simulado["email"]);

// Mensagem de orientação de acordo com o objetivo escolhido
switch ($simulado["objetivo"]) {
    case 'revisar':
        $orientacao = "As questões estão agrupadas por tema. Ao final, consulte o gabarito comentado para revisar cada conteúdo.";
        break;
    case 'praticar':
        $orientacao = "As questões priorizam os textos originais do ENEM. Simule as condições da prova: sem consulta e com cronômetro.";
        break;
    default:
        $orientacao = "As questões estão em ordem aleatória. Confira o gabarito somente depois de responder todas.";
        break;
}

$instrucoes = [
    "Este caderno contém " . count($questoes) . " questões de Ecologia, numeradas de 01 a " . str_pad(count($questoes), 2, "0", STR_PAD_LEFT) . ", cada uma com 5 alternativas e apenas uma correta.",
    "Você tem " . $simulado["tempo"] . " minutos para resolver o simulado: cerca de " . $simulado["tempo_por_questao"] . " minutos por questão.",
    $orientacao,
    "Marque suas respostas na Folha de Respostas, ao final do caderno.",
    "Questões indicadas como \"(adaptada)\" tiveram o texto-base adaptado do original."
];

/* ===================== MONTAGEM DO PDF ===================== */

$pdf = new PDFSimulado("P", "mm", "A4");
$pdf->dados = $simulado;
$pdf->SetTitle(pdf_texto("Simuladão EcoEnem - " . $nome));
$pdf->SetAuthor("EcoEnem");
$pdf->SetMargins(PDFSimulado::MARGEM, 30, PDFSimulado::MARGEM);
$pdf->SetAutoPageBreak(true, 297 - PDFSimulado::LIMITE_INFERIOR);
$pdf->AddPage();

## Caixa de identificação do estudante ##
$y = $pdf->GetY();
$pdf->SetDrawColor(...PDFSimulado::VERDE);
$pdf->SetFillColor(243, 246, 244);
$pdf->SetLineWidth(0.4);
$pdf->retanguloArredondado(12, $y, 186, 32, 2.5, "DF");

$pdf->SetFillColor(...PDFSimulado::VERDE_ESCURO);
$pdf->Rect(12.2, $y + 0.2, 185.6, 6.5, "F");
$pdf->SetXY(16, $y + 0.5);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont("Helvetica", "B", 9);
$pdf->Cell(100, 6, pdf_texto("IDENTIFICAÇÃO DO ESTUDANTE"), 0, 0);
$pdf->Cell(78, 6, pdf_texto("Gerado em " . $simulado["data"]), 0, 1, "R");

// Linhas "Rótulo: valor" da identificação
$linhas = [
    ["Nome:", $nome],
    ["E-mail:", $email],
    ["Questões:", $simulado["quantidade"] . "     Tempo: " . $simulado["tempo"] . " minutos"],
    ["Objetivo:", $simulado["objetivo_texto"]],
];
$pdf->SetY($y + 9);
foreach ($linhas as $linha) {
    $pdf->SetX(16);
    $pdf->SetTextColor(...PDFSimulado::VERDE_ESCURO);
    $pdf->SetFont("Helvetica", "B", 9.5);
    $pdf->Cell(18, 5.4, pdf_texto($linha[0]), 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont("Helvetica", "", 9.5);
    $pdf->Cell(115, 5.4, pdf_texto($linha[1]), 0, 1);
}

$pdf->codigoBarras(150, $y + 12, 44, 10, $simulado["codigo"]);

## Instruções (como no ENEM) ##
$pdf->SetY($y + 36);
$pdf->SetX(12);
$pdf->SetTextColor(...PDFSimulado::VERDE_ESCURO);
$pdf->SetFont("Helvetica", "B", 10);
$pdf->Cell(0, 6, pdf_texto("LEIA ATENTAMENTE AS INSTRUÇÕES SEGUINTES"), 0, 1);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont("Helvetica", "", 9);
foreach ($instrucoes as $indice => $instrucao) {
    $pdf->SetX(12);
    $pdf->SetFont("Helvetica", "B", 9);
    $pdf->Cell(6, 4.4, ($indice + 1) . ".", 0, 0);
    $pdf->SetFont("Helvetica", "", 9);
    $pdf->MultiCell(180, 4.4, pdf_texto($instrucao), 0, "L");
}

$pdf->Ln(3);
$pdf->SetDrawColor(...PDFSimulado::VERDE);
$pdf->SetLineWidth(0.6);
$pdf->Line(12, $pdf->GetY(), 193, $pdf->GetY());
$pdf->Ln(5);

## Questões em duas colunas ##
$pdf->iniciarColunas();
foreach ($questoes as $indice => $questao) {
    $pdf->questao($indice + 1, $questao);
}
$pdf->encerrarColunas();

## Folha de respostas ##
$pdf->AddPage();
$pdf->SetTextColor(...PDFSimulado::VERDE_ESCURO);
$pdf->SetFont("Helvetica", "B", 15);
$pdf->Cell(0, 9, "FOLHA DE RESPOSTAS", 0, 1, "C");
$pdf->SetTextColor(80, 80, 80);
$pdf->SetFont("Helvetica", "", 9);
$pdf->Cell(0, 5, pdf_texto("Preencha completamente o círculo correspondente à alternativa escolhida."), 0, 1, "C");
$pdf->Ln(2);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 5, pdf_texto("Estudante: " . $nome . "     E-mail: " . $email), 0, 1, "C");
$pdf->Ln(4);

$total = count($questoes);
$colunasFolha = 3;
$linhasFolha = (int) ceil($total / $colunasFolha);
$topoFolha = $pdf->GetY();
$alturaFolha = $linhasFolha * 9 + 8;

$pdf->SetDrawColor(...PDFSimulado::VERDE);
$pdf->SetLineWidth(0.4);
$pdf->retanguloArredondado(12, $topoFolha, 186, $alturaFolha, 3);

for ($i = 0; $i < $total; $i++) {
    $coluna = (int) floor($i / $linhasFolha);
    $linha = $i % $linhasFolha;
    $x = 20 + $coluna * 60;
    $yLinha = $topoFolha + 5 + $linha * 9;

    $pdf->SetXY($x, $yLinha);
    $pdf->SetTextColor(...PDFSimulado::VERDE_ESCURO);
    $pdf->SetFont("Helvetica", "B", 10);
    $pdf->Cell(9, 6, str_pad($i + 1, 2, "0", STR_PAD_LEFT), 0, 0);

    foreach (["A", "B", "C", "D", "E"] as $posicao => $letra) {
        $cx = $x + 12 + $posicao * 8;
        $pdf->SetDrawColor(60, 60, 60);
        $pdf->SetLineWidth(0.3);
        $pdf->circulo($cx, $yLinha + 3, 2.8);
        $pdf->SetXY($cx - 2.8, $yLinha + 0.2);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->SetFont("Helvetica", "", 7.5);
        $pdf->Cell(5.6, 5.6, $letra, 0, 0, "C");
    }
}

## Gabarito ##
$pdf->AddPage();
$pdf->SetTextColor(...PDFSimulado::VERDE_ESCURO);
$pdf->SetFont("Helvetica", "B", 15);
$titulo = ($simulado["objetivo"] == "revisar") ? "GABARITO COMENTADO" : "GABARITO";
$pdf->Cell(0, 9, $titulo, 0, 1, "C");
$pdf->SetTextColor(80, 80, 80);
$pdf->SetFont("Helvetica", "", 9);
$pdf->Cell(0, 5, pdf_texto("Confira somente depois de terminar o simulado."), 0, 1, "C");
$pdf->Ln(4);

// Tabela com o número da questão e a letra correta (até 15 por linha)
$letras = ["A", "B", "C", "D", "E"];
$porLinha = 15;
$largCelula = 186 / $porLinha;
for ($inicio = 0; $inicio < $total; $inicio += $porLinha) {
    $fim = min($inicio + $porLinha, $total);

    $pdf->SetX(12);
    $pdf->SetFillColor(...PDFSimulado::VERDE_ESCURO);
    $pdf->SetDrawColor(...PDFSimulado::VERDE);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont("Helvetica", "B", 9);
    for ($i = $inicio; $i < $fim; $i++) {
        $pdf->Cell($largCelula, 7, str_pad($i + 1, 2, "0", STR_PAD_LEFT), 1, 0, "C", true);
    }
    $pdf->Ln();

    $pdf->SetX(12);
    $pdf->SetFillColor(243, 246, 244);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont("Helvetica", "B", 11);
    for ($i = $inicio; $i < $fim; $i++) {
        $pdf->Cell($largCelula, 8, $letras[$questoes[$i]["correta"]], 1, 0, "C", true);
    }
    $pdf->Ln(12);
}

// Comentários de cada questão (somente no objetivo "Revisar conteúdos")
if ($simulado["objetivo"] == "revisar") {
    $temaAtual = "";
    foreach ($questoes as $indice => $questao) {
        // Evita que o título do comentário fique sozinho no fim da página
        if ($pdf->GetY() > PDFSimulado::LIMITE_INFERIOR - 30) {
            $pdf->AddPage();
        }

        // Escreve o nome do tema sempre que ele muda
        if ($questao["tema"] != $temaAtual) {
            $temaAtual = $questao["tema"];
            $pdf->Ln(2);
            $pdf->SetTextColor(...PDFSimulado::VERDE);
            $pdf->SetFont("Helvetica", "B", 11);
            $pdf->Cell(0, 7, pdf_texto(mb_strtoupper($temaAtual, "UTF-8")), "B", 1);
            $pdf->Ln(2);
        }

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont("Helvetica", "B", 9.5);
        $cabecalho = "Questão " . str_pad($indice + 1, 2, "0", STR_PAD_LEFT) . " — Alternativa " . $letras[$questao["correta"]];
        $pdf->Cell(0, 5, pdf_texto($cabecalho), 0, 1);
        $pdf->SetFont("Helvetica", "", 9.5);
        $pdf->MultiCell(0, 4.6, pdf_texto($questao["comentario"]), 0, "J");
        $pdf->Ln(2.5);
    }
}

// Nome do arquivo: simuladao-ecoenem-nome-do-estudante.pdf
$nomeArquivo = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "-", iconv("UTF-8", "ASCII//TRANSLIT", $nome)));
$pdf->Output("I", "simuladao-ecoenem-" . trim($nomeArquivo, "-") . ".pdf");
