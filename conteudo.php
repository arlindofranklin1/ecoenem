<?php require_once __DIR__ . "/includes/auth_guard.php"; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Explore a Ecologia, as relações ecológicas, os biomas brasileiros e prepare-se para o ENEM.">
    <title>Ecologia | EcoENEM</title>
    <link rel="shortcut icon" href="imgs/ecoenemicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/conteudo.css">
<link rel="stylesheet" href="css/auth-ui.css">
</head>

<body>

<header>
    <div class="logo">
        <a href="index.php">EcoENEM</a>
    </div>
    <nav aria-label="Navegação principal">
        <a href="ecoia.php">EcoIA</a>
        <a href="conteudo.php" aria-current="page">Conteúdo</a>
        <a href="questoes.php">Questões</a>
        <a href="simulado.php">Simuladão</a>
        <a href="sobre.php">Sobre</a>
    </nav>
</header>

<main>

<!-- ÍNDICE INTERNO -->
<!-- 1. HERO -->
<section class="titulo reveal" id="inicio">
    <div class="hero-decoration" aria-hidden="true">
        <svg viewBox="0 0 120 120">
            <path d="M95 15C40 18 12 48 20 88c35 5 68-24 75-73Z"/>
            <path d="M20 88 80 30"/>
            <path d="M48 61 42 39M61 49 78 52"/>
        </svg>
    </div>

    <span class="eyebrow">BIOLOGIA • CIÊNCIAS DA NATUREZA</span>
    <h1>Ecologia</h1>

    <p>
        A natureza funciona como uma grande rede de conexões.
        Cada organismo depende de outros seres vivos e das condições
        ambientais para sobreviver. Descubra como essas relações
        mantêm o equilíbrio dos ecossistemas.
    </p>

    <div class="hero-extra">
        <div class="hero-info glass">
            <span>O que significa Ecologia?</span>
            <h3>O estudo da nossa casa.</h3>
            <p>
                A palavra vem do grego <i>oikos</i> (casa) e
                <i>logos</i> (estudo). O termo foi criado por
                Ernst Haeckel na segunda metade da década de 1860.
            </p>
        </div>

        <div class="hero-stats">
            <div class="stat glass">
                <strong data-count="12">0</strong>
                <span>Níveis de organização biológica</span>
            </div>
            <div class="stat glass">
                <strong data-count="6">0</strong>
                <span>Biomas brasileiros</span>
            </div>
            <div class="stat glass">
                <strong data-count="15">0</strong>
                <span>Etapas de aprendizagem</span>
            </div>
        </div>
    </div>

    <a href="#organizacao" class="explore-button">
        Começar a explorar
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="m5 9 7 7 7-7"/>
        </svg>
    </a>
</section>

<!-- 2. ORGANIZAÇÃO -->
<section class="content-section reveal" id="organizacao">
    <div class="section-heading">
        <span class="eyebrow">01 • ESTRUTURA DA VIDA</span>
        <h2>Níveis de organização biológica</h2>
        <p>Da menor estrutura biológica à totalidade dos ambientes do planeta.</p>
    </div>

    <div class="organization-layout glass">
        <div class="organization-ladder">
            <p class="small-label">SELECIONE UM NÍVEL</p>

            <div class="level-list" role="group" aria-label="Níveis de organização">
                <button data-level="organismo" class="level-button active">08 · Organismo</button>
                <button data-level="populacao" class="level-button">09 · População</button>
                <button data-level="comunidade" class="level-button">10 · Comunidade</button>
                <button data-level="ecossistema" class="level-button">11 · Ecossistema</button>
                <button data-level="bioma" class="level-button">12 · Bioma</button>
                <button data-level="biosfera" class="level-button">13 · Biosfera</button>
            </div>

            <p class="level-note">
                A Ecologia estuda principalmente os níveis que vão
                do organismo à biosfera.
            </p>
        </div>

        <div class="level-detail" id="levelDetail" aria-live="polite">
            <span class="detail-number">08</span>
            <h3>Organismo</h3>
            <p>Indivíduo pertencente a uma espécie, capaz de realizar as funções necessárias à vida.</p>
            <div class="detail-example">
                <strong>Exemplo</strong>
                <p>Uma capivara vivendo nas margens de um rio.</p>
            </div>
            <div class="detail-study">
                <strong>O que a Ecologia estuda?</strong>
                <p>Como as condições ambientais afetam esse indivíduo e como ele interfere no ambiente.</p>
            </div>
        </div>
    </div>

    <div class="hierarchy glass">
        <h3>Hierarquia completa</h3>
        <div class="hierarchy-flow">
            <span>Átomo</span><i>›</i>
            <span>Molécula</span><i>›</i>
            <span>Organela</span><i>›</i>
            <span>Célula</span><i>›</i>
            <span>Tecido</span><i>›</i>
            <span>Órgão</span><i>›</i>
            <span>Sistema</span><i>›</i>
            <span>Organismo</span><i>›</i>
            <span>População</span><i>›</i>
            <span>Comunidade</span><i>›</i>
            <span>Ecossistema</span><i>›</i>
            <span>Biosfera</span>
        </div>
    </div>

    <div class="warning glass">
        <strong>Cuidado com a confusão!</strong>
        <p>
            <b>Biocenose</b> é o conjunto de populações que forma
            uma comunidade biológica. Já o <b>biótopo</b> corresponde
            ao espaço físico e às condições ambientais onde essa
            comunidade vive. Os dois conceitos não são sinônimos.
        </p>
    </div>

    <div class="info-grid">
        <article class="info-card glass">
            <h3>Espécie</h3>
            <p>
                Em uma definição biológica tradicional, reúne organismos
                capazes de cruzar entre si e produzir descendentes férteis.
                Essa definição possui limitações para organismos assexuados
                e fósseis.
            </p>
        </article>
        <article class="info-card glass">
            <h3>Escalas ecológicas</h3>
            <p>
                Uma comunidade pode existir em uma pequena gota de água
                ou ocupar uma extensa região. A escala depende do objeto
                de estudo.
            </p>
        </article>
    </div>
</section>

