/* Menu de conta compartilhado entre as páginas protegidas. */
(async function(){
 try{
  const client=await EcoAuth.client;let {data:{session}}=await client.auth.getSession();
  if(!session){await EcoAuth.sync(null);location.replace('login.php');return;}
  await EcoAuth.sync(session);await EcoAuth.registrarAcesso();
  client.auth.onAuthStateChange(async(event,next)=>{if(event==='SIGNED_OUT'){await EcoAuth.sync(null);location.replace('login.php');}else if(next){EcoAuth.sync(next).catch(console.warn);}});
  const nav=document.querySelector('header nav');if(!nav)return;
  const {data:profile}=await client.from('profiles').select('nome,sobrenome,avatar_url,email').eq('id',session.user.id).maybeSingle();
  const nome=profile?.nome||session.user.user_metadata?.nome||session.user.email||'Usuário';
  const iniciais=(nome.trim().split(/\s+/).slice(0,2).map(p=>p[0]).join('')||'U').toUpperCase();
  const wrap=document.createElement('div');wrap.className='eco-user-menu';
  const trigger=document.createElement('button');trigger.className='eco-avatar glass';trigger.type='button';trigger.setAttribute('aria-label','Abrir menu do perfil');
  if(profile?.avatar_url){const img=document.createElement('img');img.src=profile.avatar_url;img.alt='Foto de perfil';trigger.appendChild(img);}else trigger.textContent=iniciais;
  const menu=document.createElement('div');menu.className='eco-user-dropdown glass';menu.hidden=true;
  const link=document.createElement('a');link.href='perfil.php';link.textContent='Meu perfil';
  const email=document.createElement('span');email.className='eco-user-email';email.textContent=profile?.email||session.user.email||'';
  const logout=document.createElement('button');logout.type='button';logout.textContent='Sair';logout.addEventListener('click',async()=>{logout.disabled=true;try{await client.auth.signOut();await EcoAuth.sync(null);location.href='login.php';}catch(e){logout.disabled=false;alert('Não foi possível sair. Tente novamente.');}});
  menu.append(email,link,logout);wrap.append(trigger,menu);nav.appendChild(wrap);
  trigger.addEventListener('click',()=>{menu.hidden=!menu.hidden;});document.addEventListener('click',e=>{if(!wrap.contains(e.target))menu.hidden=true;});
 }catch(e){console.error('Falha ao iniciar autenticação:',e);document.body.insertAdjacentHTML('afterbegin','<div class="eco-auth-warning">Não foi possível validar sua sessão. Atualize a página ou entre novamente.</div>');}
})();
