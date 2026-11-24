let atual = 0;
let respondidas = 0;
let acertos = 0;

const respondidasQuestoes = new Array(questoes.length).fill(false);
// Guarda a alternativa escolhida em cada questão para que, ao voltar,
// a correção continue visível e a questão não seja contada duas vezes.
const escolhas = new Array(questoes.length).fill(null);

const letras = ["A", "B", "C", "D", "E"];
const box = document.getElementById("questaoBox");
const convite = document.getElementById("ecoiaConvite");

// Remove espaços e quebras de linha vindos da indentação do PHP.
const limpar = texto => String(texto).replace(/[ \t]+\n/g, "\n").trim();

function escaparHTML(texto) {
    return String(texto)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}

function render() {

    const q = questoes[atual];

    convite.classList.remove("ativo");

    let html = "";

    q.alternativas.forEach((alt, index) => {
        html += `
            <button type="button" class="alt" data-index="${index}">
                <strong>${letras[index]})</strong> ${escaparHTML(limpar(alt))}
            </button>
        `;
    });

    box.innerHTML = `
    <div class="questao">

        <div class="fonte">
            ${escaparHTML(q.fonte)}
        </div>

        <p class="enunciado">${escaparHTML(limpar(q.enunciado))}</p>

        ${q.imagem ? `<img src="${q.imagem}" alt="Imagem de apoio à questão" loading="lazy">` : ""}

        <div class="alternativas" role="group" aria-label="Alternativas">
            ${html}
        </div>

    </div>
`;

    box.querySelectorAll(".alt").forEach(botao => {
        botao.addEventListener("click", () =>
            responder(botao, Number(botao.dataset.index), q.correta)
        );
    });

    // Questão já respondida: mostra novamente a correção, sem recontar.
    if (escolhas[atual] !== null) {
        const escolhida = box.querySelectorAll(".alt")[escolhas[atual]];
        mostrarCorrecao(escolhida, escolhas[atual], q.correta);
    }

    document.getElementById("prevBtn").disabled = atual === 0;
}

function responder(el, index, correta) {

    if (escolhas[atual] !== null) return;

    escolhas[atual] = index;

    if (!respondidasQuestoes[atual]) {
        respondidasQuestoes[atual] = true;
        respondidas++;
        if (index === correta) acertos++;
        atualizarProgresso();
        // Registra a questão no contador do usuário com incremento atômico.
        if (window.EcoAuth) {
            EcoAuth.client.then(cliente => cliente.rpc("incrementar_estatistica", { p_campo: "questoes_respondidas" }))
                .then(({ error }) => { if (error) console.warn("Falha ao registrar questão:", error); })
                .catch(erro => console.warn("Falha ao registrar questão:", erro));
        }
    }

    mostrarCorrecao(el, index, correta);
}

function mostrarCorrecao(el, index, correta) {

    const alternativas = box.querySelectorAll(".alt");
    const q = questoes[atual];

    alternativas.forEach(a => {
        a.disabled = true;
        a.style.pointerEvents = "none";
    });

    let feedback = "";

    if (index === correta) {

        // classe em vez de cor inline: o CSS cuida do visual de vidro
        el.classList.add("correta");

        feedback = `
            <div class="feedback-correto" role="status">
                <strong>Alternativa ${letras[correta]} correta.</strong><br><br>
                ${q.explicacao}
            </div>
        `;

    } else {

        el.classList.add("errada");
        alternativas[correta].classList.add("correta");

        feedback = `
            <div class="feedback-errado" role="status">
                <strong>Alternativa ${letras[index]} incorreta.</strong><br><br>
                ${q.explicacao}
            </div>
        `;
    }

    // MOSTRA CONVITE PARA A ECOIA
    convite.classList.add("ativo");

    // PREPARA PERGUNTA PARA A ECOIA
    document.getElementById("perguntarEcoIA").onclick = () => {

        const perguntaEcoIA = `
Estou estudando uma questão de Biologia do EcoENEM e gostaria de entender melhor o conteúdo.

QUESTÃO:
${limpar(q.enunciado)}

ALTERNATIVAS:
${q.alternativas.map((alt, i) =>
    `${String.fromCharCode(65 + i)}) ${limpar(alt)}`
).join("\n")}

MINHA RESPOSTA:
${limpar(q.alternativas[index])}

RESPOSTA CORRETA:
${limpar(q.alternativas[correta])}

EXPLICAÇÃO:
${q.explicacao}

Com base nessa questão, explique detalhadamente o conteúdo envolvido, por que a alternativa correta está certa e por que minha resposta está certa ou errada.

Utilize uma linguagem simples e didática, como um professor explicando para um estudante que está se preparando para o ENEM.

Se possível, dê exemplos práticos para facilitar meu entendimento.
        `.trim();

        // SALVA A PERGUNTA PARA A ECOIA
        localStorage.setItem("ecoia_pergunta_inicial", perguntaEcoIA);

        // REDIRECIONA PARA A ECOIA
        window.location.href = "ecoia.php";
    };

    box.querySelector(".questao").insertAdjacentHTML("beforeend", feedback);
}

function irPara(indice) {
    atual = indice;
    render();
    // No celular, leva o início da nova questão para a área visível.
    const topo = document.querySelector(".progresso-container");
    if (topo && window.innerWidth <= 768 && topo.getBoundingClientRect().top < 0) {
        topo.scrollIntoView({ block: "start", behavior: "smooth" });
    }
}

document.getElementById("nextBtn").onclick = () => {

    if (atual < questoes.length - 1) {
        irPara(atual + 1);
    } else {
        mostrarResultado();
    }
};

document.getElementById("prevBtn").onclick = () => {
    if (atual > 0) {
        irPara(atual - 1);
    }
};

function atualizarProgresso() {

    const porcentagem = (respondidas / questoes.length) * 100;

    document.getElementById("progressoBar").style.width = porcentagem + "%";

    document.getElementById("contadorQuestoes").textContent =
        `${respondidas} / ${questoes.length} respondidas`;
}

function mostrarResultado() {

    let mensagem = "";

    const percentual = Math.round((acertos / questoes.length) * 100);

    if (percentual >= 80) {
        mensagem = "Excelente desempenho! Continue assim.";
    } else if (percentual >= 60) {
        mensagem = "Muito bem! Você está no caminho certo.";
    } else if (percentual >= 40) {
        mensagem = "Bom esforço! Continue estudando para melhorar.";
    } else {
        mensagem = "Não desista! Revise os conteúdos e tente novamente.";
    }

    convite.classList.remove("ativo");

    box.innerHTML = `
        <div class="questao resultado-final">

            <h2>Resumo Final</h2>

            <p>
                Você acertou <strong>${acertos}</strong>
                de <strong>${questoes.length}</strong> questões.
            </p>

            <p>
                Percentual de acertos:
                <strong>${percentual}%</strong>
            </p>

            <p>${mensagem}</p>

            <button class="reiniciar-btn"
                onclick="location.reload()">
                Responder novamente
            </button>

        </div>
    `;
}

render();