<!-- 3. CONCEITOS -->
<section class="content-section reveal" id="conceitos">
    <div class="section-heading">
        <span class="eyebrow">02 • FUNDAMENTOS</span>
        <h2>Conceitos fundamentais</h2>
        <p>Conheça os termos essenciais para compreender os ecossistemas.</p>
    </div>

    <div class="comparison-grid">
        <article class="comparison glass">
            <span class="concept-icon">01</span>
            <h3>Habitat</h3>
            <p>É o endereço de uma espécie: o lugar onde ela encontra condições para viver.</p>
            <div class="example">
                <b>Exemplo</b>
                <p>O habitat do leão é associado às savanas africanas.</p>
            </div>
        </article>

        <article class="comparison glass">
            <span class="concept-icon">02</span>
            <h3>Nicho ecológico</h3>
            <p>É a profissão da espécie: seu modo de vida, alimentação, reprodução e função ecológica.</p>
            <div class="example">
                <b>Exemplo</b>
                <p>O leão atua como predador e participa do controle de populações de herbívoros.</p>
            </div>
        </article>
    </div>

    <div class="warning glass">
        <strong>Habitat x Nicho ecológico</strong>
        <p>Habitat representa onde a espécie vive. Nicho ecológico representa como ela vive e interage com o ambiente.</p>
    </div>

    <div class="concept-grid">
        <article class="info-card glass">
            <h3>Biodiversidade</h3>
            <p>Variedade de genes, espécies e ecossistemas existentes em determinada região ou no planeta.</p>
        </article>
        <article class="info-card glass">
            <h3>Bioma</h3>
            <p>Grande conjunto de ecossistemas com características ambientais, climáticas e vegetacionais semelhantes.</p>
        </article>
        <article class="info-card glass">
            <h3>Biótopo</h3>
            <p>Espaço físico que apresenta as condições necessárias à existência de uma comunidade.</p>
        </article>
        <article class="info-card glass">
            <h3>Biosfera</h3>
            <p>Conjunto das regiões da Terra onde existe vida.</p>
        </article>
        <article class="info-card glass">
            <h3>Seres autotróficos</h3>
            <p>Produzem matéria orgânica a partir de substâncias inorgânicas, por fotossíntese ou quimiossíntese.</p>
        </article>
        <article class="info-card glass">
            <h3>Seres heterotróficos</h3>
            <p>Obtêm matéria orgânica consumindo outros organismos ou seus restos.</p>
        </article>
    </div>

    <div class="concept-grid">
        <article class="info-card glass">
            <h3>Fatores bióticos</h3>
            <p>Componentes vivos do ambiente, como animais, plantas, fungos e bactérias.</p>
        </article>
        <article class="info-card glass">
            <h3>Fatores abióticos</h3>
            <p>Componentes não vivos, como água, luz, solo, temperatura e umidade.</p>
        </article>
    </div>

    <div class="activity glass" id="bioticGame">
        <span class="eyebrow">ATIVIDADE INTERATIVA</span>
        <h3>Biótico ou abiótico?</h3>
        <p>Selecione a classificação correta para cada elemento.</p>
        <div class="biotic-items" id="bioticItems">
            <button data-answer="abiotico">Sol</button>
            <button data-answer="biotico">Bactéria</button>
            <button data-answer="abiotico">Água</button>
            <button data-answer="biotico">Ipê</button>
            <button data-answer="abiotico">Temperatura</button>
            <button data-answer="biotico">Onça-pintada</button>
            <button data-answer="abiotico">Umidade</button>
            <button data-answer="biotico">Fungo</button>
        </div>
        <div class="activity-controls">
            <button class="glass-button" data-classify="biotico">Biótico</button>
            <button class="glass-button" data-classify="abiotico">Abiótico</button>
        </div>
        <p id="bioticFeedback" class="feedback" aria-live="polite">Escolha um elemento e depois sua classificação.</p>
        <p>Pontuação: <strong id="bioticScore">0</strong> / 8</p>
    </div>

    <div class="warning glass">
        <strong>Espécies invasoras</strong>
        <p>
            São organismos introduzidos fora de sua área natural de
            distribuição que conseguem se estabelecer e se espalhar.
            Quando causam impactos, podem competir com espécies nativas,
            modificar habitats, transmitir doenças e alterar cadeias
            alimentares. A tilápia-do-nilo, introduzida em diversos
            ambientes, é um exemplo que exige atenção no manejo ambiental.
        </p>
    </div>

    <div class="info-card glass">
        <h3>Generalistas e especialistas</h3>
        <p>
            Espécies generalistas utilizam uma variedade maior de recursos
            e toleram diferentes condições ambientais. Espécies especialistas
            apresentam exigências mais restritas, podendo ser mais vulneráveis
            a mudanças em seu habitat.
        </p>
    </div>
</section>

