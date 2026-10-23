<?php
/*
 * ============================================================
 *  SIMULADÃO ECOENEM — BANCO DE QUESTÕES
 * ============================================================
 *  Questões de Ecologia e Ciências Ambientais do ENEM, retiradas
 *  do QConcursos (filtro "ENEM > Biologia > Ecologia e Ciências
 *  Ambientais").
 *
 *  Cada questão é um array associativo com:
 *   - id           : identificador único (usado na sessão)
 *   - fonte        : prova/ano de origem
 *   - tema         : assunto de Ecologia (usado para ordenar o
 *                    simulado quando o objetivo é "Revisar conteúdos")
 *   - adaptada     : true quando o texto-base foi adaptado
 *   - texto        : parágrafos do texto-base
 *   - imagem       : caminho da imagem (ou "" quando não houver)
 *   - comando      : pergunta da questão
 *   - alternativas : 5 alternativas (A a E)
 *   - correta      : índice da alternativa correta (0 = A ... 4 = E)
 *   - comentario   : explicação usada no gabarito comentado
 * ============================================================
 */

$bancoQuestoes = [

    [
        "id" => 1,
        "fonte" => "ENEM 2024",
        "tema" => "Impactos ambientais",
        "adaptada" => false,
        "texto" => [
            "O esquema apresenta as etapas do processo de biodigestão, uma forma de dar um destino sustentável a excrementos animais e restos de comida produzidos em áreas de criação de gado e porcos confinados."
        ],
        "imagem" => "imgs/Q1.jpeg",
        "comando" => "Dar destino sustentável às sobras, conforme apresentado na etapa 4, ajuda a evitar a",
        "alternativas" => [
            "bioacumulação de toxinas em plantas.",
            "eutrofização dos corpos de água.",
            "destruição da camada de ozônio.",
            "ocorrência de inversão térmica.",
            "produção de chuva ácida."
        ],
        "correta" => 1,
        "comentario" => "O descarte de matéria orgânica em rios e lagos aumenta a oferta de nutrientes, provoca a proliferação de algas e reduz o oxigênio da água (eutrofização). Usar as sobras como fertilizante evita esse descarte."
    ],

    [
        "id" => 2,
        "fonte" => "ENEM 2024",
        "tema" => "Mudanças climáticas",
        "adaptada" => true,
        "texto" => [
            "Desde o século XIX, a concentração de dióxido de carbono (CO2) na atmosfera aumentou cerca de 50%, e a temperatura média do planeta subiu aproximadamente 1,2 °C."
        ],
        "imagem" => "",
        "comando" => "Qual ação mitigadora auxilia na remoção desse gás presente na atmosfera, reduzindo seus níveis?",
        "alternativas" => [
            "Plantar mais árvores.",
            "Instalar mais usinas eólicas.",
            "Ampliar o uso de energia solar.",
            "Manter os combustíveis fósseis no solo.",
            "Produzir menos resíduos sólidos urbanos."
        ],
        "correta" => 0,
        "comentario" => "As demais ações apenas reduzem novas emissões. Somente o plantio de árvores retira CO2 que já está na atmosfera, pois as plantas o fixam na fotossíntese e o armazenam na biomassa."
    ],

    [
        "id" => 3,
        "fonte" => "ENEM 2024",
        "tema" => "Biomas brasileiros",
        "adaptada" => true,
        "texto" => [
            "O monstro-de-gila é um lagarto que vive em uma região desértica dos Estados Unidos, com clima quente e seco e chuvas escassas. Esse animal apresenta adaptações ao ambiente árido e produz um hormônio que regula os níveis de açúcar no sangue, estudado no tratamento de pessoas diabéticas."
        ],
        "imagem" => "",
        "comando" => "Considerando as características do ambiente em que esse animal vive, onde seria mais provável encontrar lagartos com essas características no território brasileiro?",
        "alternativas" => [
            "Cerrado.",
            "Pampas.",
            "Caatinga.",
            "Restinga.",
            "Pantanal."
        ],
        "correta" => 2,
        "comentario" => "A Caatinga é o bioma brasileiro de clima semiárido, quente e com chuvas escassas e irregulares — o mais parecido com o deserto onde vive o monstro-de-gila."
    ],

    [
        "id" => 4,
        "fonte" => "ENEM 2024",
        "tema" => "Biomas brasileiros",
        "adaptada" => false,
        "texto" => [
            "O bioma Cerrado é caracterizado por apresentar ampla e natural diversidade de espécies vegetais. Nos últimos tempos, o homem vem modificando esse cenário pela inserção de plantas exóticas nesse ambiente. Exemplo disso é o cultivo do capim-gordura, nativo do continente africano e utilizado nesse tipo de ecossistema como forma de pastagem. É importante ressaltar que essa espécie vegetal é capaz de se espalhar por grandes áreas, devido à sua agressividade e poder competitivo."
        ],
        "imagem" => "",
        "comando" => "Em longo prazo, essa ação do homem pode gerar qual consequência?",
        "alternativas" => [
            "Diversificar nichos ecológicos.",
            "Assorear as nascentes do bioma.",
            "Dificultar a infiltração de água na terra.",
            "Diminuir as espécies nativas do bioma.",
            "Contribuir com a redução das queimadas."
        ],
        "correta" => 3,
        "comentario" => "O capim-gordura é uma espécie exótica invasora: compete com as plantas nativas por água, luz e nutrientes e, com o tempo, reduz a quantidade de espécies nativas do Cerrado."
    ],

    [
        "id" => 5,
        "fonte" => "ENEM 2024",
        "tema" => "Relações ecológicas",
        "adaptada" => true,
        "texto" => [
            "Os sapinhos-ponta-de-flecha são anfíbios conhecidos por sua pele tóxica. Essas toxinas não são produzidas pelo próprio animal: elas são obtidas a partir da ingestão de formigas e cupins presentes no seu hábitat natural. Indivíduos nascidos em cativeiro, entretanto, não são tóxicos."
        ],
        "imagem" => "",
        "comando" => "A perda da capacidade de se obter a toxina nos nascidos em cativeiro é causada pela",
        "alternativas" => [
            "diferença de umidade entre os ambientes.",
            "ausência de alimentação natural.",
            "adaptação ao novo ambiente.",
            "mudança de comportamento.",
            "variabilidade genética."
        ],
        "correta" => 1,
        "comentario" => "Como a toxina vem das formigas e cupins ingeridos, os sapos criados em cativeiro, que não recebem essa alimentação natural, não acumulam a substância."
    ],

    [
        "id" => 6,
        "fonte" => "ENEM 2024 – PPL",
        "tema" => "Impactos ambientais",
        "adaptada" => false,
        "texto" => [
            "O cágado-de-barbelas vive nas águas poluídas do Rio Preto, em São José do Rio Preto, interior de São Paulo. Os animais dessa espécie, usados em estudos de ecotoxicologia, são atraídos para esses locais por causa do acúmulo de matéria orgânica nos pontos de despejo de esgoto, ambientes com grande quantidade de metais e baixíssimo teor de oxigênio."
        ],
        "imagem" => "",
        "comando" => "Nesse contexto, do ponto de vista ecológico, esses animais são considerados",
        "alternativas" => [
            "bioindicadores.",
            "biorremediadores.",
            "organismos invasores.",
            "anaeróbios facultativos.",
            "decompositores adaptados."
        ],
        "correta" => 0,
        "comentario" => "A presença do cágado-de-barbelas e a análise de seu organismo fornecem informações sobre a qualidade do ambiente poluído; por isso, ele atua como bioindicador."
    ],

    [
        "id" => 7,
        "fonte" => "ENEM 2024 – PPL",
        "tema" => "Adaptações ao ambiente",
        "adaptada" => false,
        "texto" => [
            "As lhamas que vivem nas montanhas dos Andes da América do Sul têm hemoglobinas geneticamente diferenciadas de outros mamíferos que vivem ao nível do mar, por exemplo. Essa diferenciação trata-se de uma adaptação à sua sobrevivência em altitudes elevadas onde o ar é rarefeito."
        ],
        "imagem" => "",
        "comando" => "A adaptação desses animais em relação ao seu ambiente confere maior",
        "alternativas" => [
            "afinidade pelo O2, maximizando a captação desse gás.",
            "capacidade de tamponamento, evitando alterações de pH no sangue.",
            "afinidade pelo CO2, facilitando seu transporte para eliminação nos pulmões.",
            "velocidade no transporte de gases, aumentando a eficiência de troca gasosa.",
            "solubilidade de gases no plasma, melhorando seu transporte nos tecidos."
        ],
        "correta" => 0,
        "comentario" => "Em grandes altitudes há menos oxigênio disponível. Uma hemoglobina com maior afinidade pelo O2 consegue captar mais desse gás mesmo com o ar rarefeito."
    ],

    [
        "id" => 8,
        "fonte" => "ENEM 2024 – PPL",
        "tema" => "Relações ecológicas",
        "adaptada" => true,
        "texto" => [
            "A mamangava (Xylocopa sp.) é a principal e mais eficiente polinizadora das flores do maracujazeiro. A abelha-europeia (Apis mellifera) também visita essas flores, mas apenas coleta o pólen, sem promover a fecundação."
        ],
        "imagem" => "",
        "comando" => "A relação ecológica estabelecida entre esses dois insetos é de",
        "alternativas" => [
            "predação.",
            "mutualismo.",
            "competição.",
            "inquilinismo.",
            "comensalismo."
        ],
        "correta" => 2,
        "comentario" => "As duas espécies de abelha disputam o mesmo recurso (o pólen das flores do maracujazeiro), o que caracteriza competição interespecífica."
    ],

    [
        "id" => 9,
        "fonte" => "ENEM 2024",
        "tema" => "Sucessão ecológica",
        "adaptada" => true,
        "texto" => [
            "Nos sistemas agroflorestais, diferentes espécies vegetais são introduzidas em etapas ao longo do tempo. Esse manejo provoca mudanças graduais na estrutura e na composição da vegetação, aumentando a diversidade de espécies na área."
        ],
        "imagem" => "",
        "comando" => "Esse conjunto de mudanças é análogo a qual processo natural?",
        "alternativas" => [
            "Rotação de culturas.",
            "Sucessão ecológica.",
            "Coevolução específica.",
            "Adaptação por seleção.",
            "Convergência adaptativa."
        ],
        "correta" => 1,
        "comentario" => "Mudanças graduais e ordenadas na composição de espécies de uma comunidade, com aumento da diversidade ao longo do tempo, caracterizam a sucessão ecológica."
    ],

    [
        "id" => 10,
        "fonte" => "ENEM 2023 – PPL",
        "tema" => "Relações ecológicas",
        "adaptada" => false,
        "texto" => [
            "O palmito juçara (Euterpe edulis) é uma planta que ocorre em áreas florestadas e produz frutos com tamanhos variados. Entretanto, pesquisadores perceberam que, em áreas nas quais as aves de maior porte foram extintas, as novas plantas produzem apenas frutos pequenos."
        ],
        "imagem" => "",
        "comando" => "Essa mudança apresentada pelas plantas é uma adaptação vantajosa porque os frutos",
        "alternativas" => [
            "atraem aves de pequeno porte, garantindo a ingestão dos frutos.",
            "são transportados pelo vento, aumentando a dispersão de sementes.",
            "facilitam a desidratação das sementes, prolongando sua durabilidade no solo.",
            "aceleram a germinação das sementes, favorecendo a colonização de outras áreas.",
            "armazenam menor quantidade de fotoassimilados, mantendo as reservas da planta."
        ],
        "correta" => 0,
        "comentario" => "Sem as aves grandes, apenas aves pequenas conseguem engolir os frutos e dispersar as sementes. Frutos menores garantem que a dispersão continue acontecendo."
    ],

    [
        "id" => 11,
        "fonte" => "ENEM 2023 – PPL",
        "tema" => "Ciclos biogeoquímicos",
        "adaptada" => false,
        "texto" => [
            "No ciclo biogeoquímico do nitrogênio participam vários organismos vivos. Um dos efeitos desse ciclo é aumentar a disponibilidade dos compostos nitrogenados no solo."
        ],
        "imagem" => "",
        "comando" => "As minhocas participam desse ciclo quando",
        "alternativas" => [
            "oxidam o nitrito a nitrato.",
            "reduzem o nitrito a amônia.",
            "oxidam a amônia liberada da matéria orgânica.",
            "transformam a matéria orgânica, liberando amônia.",
            "fixam o nitrogênio molecular presente no ar atmosférico."
        ],
        "correta" => 3,
        "comentario" => "As minhocas ingerem e fragmentam a matéria orgânica e, com seu metabolismo e excretas, liberam amônia no solo (amonificação). Oxidação e fixação são feitas por bactérias."
    ],

    [
        "id" => 12,
        "fonte" => "ENEM 2023 – PPL",
        "tema" => "Impactos ambientais",
        "adaptada" => false,
        "texto" => [
            "Atividades humanas como a construção de estradas e ferrovias e a expansão de áreas urbanas e agrícolas contribuem de forma determinante para a redução das áreas de vegetação original, em um processo conhecido como fragmentação do hábitat. Particularmente marcante em áreas de floresta, os impactos sofridos pela biota não estão restritos à redução do hábitat, mas também à modificação das suas características internas, como a diminuição da umidade do ar e o aumento nos níveis de luminosidade, temperatura e vento. Esse conjunto de alterações no fragmento é conhecido como “efeito de borda”, podendo se estender por vários metros em direção ao seu interior."
        ],
        "imagem" => "",
        "comando" => "As espécies vegetais diretamente prejudicadas por esse efeito são as",
        "alternativas" => [
            "exóticas.",
            "polinizadas pelo vento.",
            "com baixo potencial de dispersão.",
            "pertencentes à comunidade clímax.",
            "que apresentam grande área de vida."
        ],
        "correta" => 3,
        "comentario" => "As espécies da comunidade clímax são adaptadas ao interior sombreado e úmido da floresta. O aumento de luz, temperatura e vento na borda as prejudica diretamente."
    ],

    [
        "id" => 13,
        "fonte" => "ENEM 2023 – PPL",
        "tema" => "Mudanças climáticas",
        "adaptada" => false,
        "texto" => [
            "O Brasil foi o primeiro país a usar o álcool em larga escala como combustível de automóvel. Hoje, a indústria automobilística produz e equipa os automóveis com motores que funcionam tanto com gasolina como com álcool, ou ainda com uma mistura dos dois. No Brasil, o álcool é obtido principalmente da cana-de-açúcar, razão pela qual o classificam como biocombustível."
        ],
        "imagem" => "",
        "comando" => "Com essa atitude, contribui-se diretamente para a",
        "alternativas" => [
            "preservação de rios e lagos.",
            "preservação da biodiversidade.",
            "diminuição do buraco da camada de ozônio.",
            "preservação de espécies ameaçadas de extinção.",
            "diminuição da emissão efetiva de dióxido de carbono."
        ],
        "correta" => 4,
        "comentario" => "O CO2 liberado na queima do álcool foi retirado da atmosfera pela cana-de-açúcar durante a fotossíntese, o que reduz a emissão efetiva (líquida) de dióxido de carbono."
    ],

    [
        "id" => 14,
        "fonte" => "ENEM 2023 – PPL",
        "tema" => "Conservação da biodiversidade",
        "adaptada" => false,
        "texto" => [
            "A alteração de hábitats é uma causa bem documentada no que tange à redução de populações de anfíbios no mundo. Uma pesquisa feita em um remanescente da Floresta de Araucária, no município de Fazenda Rio Grande (PR), revelou que cerca de 40% das espécies de anfíbios anuros daquela região estão associadas à mata estudada, distribuindo-se desde o seu interior até o entorno."
        ],
        "imagem" => "",
        "comando" => "Qual é a proposta adequada para a conservação da diversidade biológica dos anuros na região citada?",
        "alternativas" => [
            "Reflorestar com eucaliptos, por crescerem rapidamente.",
            "Modificar geneticamente as espécies de anuros nativas.",
            "Soltar anuros criados em cativeiro no fragmento florestal.",
            "Introduzir novas espécies de árvores no fragmento florestal.",
            "Acabar com o desmatamento do remanescente de Floresta de Araucária."
        ],
        "correta" => 4,
        "comentario" => "Como a principal ameaça é a alteração do hábitat, a medida mais adequada é proteger o remanescente de floresta, impedindo o desmatamento."
    ],

    [
        "id" => 15,
        "fonte" => "ENEM 2023 – PPL",
        "tema" => "Impactos ambientais",
        "adaptada" => false,
        "texto" => [
            "Três inseticidas mortais para as abelhas serão proibidos na União Europeia (UE) durante dois anos, a partir de julho, anunciou a Comissão Europeia em 2013. A medida foi adotada por causa da morte de milhares de abelhas, insetos vitais para o nosso ecossistema, uma vez que desempenham um importante papel na agricultura e, portanto, na produção de alimentos para a humanidade."
        ],
        "imagem" => "",
        "comando" => "O impacto dos inseticidas mencionados na produção de alimentos agrícolas é causado pelo(a)",
        "alternativas" => [
            "redução da produção de mel.",
            "decréscimo da taxa de polinização.",
            "contaminação do solo com abelhas mortas.",
            "aumento de resíduos tóxicos nos alimentos.",
            "alteração da cadeia alimentar no ecossistema."
        ],
        "correta" => 1,
        "comentario" => "As abelhas são importantes polinizadoras. Com a morte delas, menos flores são polinizadas e, consequentemente, menos frutos e sementes são produzidos."
    ],

    [
        "id" => 16,
        "fonte" => "ENEM 2023",
        "tema" => "Impactos ambientais",
        "adaptada" => true,
        "texto" => [
            "A biorremediação utiliza organismos vivos para reduzir ou eliminar substâncias tóxicas do ambiente. O aguapé, uma planta aquática, é capaz de absorver metais tóxicos presentes em águas contaminadas."
        ],
        "imagem" => "",
        "comando" => "No interior dessa planta, esses contaminantes são",
        "alternativas" => [
            "digeridos por enzimas.",
            "armazenados em seus tecidos.",
            "eliminados pelos estômatos.",
            "metabolizados por glândulas.",
            "utilizados como fonte de energia."
        ],
        "correta" => 1,
        "comentario" => "Metais não são digeridos nem servem como fonte de energia: a planta os absorve e acumula em seus tecidos, retirando-os da água."
    ],

    [
        "id" => 17,
        "fonte" => "ENEM 2022",
        "tema" => "Conservação da biodiversidade",
        "adaptada" => true,
        "texto" => [
            "A introdução de espécies exóticas é uma das causas da perda de biodiversidade. A jaqueira, árvore nativa da Índia e do Sudeste Asiático, foi trazida para o Brasil e hoje se espalha por áreas de Mata Atlântica."
        ],
        "imagem" => "",
        "comando" => "Espécies como a jaqueira podem reduzir a biodiversidade local porque",
        "alternativas" => [
            "ocupam áreas de vegetação nativa, substituindo parcialmente a flora original.",
            "competem com os animais nativos pelos frutos, eliminando as espécies perdedoras.",
            "alteram os nichos ecológicos, aumentando o número de interações entre os organismos.",
            "reproduzem-se em altas taxas, ultrapassando a capacidade de suporte do ambiente.",
            "diminuem a competição entre polinizadores, favorecendo os dispersores de sementes nativas."
        ],
        "correta" => 0,
        "comentario" => "A espécie exótica invasora ocupa o espaço de plantas nativas e passa a substituir parte da flora original, reduzindo a diversidade local."
    ],

    [
        "id" => 18,
        "fonte" => "ENEM 2022 – PPL",
        "tema" => "Relações ecológicas",
        "adaptada" => true,
        "texto" => [
            "Em cupinzeiros do Cerrado vivem larvas de vaga-lumes que emitem luz durante a noite. A luminosidade atrai insetos voadores, que são capturados e devorados pelas larvas."
        ],
        "imagem" => "",
        "comando" => "A relação ecológica entre as larvas de vaga-lume e os insetos voadores é de",
        "alternativas" => [
            "predação.",
            "inquilinismo.",
            "mutualismo.",
            "parasitismo.",
            "competição."
        ],
        "correta" => 0,
        "comentario" => "As larvas capturam e matam os insetos para se alimentar: trata-se de predação."
    ],

    [
        "id" => 19,
        "fonte" => "ENEM 2022 – PPL",
        "tema" => "Impactos ambientais",
        "adaptada" => true,
        "texto" => [
            "Estudantes visitaram uma lagoa e observaram peixes mortos, intensa decomposição de matéria orgânica, grande quantidade de algas impedindo a passagem de luz, entrada de esgoto doméstico e bolhas de gás subindo do fundo. A lagoa estava isolada do oceano por um banco de areia."
        ],
        "imagem" => "",
        "comando" => "Essa lagoa está passando por um processo de",
        "alternativas" => [
            "autodepuração.",
            "potabilização.",
            "eutrofização.",
            "oxigenação.",
            "salinização."
        ],
        "correta" => 2,
        "comentario" => "O esgoto enriquece a água em nutrientes, as algas proliferam, bloqueiam a luz e morrem; a decomposição consome o oxigênio e os peixes morrem. Isso é eutrofização."
    ],

    [
        "id" => 20,
        "fonte" => "ENEM 2022 – PPL",
        "tema" => "Ciclos biogeoquímicos",
        "adaptada" => true,
        "texto" => [
            "A Floresta Amazônica transfere grandes quantidades de água para a atmosfera, formando correntes de umidade que levam chuvas a regiões distantes do país. O desmatamento prejudica esse transporte de água."
        ],
        "imagem" => "",
        "comando" => "Isso acontece porque, sem as árvores, a floresta",
        "alternativas" => [
            "diminui a quantidade de água armazenada nos caules.",
            "diminui o volume de solo ocupado pelas raízes.",
            "diminui a superfície total de transpiração.",
            "aumenta a evaporação dos rios e lagos.",
            "aumenta o assoreamento dos rios."
        ],
        "correta" => 2,
        "comentario" => "As folhas das árvores liberam vapor de água pela transpiração. Com o desmatamento, a superfície de transpiração diminui e menos umidade chega à atmosfera."
    ],

    [
        "id" => 21,
        "fonte" => "ENEM 2021",
        "tema" => "Relações ecológicas",
        "adaptada" => true,
        "texto" => [
            "As preguiças-gigantes, extintas há milhares de anos, alimentavam-se de frutos e sementes de plantas do Pantanal. Após a extinção desses animais, a flora da região sofreu modificações."
        ],
        "imagem" => "",
        "comando" => "Essa mudança na flora ocorreu em função",
        "alternativas" => [
            "da produção de flores.",
            "do tamanho das plantas.",
            "de fatores de disseminação das sementes.",
            "da quantidade de sementes por fruto.",
            "dos hábitats disponíveis para as plantas."
        ],
        "correta" => 2,
        "comentario" => "Ao comer frutos e eliminar as sementes em outros locais, as preguiças-gigantes atuavam como dispersoras. Sem elas, a disseminação das sementes mudou."
    ],

    [
        "id" => 22,
        "fonte" => "ENEM 2021",
        "tema" => "Impactos ambientais",
        "adaptada" => true,
        "texto" => [
            "Uma escola planejou implantar a coleta seletiva. O planejamento incluiu ações de sensibilização da comunidade escolar, capacitação da equipe de limpeza e instalação de coletores para os diferentes tipos de resíduos."
        ],
        "imagem" => "",
        "comando" => "Para que a coleta seletiva funcione, o que falta nesse planejamento?",
        "alternativas" => [
            "Realizar campanhas educativas de sensibilização em bairros vizinhos para fortalecer a coleta seletiva.",
            "Firmar parceria com a prefeitura ou cooperativa de catadores para recolhimento dos materiais recicláveis e destinação apropriada.",
            "Organizar visitas ao lixão ou aterro local para identificar aspectos importantes sobre a disposição final do lixo.",
            "Divulgar na rádio local, no jornal impresso e nas redes sociais que a escola está realizando a coleta seletiva.",
            "Colocar recipientes coletores de lixo reciclável fora da escola para entrega voluntária pela população."
        ],
        "correta" => 1,
        "comentario" => "Separar o lixo não basta: é preciso garantir que os recicláveis sejam recolhidos e tenham destinação adequada, o que exige parceria com a prefeitura ou cooperativas."
    ],

    [
        "id" => 23,
        "fonte" => "ENEM 2021 – PPL",
        "tema" => "Impactos ambientais",
        "adaptada" => true,
        "texto" => [
            "Algumas bactérias do gênero Pseudomonas são capazes de degradar o octano, um hidrocarboneto, utilizando-o como fonte de carbono e energia."
        ],
        "imagem" => "",
        "comando" => "Essas bactérias poderiam ser utilizadas na recuperação de áreas contaminadas por",
        "alternativas" => [
            "petróleo.",
            "pesticidas.",
            "lixo nuclear.",
            "gases tóxicos.",
            "metais pesados."
        ],
        "correta" => 0,
        "comentario" => "O octano é um hidrocarboneto presente no petróleo. Bactérias que o degradam podem ser usadas na biorremediação de áreas com derramamento de petróleo."
    ],

];
