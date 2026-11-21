/* Cliente Supabase único e compartilhado pelo EcoENEM. */
(function(){
 const configPromise=fetch('/auth/config.php',{cache:'no-store'}).then(r=>r.json()).then(c=>{if(!c.url||!c.anonKey)throw new Error(c.erro||'Configuração do Supabase ausente.');return c;});
 let clientPromise;
 window.EcoAuth={
  get client(){if(!clientPromise)clientPromise=configPromise.then(async c=>{const cliente=window.supabase.createClient(c.url,c.anonKey,{auth:{persistSession:true,autoRefreshToken:true,detectSessionInUrl:true}});const sessao=window.ECOENEM_SERVER_SESSION;if(sessao?.access_token&&sessao?.refresh_token){const {error}=await cliente.auth.setSession(sessao);if(error)console.warn('Não foi possível sincronizar a sessão do servidor:',error);}return cliente;});return clientPromise;},
  async sync(session){const r=await fetch('/auth/session_sync.php',{method:'POST',headers:{'Content-Type':'application/json'},credentials:'same-origin',body:JSON.stringify({access_token:session?.access_token||'',refresh_token:session?.refresh_token||''})});if(!r.ok)throw new Error('Não foi possível sincronizar a sessão segura.');return r;},
  async rpc(nome,args={}){const c=await this.client;const {error}=await c.rpc(nome,args);if(error)throw error;},
  async registrarAcesso(){try{await this.rpc('registrar_acesso_diario');}catch(e){console.warn('Não foi possível atualizar o acesso diário:',e);}},
  mensagemErro(e){const m=(e?.message||'').toLowerCase();if(m.includes('invalid login credentials'))return 'E-mail ou senha incorretos.';if(m.includes('already registered'))return 'Este e-mail já está cadastrado.';if(m.includes('email not confirmed'))return 'Confirme seu e-mail antes de entrar.';if(m.includes('password'))return 'A senha não atende aos requisitos de segurança.';return e?.message||'Não foi possível concluir a operação. Verifique sua conexão e tente novamente.';}
 };
 document.addEventListener('click',e=>{const b=e.target.closest('.toggle-password');if(!b)return;const input=document.getElementById(b.dataset.target);input.type=input.type==='password'?'text':'password';b.setAttribute('aria-label',input.type==='password'?'Mostrar senha':'Ocultar senha');});
})();