<!-- 4. RELAÇÕES ECOLÓGICAS -->
<section class="content-section relations-section reveal" id="relacoes">
    <div class="relations-hero glass">
        <span class="eyebrow">03 • DESTAQUE ESPECIAL</span>
        <h2>Relações ecológicas</h2>
        <p>
            Nenhum organismo vive completamente isolado.
            Cooperação, disputa, alimentação e dependência fazem parte
            da complexa rede de interações da natureza.
        </p>
        <svg class="relations-illustration" viewBox="0 0 400 150" role="img" aria-label="Ilustração vetorial de plantas e organismos conectados">
            <path d="M200 140V55M200 90C150 90 125 65 120 25C165 20 195 42 200 90ZM200 110C250 110 285 85 290 45C245 40 215 65 200 110Z"/>
            <path d="M120 25 200 90M290 45 200 110"/>
            <circle cx="120" cy="25" r="8"/>
            <circle cx="290" cy="45" r="8"/>
            <circle cx="200" cy="55" r="8"/>
        </svg>
    </div>

    <div class="section-heading">
        <h3>Como podemos classificar?</h3>
        <p>As relações são classificadas conforme as espécies envolvidas e os efeitos sobre os participantes.</p>
    </div>

    <div class="matrix-grid">
        <button class="matrix-card glass active" data-relation-filter="intra-harmonica">
            <span>01</span>
            <h3>Intraespecíficas harmônicas</h3>
            <p>Mesma espécie. Ninguém é prejudicado.</p>
            <b>(+) / (+)</b>
        </button>
        <button class="matrix-card glass" data-relation-filter="intra-desarmonica">
            <span>02</span>
            <h3>Intraespecíficas desarmônicas</h3>
            <p>Mesma espécie. Pelo menos um participante perde.</p>
            <b>(+) / (-)</b>
        </button>
        <button class="matrix-card glass" data-relation-filter="inter-harmonica">
            <span>03</span>
            <h3>Interespecíficas harmônicas</h3>
            <p>Espécies diferentes. Ninguém é prejudicado.</p>
            <b>(+) / (+) ou (+) / (0)</b>
        </button>
        <button class="matrix-card glass" data-relation-filter="inter-desarmonica">
            <span>04</span>
            <h3>Interespecíficas desarmônicas</h3>
            <p>Espécies diferentes. Existe prejuízo.</p>
            <b>(+) / (-), (-) / (-) ou (0) / (-)</b>
        </button>
    </div>

    <div class="relation-toolbar">
        <button class="filter-chip active" data-relation-filter="todas">Todas</button>
        <button class="filter-chip" data-relation-filter="harmonicas">Harmônicas</button>
        <button class="filter-chip" data-relation-filter="desarmonicas">Desarmônicas</button>
        <button class="filter-chip" data-relation-filter="intra">Intraespecíficas</button>
        <button class="filter-chip" data-relation-filter="inter">Interespecíficas</button>
    </div>

    <div class="relation-grid" id="relationGrid">

        <article class="relation-card glass" data-type="intra-harmonica" data-name="Colônia">
            <span class="relation-category">INTRAESPECÍFICA HARMÔNICA</span>
            <h3>Colônia</h3>
            <div class="effect"><span>+ Benefício</span><span>+ Benefício</span></div>
            <p>Indivíduos da mesma espécie vivem unidos fisicamente e podem apresentar divisão de funções.</p>
            <div class="relation-example"><b>Exemplo:</b> caravela-portuguesa, formada por zooides especializados e interdependentes.</div>
            <div class="relation-curiosity"><b>Curiosidade:</b> os indivíduos da caravela desempenham funções diferentes, como alimentação e reprodução.</div>
        </article>

        <article class="relation-card glass" data-type="intra-harmonica" data-name="Sociedade">
            <span class="relation-category">INTRAESPECÍFICA HARMÔNICA</span>
            <h3>Sociedade</h3>
            <div class="effect"><span>+ Benefício</span><span>+ Benefício</span></div>
            <p>Indivíduos da mesma espécie cooperam, com divisão de tarefas, mas não ficam fisicamente unidos.</p>
            <div class="relation-example"><b>Exemplo:</b> formigas e abelhas.</div>
            <div class="relation-curiosity"><b>Curiosidade:</b> em formigueiros, diferentes indivíduos podem atuar na defesa, reprodução e manutenção da colônia.</div>
        </article>

        <article class="relation-card glass" data-type="intra-desarmonica" data-name="Canibalismo">
            <span class="relation-category">INTRAESPECÍFICA DESARMÔNICA</span>
            <h3>Canibalismo</h3>
            <div class="effect"><span>+ Benefício</span><span>- Prejuízo</span></div>
            <p>Um indivíduo mata e consome outro indivíduo da mesma espécie.</p>
            <div class="relation-example"><b>Exemplo:</b> algumas fêmeas de louva-a-deus podem consumir o macho durante ou após a reprodução.</div>
            <div class="relation-curiosity"><b>Curiosidade:</b> o comportamento não acontece obrigatoriamente em todos os acasalamentos.</div>
        </article>

        <article class="relation-card glass" data-type="intra-desarmonica" data-name="Competição intraespecífica">
            <span class="relation-category">INTRAESPECÍFICA DESARMÔNICA</span>
            <h3>Competição intraespecífica</h3>
            <div class="effect"><span>- Prejuízo</span><span>- Prejuízo</span></div>
            <p>Indivíduos da mesma espécie disputam recursos limitados, como alimento, território e parceiros.</p>
            <div class="relation-example"><b>Exemplo:</b> machos de leões disputando acesso a territórios e oportunidades reprodutivas.</div>
            <div class="relation-curiosity"><b>Curiosidade:</b> a competição pode regular o tamanho das populações.</div>
        </article>

        <article class="relation-card glass" data-type="inter-harmonica" data-name="Mutualismo">
            <span class="relation-category">INTERESPECÍFICA HARMÔNICA</span>
            <h3>Mutualismo</h3>
            <div class="effect"><span>+ Benefício</span><span>+ Benefício</span></div>
            <p>As duas espécies obtêm vantagens na interação. Em sua forma obrigatória, a associação é essencial para a sobrevivência de pelo menos uma delas.</p>
            <div class="relation-example"><b>Exemplo:</b> fungos e algas ou cianobactérias formando líquens.</div>
            <div class="relation-curiosity"><b>Curiosidade:</b> o fungo oferece proteção e retém água, enquanto o parceiro fotossintetizante produz matéria orgânica.</div>
        </article>

        <article class="relation-card glass" data-type="inter-harmonica" data-name="Protocooperação">
            <span class="relation-category">INTERESPECÍFICA HARMÔNICA</span>
            <h3>Protocooperação</h3>
            <div class="effect"><span>+ Benefício</span><span>+ Benefício</span></div>
            <p>As duas espécies se beneficiam, mas conseguem viver separadamente.</p>
            <div class="relation-example"><b>Exemplo:</b> formigas e acácias em determinadas associações ecológicas.</div>
            <div class="relation-curiosity"><b>Curiosidade:</b> as formigas podem receber abrigo e alimento e proteger a planta contra herbívoros.</div>
        </article>

        <article class="relation-card glass" data-type="inter-harmonica" data-name="Comensalismo">
            <span class="relation-category">INTERESPECÍFICA HARMÔNICA</span>
            <h3>Comensalismo</h3>
            <div class="effect"><span>+ Benefício</span><span>0 Neutro</span></div>
            <p>Uma espécie obtém alimento ou outra vantagem, enquanto a outra não apresenta benefício ou prejuízo significativo.</p>
            <div class="relation-example"><b>Exemplo:</b> rêmoras que aproveitam restos alimentares e transporte junto a grandes animais marinhos.</div>
            <div class="relation-curiosity"><b>Curiosidade:</b> a rêmora utiliza uma estrutura de sucção para se fixar ao hospedeiro.</div>
        </article>

        <article class="relation-card glass" data-type="inter-harmonica" data-name="Inquilinismo">
            <span class="relation-category">INTERESPECÍFICA HARMÔNICA</span>
            <h3>Inquilinismo</h3>
            <div class="effect"><span>+ Benefício</span><span>0 Neutro</span></div>
            <p>Uma espécie utiliza outra como abrigo ou suporte, sem causar prejuízo relevante.</p>
            <div class="relation-example"><b>Exemplo:</b> orquídeas que crescem sobre troncos de árvores.</div>
            <div class="relation-curiosity"><b>Curiosidade:</b> as orquídeas epífitas não retiram alimento diretamente da árvore que as sustenta.</div>
        </article>

        <article class="relation-card glass" data-type="inter-desarmonica" data-name="Predação">
            <span class="relation-category">INTERESPECÍFICA DESARMÔNICA</span>
            <h3>Predação</h3>
            <div class="effect"><span>+ Benefício</span><span>- Prejuízo</span></div>
            <p>Um organismo captura, mata e consome outro organismo.</p>
            <div class="relation-example"><b>Exemplo:</b> serpente que captura e consome um roedor.</div>
            <div class="relation-curiosity"><b>Curiosidade:</b> predadores podem contribuir para a regulação das populações de presas.</div>
        </article>

        <article class="relation-card glass" data-type="inter-desarmonica" data-name="Parasitismo">
            <span class="relation-category">INTERESPECÍFICA DESARMÔNICA</span>
            <h3>Parasitismo</h3>
            <div class="effect"><span>+ Benefício</span><span>- Prejuízo</span></div>
            <p>O parasita obtém recursos de um hospedeiro, causando prejuízos sem necessariamente matá-lo imediatamente.</p>
            <div class="relation-example"><b>Exemplo:</b> piolhos (ectoparasitas) e lombrigas (endoparasitas).</div>
            <div class="relation-curiosity"><b>Curiosidade:</b> parasitas podem viver na superfície ou no interior do hospedeiro.</div>
        </article>

        <article class="relation-card glass" data-type="inter-desarmonica" data-name="Competição interespecífica">
            <span class="relation-category">INTERESPECÍFICA DESARMÔNICA</span>
            <h3>Competição interespecífica</h3>
            <div class="effect"><span>- Prejuízo</span><span>- Prejuízo</span></div>
            <p>Espécies diferentes disputam recursos que são limitados e possuem nichos parcialmente sobrepostos.</p>
            <div class="relation-example"><b>Exemplo:</b> herbívoros de espécies diferentes disputando vegetação durante a seca.</div>
            <div class="relation-curiosity"><b>Curiosidade:</b> a competição pode levar à especialização ou à exclusão competitiva.</div>
        </article>

        <article class="relation-card glass" data-type="inter-desarmonica" data-name="Amensalismo">
            <span class="relation-category">INTERESPECÍFICA DESARMÔNICA</span>
            <h3>Amensalismo</h3>
            <div class="effect"><span>0 Neutro</span><span>- Prejuízo</span></div>
            <p>Uma espécie libera substâncias ou provoca efeitos que prejudicam outra, sem obter necessariamente benefício direto.</p>
            <div class="relation-example"><b>Exemplo:</b> fungos do gênero Penicillium produzem substâncias que inibem determinadas bactérias.</div>
            <div class="relation-curiosity"><b>Curiosidade:</b> a penicilina contribuiu para o desenvolvimento dos antibióticos.</div>
        </article>

        <article class="relation-card glass" data-type="inter-desarmonica" data-name="Herbivoria">
            <span class="relation-category">INTERESPECÍFICA DESARMÔNICA</span>
            <h3>Herbivoria</h3>
            <div class="effect"><span>+ Benefício</span><span>- Prejuízo</span></div>
            <p>Um animal consome partes de plantas ou algas, obtendo alimento e causando prejuízo ao organismo consumido.</p>
            <div class="relation-example"><b>Exemplo:</b> gafanhotos consumindo folhas de vegetação.</div>
            <div class="relation-curiosity"><b>Curiosidade:</b> algumas plantas desenvolveram defesas químicas e estruturas físicas contra herbívoros.</div>
        </article>

    </div>

    <div class="warning glass">
        <h3>Princípio da exclusão competitiva</h3>
        <p>
            Associado ao pesquisador G. F. Gause, o princípio estabelece
            que duas espécies que ocupam nichos ecológicos muito semelhantes
            e dependem dos mesmos recursos limitados não tendem a coexistir
            indefinidamente nas mesmas condições. Uma delas pode ser
            excluída ou as espécies podem modificar o uso dos recursos.
        </p>
    </div>

    <div class="activity glass">
        <span class="eyebrow">DESAFIO DAS RELAÇÕES</span>
        <h3>Que relação é essa?</h3>
        <p id="relationQuestion">Carregando situação...</p>
        <div id="relationOptions" class="quiz-options"></div>
        <p id="relationFeedback" class="feedback" aria-live="polite"></p>
        <div class="quiz-status">
            <span>Pontos: <b id="relationPoints">0</b></span>
            <span>Sequência: <b id="relationStreak">0</b></span>
            <span>Questão: <b id="relationNumber">1</b>/10</span>
        </div>
        <button class="glass-button" id="nextRelation" hidden>Próxima situação</button>
    </div>

    <div class="glass table-container">
        <h3>Quem ganha e quem perde?</h3>
        <div class="responsive-table">
            <table>
                <thead>
                    <tr>
                        <th>Relação</th>
                        <th>Classificação</th>
                        <th>Espécie 1</th>
                        <th>Espécie 2</th>
                    </tr>
                </thead>
                <tbody id="relationTable"></tbody>
            </table>
        </div>
    </div>
