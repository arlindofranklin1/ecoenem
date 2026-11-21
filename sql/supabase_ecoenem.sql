-- EcoENEM: execute este script no SQL Editor do Supabase.
-- Tabelas privadas: cada estudante acessa somente o próprio registro.
create extension if not exists pgcrypto;

create table if not exists public.profiles (
  id uuid primary key references auth.users(id) on delete cascade,
  nome text not null default '',
  sobrenome text not null default '',
  email text not null,
  avatar_url text,
  biografia varchar(300) not null default '',
  criado_em timestamptz not null default now()
);

create table if not exists public.user_stats (
  id uuid primary key references auth.users(id) on delete cascade,
  questoes_respondidas integer not null default 0 check (questoes_respondidas >= 0),
  simulados_feitos integer not null default 0 check (simulados_feitos >= 0),
  uso_ia integer not null default 0 check (uso_ia >= 0),
  dias_de_uso integer not null default 0 check (dias_de_uso >= 0),
  ultimo_acesso date,
  sequencia_atual integer not null default 0 check (sequencia_atual >= 0),
  atualizado_em timestamptz not null default now()
);

create or replace function public.ecoenem_novo_usuario()
returns trigger language plpgsql security definer set search_path = public, auth
as $$
begin
  insert into public.profiles(id,nome,sobrenome,email)
  values (new.id, coalesce(new.raw_user_meta_data->>'nome',''), coalesce(new.raw_user_meta_data->>'sobrenome',''), coalesce(new.email,''))
  on conflict (id) do update set email=excluded.email;
  insert into public.user_stats(id) values(new.id) on conflict (id) do nothing;
  return new;
end;
$$;
drop trigger if exists on_auth_user_created_ecoenem on auth.users;
create trigger on_auth_user_created_ecoenem after insert on auth.users
for each row execute procedure public.ecoenem_novo_usuario();

alter table public.profiles enable row level security;
alter table public.user_stats enable row level security;
revoke all on public.profiles from anon;
revoke all on public.user_stats from anon;
grant select, update on public.profiles to authenticated;
grant select on public.user_stats to authenticated;
drop policy if exists "Usuário lê o próprio perfil" on public.profiles;
create policy "Usuário lê o próprio perfil" on public.profiles for select to authenticated using (auth.uid() = id);
drop policy if exists "Usuário atualiza o próprio perfil" on public.profiles;
create policy "Usuário atualiza o próprio perfil" on public.profiles for update to authenticated using (auth.uid() = id) with check (auth.uid() = id);
drop policy if exists "Usuário lê as próprias estatísticas" on public.user_stats;
create policy "Usuário lê as próprias estatísticas" on public.user_stats for select to authenticated using (auth.uid() = id);

-- RPC de incremento atômico: não aceita nome arbitrário de coluna.
create or replace function public.incrementar_estatistica(p_campo text)
returns void language plpgsql security definer set search_path = public, auth
as $$
begin
  if auth.uid() is null then raise exception 'Sessão não autenticada'; end if;
  if p_campo not in ('questoes_respondidas','simulados_feitos','uso_ia') then raise exception 'Contador inválido'; end if;
  insert into public.user_stats(id) values(auth.uid()) on conflict (id) do nothing;
  if p_campo = 'questoes_respondidas' then
    update public.user_stats set questoes_respondidas=questoes_respondidas+1, atualizado_em=now() where id=auth.uid();
  elsif p_campo = 'simulados_feitos' then
    update public.user_stats set simulados_feitos=simulados_feitos+1, atualizado_em=now() where id=auth.uid();
  else
    update public.user_stats set uso_ia=uso_ia+1, atualizado_em=now() where id=auth.uid();
  end if;
end;
$$;
revoke all on function public.incrementar_estatistica(text) from public;
grant execute on function public.incrementar_estatistica(text) to authenticated;

create or replace function public.registrar_acesso_diario()
returns void language plpgsql security definer set search_path = public, auth
as $$
declare hoje date := (now() at time zone 'America/Recife')::date;
begin
  if auth.uid() is null then raise exception 'Sessão não autenticada'; end if;
  insert into public.user_stats(id) values(auth.uid()) on conflict (id) do nothing;
  update public.user_stats set
    dias_de_uso = dias_de_uso + case when ultimo_acesso is distinct from hoje then 1 else 0 end,
    sequencia_atual = case
      when ultimo_acesso = hoje then sequencia_atual
      when ultimo_acesso = hoje - 1 then sequencia_atual + 1
      else 1 end,
    ultimo_acesso = hoje,
    atualizado_em = now()
  where id=auth.uid();
end;
$$;
revoke all on function public.registrar_acesso_diario() from public;
grant execute on function public.registrar_acesso_diario() to authenticated;

-- Storage: bucket público para leitura das imagens; escrita restrita à pasta do próprio UID.
insert into storage.buckets(id,name,public,file_size_limit,allowed_mime_types)
values ('avatars','avatars',true,2097152,array['image/jpeg','image/png','image/webp'])
on conflict (id) do update set public=true,file_size_limit=2097152,allowed_mime_types=array['image/jpeg','image/png','image/webp'];
drop policy if exists "Avatares públicos podem ser visualizados" on storage.objects;
create policy "Avatares públicos podem ser visualizados" on storage.objects for select using (bucket_id='avatars');
drop policy if exists "Usuário envia o próprio avatar" on storage.objects;
create policy "Usuário envia o próprio avatar" on storage.objects for insert to authenticated with check (bucket_id='avatars' and (storage.foldername(name))[1]=auth.uid()::text);
drop policy if exists "Usuário atualiza o próprio avatar" on storage.objects;
create policy "Usuário atualiza o próprio avatar" on storage.objects for update to authenticated using (bucket_id='avatars' and (storage.foldername(name))[1]=auth.uid()::text) with check (bucket_id='avatars' and (storage.foldername(name))[1]=auth.uid()::text);
drop policy if exists "Usuário remove o próprio avatar" on storage.objects;
create policy "Usuário remove o próprio avatar" on storage.objects for delete to authenticated using (bucket_id='avatars' and (storage.foldername(name))[1]=auth.uid()::text);
