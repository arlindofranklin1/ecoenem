<?php

/*
 * ============================================================
 *  SIMULADÃO ECOENEM — MODELO DO PDF
 * ============================================================
 *  Classe que estende a biblioteca FPDF (lib/fpdf) e desenha o
 *  caderno do simulado no estilo da prova do ENEM:
 *   - cabeçalho institucional na primeira página;
 *   - cabeçalho "ecoenem2026" com código de barras nas demais;
 *   - questões em duas colunas, com alternativas em círculos;
 *   - rodapé com a área do conhecimento e o número da página.
 * ============================================================
 */

require "lib/fpdf/fpdf.php"; // Importa a biblioteca FPDF

/**
 * Converte um texto UTF-8 para a codificação usada pelas fontes
 * padrão do FPDF (Windows-1252), para os acentos saírem corretos no PDF.
 *
 * @param string $texto Texto em UTF-8.
 * @return string Texto convertido.
 */
function pdf_texto($texto)
{
    return iconv("UTF-8", "windows-1252//TRANSLIT", $texto);
}

class PDFSimulado extends FPDF
{
    // Dados do simulado (nome, e-mail, código...) usados no cabeçalho
    public $dados = [];

    // Controle das duas colunas
    public $emColunas = false;
    public $coluna = 0;
    public $topoColunas = 30;

    // Medidas da página (em milímetros)
    const MARGEM = 12;
    const LARGURA_COLUNA = 89;
    const ESPACO_COLUNAS = 8;
    const LIMITE_INFERIOR = 279;
    const ALTURA_LINHA = 4.3;

    // Cores do EcoEnem (as mesmas do site)
    const VERDE_ESCURO = [43, 67, 52];   // #2B4334
    const VERDE = [73, 111, 86];         // #496F56
    const VERDE_CLARO = [134, 164, 143]; // #86A48F

    /* ===================== CABEÇALHO ===================== */

    function Header()
    {
        // Faixa verde vertical no canto da página (como a cor do caderno do ENEM)
        $this->SetFillColor(...self::VERDE);
        $this->Rect(203, 0, 7, 32, "F");

        if ($this->PageNo() == 1) {
            $this->cabecalhoInstitucional();
        } else {
            $this->cabecalhoEnem();
        }

        // Linha que separa as duas colunas
        if ($this->emColunas) {
            $this->linhaDivisoria();
        }
    }

    // Cabeçalho da primeira página, no padrão da proposta do projeto
    function cabecalhoInstitucional()
    {
        $this->SetY(10);
        $this->SetTextColor(110, 110, 110);
        $this->SetFont("Times", "", 9.5);
        $this->Cell(0, 4.5, pdf_texto("Instituto Federal da Paraíba – Campus Santa Luzia"), 0, 1, "C");
        $this->Cell(0, 4.5, pdf_texto("Curso Técnico Integrado em Informática — Desenvolvimento de Aplicações Web II"), 0, 1, "C");

        $this->Ln(3);
        $this->SetTextColor(...self::VERDE);
        $this->SetFont("Times", "B", 28);
        $this->Cell(0, 11, "EcoEnem", 0, 1, "C");

        $this->SetFont("Times", "I", 13);
        $this->Cell(0, 6, pdf_texto("Simuladão EcoEnem — Simulado de Ecologia"), 0, 1, "C");

        $this->SetTextColor(110, 110, 110);
        $this->SetFont("Times", "", 9);
        $this->MultiCell(0, 4, pdf_texto("Plataforma desenvolvida por Arlindo Franklim Borborema Neto, Lúcia de Fátima Pinheiro da Nóbrega e Manuela Raquelly Ferreira do Nascimento"), 0, "C");
        $this->Ln(3);
    }

    // Cabeçalho das demais páginas, no padrão do caderno do ENEM
    function cabecalhoEnem()
    {
        $this->SetY(9);
        $this->SetX(self::MARGEM);

        // "ecoenem" em negrito + "2026" mais claro (como "enem2025")
        $this->SetTextColor(...self::VERDE_ESCURO);
        $this->SetFont("Helvetica", "B", 24);
        $this->Cell($this->GetStringWidth("ecoenem"), 9, "ecoenem", 0, 0);
        $this->SetTextColor(...self::VERDE_CLARO);
        $this->SetFont("Helvetica", "", 24);
        $this->Cell(30, 9, date("Y"), 0, 1);

        $this->SetX(self::MARGEM);
        $this->SetTextColor(...self::VERDE);
        $this->SetFont("Helvetica", "", 8.5);
        $this->Cell(100, 4, pdf_texto("Simuladão EcoEnem — Ecologia"), 0, 1);

        $this->codigoBarras(150, 9, 45, 8, $this->dados["codigo"]);

        // Linha abaixo do cabeçalho
        $this->SetDrawColor(...self::VERDE);
        $this->SetLineWidth(0.6);
        $this->Line(self::MARGEM, 25, 210 - self::MARGEM - 5, 25);

        $this->topoColunas = 30;
        $this->SetY($this->topoColunas);
    }