</section>

<!-- 5. ENERGIA -->
<section class="content-section reveal" id="energia">
    <div class="section-heading">
        <span class="eyebrow">04 • FUNCIONAMENTO DOS ECOSSISTEMAS</span>
        <h2>Fluxo de energia e matéria</h2>
        <p>A energia percorre os níveis tróficos, enquanto a matéria participa de ciclos naturais.</p>
    </div>

    <div class="info-grid">
        <article class="info-card glass">
            <h3>Produtores</h3>
            <p>Organismos autotróficos, como plantas e algas, que produzem matéria orgânica.</p>
        </article>
        <article class="info-card glass">
            <h3>Consumidores primários</h3>
            <p>Alimentam-se diretamente dos produtores, sendo frequentemente herbívoros.</p>
        </article>
        <article class="info-card glass">
            <h3>Consumidores secundários</h3>
            <p>Consomem consumidores primários.</p>
        </article>
        <article class="info-card glass">
            <h3>Consumidores terciários</h3>
            <p>Alimentam-se de consumidores secundários.</p>
        </article>
        <article class="info-card glass">
            <h3>Decompositores</h3>
            <p>Fungos e bactérias transformam matéria orgânica e devolvem nutrientes ao ambiente.</p>
        </article>
    </div>

    <div class="warning glass">
        <strong>Energia não é reciclada como a matéria!</strong>
        <p>
            O fluxo de energia é unidirecional. Parte da energia disponível
            em cada nível é utilizada no metabolismo e dissipada como calor.
            Já os nutrientes podem retornar ao ambiente e ser reutilizados
            pelos produtores.
        </p>
    </div>

    <div class="activity glass">
        <h3>Pirâmides ecológicas</h3>
        <div class="tabs" role="tablist" aria-label="Tipo de pirâmide">
            <button role="tab" aria-selected="true" class="tab active" data-pyramid="energia">Energia</button>
            <button role="tab" aria-selected="false" class="tab" data-pyramid="biomassa">Biomassa</button>
            <button role="tab" aria-selected="false" class="tab" data-pyramid="numeros">Números</button>
        </div>
        <div class="pyramid" id="pyramid">
            <div class="pyramid-level level-4">Consumidores terciários</div>
            <div class="pyramid-level level-3">Consumidores secundários</div>
            <div class="pyramid-level level-2">Consumidores primários</div>
            <div class="pyramid-level level-1">Produtores</div>
        </div>
        <p id="pyramidExplanation" class="feedback">
            A pirâmide de energia representa a energia disponível em cada nível trófico e nunca é invertida.
        </p>
    </div>

    <div class="activity glass">
        <h3>Simulador de energia</h3>
        <label for="initialEnergy">Energia inicial dos produtores: <strong id="energyValue">10.000</strong> kJ</label>
        <input type="range" min="1000" max="20000" step="1000" value="10000" id="initialEnergy">
        <div class="energy-levels" id="energyLevels"></div>
        <p class="feedback">A regra dos 10% é uma aproximação didática: em média, apenas uma pequena parcela da energia passa ao nível seguinte.</p>
    </div>

    <div class="info-card glass">
        <h3>Cadeia alimentar e teia alimentar</h3>
        <p>
            A cadeia alimentar representa uma sequência de alimentação.
            A teia alimentar reúne várias cadeias conectadas. Um mesmo
            organismo pode ocupar posições tróficas diferentes, dependendo
            do alimento consumido.
        </p>
        <p>
            A seta indica o sentido do fluxo de energia: parte do organismo
            que é consumido e aponta para aquele que o consome.
        </p>
    </div>

    <div class="activity glass">
        <h3>Exemplo de teia alimentar</h3>
        <div class="food-web" id="foodWeb">
            <button data-organism="capim">Capim</button>
            <button data-organism="gafanhoto">Gafanhoto</button>
            <button data-organism="rato">Rato</button>
            <button data-organism="sapo">Sapo</button>
            <button data-organism="cobra">Cobra</button>
            <button data-organism="gaviao">Gavião</button>
        </div>
        <p id="foodWebInfo" class="feedback" aria-live="polite">Selecione um organismo para visualizar suas relações alimentares.</p>
        <button class="glass-button" id="removePredator">Simular retirada do predador de topo</button>
        <p id="cascadeInfo" class="feedback" aria-live="polite"></p>
    </div>

    <div class="warning glass">
        <h3>Bioacumulação e magnificação trófica</h3>
        <p>
            A bioacumulação ocorre quando uma substância se acumula
            no organismo ao longo do tempo. A magnificação trófica
            corresponde ao aumento da concentração de determinadas
            substâncias persistentes nos níveis tróficos mais elevados.
            O mercúrio, especialmente na forma de metilmercúrio,
            pode atingir concentrações preocupantes em grandes peixes
            predadores e representar riscos à saúde humana.
        </p>
    </div>
