/* Navegação mobile (Bottom Bar) e menu de conta compartilhados entre as páginas protegidas. */
(function () {
  const current = (location.pathname.split('/').pop() || 'index.php').toLowerCase();

  // Ícone de globo da EcoIA: o mesmo desenho do convite "Perguntar para EcoIA" da página de Questões.
  // O traço é dobrado porque o viewBox (48) é o dobro dos demais ícones (24): a espessura final fica igual.
  const ICONE_ECOIA =
    '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' +
      '<circle cx="24" cy="24" r="19" stroke="currentColor" stroke-width="3.6"/>' +
      '<path d="M5 24H43" stroke="currentColor" stroke-width="3.2"/>' +
      '<path d="M24 5C29 10 32 17 32 24C32 31 29 38 24 43" stroke="currentColor" stroke-width="3.2"/>' +
      '<path d="M24 5C19 10 16 17 16 24C16 31 19 38 24 43" stroke="currentColor" stroke-width="3.2"/>' +
      '<path d="M8 14H40M8 34H40" stroke="currentColor" stroke-width="2.8"/>' +
    '</svg>';

  // Ordem obrigatória: EcoIA, Conteúdo, Questões.
  const items = [
    { href: 'ecoia.php', label: 'EcoIA', key: 'ecoia.php', icon: ICONE_ECOIA },
    {
      href: 'conteudo.php', label: 'Conteúdo', key: 'conteudo.php',
      // Livro aberto: materiais de estudo.
      icon: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 6.5C10.3 5.2 7.9 4.5 4 4.5v13c3.9 0 6.3.7 8 2 1.7-1.3 4.1-2 8-2v-13c-3.9 0-6.3.7-8 2Z"/><path d="M12 6.5v13"/></svg>'
    },
    {
      href: 'questoes.php', label: 'Questões', key: 'questoes.php',
      // Lista com marcações: exercícios.
      icon: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 6.5h10M10 12h10M10 17.5h10"/><path d="m3.5 6.5 1.3 1.3L7.2 5.3M3.5 12l1.3 1.3 2.4-2.5M3.5 17.5l1.3 1.3 2.4-2.5"/></svg>'
    }
  ];

  // Cria a barra antes de consultar a sessão: a navegação não depende do Supabase.
  if (!document.querySelector('.eco-bottom-bar') && document.body) {
    const bar = document.createElement('nav');
    bar.className = 'eco-bottom-bar';
    bar.setAttribute('aria-label', 'Navegação principal');
    items.forEach(item => {
      const link = document.createElement('a');
      link.href = item.href;
      link.className = 'eco-bottom-item' + (current === item.key ? ' ativo' : '');
      if (current === item.key) link.setAttribute('aria-current', 'page');
      link.innerHTML = item.icon + '<span>' + item.label + '</span>';
      bar.appendChild(link);
    });
    document.body.appendChild(bar);
  }

  /* ---------- Menu de conta com a foto de perfil ---------- */
  const ICONE_PESSOA = '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8.5" r="3.8"/><path d="M4.5 20c1.3-3.6 4.1-5.4 7.5-5.4s6.2 1.8 7.5 5.4"/></svg>';
  const header = document.querySelector('header');
  let wrap = null, avatar = null, menu = null, emailEl = null;

  function definirFoto(url, iniciais) {
    if (!avatar) return;
    avatar.textContent = '';
    if (url) {
      const img = document.createElement('img');
      img.src = url;
      img.alt = 'Foto de perfil';
      img.decoding = 'async';
      img.addEventListener('error', () => { avatar.textContent = iniciais || ''; if (!iniciais) avatar.innerHTML = ICONE_PESSOA; });
      avatar.appendChild(img);
    } else if (iniciais) {
      avatar.textContent = iniciais;
    } else {
      avatar.innerHTML = ICONE_PESSOA;
    }
  }

  // A foto já ocupa seu lugar no cabeçalho enquanto o perfil carrega, evitando saltos no layout.
  if (header && !header.querySelector('.eco-user-menu')) {
    wrap = document.createElement('div');
    wrap.className = 'eco-user-menu';

    avatar = document.createElement('a');
    avatar.className = 'eco-avatar glass';
    avatar.href = 'perfil.php';
    avatar.setAttribute('aria-label', 'Acessar meu perfil');
    if (current === 'perfil.php') avatar.setAttribute('aria-current', 'page');
    avatar.innerHTML = ICONE_PESSOA;

    menu = document.createElement('div');
    menu.className = 'eco-user-dropdown glass';
    menu.id = 'ecoUserDropdown';
    menu.hidden = true;

    emailEl = document.createElement('span');
    emailEl.className = 'eco-user-email';

    const perfilLink = document.createElement('a');
    perfilLink.href = 'perfil.php';
    perfilLink.textContent = 'Meu perfil';

    // No celular o menu superior fica oculto; estas páginas continuam acessíveis por aqui.
    const extras = [['simulado.php', 'Simuladão'], ['sobre.php', 'Sobre']].map(([href, texto]) => {
      const a = document.createElement('a');
      a.href = href;
      a.textContent = texto;
      a.className = 'eco-dropdown-mobile';
      return a;
    });
    const separador = document.createElement('hr');
    separador.className = 'eco-dropdown-mobile';

    const logout = document.createElement('button');
    logout.type = 'button';
    logout.textContent = 'Sair';
    logout.addEventListener('click', async () => {
      logout.disabled = true;
      try {
        const client = await EcoAuth.client;
        await client.auth.signOut();
        await EcoAuth.sync(null);
        location.href = 'login.php';
      } catch (e) {
        logout.disabled = false;
        alert('Não foi possível sair. Tente novamente.');
      }
    });
    menu.append(emailEl, perfilLink, ...extras, separador, logout);

    const menuTrigger = document.createElement('button');
    menuTrigger.type = 'button';
    menuTrigger.className = 'eco-account-options';
    menuTrigger.textContent = '⋯';
    menuTrigger.setAttribute('aria-label', 'Mais opções da conta');
    menuTrigger.setAttribute('aria-haspopup', 'true');
    menuTrigger.setAttribute('aria-controls', 'ecoUserDropdown');
    menuTrigger.setAttribute('aria-expanded', 'false');
    menuTrigger.addEventListener('click', () => {
      menu.hidden = !menu.hidden;
      menuTrigger.setAttribute('aria-expanded', String(!menu.hidden));
    });

    wrap.append(menuTrigger, avatar, menu);
    header.appendChild(wrap);
    document.addEventListener('click', event => {
      if (!wrap.contains(event.target)) { menu.hidden = true; menuTrigger.setAttribute('aria-expanded', 'false'); }
    });
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape' && !menu.hidden) { menu.hidden = true; menuTrigger.setAttribute('aria-expanded', 'false'); menuTrigger.focus(); }
    });
  }

  // A página de perfil avisa quando a foto é trocada, para o cabeçalho acompanhar.
  window.addEventListener('ecoenem:avatar', event => definirFoto(event.detail && event.detail.url, event.detail && event.detail.iniciais));

  (async function iniciarConta() {
    try {
      const client = await EcoAuth.client;
      const { data: { session } } = await client.auth.getSession();
      if (!session) {
        await EcoAuth.sync(null);
        location.replace('login.php');
        return;
      }
      await EcoAuth.sync(session);
      await EcoAuth.registrarAcesso();
      client.auth.onAuthStateChange(async (event, next) => {
        if (event === 'SIGNED_OUT') {
          await EcoAuth.sync(null);
          location.replace('login.php');
        } else if (next) {
          EcoAuth.sync(next).catch(console.warn);
        }
      });

      const { data: profile } = await client
        .from('profiles')
        .select('nome,sobrenome,avatar_url,email')
        .eq('id', session.user.id)
        .maybeSingle();

      const nome = profile?.nome || session.user.user_metadata?.nome || session.user.email || 'Usuário';
      const iniciais = (nome.trim().split(/\s+/).slice(0, 2).map(p => p[0]).join('') || 'U').toUpperCase();
      definirFoto(profile?.avatar_url, iniciais);
      if (emailEl) emailEl.textContent = profile?.email || session.user.email || '';
    } catch (error) {
      // A barra e o acesso ao perfil continuam disponíveis mesmo se o perfil falhar.
      console.error('Falha ao iniciar autenticação:', error);
    }
  })();
})();
