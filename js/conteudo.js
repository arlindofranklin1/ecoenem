/* ==========================================================
   ECOENEM — JAVASCRIPT DA PÁGINA DE CONTEÚDO
   JavaScript puro, organizado por funcionalidades.
   ========================================================== */

function iniciarInteracoesConteudo() {

    "use strict";

    // Elementos com efeito de virar cartão são conectados após o DOM estar pronto.
    document.querySelectorAll('.card').forEach(function (card) {
        card.addEventListener('click', function () {
            card.classList.toggle('flip');
        });
    });

    const $ = function (selector, parent) {
        return (parent || document).querySelector(selector);
    };

    const $$ = function (selector, parent) {
        return Array.from((parent || document).querySelectorAll(selector));
    };

    /* ======================================================
       1. ANIMAÇÕES DE ENTRADA
       Respeita a preferência de acessibilidade do sistema.
       ====================================================== */

    const systemReduced = window.matchMedia
        ? window.matchMedia("(prefers-reduced-motion: reduce)")
        : { matches: false };
    const hasIntersectionObserver = typeof window.IntersectionObserver === "function";
    let observer = null;
    if (hasIntersectionObserver) {
        observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08 });
    }

    if (systemReduced.matches || !hasIntersectionObserver) {
        $$(".reveal").forEach(function (section) { section.classList.add("visible"); });
    } else {
        $$(".reveal").forEach(function (element) { observer.observe(element); });
    }

    const onMotionChange = function (event) {
        if (event.matches) $$(".reveal").forEach(function (section) { section.classList.add("visible"); });
    };
    if (systemReduced.addEventListener) systemReduced.addEventListener("change", onMotionChange);
    else if (systemReduced.addListener) systemReduced.addListener(onMotionChange);

    /* CONTADORES — funcionam também em navegadores sem IntersectionObserver. */
    function animateCounter(element) {
        if (element.dataset.counted === "true") return;
        element.dataset.counted = "true";
        const target = Number(element.dataset.count);
        const start = performance.now();
        const duration = 1100;
        function animate(now) {
            const progress = Math.min((now - start) / duration, 1);
            element.textContent = Math.floor(target * progress);
            if (progress < 1 && !systemReduced.matches) requestAnimationFrame(animate);
            else element.textContent = target;
        }
        if (systemReduced.matches) element.textContent = target;
        else requestAnimationFrame(animate);
    }
    if (hasIntersectionObserver) {
        const statObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                animateCounter(entry.target);
                statObserver.unobserve(entry.target);
            });
        });
        $$('[data-count]').forEach(function (element) { statObserver.observe(element); });
    } else {
        $$('[data-count]').forEach(animateCounter);
    }

    /* ======================================================
       2. GUIA DE ESTUDO
       ====================================================== */

    const guide = $("#natureGuide");
    const guideToggle = $("#guideToggle");

    if (guideToggle && guide) {
        let hidden = false;

        try {
            hidden = localStorage.getItem("ecoenem-guide-hidden") === "true";
        } catch (error) {}

        guide.classList.toggle("hidden", hidden);

        guideToggle.addEventListener("click", function () {
            hidden = !hidden;
            guide.classList.toggle("hidden", hidden);

            try {
                localStorage.setItem(
                    "ecoenem-guide-hidden",
                    String(hidden)
                );
            } catch (error) {}
        });
    }

    const guideMessages = [
        "Sabia que os decompositores devolvem nutrientes ao ambiente?",
        "O habitat é o endereço. O nicho é a profissão.",
        "A energia não retorna ao início da cadeia alimentar.",
        "As relações ecológicas conectam diferentes espécies.",
        "A biodiversidade ajuda a manter o funcionamento dos ecossistemas."
    ];

    let guideIndex = 0;

    if ($("#guideText")) {
        setInterval(function () {
            if (document.hidden || systemReduced.matches) return;

            guideIndex = (guideIndex + 1) % guideMessages.length;
            $("#guideText").textContent = guideMessages[guideIndex];
        }, 12000);
    }

    /* ======================================================
       3. NÍVEIS DE ORGANIZAÇÃO
       ====================================================== */

    const levels = {
        organismo: {
            number: "08",
            title: "Organismo",
            description: "Indivíduo pertencente a uma espécie, capaz de realizar as funções necessárias à vida. Pode ser unicelular ou pluricelular.",
            example: "Uma capivara vivendo nas margens de um rio.",
            study: "A Ecologia investiga como o ambiente afeta o indivíduo e como ele modifica o ambiente."
        },

        populacao: {
            number: "09",
            title: "População",
            description: "Conjunto de indivíduos da mesma espécie que ocupam determinada área durante um período.",
            example: "Capivaras que vivem em uma área do Pantanal.",
            study: "Natalidade, mortalidade, imigração, emigração, densidade populacional e competição."
        },

        comunidade: {
            number: "10",
            title: "Comunidade",
            description: "Conjunto de populações de espécies diferentes que coexistem e interagem em uma área. Também chamada de biocenose.",
            example: "Capivaras, onças, cervos-do-pantanal e tamanduás que compartilham uma região.",
            study: "A composição das espécies e as relações ecológicas entre elas."
        },

        ecossistema: {
            number: "11",
            title: "Ecossistema",
            description: "Sistema formado pela comunidade de seres vivos e pelos componentes abióticos do ambiente.",
            example: "Um lago, com peixes, algas, bactérias, água, luz e nutrientes.",
            study: "O fluxo de energia e a circulação de matéria entre os componentes vivos e não vivos."
        },

        bioma: {
            number: "12",
            title: "Bioma",
            description: "Conjunto regional de ecossistemas que compartilham características ambientais, climáticas e vegetacionais.",
            example: "Cerrado, Amazônia e Caatinga.",
            study: "A distribuição geográfica, a biodiversidade e os padrões ecológicos regionais."
        },

        biosfera: {
            number: "13",
            title: "Biosfera",
            description: "Conjunto de todos os ecossistemas e regiões do planeta onde existe vida.",
            example: "A totalidade dos ambientes terrestres, aquáticos e de transição que sustentam a vida.",
            study: "As relações ecológicas em escala planetária e as interações entre grandes sistemas naturais."
        }
    };

    $$(".level-button").forEach(function (button) {
        button.addEventListener("click", function () {
            const level = levels[button.dataset.level];

            if (!level) return;

            $$(".level-button").forEach(function (item) {
                item.classList.remove("active");
            });

            button.classList.add("active");

            const panel = $("#levelDetail");

            panel.innerHTML = `
                <span class="detail-number">${level.number}</span>
                <h3>${level.title}</h3>
                <p>${level.description}</p>
                <div class="detail-example">
                    <strong>Exemplo</strong>
                    <p>${level.example}</p>
                </div>
                <div class="detail-study">
                    <strong>O que a Ecologia estuda?</strong>
                    <p>${level.study}</p>
                </div>
            `;
        });
    });

    /* ======================================================
       4. ATIVIDADE: BIÓTICO OU ABIÓTICO
       ====================================================== */

    const bioticItems = $$("#bioticItems button");
    let selectedBiotic = null;
    let bioticScore = 0;
    let bioticAnswered = [];

    bioticItems.forEach(function (button) {
        button.addEventListener("click", function () {
            if (bioticAnswered.includes(button)) return;

            bioticItems.forEach(function (item) {
                item.classList.remove("selected");
            });

            selectedBiotic = button;
            button.classList.add("selected");
        });
    });

    $$("[data-classify]").forEach(function (button) {
        button.addEventListener("click", function () {
            if (!selectedBiotic) {
                $("#bioticFeedback").textContent =
                    "Selecione primeiro um elemento.";
                return;
            }

            if (bioticAnswered.includes(selectedBiotic)) return;

            const correct =
                selectedBiotic.dataset.answer === button.dataset.classify;

            bioticAnswered.push(selectedBiotic);

            if (correct) {
                bioticScore++;
                selectedBiotic.classList.add("correct");
                $("#bioticFeedback").textContent =
                    "Correto! Você identificou a classificação.";
            } else {
                selectedBiotic.classList.add("wrong");
                $("#bioticFeedback").textContent =
                    "Não foi dessa vez. Observe se o elemento possui vida.";
            }

            selectedBiotic.classList.remove("selected");
            selectedBiotic.disabled = true;
            selectedBiotic = null;

            $("#bioticScore").textContent = bioticScore;

            if (bioticAnswered.length === bioticItems.length) {
                $("#bioticFeedback").textContent =
                    "Atividade concluída! Sua pontuação foi " +
                    bioticScore + " de 8.";
            }
        });
    });

    /* ======================================================
       5. RELAÇÕES ECOLÓGICAS
       ====================================================== */

    const relationCards = $$(".relation-card");
    const relationFilters = $$(".filter-chip, .matrix-card");

    function filterRelations(filter) {
        relationCards.forEach(function (card) {
            const type = card.dataset.type;

            let visible = true;

            if (filter === "harmonicas") {
                visible = type.includes("harmonica");
            } else if (filter === "desarmonicas") {
                visible = type.includes("desarmonica");
            } else if (filter === "intra") {
                visible = type.startsWith("intra");
            } else if (filter === "inter") {
                visible = type.startsWith("inter");
            } else if (filter.includes("-")) {
                visible = type === filter;
            }

            card.hidden = !visible;

            if (visible) {
                card.style.animation = "none";
                void card.offsetWidth;
                card.style.animation = "";
            }
        });
    }

    relationFilters.forEach(function (button) {
        button.addEventListener("click", function () {
            const filter = button.dataset.relationFilter;

            $$(".filter-chip, .matrix-card").forEach(function (item) {
                item.classList.remove("active");
            });

            button.classList.add("active");

            if (button.classList.contains("matrix-card")) {
                $$(".filter-chip").forEach(function (item) {
                    if (item.dataset.relationFilter === filter) {
                        item.classList.add("active");
                    }
                });
            }

            filterRelations(filter);
        });
    });

    /* TABELA DE RELAÇÕES */

    const effects = {
        "Colônia": ["+", "+"],
        "Sociedade": ["+", "+"],
        "Canibalismo": ["+", "-"],
        "Competição intraespecífica": ["-", "-"],
        "Mutualismo": ["+", "+"],
        "Protocooperação": ["+", "+"],
        "Comensalismo": ["+", "0"],
        "Inquilinismo": ["+", "0"],
        "Predação": ["+", "-"],
        "Parasitismo": ["+", "-"],
        "Competição interespecífica": ["-", "-"],
        "Amensalismo": ["0", "-"],
        "Herbivoria": ["+", "-"]
    };

    const table = $("#relationTable");

    if (table) {
        relationCards.forEach(function (card) {
            const name = card.dataset.name;
            const type = card.dataset.type;
            const effect = effects[name];

            const row = document.createElement("tr");

            const category = type.startsWith("intra")
                ? "Intraespecífica"
                : "Interespecífica";

            row.innerHTML = `
                <td>${name}</td>
                <td>${category}</td>
                <td>${effect[0]}</td>
                <td>${effect[1]}</td>
            `;

            table.appendChild(row);
        });
    }

    /* JOGO DE RELAÇÕES */

    const relationQuestions = [
        {
            question: "Um fungo produz substâncias que inibem o crescimento de bactérias ao seu redor.",
            answer: "Amensalismo",
            explanation: "O fungo não sofre prejuízo relevante e as bactérias são prejudicadas."
        },
        {
            question: "Uma rêmora aproveita restos de alimento de um grande animal marinho.",
            answer: "Comensalismo",
            explanation: "A rêmora obtém alimento e o outro organismo não apresenta efeito significativo."
        },
        {
            question: "Uma serpente captura e consome um roedor.",
            answer: "Predação",
            explanation: "O predador captura e mata a presa para obter alimento."
        },
        {
            question: "Duas espécies de herbívoros disputam alimento escasso durante a seca.",
            answer: "Competição interespecífica",
            explanation: "Organismos de espécies diferentes disputam recursos limitados."
        },
        {
            question: "Formigas cooperam e dividem tarefas dentro de um formigueiro.",
            answer: "Sociedade",
            explanation: "São indivíduos da mesma espécie que cooperam sem união física."
        },
        {
            question: "Orquídeas utilizam troncos de árvores como suporte para crescer.",
            answer: "Inquilinismo",
            explanation: "A planta utiliza outra como suporte sem retirar nutrientes dela."
        },
        {
            question: "Uma fêmea de determinada espécie de louva-a-deus consome o macho.",
            answer: "Canibalismo",
            explanation: "Ocorre consumo de um indivíduo da mesma espécie."
        },
        {
            question: "Um piolho alimenta-se do sangue de uma pessoa.",
            answer: "Parasitismo",
            explanation: "O parasita obtém recursos e causa prejuízos ao hospedeiro."
        },
        {
            question: "Fungos e organismos fotossintetizantes formam líquens.",
            answer: "Mutualismo",
            explanation: "Os organismos envolvidos obtêm benefícios da associação."
        },
        {
            question: "Machos da mesma espécie disputam território e parceiros reprodutivos.",
            answer: "Competição intraespecífica",
            explanation: "A disputa acontece entre indivíduos da mesma espécie."
        }
    ];

    let relationOrder = [];
    let relationPosition = 0;
    let relationPoints = 0;
    let relationStreak = 0;
    let relationAnswered = false;

    const relationChoices = [
        "Mutualismo",
        "Predação",
        "Parasitismo",
        "Comensalismo",
        "Amensalismo",
        "Inquilinismo",
        "Sociedade",
        "Canibalismo",
        "Competição interespecífica",
        "Competição intraespecífica"
    ];

    function shuffle(array) {
        const copy = array.slice();

        for (let i = copy.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));

            [copy[i], copy[j]] = [copy[j], copy[i]];
        }

        return copy;
    }

    function startRelationGame() {
        relationOrder = shuffle(relationQuestions.map(function (_, index) {
            return index;
        }));

        relationPosition = 0;
        relationPoints = 0;
        relationStreak = 0;

        showRelationQuestion();
    }

    function showRelationQuestion() {
        if (relationPosition >= 10) {
            $("#relationQuestion").textContent =
                "Desafio concluído! Você acertou " +
                relationPoints + " de 10 situações.";

            $("#relationOptions").innerHTML = "";

            $("#relationFeedback").textContent =
                "Revise os cards e tente novamente para melhorar sua pontuação.";

            $("#nextRelation").textContent = "Refazer desafio";
            $("#nextRelation").hidden = false;
            return;
        }

        const question = relationQuestions[
            relationOrder[relationPosition]
        ];

        relationAnswered = false;

        $("#relationQuestion").textContent = question.question;
        $("#relationFeedback").textContent = "";
        $("#relationNumber").textContent = relationPosition + 1;

        $("#relationPoints").textContent = relationPoints;
        $("#relationStreak").textContent = relationStreak;

        $("#nextRelation").hidden = true;

        const options = shuffle([
            question.answer,
            ...shuffle(
                relationChoices.filter(function (item) {
                    return item !== question.answer;
                })
            ).slice(0, 3)
        ]);

        const container = $("#relationOptions");
        container.innerHTML = "";

        options.forEach(function (option) {
            const button = document.createElement("button");
            button.textContent = option;

            button.addEventListener("click", function () {
                if (relationAnswered) return;

                relationAnswered = true;

                const correct = option === question.answer;

                if (correct) {
                    relationPoints++;
                    relationStreak++;
                    $("#relationFeedback").textContent =
                        "Correto! " + question.explanation;
                    $("#relationFeedback").classList.add("success");
                } else {
                    relationStreak = 0;
                    $("#relationFeedback").textContent =
                        "A resposta correta é " + question.answer +
                        ". " + question.explanation;
                    $("#relationFeedback").classList.remove("success");
                }

                $$("#relationOptions button").forEach(function (item) {
                    item.disabled = true;

                    if (item.textContent === question.answer) {
                        item.classList.add("correct");
                    }

                    if (item === button && !correct) {
                        item.classList.add("wrong");
                    }
                });

                $("#relationPoints").textContent = relationPoints;
                $("#relationStreak").textContent = relationStreak;

                $("#nextRelation").textContent =
                    relationPosition === 9
                        ? "Ver resultado"
                        : "Próxima situação";

                $("#nextRelation").hidden = false;
            });

            container.appendChild(button);
        });
    }

    $("#nextRelation").addEventListener("click", function () {
        if (relationPosition >= 10) {
            startRelationGame();
        } else {
            relationPosition++;
            showRelationQuestion();
        }
    });

    startRelationGame();

    /* ======================================================
       6. PIRÂMIDES ECOLÓGICAS
       ====================================================== */

    const pyramidData = {
        energia: {
            widths: [25, 45, 70, 100],
            explanation: "A pirâmide de energia nunca é invertida, pois a energia disponível diminui ao longo dos níveis tróficos."
        },

        biomassa: {
            widths: [25, 45, 70, 100],
            explanation: "A pirâmide de biomassa pode ser invertida em alguns ecossistemas aquáticos, devido à rápida renovação do fitoplâncton."
        },

        numeros: {
            widths: [35, 70, 55, 100],
            explanation: "A pirâmide de números pode ser invertida, como em uma árvore que sustenta muitos insetos herbívoros."
        }
    };

    $$(".tab[data-pyramid]").forEach(function (button) {
        button.addEventListener("click", function () {
            $$(".tab[data-pyramid]").forEach(function (item) {
                item.classList.remove("active");
                item.setAttribute("aria-selected", "false");
            });

            button.classList.add("active");
            button.setAttribute("aria-selected", "true");

            const data = pyramidData[button.dataset.pyramid];

            $$(".pyramid-level").forEach(function (level, index) {
                level.style.width = data.widths[index] + "%";
            });

            $("#pyramidExplanation").textContent = data.explanation;
        });
    });

    /* ======================================================
       7. SIMULADOR DE ENERGIA
       ====================================================== */

    const energySlider = $("#initialEnergy");

    function calculateEnergy() {
        const initial = Number(energySlider.value);

        $("#energyValue").textContent =
            initial.toLocaleString("pt-BR");

        const levels = [
            "Produtores",
            "Consumidores primários",
            "Consumidores secundários",
            "Consumidores terciários"
        ];

        let energy = initial;

        $("#energyLevels").innerHTML = "";

        levels.forEach(function (name, index) {
            const item = document.createElement("div");
            item.className = "energy-item";

            item.textContent =
                name + ": " +
                energy.toLocaleString("pt-BR") + " kJ";

            item.style.width =
                (100 * energy / initial) + "%";

            $("#energyLevels").appendChild(item);

            energy *= .1;
        });
    }

    energySlider.addEventListener("input", calculateEnergy);
    calculateEnergy();

    /* ======================================================
       8. TEIA ALIMENTAR
       ====================================================== */

    const webRelations = {
        capim: {
            role: "Produtor",
            eatenBy: ["gafanhoto", "rato"],
            eats: []
        },

        gafanhoto: {
            role: "Consumidor primário",
            eatenBy: ["sapo"],
            eats: ["capim"]
        },

        rato: {
            role: "Consumidor primário",
            eatenBy: ["cobra", "gaviao"],
            eats: ["capim"]
        },

        sapo: {
            role: "Consumidor secundário",
            eatenBy: ["cobra", "gaviao"],
            eats: ["gafanhoto"]
        },

        cobra: {
            role: "Consumidor terciário",
            eatenBy: ["gaviao"],
            eats: ["rato", "sapo"]
        },

        gaviao: {
            role: "Predador de topo nesta teia simplificada",
            eatenBy: [],
            eats: ["rato", "sapo", "cobra"]
        }
    };

    const organismNames = {
        capim: "Capim",
        gafanhoto: "Gafanhoto",
        rato: "Rato",
        sapo: "Sapo",
        cobra: "Cobra",
        gaviao: "Gavião"
    };

    let predatorRemoved = false;

    $$("#foodWeb button").forEach(function (button) {
        button.addEventListener("click", function () {
            const id = button.dataset.organism;
            const data = webRelations[id];

            const eats = data.eats.map(function (item) {
                return organismNames[item];
            });

            const eatenBy = data.eatenBy.map(function (item) {
                return organismNames[item];
            });

            $("#foodWebInfo").textContent =
                organismNames[id] + " — " + data.role +
                ". Alimenta-se de: " +
                (eats.join(", ") || "não se aplica") +
                ". É consumido por: " +
                (eatenBy.join(", ") || "não indicado nesta teia.");

            $$("#foodWeb button").forEach(function (item) {
                item.classList.remove("selected");
            });

            button.classList.add("selected");
        });
    });

    $("#removePredator").addEventListener("click", function () {
        predatorRemoved = !predatorRemoved;

        $("#cascadeInfo").textContent = predatorRemoved
            ? "Simulação: a retirada do gavião pode reduzir a pressão de predação sobre cobras, sapos e ratos. Dependendo das condições do ambiente, as populações desses organismos podem aumentar e alterar outras relações alimentares."
            : "O predador de topo foi reintegrado à simulação. A predação volta a atuar como um dos fatores que influenciam as populações da teia.";

        $("#removePredator").textContent = predatorRemoved
            ? "Restaurar predador"
            : "Simular retirada do predador de topo";
    });

    /* ======================================================
       9. JOGO DA CADEIA ALIMENTAR
       ====================================================== */

    /*
       PARA TROCAR AS FOTOS:
       Altere somente os endereços deste objeto.

       As imagens são ilustrativas e devem ser substituídas
       por fotografias com licença adequada antes da publicação.
    */

    const imageConfig = {
        capim: "imgs/capim",
        gafanhoto: "imgs/gafanhoto",
        sapo: "imgs/sapo",
    };

    const gameStages = [
        {
            title: "Fase 1 — Campo",
            description: "Identifique os primeiros níveis tróficos de uma cadeia do Cerrado.",
            explanation: "O capim produz matéria orgânica pela fotossíntese. O gafanhoto alimenta-se do capim e o sapo pode predar o gafanhoto.",
            organisms: [
                { id: "capim", name: "Capim", level: "Produtor", image: "capim", fact: "Realiza fotossíntese e transforma energia luminosa em energia química." },
                { id: "gafanhoto", name: "Gafanhoto", level: "Consumidor primário", image: "gafanhoto", fact: "Muitas espécies consomem folhas e outras partes vegetais." },
                { id: "sapo", name: "Sapo", level: "Consumidor secundário", image: "sapo", fact: "Muitos sapos alimentam-se de insetos." }
            ]
        },

        {
            title: "Fase 2 — Ambiente aquático",
            description: "Monte uma cadeia alimentar de um ecossistema aquático.",
            explanation: "As algas produzem matéria orgânica. Pequenos peixes podem alimentar-se de organismos planctônicos e peixes maiores podem predá-los.",
            organisms: [
                { id: "alga", name: "Algas", level: "Produtor", image: "alga", fact: "As algas realizam fotossíntese e são fundamentais em ambientes aquáticos." },
                { id: "peixe", name: "Peixe pequeno", level: "Consumidor primário", image: "peixe", fact: "Alguns peixes pequenos alimentam-se de plâncton." },
                { id: "peixeGrande", name: "Peixe predador", level: "Consumidor secundário", image: "peixeGrande", fact: "Peixes predadores podem consumir peixes menores." }
            ]
        },

        {
            title: "Fase 3 — Floresta",
            description: "Organize uma cadeia alimentar de ambiente florestal.",
            explanation: "Folhas alimentam insetos herbívoros, que podem ser consumidos por sapos. Fungos e bactérias atuam na decomposição da matéria orgânica.",
            organisms: [
                { id: "folhas", name: "Folhas", level: "Produtor", image: "folhas", fact: "As folhas são estruturas importantes para a fotossíntese." },
                { id: "inseto", name: "Inseto herbívoro", level: "Consumidor primário", image: "inseto", fact: "Muitos insetos dependem de plantas para obter alimento." },
                { id: "sapo", name: "Sapo", level: "Consumidor secundário", image: "sapo", fact: "Pode consumir diversos invertebrados." },
                { id: "fungo", name: "Fungo", level: "Decompositor", image: "fungo", fact: "Fungos participam da decomposição e da ciclagem de nutrientes." }
            ]
        },

        {
            title: "Fase 4 — Pantanal",
            description: "Conheça as relações alimentares do ambiente pantaneiro.",
            explanation: "Plantas aquáticas podem ser consumidas por herbívoros como a capivara. A onça-pintada pode predar capivaras. Decompositores atuam sobre restos de todos os organismos.",
            organisms: [
                { id: "plantaAquatica", name: "Plantas aquáticas", level: "Produtor", image: "plantaAquatica", fact: "A vegetação aquática oferece alimento e abrigo." },
                { id: "capivara", name: "Capivara", level: "Consumidor primário", image: "capivara", fact: "É um roedor herbívoro de hábitos associados à água." },
                { id: "onca", name: "Onça-pintada", level: "Consumidor secundário", image: "onca", fact: "É um predador de topo em muitos ecossistemas brasileiros." },
                { id: "fungo", name: "Fungos e bactérias", level: "Decompositores", image: "fungo", fact: "Decompõem restos e devolvem nutrientes ao ambiente." }
            ]
        },

        {
            title: "Fase 5 — Caatinga",
            description: "Monte uma cadeia alimentar de ambiente semiárido.",
            explanation: "Plantas da Caatinga produzem matéria orgânica. Roedores herbívoros podem ser predados por serpentes. A decomposição completa a ciclagem de nutrientes.",
            organisms: [
                { id: "cacto", name: "Cacto", level: "Produtor", image: "cacto", fact: "Armazena água e apresenta adaptações às condições de seca." },
                { id: "roedor", name: "Roedor herbívoro", level: "Consumidor primário", image: "roedor", fact: "Pode consumir sementes e partes vegetais." },
                { id: "cobra", name: "Serpente", level: "Consumidor secundário", image: "cobra", fact: "Algumas serpentes predam pequenos mamíferos." },
                { id: "fungo", name: "Decompositores", level: "Decompositores", image: "fungo", fact: "Atuam na decomposição de restos orgânicos." }
            ]
        },

        {
            title: "Fase 6 — Desafio da floresta",
            description: "Complete a cadeia com cinco posições tróficas.",
            explanation: "Folhas são consumidas por roedores herbívoros, que podem ser predados por serpentes. Uma ave de rapina pode predar a serpente. Os decompositores atuam sobre todos os níveis.",
            organisms: [
                { id: "folhas", name: "Vegetação", level: "Produtor", image: "folhas", fact: "Produz matéria orgânica pela fotossíntese." },
                { id: "roedor", name: "Roedor", level: "Consumidor primário", image: "roedor", fact: "Alguns roedores consomem sementes e frutos." },
                { id: "cobra", name: "Serpente", level: "Consumidor secundário", image: "cobra", fact: "Pode predar pequenos mamíferos." },
                { id: "gaviao", name: "Ave de rapina", level: "Consumidor terciário", image: "gaviao", fact: "Algumas aves de rapina predam serpentes." },
                { id: "fungo", name: "Decompositores", level: "Decompositores", image: "fungo", fact: "Fungos e bactérias atuam sobre restos de todos os níveis tróficos." }
            ]
        }
    ];

    let gameStage = 0;
    let gameScore = 0;
    let gameErrors = 0;
    let selectedOrganism = null;
    let selectedSlot = null;
    let completedStages = [];
    let bestScore = 0;
    let gameAttempts = 0;

    try {
        const saved = JSON.parse(
            localStorage.getItem("ecoenem-chain-game") || "{}"
        );

        gameScore = Number(saved.score) || 0;
        bestScore = Number(saved.best) || 0;
        completedStages = Array.isArray(saved.completed)
            ? saved.completed
            : [];
    } catch (error) {
        gameScore = 0;
        bestScore = 0;
        completedStages = [];
    }

    function saveGame() {
        try {
            localStorage.setItem("ecoenem-chain-game", JSON.stringify({
                score: gameScore,
                best: bestScore,
                completed: completedStages
            }));
        } catch (error) {}
    }

    function currentStage() {
        return gameStages[gameStage];
    }

    function createImage(organism) {
        const img = document.createElement("img");

        img.src = imageConfig[organism.image] || imageConfig.capim;
        img.alt = "Fotografia ilustrativa de " + organism.name;
        img.loading = "lazy";
        img.width = 85;
        img.height = 85;

        img.addEventListener("error", function () {
            img.src =
                "data:image/svg+xml," +
                encodeURIComponent(`
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                        <defs>
                            <linearGradient id="g">
                                <stop stop-color="#2B4334"/>
                                <stop offset="1" stop-color="#496F56"/>
                            </linearGradient>
                        </defs>
                        <rect width="100" height="100" rx="50" fill="url(#g)"/>
                        <path d="M50 75V35M50 55C25 55 23 34 25 25C43 25 52 39 50 55ZM50 63C75 63 79 43 76 33C58 34 50 47 50 63Z"
                        fill="none" stroke="#B5C9BA" stroke-width="3"/>
                    </svg>
                `);
        });

        return img;
    }

    function createOrganismCard(organism, index) {
        const card = document.createElement("button");

        card.className = "organism-card";
        card.type = "button";
        card.draggable = true;
        card.dataset.organism = organism.id;
        card.dataset.index = index;

        const image = createImage(organism);
        const name = document.createElement("strong");
        const level = document.createElement("small");

        name.textContent = organism.name;
        level.textContent = $("#learningMode").checked
            ? organism.level
            : "Selecionar organismo";

        card.appendChild(image);
        card.appendChild(name);
        card.appendChild(level);

        if ($("#learningMode").checked) {
            card.title = organism.fact;
        }

        card.addEventListener("click", function () {
            if (card.classList.contains("used")) return;

            selectedOrganism = organism.id;

            $$(".organism-card").forEach(function (item) {
                item.classList.remove("selected");
            });

            card.classList.add("selected");

            $("#gameFeedback").textContent =
                $("#learningMode").checked
                    ? organism.fact + " Escolha um espaço para posicioná-lo."
                    : "Agora selecione o espaço correspondente.";
        });

        card.addEventListener("dragstart", function (event) {
            selectedOrganism = organism.id;
            card.classList.add("dragging");

            event.dataTransfer.setData("text/plain", organism.id);
        });

        card.addEventListener("dragend", function () {
            card.classList.remove("dragging");
        });

        return card;
    }

    function renderGame() {
        const stage = currentStage();

        $("#stageTitle").textContent = stage.title;
        $("#stageDescription").textContent = stage.description;

        $("#stageProgress").textContent =
            "Fase " + (gameStage + 1) + " de " + gameStages.length;

        $("#stageProgressBar").style.width =
            ((gameStage + 1) / gameStages.length * 100) + "%";

        $("#gameScore").textContent = gameScore;

        $("#gameRecord").textContent =
            "Melhor pontuação: " + bestScore +
            " | Erros nesta fase: " + gameErrors;

        $("#chainSlots").innerHTML = "";
        $("#organismTray").innerHTML = "";

        selectedOrganism = null;
        selectedSlot = null;

        stage.organisms.forEach(function (organism, index) {
            const slot = document.createElement("button");

            slot.className = "chain-slot";
            slot.type = "button";
            slot.dataset.organism = organism.id;
            slot.dataset.index = index;

            const label = document.createElement("strong");
            label.textContent = organism.level;

            const description = document.createElement("span");
            description.textContent = "Escolher posição";

            slot.appendChild(label);
            slot.appendChild(description);

            slot.addEventListener("click", function () {
                if (slot.classList.contains("filled")) return;

                selectedSlot = index;

                $$(".chain-slot").forEach(function (item) {
                    item.classList.remove("selected");
                });

                slot.classList.add("selected");

                if (selectedOrganism) {
                    placeOrganism(selectedOrganism, index);
                }
            });

            slot.addEventListener("dragover", function (event) {
                event.preventDefault();
                slot.classList.add("selected");
            });

            slot.addEventListener("dragleave", function () {
                slot.classList.remove("selected");
            });

            slot.addEventListener("drop", function (event) {
                event.preventDefault();

                const id = event.dataTransfer.getData("text/plain");

                placeOrganism(id, index);
            });

            $("#chainSlots").appendChild(slot);
        });

        const shuffled = shuffle(stage.organisms);

        shuffled.forEach(function (organism) {
            const originalIndex = stage.organisms.indexOf(organism);

            $("#organismTray").appendChild(
                createOrganismCard(organism, originalIndex)
            );
        });

        $("#gameFeedback").textContent =
            "Organize os organismos de acordo com seus níveis tróficos.";

        $("#gameExplanation").textContent = "";

        $("#gameNext").disabled = !completedStages.includes(gameStage);

        $("#gameStars").textContent = "";

        $("#gameRecord").textContent =
            "Melhor pontuação: " + bestScore +
            " | Erros nesta fase: " + gameErrors;
    }

    function placeOrganism(id, slotIndex) {
        const stage = currentStage();

        if (!id || slotIndex < 0 || slotIndex >= stage.organisms.length) {
            return;
        }

        const slot = $$(".chain-slot")[slotIndex];

        if (slot.classList.contains("filled")) return;

        const correctOrganism = stage.organisms[slotIndex];

        if (id === correctOrganism.id) {
            const organism = stage.organisms.find(function (item) {
                return item.id === id;
            });

            const card = $('.organism-card[data-organism="' + id + '"]');

            slot.classList.add("filled");
            slot.classList.remove("selected");

            slot.innerHTML = "";

            const image = createImage(organism);
            image.width = 60;
            image.height = 60;

            const name = document.createElement("strong");
            name.textContent = organism.name;

            slot.appendChild(image);
            slot.appendChild(name);

            card.classList.add("used");
            card.disabled = true;
            card.draggable = false;
            card.style.opacity = ".35";

            gameScore += 10;
            gameAttempts++;

            $("#gameScore").textContent = gameScore;

            $("#gameFeedback").textContent =
                "Correto! " + organism.name +
                " ocupa o nível " + organism.level + ".";

            selectedOrganism = null;

            const allFilled = $$(".chain-slot").every(function (item) {
                return item.classList.contains("filled");
            });

            if (allFilled) {
                completeStage();
            }
        } else {
            gameErrors++;
            gameAttempts++;

            $("#gameRecord").textContent =
                "Melhor pontuação: " + bestScore +
                " | Erros nesta fase: " + gameErrors;

            $("#gameFeedback").textContent =
                "Essa posição não corresponde ao organismo. Observe o nível trófico indicado.";

            slot.classList.add("wrong");

            if (!systemReduced.matches) {
                slot.animate([
                    { transform: "translateX(0)" },
                    { transform: "translateX(-5px)" },
                    { transform: "translateX(5px)" },
                    { transform: "translateX(0)" }
                ], { duration: 250 });
            }

            setTimeout(function () {
                slot.classList.remove("wrong");
            }, 300);
        }
    }

    function completeStage() {
        const stage = currentStage();

        completedStages.push(gameStage);

        completedStages = Array.from(new Set(completedStages));

        bestScore = Math.max(bestScore, gameScore);

        saveGame();

        $("#gameExplanation").textContent = stage.explanation;

        $("#gameFeedback").textContent =
            "Fase concluída! Você organizou todos os níveis corretamente.";

        $("#gameNext").disabled = false;

        $("#gameStars").textContent =
            gameErrors === 0 ? "★★★" :
            gameErrors <= 2 ? "★★" : "★";

        if (gameStage === gameStages.length - 1) {
            $("#gameNext").textContent = "Jogo concluído";
        }

        $("#gameRecord").textContent =
            "Melhor pontuação: " + bestScore +
            " | Tentativas totais: " + gameAttempts;
    }

    $("#gameReset").addEventListener("click", function () {
        gameErrors = 0;
        renderGame();
    });

    $("#gameHint").addEventListener("click", function () {
        const emptyIndex = $$(".chain-slot").findIndex(function (slot) {
            return !slot.classList.contains("filled");
        });

        if (emptyIndex < 0) return;

        const organism = currentStage().organisms[emptyIndex];

        $("#gameFeedback").textContent =
            "Dica: procure o organismo que exerce a função de " +
            organism.level + ".";
    });

    $("#gameNext").addEventListener("click", function () {
        if (!completedStages.includes(gameStage)) return;

        if (gameStage < gameStages.length - 1) {
            gameStage++;
            gameErrors = 0;
            renderGame();
        } else {
            $("#gameFeedback").textContent =
                "Parabéns! Você concluiu todas as seis fases.";
        }
    });

    $("#learningMode").addEventListener("change", renderGame);

    renderGame();

    /* ======================================================
       10. CICLOS BIOGEOQUÍMICOS
       ====================================================== */

    const cycles = {
        agua: {
            title: "Ciclo da água",
            description: "A água circula entre a superfície terrestre, os seres vivos e a atmosfera.",
            steps: [
                ["Evaporação", "A água passa do estado líquido para o gasoso, principalmente pela energia solar."],
                ["Transpiração", "Os vegetais liberam vapor de água para a atmosfera."],
                ["Condensação", "O vapor se resfria e forma pequenas gotículas nas nuvens."],
                ["Precipitação", "A água retorna à superfície na forma de chuva ou outras formas de precipitação."],
                ["Infiltração", "Parte da água penetra no solo e pode abastecer aquíferos."],
                ["Escoamento", "Outra parte percorre a superfície e chega aos rios e oceanos."]
            ]
        },

        carbono: {
            title: "Ciclo do carbono",
            description: "O carbono circula entre atmosfera, organismos, oceanos e reservatórios geológicos.",
            steps: [
                ["Fotossíntese", "Produtores absorvem dióxido de carbono e produzem matéria orgânica."],
                ["Alimentação", "O carbono passa entre os organismos pelas cadeias alimentares."],
                ["Respiração", "Os organismos liberam parte do carbono na forma de dióxido de carbono."],
                ["Decomposição", "A matéria orgânica é degradada e o carbono retorna ao ambiente."],
                ["Combustão", "A queima de biomassa e combustíveis fósseis libera carbono para a atmosfera."],
                ["Oceanos", "Os oceanos absorvem e liberam dióxido de carbono."]
            ]
        },

        nitrogenio: {
            title: "Ciclo do nitrogênio",
            description: "O nitrogênio atmosférico precisa ser transformado para ser utilizado por muitos organismos.",
            steps: [
                ["Fixação", "Bactérias transformam o nitrogênio atmosférico em compostos utilizáveis."],
                ["Nitrificação", "Bactérias oxidam amônia e formam nitrito e nitrato."],
                ["Assimilação", "Plantas absorvem compostos nitrogenados e produzem moléculas orgânicas."],
                ["Alimentação", "O nitrogênio passa entre os organismos pela cadeia alimentar."],
                ["Amonificação", "A decomposição transforma nitrogênio orgânico em amônia ou amônio."],
                ["Desnitrificação", "Bactérias devolvem nitrogênio gasoso à atmosfera."]
            ]
        },

        fosforo: {
            title: "Ciclo do fósforo",
            description: "O fósforo circula principalmente entre rochas, solo, água e organismos.",
            steps: [
                ["Intemperismo", "O desgaste das rochas libera fosfatos no ambiente."],
                ["Absorção", "As plantas absorvem íons fosfato pelas raízes."],
                ["Alimentação", "O fósforo passa para os animais por meio da alimentação."],
                ["Excreção", "Resíduos orgânicos devolvem compostos de fósforo ao ambiente."],
                ["Decomposição", "Microrganismos mineralizam a matéria orgânica."],
                ["Sedimentação", "Parte do fósforo é incorporada aos sedimentos."]
            ]
        },

        oxigenio: {
            title: "Ciclo do oxigênio",
            description: "O oxigênio circula por meio de processos biológicos, químicos e físicos.",
            steps: [
                ["Fotossíntese", "Organismos fotossintetizantes liberam oxigênio."],
                ["Respiração", "Os seres vivos utilizam oxigênio na respiração aeróbica."],
                ["Decomposição", "A degradação aeróbica da matéria orgânica consome oxigênio."],
                ["Combustão", "A queima de materiais consome oxigênio."],
                ["Oxidação", "O oxigênio participa de diversas reações químicas."],
                ["Atmosfera", "O gás circula entre a atmosfera e outros compartimentos ambientais."]
            ]
        }
    };

    function renderCycle(id) {
        const cycle = cycles[id];

        $("#cycleTitle").textContent = cycle.title;
        $("#cycleDescription").textContent = cycle.description;

        const container = $("#cycleSteps");
        container.innerHTML = "";

        cycle.steps.forEach(function (step, index) {
            const card = document.createElement("article");
            card.className = "cycle-step";

            const title = document.createElement("strong");
            title.textContent = (index + 1) + ". " + step[0];

            const text = document.createElement("p");
            text.textContent = step[1];

            card.appendChild(title);
            card.appendChild(text);

            container.appendChild(card);
        });
    }

    $$(".cycle-tabs [data-cycle]").forEach(function (button) {
        button.addEventListener("click", function () {
            $$(".cycle-tabs .tab").forEach(function (item) {
                item.classList.remove("active");
            });

            button.classList.add("active");
            renderCycle(button.dataset.cycle);
        });
    });

    renderCycle("agua");

    $("#playCycle").addEventListener("click", function () {
        const cards = $$(".cycle-step");
        const delay = systemReduced.matches ? 0 : 550;

        cards.forEach(function (card) {
            card.classList.remove("current");
        });

        cards.forEach(function (card, index) {
            setTimeout(function () {
                if (!document.hidden) {
                    card.classList.add("current");
                }
            }, index * delay);
        });
    });

    /* ======================================================
       11. SUCESSÃO ECOLÓGICA
       ====================================================== */

    const successionStages = [
        {
            title: "Rocha nua",
            description: "Área inicialmente sem solo desenvolvido e sem comunidade estabelecida; a colonização depende das condições locais."
        },
        {
            title: "Líquens",
            description: "Organismos pioneiros contribuem para o intemperismo e a formação do solo."
        },
        {
            title: "Musgos",
            description: "Organismos que ajudam a reter umidade e matéria orgânica."
        },
        {
            title: "Gramíneas",
            description: "Vegetação herbácea amplia a cobertura vegetal e a disponibilidade de recursos."
        },
        {
            title: "Arbustos",
            description: "Aumentam a estrutura da vegetação e oferecem novos habitats."
        },
        {
            title: "Árvores jovens",
            description: "O ambiente passa a apresentar maior estratificação vegetal."
        },
        {
            title: "Comunidade mais desenvolvida",
            description: "Comunidade com estrutura mais complexa, em equilíbrio dinâmico e sujeita a mudanças, conforme as condições locais."
        }
    ];

    let successionType = "primaria";

    function updateSuccession() {
        const value = Number($("#successionRange").value);
        const stage = successionStages[value];

        $("#successionTitle").textContent = stage.title;
        $("#successionDescription").textContent =
            successionType === "primaria"
                ? stage.description
                : value === 0
                    ? "A área já possuía uma comunidade e mantém o solo após uma perturbação."
                    : stage.description;

        const visuals = [
            "Superfície rochosa",
            "Líquens",
            "Musgos",
            "Gramíneas",
            "Arbustos",
            "Árvores jovens",
            "Vegetação madura"
        ];

        $("#sceneLife").textContent = visuals[value];

        $("#sceneGround").style.height =
            (40 + value * 9) + "px";

        $("#sceneLife").style.transform =
            "translateY(" + (-value * 5) + "px)";
    }

    $("#successionRange").addEventListener("input", updateSuccession);

    $$("[data-succession]").forEach(function (button) {
        button.addEventListener("click", function () {
            successionType = button.dataset.succession;

            $$("[data-succession]").forEach(function (item) {
                item.classList.remove("active");
            });

            button.classList.add("active");

            $("#successionRange").value = 0;
            updateSuccession();
        });
    });

    updateSuccession();

    /* ------------------------------------------------------
       GRÁFICOS DE LINHA COMPARTILHADOS
       O simulador de sucessão usa exatamente o mesmo desenho
       (eixos, rótulo, linha tracejada de referência e traço)
       e a mesma animação ponto a ponto do simulador de
       crescimento populacional.
       ------------------------------------------------------ */

    function desenharGraficoLinhas(canvas, opcoes) {
        const ctx = canvas.getContext("2d");
        if (!ctx) return;

        const width = Math.max(280, canvas.clientWidth);
        const height = 220;
        const dpr = window.devicePixelRatio || 1;

        canvas.width = width * dpr;
        canvas.height = height * dpr;

        ctx.scale(dpr, dpr);
        ctx.clearRect(0, 0, width, height);

        const padding = 25;
        const graphWidth = width - padding * 2;
        const graphHeight = height - padding * 2;
        const maximo = opcoes.maximo;
        const yDe = valor => height - padding - (valor / maximo) * graphHeight;

        // eixos
        ctx.strokeStyle = "rgba(255,255,255,.25)";
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.moveTo(padding, padding);
        ctx.lineTo(padding, height - padding);
        ctx.lineTo(width - padding, height - padding);
        ctx.stroke();

        // rótulo do eixo
        ctx.fillStyle = "#B5C9BA";
        ctx.font = "11px Inter";
        ctx.fillText(opcoes.rotulo, padding, 13);

        // linhas tracejadas de referência (capacidade de suporte / etapas)
        ctx.strokeStyle = "rgba(181,201,186,.65)";
        ctx.setLineDash([5, 4]);
        (opcoes.referenciasY || []).forEach(function (valor) {
            ctx.beginPath();
            ctx.moveTo(padding, yDe(valor));
            ctx.lineTo(width - padding, yDe(valor));
            ctx.stroke();
        });
        (opcoes.referenciasX || []).forEach(function (ref) {
            const x = padding + ref.fracao * graphWidth;
            ctx.beginPath();
            ctx.moveTo(x, padding);
            ctx.lineTo(x, height - padding);
            ctx.stroke();
            if (ref.texto) {
                ctx.fillText(ref.texto, Math.min(x + 4, width - padding - ctx.measureText(ref.texto).width), padding + 11);
            }
        });
        ctx.setLineDash([]);

        // rótulos das etapas no eixo horizontal
        (opcoes.rotulosX || []).forEach(function (rotulo) {
            const x = padding + rotulo.fracao * graphWidth - ctx.measureText(rotulo.texto).width / 2;
            ctx.fillText(rotulo.texto, Math.max(padding, Math.min(x, width - padding - ctx.measureText(rotulo.texto).width)), height - 8);
        });

        // séries
        opcoes.series.forEach(function (serie) {
            const valores = serie.valores;
            if (!valores.length) return;
            const total = opcoes.totalPontos || valores.length;

            ctx.strokeStyle = serie.cor;
            ctx.lineWidth = 2;
            ctx.beginPath();

            valores.forEach(function (value, index) {
                const x = padding + (index / Math.max(total - 1, 1)) * graphWidth;
                const y = yDe(value);
                if (index === 0) ctx.moveTo(x, y);
                else ctx.lineTo(x, y);
            });

            ctx.stroke();
        });
    }

    // Animação ponto a ponto com requestAnimationFrame (mesmo ritmo nos dois gráficos).
    function animarPontoAPonto(totalPassos, desenharPasso, aoTerminar) {
        let index = 0;
        let quadro = null;

        function passo() {
            if (index >= totalPassos) {
                quadro = null;
                if (aoTerminar) aoTerminar();
                return;
            }
            desenharPasso(index);
            index++;
            if (systemReduced.matches) passo();
            else quadro = requestAnimationFrame(passo);
        }

        passo();
        return function cancelar() { if (quadro) cancelAnimationFrame(quadro); };
    }

    /* ------------------------------------------------------
       SIMULADOR DO AVANÇO DA SUCESSÃO (valores relativos)
       ------------------------------------------------------ */

    const successionCanvas = $("#successionCanvas");
    const successionButton = $("#animateSuccessionChart");
    const successionExplanation = $("#successionExplanation");
    const PONTOS_SUCESSAO = 30;
    const MOMENTO_QUEIMADA = 0.56;
    const coresSucessao = { biomassa: "#E8F0EA", diversidade: "#9FD9B3", complexidade: "#4F9869" };

    const logistica = (t, k, meio) => 1 / (1 + Math.exp(-k * (t - meio)));

    // Curvas conceituais: biomassa e complexidade crescem de forma gradual;
    // a diversidade costuma ser maior nas fases intermediárias.
    function curvaTipica(t) {
        return {
            biomassa: 4 + 88 * logistica(t, 9, 0.5),
            diversidade: 4 + 70 * logistica(t, 10, 0.38) - 10 * logistica(t, 9, 0.82),
            complexidade: 3 + 85 * logistica(t, 8, 0.6)
        };
    }

    const trajetorias = {
        tipica: {
            calcular: curvaTipica,
            final: "Simulação concluída. Sem grandes distúrbios, a biomassa e a complexidade aumentam aos poucos. A diversidade costuma ser maior nas fases intermediárias e pode se estabilizar ou diminuir um pouco na comunidade mais desenvolvida."
        },
        disturbio: {
            calcular: function (t) {
                const normal = curvaTipica(t);
                if (t < MOMENTO_QUEIMADA) return normal;
                const antes = curvaTipica(MOMENTO_QUEIMADA);
                const restante = { biomassa: 0.2, diversidade: 0.45, complexidade: 0.25 };
                const recuperacao = 1 - Math.exp(-(t - MOMENTO_QUEIMADA) / 0.16);
                const resultado = {};
                Object.keys(normal).forEach(function (chave) {
                    const depois = antes[chave] * restante[chave];
                    resultado[chave] = depois + (normal[chave] - depois) * recuperacao;
                });
                return resultado;
            },
            final: "Simulação concluída. A queimada reduziu a biomassa, a diversidade e a complexidade. Como o solo permaneceu, começa uma sucessão secundária, que costuma ser mais rápida que a primária: o processo não é uma linha reta."
        },
        semiarido: {
            calcular: function (t) {
                return {
                    biomassa: 4 + 48 * logistica(t, 7, 0.55),
                    diversidade: 4 + 52 * logistica(t, 8, 0.45) - 5 * logistica(t, 9, 0.85),
                    complexidade: 3 + 45 * logistica(t, 7, 0.62)
                };
            },
            final: "Simulação concluída. Com pouca água e chuvas irregulares, a sucessão tende a ser mais lenta e a comunidade mais desenvolvida acumula menos biomassa — como a vegetação da Caatinga, adaptada à seca. As condições locais definem o resultado."
        }
    };

    let trajetoriaAtual = "tipica";
    let successionValues = null;
    let pontosVisiveis = 0;
    let cancelarSucessao = null;

    function gerarSerieSucessao(nome) {
        const series = { biomassa: [], diversidade: [], complexidade: [] };
        for (let i = 0; i < PONTOS_SUCESSAO; i++) {
            const ponto = trajetorias[nome].calcular(i / (PONTOS_SUCESSAO - 1));
            Object.keys(series).forEach(function (chave) { series[chave].push(ponto[chave]); });
        }
        return series;
    }

    function drawSuccessionChart() {
        if (!successionCanvas) return;
        const valores = successionValues || { biomassa: [], diversidade: [], complexidade: [] };
        const fracaoVisivel = (pontosVisiveis - 1) / (PONTOS_SUCESSAO - 1);
        const referenciasX = [{ fracao: 1 / 3 }, { fracao: 2 / 3 }];
        if (trajetoriaAtual === "disturbio" && fracaoVisivel >= MOMENTO_QUEIMADA) {
            referenciasX.push({ fracao: MOMENTO_QUEIMADA, texto: "queimada" });
        }
        desenharGraficoLinhas(successionCanvas, {
            rotulo: "Valor relativo",
            maximo: 100,
            totalPontos: PONTOS_SUCESSAO,
            referenciasX: referenciasX,
            // rótulos curtos em telas estreitas, para não se sobreporem
            rotulosX: (successionCanvas.clientWidth < 460
                ? ["Pioneiras", "Intermediária", "Desenvolvida"]
                : ["Pioneiras", "Intermediárias", "Mais desenvolvida"]
            ).map(function (texto, i) { return { fracao: (2 * i + 1) / 6, texto: texto }; }),
            series: ["biomassa", "diversidade", "complexidade"].map(function (chave) {
                return { cor: coresSucessao[chave], valores: valores[chave].slice(0, pontosVisiveis) };
            })
        });
    }

    function animateSuccessionChart() {
        if (!successionCanvas) return;
        if (cancelarSucessao) cancelarSucessao();
        successionValues = gerarSerieSucessao(trajetoriaAtual);
        pontosVisiveis = 0;
        if (successionExplanation) successionExplanation.textContent = "Observe a variação da comunidade ao longo da sucessão.";
        cancelarSucessao = animarPontoAPonto(PONTOS_SUCESSAO, function (index) {
            pontosVisiveis = index + 1;
            drawSuccessionChart();
        }, function () {
            if (successionExplanation) successionExplanation.textContent = trajetorias[trajetoriaAtual].final;
        });
    }

    if (successionCanvas) {
        drawSuccessionChart();
        $$("[data-path]").forEach(function (botao) {
            botao.addEventListener("click", function () {
                trajetoriaAtual = botao.dataset.path;
                $$("[data-path]").forEach(function (item) {
                    item.classList.toggle("active", item === botao);
                    item.setAttribute("aria-pressed", String(item === botao));
                });
                animateSuccessionChart();
            });
        });
        if (successionButton) successionButton.addEventListener("click", animateSuccessionChart);
        window.addEventListener("resize", drawSuccessionChart);
    }

    /* ======================================================
       12. DINÂMICA POPULACIONAL
       ====================================================== */

    const birthSlider = $("#birthRate");
    const deathSlider = $("#deathRate");
    const capacitySlider = $("#carryingCapacity");

    function drawPopulation() {
        const birth = Number(birthSlider.value);
        const death = Number(deathSlider.value);
        const capacity = Number(capacitySlider.value);

        $("#birthValue").textContent = birth;
        $("#deathValue").textContent = death;
        $("#capacityValue").textContent = capacity;
    }

    [birthSlider, deathSlider, capacitySlider].forEach(function (slider) {
        slider.addEventListener("input", drawPopulation);
    });

    drawPopulation();

    function drawChart(values, capacity) {
        desenharGraficoLinhas($("#populationCanvas"), {
            rotulo: "População",
            maximo: 200,
            referenciasY: [capacity],
            series: [{ cor: "#D0DDD3", valores: values }]
        });
    }

    let populationValues = [];

    $("#simulatePopulation").addEventListener("click", function () {
        const birth = Number(birthSlider.value);
        const death = Number(deathSlider.value);
        const capacity = Number(capacitySlider.value);

        populationValues = [20];

        for (let i = 1; i < 30; i++) {
            const previous = populationValues[i - 1];

            const growth = previous *
                ((birth - death) / 100) *
                (1 - previous / capacity);

            const next = Math.max(
                0,
                Math.min(capacity, previous + growth)
            );

            populationValues.push(next);
        }

        const steps = systemReduced.matches
            ? populationValues.length
            : populationValues.length;

        let index = 0;

        function animatePopulation() {
            if (index >= steps) {
                $("#populationExplanation").textContent =
                    "Simulação concluída. A população tende a se aproximar " +
                    "da capacidade de suporte, conforme os parâmetros escolhidos.";
                return;
            }

            drawChart(populationValues.slice(0, index + 1), capacity);
            index++;

            if (systemReduced.matches) {
                animatePopulation();
            } else {
                requestAnimationFrame(animatePopulation);
            }
        }

        $("#populationExplanation").textContent =
            "Observe a variação do tamanho populacional.";

        animatePopulation();
    });

    window.addEventListener("resize", function () {
        if (populationValues.length) {
            drawChart(
                populationValues,
                Number(capacitySlider.value)
            );
        }
    });

    /* ======================================================
       13. RAMOS DA ECOLOGIA
       ====================================================== */

    $$(".branch-node").forEach(function (button) {
        const panel = $("#branch-" + button.dataset.branch);

        button.setAttribute("aria-expanded", "false");

        button.addEventListener("click", function () {
            const isOpen = panel.classList.toggle("open");

            button.setAttribute("aria-expanded", String(isOpen));
        });
    });

    /* ======================================================
       14. BIOMAS BRASILEIROS
       ====================================================== */

    const biomes = {
        amazonia: {
            title: "Amazônia",
            location: "Predomina na Região Norte e ocupa partes de outros estados.",
            climate: "Predominantemente equatorial, quente e úmido, com elevada precipitação em grande parte da região.",
            vegetation: "Florestas densas, com diferentes formações vegetais e grande diversidade de árvores.",
            fauna: "Onça-pintada, boto, harpia, macacos e inúmeros peixes.",
            threats: "Desmatamento, queimadas, garimpo ilegal, fragmentação de habitats e alterações climáticas.",
            curiosity: "A Amazônia apresenta uma das maiores diversidades biológicas do planeta."
        },

        mata: {
            title: "Mata Atlântica",
            location: "Estende-se por grande parte da faixa litorânea brasileira e áreas do interior.",
            climate: "Predominam condições tropicais úmidas, com variações regionais e subtropicais no Sul.",
            vegetation: "Florestas com diferentes formações, restingas e manguezais associados à região.",
            fauna: "Mico-leão-dourado, muriqui, tucanos e diversas espécies de anfíbios.",
            threats: "Urbanização, desmatamento, fragmentação florestal e poluição.",
            curiosity: "É um dos biomas brasileiros com maior número de espécies endêmicas."
        },

        cerrado: {
            title: "Cerrado",
            location: "Predomina no Brasil Central e alcança áreas de diferentes regiões.",
            climate: "Tropical sazonal, com estação chuvosa e estação seca bem definidas.",
            vegetation: "Campos, savanas e formações florestais, com árvores frequentemente tortuosas e raízes profundas.",
            fauna: "Lobo-guará, tamanduá-bandeira, tatu-canastra e ema.",
            threats: "Conversão da vegetação nativa, queimadas inadequadas, expansão agropecuária e fragmentação.",
            curiosity: "É considerado uma das grandes savanas tropicais do mundo e abriga importantes nascentes."
        },

        caatinga: {
            title: "Caatinga",
            location: "Predomina no interior da Região Nordeste e alcança uma pequena área de Minas Gerais.",
            climate: "Semiárido, com chuvas irregulares e períodos prolongados de estiagem.",
            vegetation: "Plantas xerófitas, cactáceas e espécies que perdem as folhas durante a seca.",
            fauna: "Asa-branca, tatu-peba, mocó, preá e diversos répteis.",
            threats: "Desmatamento, degradação do solo, exploração inadequada de recursos e desertificação.",
            curiosity: "É um bioma exclusivamente brasileiro."
        },

        pampa: {
            title: "Pampa",
            location: "Ocorre no Rio Grande do Sul, na Região Sul do Brasil.",
            climate: "Subtropical, com estações relativamente definidas.",
            vegetation: "Campos com predomínio de gramíneas e outras plantas herbáceas.",
            fauna: "Veado-campeiro, graxaim-do-campo, quero-quero e espécies de aves campestres.",
            threats: "Conversão dos campos nativos, monoculturas, silvicultura e espécies exóticas invasoras.",
            curiosity: "Sua paisagem é caracterizada principalmente por campos naturais."
        },

        pantanal: {
            title: "Pantanal",
            location: "Abrange partes de Mato Grosso e Mato Grosso do Sul.",
            climate: "Tropical, com períodos de cheia e seca que influenciam o funcionamento ecológico.",
            vegetation: "Mosaico de campos, áreas alagáveis e formações florestais.",
            fauna: "Onça-pintada, arara-azul, tuiuiú, capivara e jacaré.",
            threats: "Incêndios, alterações hidrológicas, assoreamento e atividades econômicas inadequadas.",
            curiosity: "O pulso de inundação é um dos principais fatores que organizam a vida no Pantanal."
        }
    };

    $$(".biome-card").forEach(function (button) {
        button.addEventListener("click", function () {
            const biome = biomes[button.dataset.biome];

            $$(".biome-card").forEach(function (item) {
                item.classList.remove("active");
            });

            button.classList.add("active");

            const panel = $("#biomeDetail");

            panel.innerHTML = `
                <span class="eyebrow">BIOMA BRASILEIRO</span>
                <h3>${biome.title}</h3>
                <p><strong>Localização:</strong> ${biome.location}</p>
                <p><strong>Clima:</strong> ${biome.climate}</p>
                <p><strong>Vegetação:</strong> ${biome.vegetation}</p>
                <p><strong>Fauna:</strong> ${biome.fauna}</p>
                <p><strong>Ameaças:</strong> ${biome.threats}</p>
                <p><strong>Curiosidade:</strong> ${biome.curiosity}</p>
            `;
        });
    });

    /* QUIZ DOS BIOMAS */

    const biomeQuestions = [
        {
            question: "Qual bioma apresenta vegetação adaptada ao clima semiárido?",
            options: ["Amazônia", "Caatinga", "Pampa", "Mata Atlântica"],
            answer: "Caatinga"
        },
        {
            question: "Qual bioma apresenta um importante pulso de inundação?",
            options: ["Cerrado", "Pampa", "Pantanal", "Caatinga"],
            answer: "Pantanal"
        },
        {
            question: "Qual bioma brasileiro é caracterizado por campos naturais no Sul?",
            options: ["Pampa", "Amazônia", "Cerrado", "Pantanal"],
            answer: "Pampa"
        }
    ];

    let biomeQuestionIndex = 0;

    function showBiomeQuestion() {
        if (biomeQuestionIndex >= biomeQuestions.length) {
            biomeQuestionIndex = 0;
        }

        const question = biomeQuestions[biomeQuestionIndex];

        $("#biomeQuestion").textContent = question.question;
        $("#biomeFeedback").textContent = "";
        $("#biomeOptions").innerHTML = "";

        shuffle(question.options).forEach(function (option) {
            const button = document.createElement("button");
            button.textContent = option;

            button.addEventListener("click", function () {
                $$("#biomeOptions button").forEach(function (item) {
                    item.disabled = true;
                });

                const correct = option === question.answer;

                button.classList.add(correct ? "correct" : "wrong");

                $("#biomeFeedback").textContent = correct
                    ? "Correto! Você identificou o bioma."
                    : "Resposta correta: " + question.answer + ".";

                biomeQuestionIndex++;

                const next = document.createElement("button");
                next.className = "glass-button";
                next.textContent = "Próxima pista";

                next.addEventListener("click", showBiomeQuestion);

                $("#biomeOptions").appendChild(next);
            });

            $("#biomeOptions").appendChild(button);
        });
    }

    showBiomeQuestion();

    /* ======================================================
       15. CHECKLIST AMBIENTAL
       ====================================================== */

    const checks = $$(".action-checklist input");

    function updateActions() {
        const checked = checks.filter(function (item) {
            return item.checked;
        }).length;

        $("#actionProgress").textContent =
            checked + " de 6 atitudes realizadas.";

        const stem = $("#plantStem");

        stem.style.height = (20 + checked * 12) + "px";

        stem.classList.toggle("growing", checked > 0);
    }

    checks.forEach(function (checkbox) {
        checkbox.addEventListener("change", updateActions);
    });

    updateActions();

    /* ======================================================
       16. QUIZ FINAL
       ====================================================== */

    const questions = [
        {
            question: "A descrição de um pequeno tamanduá solitário, noturno, que utiliza cipós para repousar e apresenta hábitos específicos de alimentação e reprodução representa seu:",
            options: [
                "Habitat",
                "Biótopo",
                "Nível trófico",
                "Nicho ecológico",
                "Potencial biótico"
            ],
            answer: 3,
            explanation: "O nicho ecológico reúne o modo de vida, os hábitos e as funções desempenhadas por uma espécie."
        },

        {
            question: "Qual elemento está presente nos ciclos da água, carbono, enxofre, fósforo, nitrogênio e oxigênio?",
            options: [
                "Fósforo",
                "Enxofre",
                "Carbono",
                "Oxigênio",
                "Nitrogênio"
            ],
            answer: 3,
            explanation: "O oxigênio participa de todos esses ciclos por meio de processos biológicos e químicos."
        },

        {
            question: "A utilização da bactéria Wolbachia para reduzir a capacidade de transmissão de vírus pelo Aedes aegypti é um exemplo de:",
            options: [
                "Clonagem",
                "Mutualismo",
                "Parasitismo",
                "Transgênese",
                "Controle biológico"
            ],
            answer: 4,
            explanation: "A estratégia utiliza um agente biológico para interferir na capacidade de transmissão de patógenos pelo mosquito."
        },

        {
            question: "Em um ecossistema aquático contaminado por mercúrio, qual tendência pode ocorrer?",
            options: [
                "Maior concentração nos produtores do que em todos os consumidores.",
                "Concentrações iguais em todos os níveis.",
                "Menor concentração nos consumidores de maior ordem.",
                "Maior concentração nos consumidores primários.",
                "Aumento da concentração nos níveis tróficos mais elevados."
            ],
            answer: 4,
            explanation: "A magnificação trófica pode aumentar a concentração de contaminantes persistentes nos níveis mais elevados."
        },

        {
            question: "Sobre a pirâmide ecológica de energia, assinale a afirmação correta.",
            options: [
                "A energia aumenta a cada nível.",
                "Nunca pode ser invertida.",
                "Representa o número total de indivíduos.",
                "É formada apenas por consumidores.",
                "Sua base corresponde aos consumidores primários."
            ],
            answer: 1,
            explanation: "A energia diminui ao longo dos níveis tróficos, por isso a pirâmide de energia não é invertida."
        },

        {
            question: "Solo, água, temperatura e umidade são classificados como:",
            options: [
                "Fatores abióticos",
                "Fatores bióticos",
                "Biosfera",
                "Nicho ecológico",
                "Comunidades"
            ],
            answer: 0,
            explanation: "São componentes não vivos que influenciam os organismos e os ecossistemas."
        },

        {
            question: "Espécies que apresentam nicho ecológico amplo são chamadas de:",
            options: [
                "Especialistas",
                "Consumistas",
                "Amplas",
                "Generalistas",
                "Competitivas"
            ],
            answer: 3,
            explanation: "Espécies generalistas utilizam uma variedade maior de recursos e toleram diferentes condições."
        },

        {
            question: "A introdução de um peixe predador em um reservatório para reduzir a população de piranhas é uma estratégia de:",
            options: [
                "Aumento da natalidade",
                "Competição intraespecífica",
                "Variação do habitat",
                "Alteração cíclica",
                "Controle biológico por predatismo"
            ],
            answer: 4,
            explanation: "A estratégia utiliza um predador para interferir na população de outra espécie. Seus impactos ecológicos também precisam ser avaliados."
        },

        {
            question: "Uma comunidade de líquens começa a ocupar uma superfície rochosa sem solo desenvolvido. Esse processo representa:",
            options: [
                "Sucessão secundária",
                "Sucessão primária",
                "Competição intraespecífica",
                "Magnificação trófica",
                "Bioacumulação"
            ],
            answer: 1,
            explanation: "A sucessão primária pode começar em superfícies sem solo desenvolvido, com a participação de organismos pioneiros."
        },

        {
            question: "Quando uma orquídea cresce sobre o tronco de uma árvore sem retirar nutrientes dela, ocorre:",
            options: [
                "Predação",
                "Parasitismo",
                "Inquilinismo",
                "Canibalismo",
                "Competição"
            ],
            answer: 2,
            explanation: "A orquídea utiliza a árvore como suporte, sem causar prejuízo significativo."
        },

        {
            question: "Em uma população, o número de indivíduos aumenta devido à entrada de organismos vindos de outra região. Esse processo é chamado de:",
            options: [
                "Emigração",
                "Mortalidade",
                "Imigração",
                "Predação",
                "Desnitrificação"
            ],
            answer: 2,
            explanation: "Imigração corresponde à entrada de indivíduos em uma população."
        },

        {
            question: "Em uma cadeia alimentar, os decompositores são importantes porque:",
            options: [
                "Produzem toda a energia do ecossistema.",
                "Impedem a respiração dos consumidores.",
                "Transformam matéria orgânica e contribuem para a ciclagem de nutrientes.",
                "Eliminam todos os produtores.",
                "Fazem a energia retornar integralmente ao Sol."
            ],
            answer: 2,
            explanation: "Fungos e bactérias decompõem restos orgânicos e devolvem nutrientes ao ambiente."
        },

        {
            question: "Uma espécie de inseto e uma espécie de planta são beneficiadas pela interação entre ambas. A relação pode ser classificada como:",
            options: [
                "Mutualismo ou protocooperação",
                "Canibalismo",
                "Amensalismo",
                "Competição",
                "Predação"
            ],
            answer: 0,
            explanation: "Quando ambas se beneficiam, a interação é harmônica. A obrigatoriedade da associação ajuda a distinguir mutualismo e protocooperação."
        },

        {
            question: "A retirada de um predador de topo pode provocar:",
            options: [
                "Ausência de mudanças no ecossistema.",
                "Aumento obrigatório de todos os produtores.",
                "Alterações em cascata nas populações da teia alimentar.",
                "Fim imediato da decomposição.",
                "Interrupção de todos os ciclos biogeoquímicos."
            ],
            answer: 2,
            explanation: "A retirada de um predador pode alterar as populações de presas e produzir efeitos indiretos em outros níveis tróficos."
        }
    ];

    let quizIndex = 0;
    let quizCorrect = 0;
    let quizAnswered = false;

    function showQuizQuestion() {
        if (quizIndex >= questions.length) {
            showQuizResult();
            return;
        }

        const question = questions[quizIndex];

        quizAnswered = false;

        $("#quizCounter").textContent =
            "Questão " + (quizIndex + 1) +
            " de " + questions.length;

        $("#quizScore").textContent =
            "Acertos: " + quizCorrect;

        $("#quizProgress").style.width =
            (quizIndex / questions.length * 100) + "%";

        $("#quizQuestion").textContent = question.question;

        $("#quizFeedback").textContent = "";

        $("#quizNext").disabled = true;
        $("#quizNext").textContent = "Próxima questão";

        $("#quizAnswers").innerHTML = "";

        question.options.forEach(function (option, index) {
            const button = document.createElement("button");

            button.textContent =
                String.fromCharCode(65 + index) + ") " + option;

            button.addEventListener("click", function () {
                if (quizAnswered) return;

                quizAnswered = true;

                const correct = index === question.answer;

                if (correct) {
                    quizCorrect++;
                }

                $$("#quizAnswers button").forEach(function (item, i) {
                    item.disabled = true;

                    if (i === question.answer) {
                        item.classList.add("correct");
                    }

                    if (i === index && !correct) {
                        item.classList.add("wrong");
                    }
                });

                $("#quizScore").textContent =
                    "Acertos: " + quizCorrect;

                $("#quizFeedback").textContent =
                    (correct ? "Correto! " : "Não foi dessa vez. ") +
                    question.explanation;

                $("#quizFeedback").classList.toggle("success", correct);

                $("#quizNext").disabled = false;

                if (quizIndex === questions.length - 1) {
                    $("#quizNext").textContent = "Ver resultado";
                }
            });

            $("#quizAnswers").appendChild(button);
        });
    }

    function showQuizResult() {
        $("#quizProgress").style.width = "100%";

        $("#quizQuestion").textContent = "Quiz concluído!";

        $("#quizAnswers").innerHTML = "";
        $("#quizFeedback").textContent = "";

        $("#quizNext").hidden = true;

        const percentage = Math.round(
            quizCorrect / questions.length * 100
        );

        let message;

        if (percentage >= 90) {
            message = "Excelente! Você demonstrou um ótimo domínio da Ecologia.";
        } else if (percentage >= 70) {
            message = "Muito bem! Você compreendeu boa parte dos conteúdos.";
        } else if (percentage >= 50) {
            message = "Bom começo! Revise os assuntos em que teve dificuldade.";
        } else {
            message = "Continue estudando! Retorne às seções e tente novamente.";
        }

        const result = $("#quizResult");

        result.hidden = false;

        result.innerHTML = `
            <span class="eyebrow">SEU RESULTADO</span>
            <h3>${quizCorrect} de ${questions.length} acertos</h3>
            <h2>${percentage}%</h2>
            <p>${message}</p>
            <button class="glass-button" id="restartQuiz">Refazer quiz</button>
        `;

        $("#restartQuiz").addEventListener("click", function () {
            quizIndex = 0;
            quizCorrect = 0;
            quizAnswered = false;

            $("#quizNext").hidden = false;
            result.hidden = true;

            showQuizQuestion();
        });
    }

    $("#quizNext").addEventListener("click", function () {
        if (!quizAnswered) return;

        quizIndex++;
        showQuizQuestion();
    });

    showQuizQuestion();

    /* ======================================================
       17. CITAÇÃO ABNT
       ====================================================== */

    $("#copyCitation").addEventListener("click", async function () {
        const citation =
            "ECOENEM. Ecologia: material didático sobre Ecologia. 2026. Disponível em: página de conteúdo do EcoENEM. Acesso em: 19 nov. 2026.";

        try {
            await navigator.clipboard.writeText(citation);

            $("#citationFeedback").textContent =
                "Citação copiada com sucesso!";
        } catch (error) {
            $("#citationFeedback").textContent =
                "Não foi possível copiar automaticamente. " +
                "Verifique as permissões do navegador.";
        }
    });

}

// Garante a inicialização mesmo quando o arquivo é carregado tardiamente.
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", iniciarInteracoesConteudo, { once: true });
} else {
    iniciarInteracoesConteudo();
}
