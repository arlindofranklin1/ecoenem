/* Menu de conta compartilhado entre as páginas protegidas. */
(async function(){
 try{
  const client=await EcoAuth.client;let {data:{session}}=await client.auth.getSession();
  if(!session){await EcoAuth.sync(null);location.replace('login.php');return;}
  await EcoAuth.sync(session);await EcoAuth.registrarAcesso();
  client.auth.onAuthStateChange(async(event,next)=>{if(event==='SIGNED_OUT'){await EcoAuth.sync(null);location.replace('login.php');}else if(next){EcoAuth.sync(next).catch(console.warn);}});
  const {data:profile}=await client.from('profiles').select('nome,sobrenome,avatar_url,email').eq('id',session.user.id).maybeSingle();
  const nome=profile?.nome||session.user.user_metadata?.nome||session.user.email||'Usuário';
  const iniciais=(nome.trim().split(/\s+/).slice(0,2).map(p=>p[0]).join('')||'U').toUpperCase();
  const wrap=document.createElement('div');wrap.className='eco-user-menu';
  const trigger=document.createElement('a');trigger.className='eco-avatar glass';trigger.href='perfil.php';trigger.setAttribute('aria-label','Acessar meu perfil');
  if(profile?.avatar_url){const img=document.createElement('img');img.src=profile.avatar_url;img.alt='Foto de perfil';trigger.appendChild(img);}else trigger.textContent=iniciais;
  const menu=document.createElement('div');menu.className='eco-user-dropdown glass';menu.hidden=true;
  const link=document.createElement('a');link.href='perfil.php';link.textContent='Meu perfil';
  const email=document.createElement('span');email.className='eco-user-email';email.textContent=profile?.email||session.user.email||'';
  const logout=document.createElement('button');logout.type='button';logout.textContent='Sair';logout.addEventListener('click',async()=>{logout.disabled=true;try{await client.auth.signOut();await EcoAuth.sync(null);location.href='login.php';}catch(e){logout.disabled=false;alert('Não foi possível sair. Tente novamente.');}});
  menu.append(email,link,logout);
  const menuTrigger=document.createElement('button');menuTrigger.type='button';menuTrigger.className='eco-account-options';menuTrigger.textContent='⋯';menuTrigger.setAttribute('aria-label','Mais opções da conta');
  menuTrigger.addEventListener('click',()=>{menu.hidden=!menu.hidden;});
  wrap.append(menuTrigger,trigger,menu);(document.querySelector('header')||document.body).appendChild(wrap);
  const current=(location.pathname.split('/').pop()||'index.php').toLowerCase();
  const items=[
   {href:'ecoia.php',label:'Eco IA',key:'ecoia.php',icon:'<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="24" cy="24" r="19" stroke="currentColor" stroke-width="1.6"/><path d="M5 24H43" stroke="currentColor" stroke-width="1.4"/><path d="M24 5C29 10 32 17 32 24C32 31 29 38 24 43" stroke="currentColor" stroke-width="1.4"/><path d="M24 5C19 10 16 17 16 24C16 31 19 38 24 43" stroke="currentColor" stroke-width="1.4"/><path d="M8 14H40M8 34H40" stroke="currentColor" stroke-width="1.2"/></svg>'},
   {href:'conteudo.php',label:'Conteúdo',key:'conteudo.php',icon:'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21z"/><path d="M4 5.5V21M8 7h8M8 11h8"/></svg>'},
   {href:'questoes.php',label:'Questões',key:'questoes.php',icon:'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6h11M9 12h11M9 18h11"/><path d="m3 6 1.2 1.2L6.5 5M3 12l1.2 1.2L6.5 11M3 18l1.2 1.2L6.5 17"/></svg>'}
  ];
  if(!document.querySelector('.eco-bottom-bar')){
   const bar=document.createElement('nav');bar.className='eco-bottom-bar';bar.setAttribute('aria-label','Navegação mobile');
   items.forEach(item=>{const a=document.createElement('a');a.href=item.href;a.className='eco-bottom-item'+(current===item.key?' ativo':'');if(current===item.key)a.setAttribute('aria-current','page');a.innerHTML=item.icon+'<span>'+item.label+'</span>';bar.appendChild(a);});
   document.body.appendChild(bar);
  }document.addEventListener('click',e=>{if(!wrap.contains(e.target))menu.hidden=true;});
 }catch(e){console.error('Falha ao iniciar autenticação:',e);document.body.insertAdjacentHTML('afterbegin','<div class="eco-auth-warning">Não foi possível validar sua sessão. Atualize a página ou entre novamente.</div>');}
})();
