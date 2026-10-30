<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#000000">

<title>EcoENEM</title>

<link rel="shortcut icon" href="imgs/ecoenemicon.png" type="image/x-icon">
<link rel="stylesheet" href="css/style.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

</head>
<body>

<header>

    <div class="logo">
        <a href="index.php">EcoENEM</a>
    </div>

    <nav aria-label="Navegação principal">
        <a href="ecoia.php">EcoIA</a>
        <a href="conteudo.php">Conteúdo</a>
        <a href="questoes.php">Questões</a>
        <a href="simulado.php">Simuladão</a>
        <a href="sobre.php">Sobre</a>
    </nav>

</header>

<section class="hero">

    <div class="left">

        <div class="content">

            <h1>EcoENEM</h1>

            <div class="painel glass">
                <p>
                    Aprenda Ecologia de forma prática com conteúdos organizados e questões para reforçar seu conhecimento.
                </p>
            </div>

            <div class="acoes">
                <a href="conteudo.php" class="btn btn-principal glass">
                    Começar a estudar
                </a>

                <a href="ecoia.php" class="btn glass">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3z"/>
                        <path d="M19 16l.7 2.3L22 19l-2.3.7L19 22l-.7-2.3L16 19l2.3-.7L19 16z"/>
                    </svg>
                    Perguntar à EcoIA
                </a>
            </div>

        </div>

    </div>

    <div class="right">

        <div id="earth-container"></div>

    </div>

</section>

<script src="js/script.js"></script>

</body>
</html>