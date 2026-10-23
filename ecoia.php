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

<section class="titulo">

    <h1>EcoIA</h1>

   
</section>

<section class="chat-container">

    <div id="chat">

        <div class="mensagem ia">

            <div class="bolha">
                Olá! Sou a EcoIA 🌱<br>
                Posso responder perguntas sobre Ecologia.
            </div>

        </div>

    </div>

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
        id="enviarBtn">
        <i class="fa-solid fa-paper-plane"></i>
    </button>

</form>

</section>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<script src="js/ecoia.js"></script>

</body>
</html>