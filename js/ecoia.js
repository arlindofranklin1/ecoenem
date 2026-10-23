const chat = document.getElementById("chat");
const prompt = document.getElementById("prompt");
const enviarBtn = document.getElementById("enviarBtn");



const chatForm = document.getElementById("chatForm");

/* previne de recarrer a página */
chatForm.addEventListener("submit", (e) => {
    e.preventDefault();
    enviarMensagem();
});


// histórico da conversa
let historico = [];

/* =========================
   ENVIO
========================= */

prompt.addEventListener("keydown", (e) => {
    if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        enviarMensagem();
    }
});

async function enviarMensagem() {

    const texto = prompt.value.trim();

    if (texto === "") return;

    /* =========================
       MOSTRA MENSAGEM DO USUÁRIO
    ========================= */

    chat.innerHTML += `
        <div class="mensagem usuario">
            <div class="bolha">
            ${texto}
            </div>
        </div>
    `;

    /* =========================
       SALVA NO HISTÓRICO
    ========================= */

    historico.push({
        role: "user",
        content: texto
    });

    /* =========================
       PREPARA ENVIO (ÚLTIMAS 3 MENSAGENS)
    ========================= */

    const contexto = historico.slice(-3);

    const formData = new FormData();
    formData.append("mensagens", JSON.stringify(contexto));

    prompt.value = "";

    /* =========================
       LOADING IA
    ========================= */

    const respostaDiv = document.createElement("div");
    respostaDiv.className = "mensagem ia";
    respostaDiv.innerHTML = `
        <div class="bolha">
            <div class="loader-bolas">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    `;

    chat.appendChild(respostaDiv);
    chat.scrollTop = chat.scrollHeight;

    /* =========================
       FETCH PHP
    ========================= */

    let data;

    try {
        const response = await fetch("api/chat.php", {
            method: "POST",
            body: formData
        });

        data = await response.json();
    } catch (erro) {
        // servidor fora do ar, sem internet ou resposta que nao e JSON
        data = {
            resposta: "Não foi possível falar com a EcoIA. Verifique se o servidor PHP está rodando e tente novamente.",
            erro: true
        };
    }

    const resposta = data.resposta || "A EcoIA não retornou resposta. Tente novamente.";

    /* =========================
       MOSTRA RESPOSTA
    ========================= */

    respostaDiv.innerHTML = `
    <div class="bolha">
        ${marked.parse(resposta)}
    </div>
`;

    /* =========================
       SALVA RESPOSTA NO HISTÓRICO
    ========================= */

    if (data.erro) {
        // deu erro: tira a pergunta do historico para nao confundir a IA depois
        historico.pop();
    } else {
        historico.push({
            role: "assistant",
            content: resposta
        });
    }

    chat.scrollTop = chat.scrollHeight;
}