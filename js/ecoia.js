
/* ==========================================================
   EcoIA — Sistema completo de chat e histórico
   - Envio de mensagens para a IA
   - Integração com api/chat.php
   - Salvamento automático no localStorage
   - Criação, abertura e exclusão de conversas
   - Contexto individual para cada conversa
   - Sugestões de perguntas
   - Recebimento de perguntas vindas das questões
   - Suporte a Markdown
   ========================================================== */

(() => {
    const chat = document.getElementById("chat");
    const prompt = document.getElementById("prompt");
    const enviarBtn = document.getElementById("enviarBtn");
    const chatForm = document.getElementById("chatForm");

    const body = document.body;
    const boasVindas = document.querySelector(".boas-vindas");

    const menuBtn = document.getElementById("menuBtn");
    const fecharBtn = document.getElementById("fecharHist");
    const fundo = document.getElementById("fundoHist");
    const novoBtn = document.getElementById("novoChat");
    const listaChats = document.getElementById("listaChats");

    // Verifica os elementos essenciais.
    if (!chat || !prompt || !chatForm || !enviarBtn) return;

    const CHAVE_CHATS = "ecoia_chats";
    const CHAVE_PAINEL = "ecoia_hist_aberto";
    const MAX_CHATS = 50;

    let chats = carregarChats();
    let idAtual = null;
    let historico = [];
    let enviando = false;
    let controlador = null;
    let identificadorRequisicao = 0;

    /* ======================================================
       ARMAZENAMENTO
    ====================================================== */

    function carregarChats() {
        try {
            const dados = JSON.parse(
                localStorage.getItem(CHAVE_CHATS)
            );

            if (!Array.isArray(dados)) return [];

            dados.sort((a, b) => {
                return (a.criadoEm || a.atualizado || 0) -
                       (b.criadoEm || b.atualizado || 0);
            });

            let proximoNumero = 1;

            dados.forEach(conversa => {
                if (!conversa.criadoEm) {
                    conversa.criadoEm =
                        conversa.atualizado || Date.now();
                }

                if (!Number.isInteger(conversa.numero)) {
                    conversa.numero = proximoNumero;
                }

                proximoNumero = Math.max(
                    proximoNumero,
                    conversa.numero + 1
                );
            });

            localStorage.setItem(
                CHAVE_CHATS,
                JSON.stringify(dados)
            );

            return dados;

        } catch (erro) {
            console.error("Erro ao carregar histórico:", erro);
            return [];
        }
    }

    function gravarChats() {
        try {
            localStorage.setItem(
                CHAVE_CHATS,
                JSON.stringify(chats)
            );

            return true;

        } catch (erro) {
            console.error("Erro ao salvar histórico:", erro);
            return false;
        }
    }

    function gerarId() {
        return Date.now().toString(36) +
            Math.random().toString(36).substring(2, 9);
    }

    function atualizarVazio() {
        const temMensagem = historico.some(
            m => m.role === "user"
        );

        body.classList.toggle("chat-vazio", !temMensagem);

        if (boasVindas) {
            boasVindas.style.display =
                temMensagem ? "none" : "";
        }
    }

    function salvarConversa() {
        if (!historico.some(m => m.role === "user")) return;

        if (!idAtual) {
            idAtual = gerarId();
        }

        const existente = chats.find(
            c => c.id === idAtual
        );

        const primeiraMensagem = historico.find(
            m => m.role === "user"
        );

        const conversa = {
            id: idAtual,

            numero: existente
                ? existente.numero
                : Math.max(
                    0,
                    ...chats.map(c => c.numero || 0)
                ) + 1,

            criadoEm: existente
                ? existente.criadoEm
                : Date.now(),

            titulo: primeiraMensagem.content
                .replace(/\s+/g, " ")
                .trim()
                .slice(0, 60) || "Nova conversa",

            ctx: historico.map(m => ({
                role: m.role,
                content: m.content
            })),

            atualizado: Date.now()
        };

        if (existente) {
            Object.assign(existente, conversa);
        } else {
            chats.push(conversa);
        }

        chats.sort((a, b) => b.numero - a.numero);

        chats = chats.slice(0, MAX_CHATS);

        gravarChats();
        renderizarLista();
    }

    /* ======================================================
       RENDERIZAÇÃO DAS MENSAGENS
    ====================================================== */

    function adicionarMensagem(papel, texto) {
        const mensagem = document.createElement("div");

        mensagem.className =
            "mensagem " +
            (papel === "user" ? "usuario" : "ia");

        const bolha = document.createElement("div");
        bolha.className = "bolha";

        if (papel === "user") {
            bolha.textContent = texto;

        } else if (typeof marked !== "undefined") {
            bolha.innerHTML = marked.parse(texto);

        } else {
            bolha.textContent = texto;
        }

        mensagem.appendChild(bolha);
        chat.appendChild(mensagem);

        chat.scrollTop = chat.scrollHeight;

        return mensagem;
    }

    function mostrarCarregamento() {
        const mensagem = document.createElement("div");
        mensagem.className = "mensagem ia";

        const bolha = document.createElement("div");
        bolha.className = "bolha";

        bolha.innerHTML = `
            <div class="loader-bolas">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        `;

        mensagem.appendChild(bolha);
        chat.appendChild(mensagem);

        chat.scrollTop = chat.scrollHeight;

        return mensagem;
    }

    function renderizarMensagens() {
        chat.innerHTML = "";

        historico.forEach(mensagem => {
            adicionarMensagem(
                mensagem.role,
                mensagem.content
            );
        });

        atualizarVazio();
        chat.scrollTop = chat.scrollHeight;
    }

    /* ======================================================
       ENVIO DE MENSAGENS
    ====================================================== */

    chatForm.addEventListener("submit", e => {
        e.preventDefault();
        enviarMensagem();
    });

    prompt.addEventListener("keydown", e => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            chatForm.requestSubmit();
        }
    });

    async function enviarMensagem() {
        if (enviando) return;

        const texto = prompt.value.trim();

        if (!texto) return;

        if (texto.length > 5000) {
            alert(
                "Sua mensagem deve ter no máximo 5.000 caracteres."
            );
            return;
        }

        enviando = true;

        const requisicaoAtual =
            ++identificadorRequisicao;

        controlador = new AbortController();

        enviarBtn.disabled = true;

        /* Mensagem do usuário */

        historico.push({
            role: "user",
            content: texto
        });

        adicionarMensagem("user", texto);

        prompt.value = "";

        atualizarVazio();

        // Salva a pergunta imediatamente.
        salvarConversa();

        /*
         * Envia as últimas mensagens anteriores.
         * O PHP recebe a pergunta atual separadamente.
         */

        const contexto = historico.slice(-10, -1);

        const formData = new FormData();

        formData.append("mensagem", texto);

        formData.append(
            "mensagens",
            JSON.stringify(contexto)
        );

        const carregamento = mostrarCarregamento();

        try {
            const response = await fetch("api/chat.php", {
                method: "POST",
                body: formData,
                signal: controlador.signal
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

            if (
                !response.ok ||
                data.erro ||
                data.error
            ) {
                throw new Error(
                    data.resposta ||
                    data.error ||
                    `Não foi possível consultar a EcoIA (HTTP ${response.status}).`
                );
            }

            if (requisicaoAtual !== identificadorRequisicao) {
                return;
            }

            const resposta = data.resposta;

            if (
                typeof resposta !== "string" ||
                !resposta.trim()
            ) {
                throw new Error(
                    "A EcoIA não retornou uma resposta válida."
                );
            }

            carregamento.remove();

            // Registra a resposta no histórico.
            historico.push({
                role: "assistant",
                content: resposta
            });

            // Exibe a resposta.
            adicionarMensagem("assistant", resposta);

            // Salva a conversa completa.
            salvarConversa();

        } catch (erro) {

            if (requisicaoAtual !== identificadorRequisicao) {
                return;
            }

            carregamento.remove();

            if (erro.name === "AbortError") return;

            console.error("Erro na EcoIA:", erro);

            const mensagemErro =
                erro.message ||
                "Não foi possível falar com a EcoIA. Tente novamente.";

            adicionarMensagem("assistant", mensagemErro);

            // Remove a pergunta que falhou do histórico.
            const ultima = historico[historico.length - 1];

            if (
                ultima &&
                ultima.role === "user" &&
                ultima.content === texto
            ) {
                historico.pop();
            }

            salvarConversa();

        } finally {

            if (requisicaoAtual === identificadorRequisicao) {
                enviando = false;
                controlador = null;

                enviarBtn.disabled = false;

                prompt.focus();

                chat.scrollTop = chat.scrollHeight;
            }
        }
    }

    /* ======================================================
       HISTÓRICO — LISTAGEM
    ====================================================== */

    function renderizarLista() {
        if (!listaChats) return;

        listaChats.innerHTML = "";

        if (!chats.length) {
            const vazio = document.createElement("li");

            vazio.className = "hist-vazio";
            vazio.textContent =
                "Suas conversas aparecerão aqui.";

            listaChats.appendChild(vazio);
            return;
        }

        chats
            .slice()
            .sort((a, b) => b.numero - a.numero)
            .forEach(conversa => {

                const li = document.createElement("li");

                li.className =
                    "chat-item" +
                    (conversa.id === idAtual ? " ativo" : "");

                const abrir = document.createElement("button");

                abrir.type = "button";
                abrir.className = "chat-titulo";

                abrir.textContent =
                    `Chat ${conversa.numero}`;

                abrir.title =
                    `Chat ${conversa.numero}`;

                abrir.addEventListener("click", () => {
                    abrirConversa(conversa.id);
                });

                const excluir = document.createElement("button");

                excluir.type = "button";
                excluir.className = "excluir";

                excluir.setAttribute(
                    "aria-label",
                    "Excluir conversa"
                );

                excluir.innerHTML =
                    '<i class="fa-solid fa-trash-can"></i>';

                excluir.addEventListener("click", () => {
                    excluirConversa(conversa.id);
                });

                li.appendChild(abrir);
                li.appendChild(excluir);

                listaChats.appendChild(li);
            });
    }

    /* ======================================================
       ABRIR CONVERSA ANTERIOR
    ====================================================== */

    function abrirConversa(id) {
        const conversa = chats.find(c => c.id === id);

        if (!conversa) return;

        // Impede respostas antigas de aparecerem na conversa aberta.
        cancelarRequisicao();

        salvarConversa();

        idAtual = conversa.id;

        // Compatibilidade com versões anteriores.
        if (Array.isArray(conversa.ctx)) {

            historico = conversa.ctx.map(m => ({
                role: m.role,
                content: m.content
            }));

        } else {

            historico = (conversa.msgs || []).map(m => {
                const temporario =
                    document.createElement("div");

                temporario.innerHTML = m.html || "";

                return {
                    role: m.papel === "usuario"
                        ? "user"
                        : "assistant",

                    content: temporario.textContent.trim()
                };
            });
        }

        renderizarMensagens();
        renderizarLista();

        fecharPainel();
        prompt.focus();
    }

    /* ======================================================
       CANCELAR REQUISIÇÃO
    ====================================================== */

    function cancelarRequisicao() {
        identificadorRequisicao++;

        if (controlador) {
            controlador.abort();
            controlador = null;
        }

        enviando = false;

        enviarBtn.disabled = false;
    }

    /* ======================================================
       NOVO CHAT
    ====================================================== */

    function novaConversa() {

        salvarConversa();

        cancelarRequisicao();

        idAtual = null;
        historico = [];

        chat.innerHTML = "";
        prompt.value = "";

        atualizarVazio();
        renderizarLista();

        fecharPainel();
        prompt.focus();
    }

    if (novoBtn) {
        novoBtn.addEventListener("click", novaConversa);
    }

    /* ======================================================
       EXCLUIR CONVERSA
    ====================================================== */

    function excluirConversa(id) {
        if (
            !confirm(
                "Deseja realmente excluir esta conversa?"
            )
        ) {
            return;
        }

        chats = chats.filter(c => c.id !== id);

        gravarChats();

        if (id === idAtual) {

            cancelarRequisicao();

            idAtual = null;
            historico = [];

            chat.innerHTML = "";
            prompt.value = "";

            atualizarVazio();
        }

        renderizarLista();
    }

    /* ======================================================
       SUGESTÕES
    ====================================================== */

    document.querySelectorAll(".sugestao").forEach(botao => {

        botao.addEventListener("click", () => {

            // Apenas preenche o campo, sem enviar.
            prompt.value = botao.textContent.trim();

            prompt.focus();

            prompt.setSelectionRange(
                prompt.value.length,
                prompt.value.length
            );
        });
    });

    /* ======================================================
       PERGUNTA RECEBIDA DAS QUESTÕES
    ====================================================== */

    function receberPerguntaInicial() {
        const perguntaInicial = localStorage.getItem(
            "ecoia_pergunta_inicial"
        );

        if (perguntaInicial) {

            prompt.value = perguntaInicial;

            localStorage.removeItem(
                "ecoia_pergunta_inicial"
            );

            prompt.focus();

            prompt.setSelectionRange(
                prompt.value.length,
                prompt.value.length
            );
        }
    }

    // Executa mesmo se o DOMContentLoaded já tiver ocorrido.
    if (document.readyState === "loading") {
        document.addEventListener(
            "DOMContentLoaded",
            receberPerguntaInicial,
            { once: true }
        );
    } else {
        receberPerguntaInicial();
    }

    /* ======================================================
       PAINEL LATERAL
    ====================================================== */

    function abrirPainel() {
        body.classList.add("hist-aberto");

        if (menuBtn) {
            menuBtn.setAttribute(
                "aria-expanded",
                "true"
            );

            menuBtn.setAttribute(
                "aria-label",
                "Fechar histórico"
            );
        }

        if (!window.matchMedia("(max-width: 768px)").matches) {
            sessionStorage.setItem(CHAVE_PAINEL, "1");
        }
    }

    function fecharPainel() {
        body.classList.remove("hist-aberto");

        if (menuBtn) {
            menuBtn.setAttribute(
                "aria-expanded",
                "false"
            );

            menuBtn.setAttribute(
                "aria-label",
                "Abrir histórico"
            );
        }

        sessionStorage.removeItem(CHAVE_PAINEL);
    }

    if (menuBtn) {
        menuBtn.addEventListener("click", () => {

            if (body.classList.contains("hist-aberto")) {
                fecharPainel();
            } else {
                abrirPainel();
            }
        });
    }

    if (fecharBtn) {
        fecharBtn.addEventListener("click", fecharPainel);
    }

    if (fundo) {
        fundo.addEventListener("click", fecharPainel);
    }

    document.addEventListener("keydown", e => {

        if (
            e.key === "Escape" &&
            body.classList.contains("hist-aberto")
        ) {
            fecharPainel();

            if (menuBtn) {
                menuBtn.focus();
            }
        }
    });

    /* ======================================================
       INICIALIZAÇÃO
    ====================================================== */

    // Toda entrada na página começa com um chat novo.
    idAtual = null;
    historico = [];

    chat.innerHTML = "";
    prompt.value = "";

    atualizarVazio();
    renderizarLista();

    // Restaura o painel aberto apenas no desktop.
    if (
        window.matchMedia("(min-width: 769px)").matches &&
        sessionStorage.getItem(CHAVE_PAINEL) === "1"
    ) {
        abrirPainel();
    }

    // Salva a conversa caso o usuário saia da página.
    window.addEventListener("pagehide", salvarConversa);

})();