    // Desenha um código de barras decorativo gerado a partir do código do simulado
    function codigoBarras($x, $y, $largura, $altura, $codigo)
    {
        mt_srand(crc32($codigo)); // Mesmo código = mesmo desenho
        $this->SetFillColor(0, 0, 0);
        $posicao = $x;
        while ($posicao < $x + $largura) {
            $barra = mt_rand(2, 8) / 10;
            $this->Rect($posicao, $y, $barra, $altura, "F");
            $posicao += $barra + mt_rand(3, 8) / 10;
        }
        mt_srand(); // Volta o sorteio ao normal

        $this->SetXY($x, $y + $altura + 0.5);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont("Courier", "", 7);
        $this->Cell($largura, 3, "* " . implode(" ", str_split($codigo)) . " *", 0, 0, "C");
    }

    /* ===================== RODAPÉ ===================== */

    function Footer()
    {
        // Usa a largura total da página (as colunas mudam as margens)
        $margemEsquerda = $this->lMargin;
        $margemDireita = $this->rMargin;
        $this->SetLeftMargin(self::MARGEM);
        $this->SetRightMargin(self::MARGEM);

        $this->SetY(-16);
        $this->SetDrawColor(160, 160, 160);
        $this->SetLineWidth(0.3);
        $this->Line(self::MARGEM, $this->GetY(), 210 - self::MARGEM, $this->GetY());

        $this->Ln(1.5);
        $this->SetTextColor(110, 110, 110);
        $this->SetFont("Helvetica", "", 7.5);
        $this->Cell(150, 6, pdf_texto("CIÊNCIAS DA NATUREZA E SUAS TECNOLOGIAS  |  ECOLOGIA  |  SIMULADÃO ECOENEM  |  " . $this->dados["codigo"]), 0, 0, "L");

        $this->SetTextColor(0, 0, 0);
        $this->SetFont("Helvetica", "B", 12);
        $this->Cell(0, 6, $this->PageNo(), 0, 0, "R");

        // Devolve as margens da coluna atual
        $this->SetLeftMargin($margemEsquerda);
        $this->SetRightMargin($margemDireita);
    }

    /* ===================== COLUNAS ===================== */

    // Linha vertical entre as duas colunas
    function linhaDivisoria()
    {
        $meio = self::MARGEM + self::LARGURA_COLUNA + self::ESPACO_COLUNAS / 2;
        $this->SetDrawColor(190, 190, 190);
        $this->SetLineWidth(0.2);
        $this->Line($meio, $this->topoColunas, $meio, self::LIMITE_INFERIOR);
    }

    // Posiciona a escrita na coluna escolhida (0 = esquerda, 1 = direita)
    function definirColuna($coluna)
    {
        $this->coluna = $coluna;
        $x = self::MARGEM + $coluna * (self::LARGURA_COLUNA + self::ESPACO_COLUNAS);
        $this->SetLeftMargin($x);
        $this->SetRightMargin(210 - $x - self::LARGURA_COLUNA);
        $this->SetX($x);
    }

    // Inicia a área de duas colunas a partir da posição atual
    function iniciarColunas()
    {
        $this->emColunas = true;
        $this->topoColunas = $this->GetY();
        $this->linhaDivisoria();
        $this->definirColuna(0);
    }

    // Encerra as colunas e volta para a largura total da página
    function encerrarColunas()
    {
        $this->emColunas = false;
        $this->SetLeftMargin(self::MARGEM);
        $this->SetRightMargin(self::MARGEM);
    }

    // Passa para a próxima coluna (ou para uma nova página)
    function proximaColuna()
    {
        if ($this->coluna == 0) {
            $this->definirColuna(1);
            $this->SetY($this->topoColunas);
        } else {
            $this->definirColuna(0);
            $this->AddPage();
        }
    }

