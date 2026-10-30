<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoIA</title>

    <link rel="shortcut icon" href="imgs/ecoenemicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/ecoia.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="chat-vazio">

<!-- HISTÓRICO (abre pela esquerda e empurra a tela) -->
<aside class="historico" id="historico" aria-label="Histórico de conversas">

    <div class="hist-topo">
        <h2>Histórico</h2>

        <button type="button" class="fechar-hist" id="fecharHist" aria-label="Fechar histórico">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <button type="button" class="novo-chat" id="novoChat">
        <i class="fa-solid fa-plus"></i>
        Novo chat
    </button>

    <p class="hist-rotulo">Conversas salvas</p>

    <ul class="hist-lista" id="listaChats"></ul>

</aside>

<div class="fundo-hist" id="fundoHist"></div>

<header>

    <div class="header-esq">

        <div class="logo">
            <a href="index.php">EcoENEM</a>
        </div>

    </div>

    <nav>
        <a href="ecoia.php">EcoIA</a>
        <a href="conteudo.php">Conteúdo</a>
        <a href="questoes.php">Questões</a>
        <a href="simulado.php">Simuladão</a>
        <a href="sobre.php">Sobre</a>
    </nav>

</header>

<!-- botão do histórico: círculo de vidro logo abaixo do cabeçalho -->
<button type="button" class="menu-btn" id="menuBtn"
    aria-label="Abrir histórico" aria-expanded="false" aria-controls="historico">
    <span></span>
    <span></span>
    <span></span>
</button>

<section class="chat-container">

    <!-- aparece só enquanto o chat está vazio -->
    <div class="boas-vindas">

        <h1>EcoIA</h1>

        <p>
            Sua assistente de Ecologia para o ENEM. Tire dúvidas, revise conceitos e entenda o porquê de cada resposta.
        </p>

        <div class="sugestoes">
            <button type="button" class="sugestao">O que é uma cadeia alimentar?</button>
            <button type="button" class="sugestao">Qual a diferença entre habitat e nicho ecológico?</button>
            <button type="button" class="sugestao">Explique o ciclo do carbono</button>
        </div>

    </div>

    <div id="chat"></div>

    <form
    id="chatForm"
    class="input-area"
    method="POST"
    enctype="multipart/form-data">

    <textarea
        id="prompt"
        name="mensagem"
        placeholder="Digite sua pergunta..."></textarea>

    <button
        type="submit"
        id="enviarBtn"
        aria-label="Enviar pergunta">
        <i class="fa-solid fa-paper-plane"></i>
    </button>

</form>

</section>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<script src="js/ecoia.js"></script>

<!-- histórico de conversas (precisa vir depois do ecoia.js) -->
<script src="js/historico.js"></script>

</body>
</html>