</section>

<!-- 6. JOGO -->
<section class="content-section reveal" id="jogo">
    <div class="section-heading">
        <span class="eyebrow">05 • APRENDA JOGANDO</span>
        <h2>Monte a cadeia alimentar</h2>
        <p>Organize os organismos conforme suas funções no ecossistema.</p>
    </div>

    <div class="game glass" id="chainGame">
        <div class="game-header">
            <div>
                <span class="eyebrow">DESAFIO ECOLÓGICO</span>
                <h3 id="stageTitle">Fase 1 — Campo</h3>
                <p id="stageDescription">Monte uma sequência alimentar do Cerrado.</p>
            </div>
            <div class="game-score">
                <span>Pontos</span>
                <strong id="gameScore">0</strong>
            </div>
        </div>

        <div class="game-progress">
            <span id="stageProgress">Fase 1 de 6</span>
            <div class="progress-track"><span id="stageProgressBar"></span></div>
        </div>

        <div class="chain-slots" id="chainSlots"></div>

        <p class="game-instruction">
            Arraste os cartões ou selecione um organismo e escolha o espaço correspondente.
        </p>

        <div class="organism-tray" id="organismTray"></div>

        <div class="game-actions">
            <button class="glass-button" id="gameHint">Dica</button>
            <button class="glass-button" id="gameReset">Reiniciar</button>
            <button class="glass-button" id="gameNext" disabled>Próxima fase</button>
        </div>

        <div class="game-learning">
            <label>
                <input type="checkbox" id="learningMode" checked>
                Modo Aprender: exibir curiosidades sobre os organismos.
            </label>
            <p id="gameFeedback" class="feedback" aria-live="polite"></p>
            <p id="gameExplanation"></p>
            <div id="gameStars" class="game-stars" aria-label="Estrelas conquistadas"></div>
        </div>

        <p class="game-legend">
            A seta mostra o caminho da energia: vai de quem é comido para quem come.
        </p>
        <p class="game-record" id="gameRecord"></p>
    </div>