    // Chamado automaticamente pelo FPDF quando o texto chega ao fim da página
    function AcceptPageBreak()
    {
        if (!$this->emColunas) {
            return true;
        }
        if ($this->coluna == 0) {
            $this->definirColuna(1);
            $this->SetY($this->topoColunas);
            return false; // Continua na coluna da direita, sem criar página
        }
        $this->definirColuna(0);
        return true; // Cria uma nova página e volta para a coluna da esquerda
    }

    /* ===================== QUESTÕES ===================== */

    // Calcula a altura (em mm) que uma questão vai ocupar, sem desenhá-la
    function alturaQuestao($questao)
    {
        $altura = 9; // título "QUESTÃO XX" + linha

        $this->SetFont("Helvetica", "", 9.5);
        foreach ($questao["texto"] as $paragrafo) {
            $altura += $this->contarLinhas(self::LARGURA_COLUNA, pdf_texto($paragrafo)) * self::ALTURA_LINHA + 1.5;
        }
        if (!empty($questao["imagem"])) {
            $altura += $this->alturaImagem($questao["imagem"]) + 3;
        }
        $altura += $this->contarLinhas(self::LARGURA_COLUNA, pdf_texto($questao["comando"])) * self::ALTURA_LINHA + 2;

        foreach ($questao["alternativas"] as $alternativa) {
            $linhas = $this->contarLinhas(self::LARGURA_COLUNA - 6, pdf_texto($alternativa));
            $altura += max($linhas * self::ALTURA_LINHA, 4.5) + 1.2;
        }
        return $altura;
    }

    // Altura da imagem quando ajustada à largura da coluna
    function alturaImagem($caminho)
    {
        list($largura, $altura) = getimagesize($caminho);
        return (self::LARGURA_COLUNA * 0.8) * $altura / $largura;
    }

    // Desenha uma questão completa no estilo do ENEM
    function questao($numero, $questao)
    {
        // Se a questão não couber no espaço que resta, passa para a próxima coluna
        $altura = $this->alturaQuestao($questao);
        for ($tentativa = 0; $tentativa < 3 && $altura > self::LIMITE_INFERIOR - $this->GetY(); $tentativa++) {
            $this->proximaColuna();
        }

        $x = $this->GetX();
        $largura = self::LARGURA_COLUNA;

        // Título: "QUESTÃO 01" e a fonte da questão
        $this->SetTextColor(0, 0, 0);
        $this->SetFont("Helvetica", "B", 11);
        $this->Cell($largura / 2, 6, pdf_texto("QUESTÃO " . str_pad($numero, 2, "0", STR_PAD_LEFT)), 0, 0);

        $fonte = $questao["fonte"] . ($questao["adaptada"] ? " (adaptada)" : "");
        $this->SetTextColor(110, 110, 110);
        $this->SetFont("Helvetica", "", 7.5);
        $this->Cell($largura / 2, 6, pdf_texto($fonte), 0, 1, "R");

        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.3);
        $this->Line($x, $this->GetY() + 0.5, $x + $largura, $this->GetY() + 0.5);
        $this->Ln(3);

        // Texto-base
        $this->SetTextColor(0, 0, 0);
        $this->SetFont("Helvetica", "", 9.5);
        foreach ($questao["texto"] as $paragrafo) {
            $this->MultiCell($largura, self::ALTURA_LINHA, pdf_texto($paragrafo), 0, "J");
            $this->Ln(1.5);
        }

        // Imagem (quando houver), centralizada na coluna
        if (!empty($questao["imagem"])) {
            $larguraImagem = $largura * 0.8;
            $alturaImagem = $this->alturaImagem($questao["imagem"]);
            $this->Image($questao["imagem"], $x + ($largura - $larguraImagem) / 2, $this->GetY() + 1, $larguraImagem);
            $this->SetY($this->GetY() + $alturaImagem + 3);
            $this->SetX($x);
        }

        // Comando da questão
        $this->MultiCell($largura, self::ALTURA_LINHA, pdf_texto($questao["comando"]), 0, "J");
        $this->Ln(2);

