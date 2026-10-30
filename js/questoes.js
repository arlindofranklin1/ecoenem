let atual = 0;
let respondidas = 0;
let acertos = 0;

const respondidasQuestoes = new Array(questoes.length).fill(false);


const box = document.getElementById("questaoBox");

function render() {

    const q = questoes[atual];

    document.getElementById("ecoiaConvite")
    .classList.remove("ativo");

    let html = "";

    const letras = ["A", "B", "C", "D", "E"];

    q.alternativas.forEach((alt, index) => {

        html += `
            <div class="alt" onclick="responder(this, ${index}, ${q.correta})">
                <strong>${letras[index]})</strong> ${alt}
            </div>
        `;
    });

    box.innerHTML = `
    <div class="questao">

        <div class="fonte">
            ${q.fonte}
        </div>

        <p>${q.enunciado}</p>

        ${q.imagem ? `<img src="${q.imagem}">` : ""}

        <div class="alternativas">
            ${html}
        </div>

    </div>
`;
}

function responder(el, index, correta) {

    const alternativas = document.querySelectorAll(".alt");

    alternativas.forEach(a => a.style.pointerEvents = "none");

    if(!respondidasQuestoes[atual]){
        respondidasQuestoes[atual] = true;
        respondidas++;
        atualizarProgresso();
    }

    const q = questoes[atual];
    const letras = ["A", "B", "C", "D", "E"];

    let feedback = "";

    if(index === correta){

        acertos++;

        // classe em vez de cor inline: o CSS cuida do visual de vidro
        el.classList.add("correta");

        feedback = `
            <div class="feedback-correto">
                <strong>Alternativa ${letras[correta]} correta.</strong><br><br>
                ${q.explicacao}
            </div>
        `;

    }else{

        el.classList.add("errada");
        alternativas[correta].classList.add("correta");

        feedback = `
            <div class="feedback-errado">
                <strong>Alternativa ${letras[index]} incorreta.</strong><br><br>
                ${q.explicacao}
            </div>
        `;
    }

    // MOSTRA CONVITE PARA A ECOIA
document.getElementById("ecoiaConvite")
    .classList.add("ativo");

// PREPARA PERGUNTA PARA A ECOIA
const botaoEcoIA = document.getElementById("perguntarEcoIA");

botaoEcoIA.onclick = () => {

    const perguntaEcoIA = `
Estou estudando uma questão de Biologia do EcoENEM e gostaria de entender melhor o conteúdo.

QUESTÃO:
${q.enunciado}

ALTERNATIVAS:
${q.alternativas.map((alt, i) =>
    `${String.fromCharCode(65 + i)}) ${alt}`
).join("\n")}

MINHA RESPOSTA:
${q.alternativas[index]}

RESPOSTA CORRETA:
${q.alternativas[correta]}

EXPLICAÇÃO:
${q.explicacao}

Com base nessa questão, explique detalhadamente o conteúdo envolvido, por que a alternativa correta está certa e por que minha resposta está certa ou errada.

Utilize uma linguagem simples e didática, como um professor explicando para um estudante que está se preparando para o ENEM.

Se possível, dê exemplos práticos para facilitar meu entendimento.
    `.trim();

    // SALVA A PERGUNTA PARA A ECOIA
    localStorage.setItem(
        "ecoia_pergunta_inicial",
        perguntaEcoIA
    );

    // REDIRECIONA PARA A ECOIA
    window.location.href = "ecoia.php";
};

    document.querySelector(".questao").insertAdjacentHTML(
        "beforeend",
        feedback
    );
}

document.getElementById("nextBtn").onclick = () => {

    if (atual < questoes.length - 1) {

        atual++;
        render();

    } else {

        mostrarResultado();
    }
};

document.getElementById("prevBtn").onclick = () => {
    if (atual > 0) {
        atual--;
        render();
    }
};

function atualizarProgresso() {

    const porcentagem =
        (respondidas / questoes.length) * 100;

    document.getElementById("progressoBar").style.width =
        porcentagem + "%";

    document.getElementById("contadorQuestoes").textContent =
        `${respondidas} / ${questoes.length} respondidas`;
}

function mostrarResultado() {

    let mensagem = "";

    const percentual = Math.round(
        (acertos / questoes.length) * 100
    );

    if(percentual >= 80){
        mensagem = "Excelente desempenho! Continue assim.";
    }
    else if(percentual >= 60){
        mensagem = "Muito bem! Você está no caminho certo.";
    }
    else if(percentual >= 40){
        mensagem = "Bom esforço! Continue estudando para melhorar.";
    }
    else{
        mensagem = "Não desista! Revise os conteúdos e tente novamente.";
    }

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