</section>

<!-- 7. CICLOS -->
<section class="content-section reveal" id="ciclos">
    <div class="section-heading">
        <span class="eyebrow">06 • MATÉRIA NA NATUREZA</span>
        <h2>Ciclos biogeoquímicos</h2>
        <p>Os elementos químicos circulam continuamente entre os componentes vivos e não vivos da Terra.</p>
    </div>

    <div class="cycle-tabs tabs" role="tablist" aria-label="Ciclos biogeoquímicos">
        <button class="tab active" data-cycle="agua">Água</button>
        <button class="tab" data-cycle="carbono">Carbono</button>
        <button class="tab" data-cycle="nitrogenio">Nitrogênio</button>
        <button class="tab" data-cycle="fosforo">Fósforo</button>
        <button class="tab" data-cycle="oxigenio">Oxigênio</button>
    </div>

    <div class="cycle-panel glass" id="cyclePanel">
        <h3 id="cycleTitle">Ciclo da água</h3>
        <p id="cycleDescription">A água circula entre a atmosfera, a superfície e o subsolo.</p>
        <div id="cycleSteps" class="cycle-steps"></div>
        <button class="glass-button" id="playCycle">Reproduzir ciclo</button>
    </div>

    <div class="info-grid">
        <article class="info-card glass">
            <h3>Carbono</h3>
            <p>Envolve fotossíntese, respiração, decomposição e combustão. O aumento de dióxido de carbono atmosférico intensifica o efeito estufa.</p>
        </article>
        <article class="info-card glass">
            <h3>Nitrogênio</h3>
            <p>Passa por fixação, nitrificação, assimilação e desnitrificação. É fundamental para proteínas e ácidos nucleicos.</p>
        </article>
        <article class="info-card glass">
            <h3>Fósforo</h3>
            <p>Circula principalmente entre rochas, solo, água e organismos. Não possui uma fase atmosférica significativa.</p>
        </article>
        <article class="info-card glass">
            <h3>Oxigênio</h3>
            <p>Participa de processos como fotossíntese, respiração e combustão.</p>
        </article>
    </div>

    <div class="warning glass">
        <strong>Conexão com o ENEM</strong>
        <p>O oxigênio está presente nos ciclos da água, carbono, nitrogênio, fósforo e enxofre, sendo uma conexão importante entre esses processos.</p>
    </div>
</section>

<!-- 8. SUCESSÃO -->
<section class="content-section reveal" id="sucessao">
    <div class="section-heading">
        <span class="eyebrow">07 • TRANSFORMAÇÃO DOS AMBIENTES</span>
        <h2>Sucessão ecológica</h2>
        <p>As comunidades se transformam gradualmente ao longo do tempo.</p>
    </div>

    <div class="activity glass">
        <div class="tabs">
            <button class="tab active" data-succession="primaria">Sucessão primária</button>
            <button class="tab" data-succession="secundaria">Sucessão secundária</button>
        </div>
        <div class="succession-scene">
            <div class="scene-ground" id="sceneGround"></div>
            <div class="scene-life" id="sceneLife"></div>
        </div>
        <label for="successionRange">Avanço da sucessão</label>
        <input type="range" id="successionRange" min="0" max="6" value="0" step="1">
        <h3 id="successionTitle">Rocha nua</h3>
        <p id="successionDescription">Ambiente inicialmente sem solo desenvolvido e sem uma comunidade estabelecida.</p>
        <div class="timeline-labels">
            <span>Início</span><span>Desenvolvimento</span><span>Comunidade madura</span>
        </div>
    </div>

    <div class="info-grid">
        <article class="info-card glass">
            <h3>Sucessão primária</h3>
            <p>Começa em uma área sem comunidade anterior e sem solo desenvolvido, como uma rocha recém-exposta.</p>
        </article>
        <article class="info-card glass">
            <h3>Sucessão secundária</h3>
            <p>Ocorre após um distúrbio em uma área anteriormente ocupada, onde o solo permanece, como em certas áreas após queimadas.</p>
        </article>
    </div>

    <div class="warning glass">
        <strong>Comunidade clímax</strong>
        <p>
            Líquens e musgos podem atuar como pioneiros, contribuindo
            para o intemperismo e a formação do solo. Com o tempo,
            podem surgir gramíneas, arbustos e árvores. A comunidade
            madura apresenta maior complexidade, mas não é imóvel:
            permanece em equilíbrio dinâmico e sujeita a perturbações.
        </p>
    </div>
</section>