        // Alternativas com a letra dentro de um círculo preto
        $letras = ["A", "B", "C", "D", "E"];
        foreach ($questao["alternativas"] as $indice => $alternativa) {
            $y = $this->GetY();

            $this->SetFillColor(0, 0, 0);
            $this->circulo($x + 2.2, $y + 2.15, 2.1, "F");
            $this->SetXY($x, $y);
            $this->SetTextColor(255, 255, 255);
            $this->SetFont("Helvetica", "B", 8);
            $this->Cell(4.4, 4.3, $letras[$indice], 0, 0, "C");

            $this->SetXY($x + 6, $y);
            $this->SetTextColor(0, 0, 0);
            $this->SetFont("Helvetica", "", 9.5);
            $this->MultiCell($largura - 6, self::ALTURA_LINHA, pdf_texto($alternativa), 0, "L");

            if ($this->GetY() < $y + 4.5) {
                $this->SetY($y + 4.5);
            }
            $this->Ln(1.2);
            $this->SetX($x);
        }

        $this->Ln(6);
        $this->SetX($this->lMargin);
    }

    /* ===================== FUNÇÕES DE DESENHO ===================== */

    // Conta quantas linhas um texto ocupa em uma MultiCell de largura $w
    function contarLinhas($w, $txt)
    {
        $cw = $this->CurrentFont["cw"];
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", "", (string) $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n") {
            $nb--;
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == " ") {
                $sep = $i;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }

    // Desenha um círculo de centro ($x, $y) e raio $r
    function circulo($x, $y, $r, $estilo = "D")
    {
        $op = ($estilo == "F") ? "f" : (($estilo == "FD" || $estilo == "DF") ? "B" : "S");
        $lx = 4 / 3 * (M_SQRT2 - 1) * $r;
        $k = $this->k;
        $h = $this->h;
        $this->_out(sprintf("%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c", ($x + $r) * $k, ($h - $y) * $k, ($x + $r) * $k, ($h - ($y - $lx)) * $k, ($x + $lx) * $k, ($h - ($y - $r)) * $k, $x * $k, ($h - ($y - $r)) * $k));
        $this->_out(sprintf("%.2F %.2F %.2F %.2F %.2F %.2F c", ($x - $lx) * $k, ($h - ($y - $r)) * $k, ($x - $r) * $k, ($h - ($y - $lx)) * $k, ($x - $r) * $k, ($h - $y) * $k));
        $this->_out(sprintf("%.2F %.2F %.2F %.2F %.2F %.2F c", ($x - $r) * $k, ($h - ($y + $lx)) * $k, ($x - $lx) * $k, ($h - ($y + $r)) * $k, $x * $k, ($h - ($y + $r)) * $k));
        $this->_out(sprintf("%.2F %.2F %.2F %.2F %.2F %.2F c %s", ($x + $lx) * $k, ($h - ($y + $r)) * $k, ($x + $r) * $k, ($h - ($y + $lx)) * $k, ($x + $r) * $k, ($h - $y) * $k, $op));
    }

    // Desenha um retângulo com cantos arredondados
    function retanguloArredondado($x, $y, $w, $h, $r, $estilo = "D")
    {
        $k = $this->k;
        $hp = $this->h;
        $op = ($estilo == "F") ? "f" : (($estilo == "FD" || $estilo == "DF") ? "B" : "S");
        $arco = 4 / 3 * (sqrt(2) - 1);
        $this->_out(sprintf("%.2F %.2F m", ($x + $r) * $k, ($hp - $y) * $k));
        $xc = $x + $w - $r;
        $yc = $y + $r;
        $this->_out(sprintf("%.2F %.2F l", $xc * $k, ($hp - $y) * $k));
        $this->curva($xc + $r * $arco, $yc - $r, $xc + $r, $yc - $r * $arco, $xc + $r, $yc);
        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf("%.2F %.2F l", ($x + $w) * $k, ($hp - $yc) * $k));
        $this->curva($xc + $r, $yc + $r * $arco, $xc + $r * $arco, $yc + $r, $xc, $yc + $r);
        $xc = $x + $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf("%.2F %.2F l", $xc * $k, ($hp - ($y + $h)) * $k));
        $this->curva($xc - $r * $arco, $yc + $r, $xc - $r, $yc + $r * $arco, $xc - $r, $yc);
        $xc = $x + $r;
        $yc = $y + $r;
        $this->_out(sprintf("%.2F %.2F l", $x * $k, ($hp - $yc) * $k));
        $this->curva($xc - $r, $yc - $r * $arco, $xc - $r * $arco, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function curva($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $k = $this->k;
        $this->_out(sprintf("%.2F %.2F %.2F %.2F %.2F %.2F c ", $x1 * $k, ($h - $y1) * $k, $x2 * $k, ($h - $y2) * $k, $x3 * $k, ($h - $y3) * $k));
    }
}
