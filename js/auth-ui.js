/* Conta do usuário compartilhada entre todas as páginas do EcoENEM.
   A navegação mobile é renderizada pelo PHP para não depender do JavaScript. */
(function () {
  const header = document.querySelector('header');
  if (!header || header.querySelector('.eco-user-menu')) return;

  // Exibe imediatamente um acesso ao perfil; a foto real substitui as iniciais
  // assim que os dados da conta forem carregados.
  const wrap = document.createElement('div');
  wrap.className = 'eco-user-menu';

  const trigger = document.createElement('a');
  trigger.className = 'eco-avatar glass';
  trigger.href = 'perfil.php';
  trigger.setAttribute('aria-label', 'Acessar meu perfil');
  trigger.textContent = '👤';

  const menu = document.createElement('div');
  menu.className = 'eco-user-dropdown glass';
  menu.hidden = true;

  const link = document.createElement('a');
  link.href = 'perfil.php';
  link.textContent = 'Meu perfil';

  const email = document.createElement('span');
  email.className = 'eco-user-email';
  const logout = document.createElement('button');
  logout.type = 'button';
  logout.textContent = 'Sair';
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

  (async function carregarConta() {
    try {
      if (!window.EcoAuth) throw new Error('Módulo de autenticação não carregado.');
      const client = await EcoAuth.client;
      const { data: { session } = {} } = await client.auth.getSession();
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

      const { data: profile, error } = await client
        .from('profiles')
        .select('nome,sobrenome,avatar_url,email')
        .eq('id', session.user.id)
        .maybeSingle();
      if (error) console.warn('Não foi possível carregar o perfil:', error);

      const nome = [profile?.nome, profile?.sobrenome].filter(Boolean).join(' ')
        || session.user.user_metadata?.nome || session.user.email || 'Usuário';
      const iniciais = (nome.trim().split(/\s+/).slice(0, 2).map(p => p[0]).join('') || 'U').toUpperCase();
      trigger.replaceChildren();
      if (profile?.avatar_url) {
        const img = document.createElement('img');
        img.src = profile.avatar_url;
        img.alt = 'Foto de perfil';
        img.onerror = () => { trigger.textContent = iniciais; };
        trigger.appendChild(img);
      } else {
        trigger.textContent = iniciais;
      }
      email.textContent = profile?.email || session.user.email || '';

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
    } catch (error) {
      console.error('Falha ao carregar a conta:', error);
      // O link de perfil continua acessível mesmo se o serviço de conta falhar.
    }
  })();
})();
