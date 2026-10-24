const chat = document.getElementById("chat");
const prompt = document.getElementById("prompt");
const enviarBtn = document.getElementById("enviarBtn");
const chatForm = document.getElementById("chatForm");

let historico = [];
let enviando = false;

/* Evita recarregar a página ao enviar */
chatForm.addEventListener("submit", (e) => {
    e.preventDefault();
    enviarMensagem();
});

/* Envia com Enter; Shift + Enter permite nova linha */
prompt.addEventListener("keydown", (e) => {
    if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        enviarMensagem();
    }
});

function adicionarMensagemUsuario(texto) {
    const mensagemDiv = document.createElement("div");
    mensagemDiv.className = "mensagem usuario";

    const bolha = document.createElement("div");
    bolha.className = "bolha";
    bolha.textContent = texto;

    mensagemDiv.appendChild(bolha);
    chat.appendChild(mensagemDiv);
}

async function enviarMensagem() {
    if (enviando) return;

    const texto = prompt.value.trim();

    if (!texto) return;

    if (texto.length > 5000) {
        alert("Sua mensagem deve ter no máximo 5.000 caracteres.");
        return;
    }

    enviando = true;
    enviarBtn.disabled = true;

    adicionarMensagemUsuario(texto);

    historico.push({
        role: "user",
        content: texto
    });

    // Guarda o histórico anterior à pergunta atual.
    // O PHP adicionará a pergunta atual ao histórico enviado.
    const contexto = historico.slice(-10, -1);

    prompt.value = "";

    const respostaDiv = document.createElement("div");
    respostaDiv.className = "mensagem ia";

    const bolhaLoading = document.createElement("div");
    bolhaLoading.className = "bolha";
    bolhaLoading.innerHTML = `
        <div class="loader-bolas">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    `;

    respostaDiv.appendChild(bolhaLoading);
    chat.appendChild(respostaDiv);
    chat.scrollTop = chat.scrollHeight;

    try {
        const formData = new FormData();

        // Campo que o PHP procura para obter a pergunta atual.
        formData.append("mensagem", texto);

        // Histórico anterior, sem duplicar a pergunta atual.
        formData.append("mensagens", JSON.stringify(contexto));

        const response = await fetch("api/chat.php", {
            method: "POST",
            body: formData
        });

        const corpo = await response.text();
        let data;

        try {
            data = JSON.parse(corpo);
        } catch {
            throw new Error(
                `O servidor retornou uma resposta inválida (HTTP ${response.status}).`
            );
        }

        if (!response.ok || data.erro) {
            throw new Error(
                data.resposta || data.error ||
                `Não foi possível consultar a EcoIA (HTTP ${response.status}).`
            );
        }

        const resposta = data.resposta;

        if (typeof resposta !== "string" || !resposta.trim()) {
            throw new Error("A EcoIA não retornou uma resposta válida.");
        }

        // Renderiza a resposta em Markdown.
        bolhaLoading.replaceChildren();

        if (typeof marked !== "undefined") {
            bolhaLoading.innerHTML = marked.parse(resposta);
        } else {
            bolhaLoading.textContent = resposta;
        }

        historico.push({
            role: "assistant",
            content: resposta
        });

    } catch (erro) {
        console.error("Erro na EcoIA:", erro);

        bolhaLoading.textContent =
            erro.message || "Não foi possível falar com a EcoIA. Tente novamente.";

        // Remove a pergunta que falhou para não contaminar o histórico.
        const ultima = historico[historico.length - 1];

        if (ultima && ultima.role === "user" && ultima.content === texto) {
            historico.pop();
        }

    } finally {
        enviando = false;
        enviarBtn.disabled = false;
        prompt.focus();
        chat.scrollTop = chat.scrollHeight;
    }
}