<!-- 9. POPULAÇÕES -->
<section class="content-section reveal" id="populacoes">
    <div class="section-heading">
        <span class="eyebrow">08 • DINÂMICA DOS SERES VIVOS</span>
        <h2>Dinâmica de populações</h2>
        <p>O tamanho de uma população depende de nascimentos, mortes e deslocamentos.</p>
    </div>

    <div class="info-grid">
        <article class="info-card glass">
            <h3>Natalidade</h3>
            <p>Número de nascimentos em uma população durante determinado período.</p>
        </article>
        <article class="info-card glass">
            <h3>Mortalidade</h3>
            <p>Número de mortes no mesmo intervalo.</p>
        </article>
        <article class="info-card glass">
            <h3>Imigração</h3>
            <p>Entrada de indivíduos na população.</p>
        </article>
        <article class="info-card glass">
            <h3>Emigração</h3>
            <p>Saída de indivíduos da população.</p>
        </article>
    </div>

    <div class="activity glass">
        <h3>Simulador de crescimento populacional</h3>
        <label for="birthRate">Natalidade: <strong id="birthValue">6</strong></label>
        <input type="range" min="0" max="10" value="6" id="birthRate">
        <label for="deathRate">Mortalidade: <strong id="deathValue">2</strong></label>
        <input type="range" min="0" max="10" value="2" id="deathRate">
        <label for="carryingCapacity">Capacidade de suporte: <strong id="capacityValue">100</strong></label>
        <input type="range" min="20" max="200" step="10" value="100" id="carryingCapacity">
        <button class="glass-button" id="simulatePopulation">Simular</button>
        <div class="population-chart">
            <canvas id="populationCanvas" height="220" aria-label="Gráfico de crescimento populacional"></canvas>
        </div>
        <p id="populationExplanation" class="feedback" aria-live="polite">Ajuste os parâmetros e inicie a simulação.</p>
    </div>

    <div class="info-grid">
        <article class="info-card glass">
            <h3>Potencial biótico</h3>
            <p>Capacidade de reprodução e crescimento de uma população em condições ideais.</p>
        </article>
        <article class="info-card glass">
            <h3>Resistência ambiental</h3>
            <p>Conjunto de fatores que limita o crescimento populacional.</p>
        </article>
        <article class="info-card glass">
            <h3>Crescimento exponencial</h3>
            <p>Apresenta curva em J e ocorre quando os recursos são abundantes e as limitações são pequenas.</p>
        </article>
        <article class="info-card glass">
            <h3>Crescimento logístico</h3>
            <p>Apresenta curva em S e tende a se estabilizar próximo à capacidade de suporte do ambiente.</p>
        </article>
    </div>
</section>

<!-- 10. RAMOS -->
<section class="content-section reveal" id="ramos">
    <div class="section-heading">
        <span class="eyebrow">09 • ÁREAS DE ESTUDO</span>
        <h2>Ramos da Ecologia</h2>
        <p>Os ecólogos investigam diferentes níveis, organismos e ambientes.</p>
    </div>

    <div class="branch-tree glass" id="branchTree">
        <button class="branch-node" data-branch="organismos">Organismos</button>
        <div class="branch-content" id="branch-organismos">
            <p><b>Autoecologia:</b> estuda as relações de uma espécie ou organismo com seu ambiente.</p>
        </div>
        <button class="branch-node" data-branch="populacoes">Populações e comunidades</button>
        <div class="branch-content" id="branch-populacoes">
            <p><b>Demoecologia:</b> estuda a dinâmica das populações.</p>
            <p><b>Sinecologia:</b> estuda as comunidades e suas interações.</p>
        </div>
        <button class="branch-node" data-branch="ambientes">Ambientes</button>
        <div class="branch-content" id="branch-ambientes">
            <p><b>Limnologia:</b> ambientes continentais de água doce.</p>
            <p><b>Ecologia marinha:</b> oceanos, recifes e estuários.</p>
            <p><b>Ecologia terrestre:</b> florestas, campos, desertos e montanhas.</p>
        </div>
        <button class="branch-node" data-branch="aplicacoes">Aplicações</button>
        <div class="branch-content" id="branch-aplicacoes">
            <p><b>Ecologia da paisagem:</b> distribuição espacial e fragmentação de habitats.</p>
            <p><b>Ecologia da conservação:</b> proteção da biodiversidade.</p>
            <p><b>Ecologia humana:</b> relações entre sociedades e ambiente.</p>
            <p><b>Ecologia evolutiva:</b> relações entre processos ecológicos e evolução.</p>
        </div>
    </div>
</section>

<!-- 11. BIOMAS -->
<section class="content-section reveal" id="biomas">
    <div class="section-heading">
        <span class="eyebrow">10 • BIODIVERSIDADE BRASILEIRA</span>
        <h2>Biomas brasileiros</h2>
        <p>O Brasil apresenta seis grandes biomas continentais reconhecidos pelo IBGE.</p>
    </div>

    <div class="biome-grid">
        <button class="biome-card glass" data-biome="amazonia">
            <span>01</span><h3>Amazônia</h3><p>Florestas úmidas e grande biodiversidade.</p>
        </button>
        <button class="biome-card glass" data-biome="mata">
            <span>02</span><h3>Mata Atlântica</h3><p>Florestas tropicais e alto endemismo.</p>
        </button>
        <button class="biome-card glass" data-biome="cerrado">
            <span>03</span><h3>Cerrado</h3><p>Savana brasileira e raízes profundas.</p>
        </button>
        <button class="biome-card glass" data-biome="caatinga">
            <span>04</span><h3>Caatinga</h3><p>Vegetação adaptada à seca.</p>
        </button>
        <button class="biome-card glass" data-biome="pampa">
            <span>05</span><h3>Pampa</h3><p>Campos naturais do Sul.</p>
        </button>
        <button class="biome-card glass" data-biome="pantanal">
            <span>06</span><h3>Pantanal</h3><p>Planície com ciclos de inundação.</p>
        </button>
    </div>

    <div class="biome-detail glass" id="biomeDetail" aria-live="polite">
        <h3>Escolha um bioma</h3>
        <p>Selecione um dos cartões para conhecer suas características.</p>
    </div>

    <div class="activity glass">
        <h3>Qual é o bioma?</h3>
        <p id="biomeQuestion">Possui vegetação adaptada à escassez de água e clima semiárido. Qual é?</p>
        <div id="biomeOptions" class="quiz-options"></div>
        <p id="biomeFeedback" class="feedback" aria-live="polite"></p>
    </div>
</section>

