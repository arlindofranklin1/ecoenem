/* Navegação mobile e menu de conta compartilhados entre as páginas protegidas. */
(function () {
  let current = (location.pathname.split('/').pop() || 'index.php').toLowerCase();
  if (current === '') current = 'index.php';
  const items = [
    {
      href: 'index.php', label: 'Início', key: 'index.php',
      icon: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 11.5 12 4l9 7.5"/><path d="M5.5 10v10h13V10"/><path d="M10 20v-6h4v6"/></svg>'
    },
    {
      href: 'ecoia.php', label: 'EcoIA', key: 'ecoia.php',
      // Ícone de globo reutilizado no recurso da EcoIA.
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="24" cy="24" r="19"/><path d="M5 24H43M24 5C29 10 32 17 32 24C32 31 29 38 24 43M24 5C19 10 16 17 16 24C16 31 19 38 24 43M8 14H40M8 34H40"/></svg>'
    },
    {
      href: 'conteudo.php', label: 'Conteúdo', key: 'conteudo.php',
      icon: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21z"/><path d="M4 5.5V21M8 7h8M8 11h8"/></svg>'
    },
    {
      href: 'questoes.php', label: 'Questões', key: 'questoes.php',
      icon: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6h11M9 12h11M9 18h11"/><path d="m3 6 1.2 1.2L6.5 5M3 12l1.2 1.2L6.5 11M3 18l1.2 1.2L6.5 17"/></svg>'
    },
    {
      href: 'simulado.php', label: 'Simuladão', key: 'simulado.php',
      icon: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 4h8M9 3h6v3H9zM6 5H4v16h16V5h-2"/><path d="m8 12 2 2 5-5M8 17h8"/></svg>'
    },
    {
      href: 'sobre.php', label: 'Sobre', key: 'sobre.php',
      icon: '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/></svg>'
    }
  ];

  // Cria a barra antes de consultar a sessão: a navegação não depende
  // do carregamento do perfil ou de uma resposta do Supabase.
  if (!document.querySelector('.eco-bottom-bar') && document.body) {
    const bar = document.createElement('div');
    // div (e não nav) para não herdar as regras antigas de "nav" das folhas de estilo de cada página.
    bar.setAttribute('role', 'navigation');
    bar.className = 'eco-bottom-bar';
    bar.setAttribute('aria-label', 'Navegação principal mobile');
    items.forEach(item => {
      const link = document.createElement('a');
      link.href = item.href;
      link.className = 'eco-bottom-item' + (current === item.key ? ' ativo' : '');
      if (current === item.key) {
        link.setAttribute('aria-current', 'page');
      }
      link.innerHTML = item.icon + '<span>' + item.label + '</span>';
      bar.appendChild(link);
    });
    document.body.appendChild(bar);
  }

  // A foto de perfil é criada separadamente da barra de navegação.
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
      const header = document.querySelector('header');
      if (!header || header.querySelector('.eco-user-menu')) return;

      const wrap = document.createElement('div');
      wrap.className = 'eco-user-menu';

      const trigger = document.createElement('a');
      trigger.className = 'eco-avatar glass';
      trigger.href = 'perfil.php';
      trigger.setAttribute('aria-label', 'Acessar meu perfil');
      if (profile?.avatar_url) {
        const img = document.createElement('img');
        img.src = profile.avatar_url;
        img.alt = 'Foto de perfil';
        trigger.appendChild(img);
      } else {
        trigger.textContent = iniciais;
      }

      trigger.addEventListener('click', event => {
        if (window.matchMedia('(max-width: 768px)').matches) {
          event.preventDefault();
          menu.hidden = !menu.hidden;
        }
      });

      const menu = document.createElement('div');
      menu.className = 'eco-user-dropdown glass';
      menu.hidden = true;
      const link = document.createElement('a');
      link.href = 'perfil.php';
      link.textContent = 'Meu perfil';
      const email = document.createElement('span');
      email.className = 'eco-user-email';
      email.textContent = profile?.email || session.user.email || '';
      const logout = document.createElement('button');
      logout.type = 'button';
      logout.textContent = 'Sair';
      logout.addEventListener('click', async () => {
        logout.disabled = true;
        try {
          await client.auth.signOut();
          await EcoAuth.sync(null);
          location.href = 'login.php';
        } catch (e) {
          logout.disabled = false;
          alert('Não foi possível sair. Tente novamente.');
        }
      });
      menu.append(email, link, logout);

      const menuTrigger = document.createElement('button');
      menuTrigger.type = 'button';
      menuTrigger.className = 'eco-account-options';
      menuTrigger.textContent = '⋯';
      menuTrigger.setAttribute('aria-label', 'Mais opções da conta');
      menuTrigger.addEventListener('click', () => { menu.hidden = !menu.hidden; });

      wrap.append(menuTrigger, trigger, menu);
      header.appendChild(wrap);
      document.addEventListener('click', event => {
        if (!wrap.contains(event.target)) menu.hidden = true;
      });
    } catch (error) {
      console.error('Falha ao iniciar autenticação:', error);
      // A barra de navegação continua disponível mesmo se o perfil falhar.
      if (!document.querySelector('.eco-avatar')) {
        const header = document.querySelector('header');
        if (header) {
          const fallback = document.createElement('a');
          fallback.className = 'eco-avatar glass eco-avatar-fallback';
          fallback.href = 'perfil.php';
          fallback.textContent = '👤';
          fallback.setAttribute('aria-label', 'Acessar meu perfil');
          const wrap = document.createElement('div');
          wrap.className = 'eco-user-menu';
          wrap.appendChild(fallback);
          header.appendChild(wrap);
        }
      }
    }
  })();
})();
