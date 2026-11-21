# EcoENEM

## Integrantes

- Arlindo Franklim Borborema Neto
- Lúcia de Fátima Pinheiro da Nóbrega
- Manuela Raquelly Ferreira do Nascimento

---

# Link da aplicação

https://ecoenem.onrender.com

---

# Tema Abordado

O "EcoENEM" é uma plataforma educacional desenvolvida para auxiliar estudantes na preparação para o Exame Nacional do Ensino Médio (ENEM), com foco nos conteúdos de "Ecologia" da disciplina de Biologia.

O projeto reúne recursos interativos e tecnológicos para facilitar o aprendizado, tornando o estudo mais dinâmico, intuitivo e acessível.

---

# Funcionalidades do Sistema

## Globo Terrestre 3D

A página inicial apresenta um globo terrestre em 3D que representa a diversidade ecológica do planeta, proporcionando uma experiência visual e interativa ao usuário.

## EcoIA

O sistema conta com a **EcoIA**, uma inteligência artificial desenvolvida utilizando a API do "OpenRouter".

A EcoIA tem como objetivo responder dúvidas relacionadas aos conteúdos de Ecologia estudados para o ENEM, oferecendo respostas rápidas e auxiliando o processo de aprendizagem.

## Página "Conteúdo"

Nesta página o usuário encontra diversos conteúdos sobre Ecologia, incluindo:

- Conceitos fundamentais;
- Explicações dos principais temas cobrados no ENEM;
- Videoaulas do YouTube para aprofundamento dos estudos.

## Página "Questões"

A página de questões permite que o estudante teste seus conhecimentos por meio de exercícios inspirados em provas do ENEM.

Ao responder cada questão, o sistema:

- informa se a resposta está correta ou incorreta;
- apresenta uma explicação justificando o resultado;
- calcula, ao final, o percentual de acertos obtido pelo usuário.

## Página "Sobre"

Apresenta informações sobre o projeto, incluindo:

- desenvolvedores;
- objetivo do sistema;
- tecnologias utilizadas;
- referências bibliográficas e de conteúdo.

---

# Tecnologias Utilizadas

- HTML5
- CSS3
- JavaScript
- PHP
- API OpenRouter (EcoIA)
- YouTube (videoaulas)
- Three.js (globo 3D)

---

# Instruções Básicas de Uso

1. Acesse a página inicial do sistema.
2. Explore o globo terrestre em 3D.
3. Navegue até a página "Conteúdo" para estudar os principais temas de Ecologia.
4. Utilize a "EcoIA" para esclarecer dúvidas sobre os assuntos estudados.
5. Acesse a página "Questões" para responder aos exercícios e verificar seu desempenho.
6. Consulte a página "Sobre" para conhecer mais detalhes do projeto e suas referências.

---

# Objetivo

O objetivo do "EcoENEM" é oferecer uma plataforma de estudos voltada para Ecologia, unindo conteúdos didáticos, inteligência artificial e atividades práticas para tornar a preparação para o ENEM mais eficiente, interativa e acessível aos estudantes.

# Autenticação e perfil (Supabase)

A versão atual inclui login, cadastro, perfil e estatísticas pessoais via Supabase Auth, Database e Storage.

## Variáveis de ambiente no Render

Configure no serviço Web as variáveis SUPABASE_URL e SUPABASE_ANON_KEY (ou publishable key). Não configure a service_role no navegador nem a inclua no repositório. Mantenha também ECOIA_GATEWAY_SECRET já utilizado pela EcoIA.

## Banco de dados

Execute o arquivo sql/supabase_ecoenem.sql no SQL Editor do projeto Supabase. O script cria profiles, user_stats, trigger de cadastro, RPCs, políticas RLS e o bucket avatars.

## Teste

1. Configure as variáveis no Render e faça novo deploy.
2. Execute o SQL no Supabase.
3. Acesse /login.php e crie uma conta em /cadastro.php.
4. Se a confirmação de e-mail estiver habilitada, confirme a mensagem recebida antes de entrar.
5. Entre, navegue pelas páginas, responda questões, use a EcoIA e gere um PDF de simulado.
6. Abra /perfil.php para conferir foto, biografia e estatísticas.

As estatísticas são atualizadas por RPCs autenticadas. A geração do PDF de um mesmo simulado é contabilizada uma única vez na sessão.