<!-- 12. IMPORTÂNCIA -->
<section class="content-section reveal" id="importancia">
    <div class="section-heading">
        <span class="eyebrow">11 • ECOLOGIA E SOCIEDADE</span>
        <h2>Por que a Ecologia é importante?</h2>
        <p>Compreender o ambiente é essencial para preservar a vida e planejar o futuro.</p>
    </div>

    <div class="concept-grid">
        <article class="info-card glass"><h3>Conservação</h3><p>Identificação de espécies ameaçadas e planejamento de áreas protegidas.</p></article>
        <article class="info-card glass"><h3>Clima</h3><p>Estudo das mudanças climáticas e dos impactos sobre os ecossistemas.</p></article>
        <article class="info-card glass"><h3>Saúde pública</h3><p>Compreensão de como alterações ambientais podem modificar a circulação de agentes infecciosos.</p></article>
        <article class="info-card glass"><h3>Agricultura</h3><p>Desenvolvimento de agroflorestas, manejo sustentável e controle biológico.</p></article>
        <article class="info-card glass"><h3>Políticas públicas</h3><p>Planejamento de ações de preservação, saneamento e gestão dos recursos naturais.</p></article>
        <article class="info-card glass"><h3>Educação ambiental</h3><p>Formação de cidadãos capazes de tomar decisões responsáveis.</p></article>
    </div>

    <div class="warning glass">
        <h3>Controle biológico e Wolbachia</h3>
        <p>
            A estratégia Wolbachia utiliza uma bactéria que, ao ser
            introduzida em populações de Aedes aegypti, pode reduzir
            a capacidade de transmissão de vírus como dengue, zika
            e chikungunya. Trata-se de uma ferramenta de controle
            biológico que complementa outras medidas de saúde pública.
        </p>
    </div>

    <div class="activity glass">
        <h3>O que eu posso fazer?</h3>
        <p>Pequenas atitudes podem contribuir para a conservação ambiental.</p>
        <div class="action-checklist">
            <label><input type="checkbox"> Economizar água e energia.</label>
            <label><input type="checkbox"> Separar e descartar resíduos corretamente.</label>
            <label><input type="checkbox"> Praticar o consumo consciente.</label>
            <label><input type="checkbox"> Reduzir o desperdício de alimentos.</label>
            <label><input type="checkbox"> Plantar e preservar espécies nativas.</label>
            <label><input type="checkbox"> Respeitar a fauna e seus habitats.</label>
        </div>
        <div class="plant-progress">
            <div class="plant-stem" id="plantStem"><span></span><span></span><span></span></div>
            <p id="actionProgress">0 de 6 atitudes realizadas.</p>
        </div>
    </div>
</section>

<!-- 13. ENEM -->
<section class="content-section reveal" id="enem">
    <div class="section-heading">
        <span class="eyebrow">12 • PREPARAÇÃO</span>
        <h2>Ecologia no ENEM</h2>
        <p>Aprenda a reconhecer os assuntos recorrentes e interpretar situações ambientais.</p>
    </div>

    <div class="tag-cloud">
        <a href="#energia">Cadeias alimentares</a>
        <a href="#ciclos">Ciclos biogeoquímicos</a>
        <a href="#relacoes">Relações ecológicas</a>
        <a href="#biomas">Biomas brasileiros</a>
        <a href="#sucessao">Sucessão ecológica</a>
        <a href="#importancia">Impactos ambientais</a>
        <a href="#conceitos">Biodiversidade</a>
        <a href="#energia">Magnificação trófica</a>
        <a href="#importancia">Sustentabilidade</a>
        <a href="#populacoes">Dinâmica populacional</a>
        <a href="#ciclos">Efeito estufa</a>
        <a href="#relacoes">Controle biológico</a>
    </div>

    <div class="warning glass">
        <strong>Estratégia de prova</strong>
        <p>
            Leia atentamente os textos e gráficos, identifique os organismos
            envolvidos e observe o sentido das relações. Em questões
            ambientais, procure compreender a causa e a consequência
            do fenômeno apresentado.
        </p>
    </div>
</section>

<!-- 14. QUIZ -->
<section class="content-section reveal" id="quiz">
    <div class="section-heading">
        <span class="eyebrow">13 • TESTE SEUS CONHECIMENTOS</span>
        <h2>Quiz final de Ecologia</h2>
        <p>Responda às questões e descubra o quanto aprendeu.</p>
    </div>

    <div class="quiz-container glass" id="finalQuiz">
        <div class="quiz-top">
            <span id="quizCounter">Questão 1 de 14</span>
            <span id="quizScore">Acertos: 0</span>
        </div>
        <div class="progress-track"><span id="quizProgress"></span></div>
        <h3 id="quizQuestion"></h3>
        <div class="quiz-options" id="quizAnswers"></div>
        <div id="quizFeedback" class="feedback" aria-live="polite"></div>
        <button class="glass-button" id="quizNext" disabled>Próxima questão</button>
        <div id="quizResult" hidden></div>
    </div>
</section>

<!-- 15. REFERÊNCIAS -->
<section class="content-section reveal" id="referencias">
    <div class="section-heading">
        <span class="eyebrow">14 • FONTES DE ESTUDO</span>
        <h2>Referências bibliográficas</h2>
        <p>Materiais utilizados como base para aprofundar os estudos de Ecologia.</p>
    </div>

    <div class="references glass">
        <ol>
            <li>ODUM, Eugene P.; BARRETT, Gary W. <i>Fundamentos de Ecologia.</i></li>
            <li>BEGON, Michael; TOWNSEND, Colin R.; HARPER, John L. <i>Ecologia: de indivíduos a ecossistemas.</i></li>
            <li>HANAZAKI, Natalia et al. <i>Introdução à Ecologia.</i></li>
            <li>IBGE. <i>Biomas brasileiros.</i> IBGE Educa.</li>
            <li>Toda Matéria. <i>Ecologia.</i></li>
            <li>Brasil Escola. <i>Ecologia e relações ecológicas.</i></li>
        </ol>
        <button class="glass-button" id="copyCitation">Copiar citação ABNT</button>
        <p id="citationFeedback" class="feedback" aria-live="polite"></p>
    </div>
</section>

</main>

<!-- GUIA DE ESTUDO -->
<aside class="nature-guide" id="natureGuide">
    <button id="guideToggle" aria-label="Ocultar guia de estudo">×</button>
    <div class="guide-symbol" aria-hidden="true">
        <svg viewBox="0 0 40 40">
            <path d="M33 5C13 5 4 16 8 31c14 2 24-8 25-26Z"/>
            <path d="M8 31 27 10"/>
        </svg>
    </div>
    <p id="guideText">Explore as seções e descubra como a natureza se conecta.</p>
</aside>

<footer>
    <p>© 2026 EcoENEM. Todos os direitos reservados.</p>
</footer>

<script src="js/conteudo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script>window.ECOENEM_SERVER_SESSION = <?php echo json_encode($ecoenem_sessao_servidor, JSON_UNESCAPED_SLASHES); ?>;</script>
<script src="js/auth.js"></script>
<script src="js/auth-ui.js"></script>
</body>
</html>
