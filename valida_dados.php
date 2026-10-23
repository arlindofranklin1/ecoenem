<?php
/**
 * Valida os dados recebidos, removendo espaços em branco desnecessários,
 * barras invertidas e caracteres especiais HTML.
 *
 * Usada pelo Simuladão EcoEnem para proteger o formulário contra
 * HTML Injection e JavaScript Injection: com htmlspecialchars(),
 * caracteres como < > " ' & viram entidades HTML (&lt; &gt; ...) e
 * qualquer tag digitada (ex.: <script>) é exibida como texto, sem executar.
 *
 * @param string $dados Os dados a serem validados.
 * @return string Os dados validados.
 */
function valida_dados($dados)
{
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}
