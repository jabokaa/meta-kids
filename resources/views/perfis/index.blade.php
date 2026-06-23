@extends('layouts.app')

@section('title', 'Perfis — Meta Kids')

@push('styles')
<style>
  .perfis-wrapper { position:relative; z-index:2; max-width:600px; margin:0 auto; padding:40px 20px 80px; }
  .perfis-header  { text-align:center; margin-bottom:36px; }
  .perfis-title   { font-family:'Baloo 2',sans-serif; font-weight:700; font-size:30px; color:#E2547F; margin:0 0 6px; }
  .perfis-sub     { font-size:15px; font-weight:500; color:#B07A45; margin:0; }

  .perfis-grid    { display:grid; grid-template-columns:repeat(auto-fill, minmax(140px, 1fr)); gap:16px; }

  /* card wrapper mantém a célula no grid */
  .perfil-item    { display:flex; flex-direction:column; gap:0; }
  .perfil-card    { background:#fff; border-radius:24px 24px 0 0; padding:20px 16px 16px; text-align:center; box-shadow:0 4px 20px rgba(180,120,60,.12); border:3px solid transparent; border-bottom:none; transition:all .18s ease; text-decoration:none; display:block; }
  .perfil-card:hover { transform:translateY(-4px); box-shadow:0 10px 30px rgba(226,84,127,.2); }
  .perfil-avatar  { width:72px; height:72px; border-radius:50%; margin:0 auto 12px; display:flex; align-items:center; justify-content:center; font-size:36px; border:4px solid #fff; box-shadow:0 4px 14px rgba(0,0,0,.1); overflow:hidden; }
  .perfil-avatar img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
  .perfil-nome    { font-family:'Baloo 2',sans-serif; font-weight:700; font-size:16px; color:#3D2B1A; margin:0 0 4px; }
  .perfil-idade   { font-size:13px; font-weight:500; color:#B07A45; margin:0; }

  /* barra de ações abaixo do card */
  .perfil-actions { display:flex; border-radius:0 0 24px 24px; overflow:hidden; box-shadow:0 6px 20px rgba(180,120,60,.12); border:3px solid transparent; border-top:none; }
  .btn-acao       { flex:1; padding:9px 4px; border:none; cursor:pointer; font-size:17px; display:flex; align-items:center; justify-content:center; gap:4px; font-family:'Fredoka',sans-serif; font-weight:600; font-size:12px; transition:background .15s; }
  .btn-editar     { background:#FFF0D6; color:#B07A45; border-right:1.5px solid #F0E2C8; }
  .btn-editar:hover { background:#FFE2A8; }
  .btn-meta       { background:#FFF0F5; color:#E2547F; }
  .btn-meta:hover { background:#FFD6E7; }

  /* card de adicionar */
  .add-card       { background:#fff8f0; border-radius:24px; padding:24px 16px 20px; text-align:center; cursor:pointer; box-shadow:0 4px 20px rgba(180,120,60,.1); border:3px dashed #F0C890; transition:all .18s ease; display:flex; flex-direction:column; align-items:center; justify-content:center; min-height:148px; }
  .add-card:hover { transform:translateY(-4px); box-shadow:0 10px 30px rgba(226,84,127,.15); border-color:#E2547F; background:#fff0f5; }
  .add-icon       { width:56px; height:56px; border-radius:50%; background:linear-gradient(150deg,#FF9EC0,#FF6F91); display:flex; align-items:center; justify-content:center; font-size:28px; color:#fff; font-weight:700; margin-bottom:10px; box-shadow:0 4px 12px rgba(226,84,127,.3); }
  .add-label      { font-family:'Baloo 2',sans-serif; font-weight:700; font-size:14px; color:#E2547F; }

  .empty-state    { text-align:center; padding:60px 20px; background:#fff; border-radius:28px; box-shadow:0 4px 20px rgba(180,120,60,.1); border:2px dashed #F0E2C8; margin-bottom:16px; }
  .logout-form    { text-align:center; margin-top:32px; }
  .btn-logout     { font-family:'Fredoka',sans-serif; font-size:15px; font-weight:600; color:#B07A45; background:none; border:2px solid #F0E2C8; border-radius:14px; padding:10px 28px; cursor:pointer; transition:background .14s; }
  .btn-logout:hover { background:#FBF4E6; }

  /* ── Modais ── */
  .modal-overlay  { position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:100; display:none; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(3px); }
  .modal-overlay.open { display:flex; animation:fadeIn .18s ease; }
  .modal-box      { background:#fff; border-radius:28px; padding:32px 28px 28px; width:100%; max-width:420px; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.22); animation:popIn .2s ease; position:relative; }
  .modal-title    { font-family:'Baloo 2',sans-serif; font-weight:700; font-size:22px; color:#E2547F; margin:0 0 24px; text-align:center; }
  .modal-close    { position:absolute; top:16px; right:20px; font-size:22px; cursor:pointer; color:#B07A45; background:none; border:none; line-height:1; padding:4px; }
  .modal-close:hover { color:#E2547F; }

  .avatar-preview { width:88px; height:88px; border-radius:50%; margin:0 auto 8px; display:flex; align-items:center; justify-content:center; font-size:44px; border:4px solid #F0E2C8; box-shadow:0 4px 16px rgba(0,0,0,.1); cursor:pointer; transition:transform .15s; overflow:hidden; }
  .avatar-preview:hover { transform:scale(1.06); }
  .avatar-preview img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
  .foto-hint      { text-align:center; font-size:12px; font-weight:600; color:#C7A77C; margin-bottom:20px; cursor:pointer; }
  .foto-hint:hover { color:#E2547F; }

  .field-label    { font-size:13px; font-weight:700; color:#8A6A45; margin-bottom:6px; display:block; }
  .field-wrap     { margin-bottom:16px; }
  .kid-input      { width:100%; padding:13px 16px; border-radius:16px; border:2px solid #F0E2C8; background:#FFF8F0; font-family:'Fredoka',sans-serif; font-size:16px; color:#3D2B1A; transition:box-shadow .14s; }
  .kid-select     { width:100%; padding:13px 16px; border-radius:16px; border:2px solid #F0E2C8; background:#FFF8F0; font-family:'Fredoka',sans-serif; font-size:16px; color:#3D2B1A; cursor:pointer; -webkit-appearance:none; appearance:none; }
  .date-row       { display:grid; grid-template-columns:1fr 1fr; gap:10px; }

  .estilo-grid    { display:flex; gap:10px; flex-wrap:wrap; }
  .estilo-opt     { flex:1; min-width:52px; padding:10px 6px; border-radius:16px; border:3px solid #F0E2C8; background:#FFF8F0; text-align:center; font-size:26px; cursor:pointer; transition:all .15s; }
  .estilo-opt:hover   { border-color:#FF9EC0; transform:scale(1.08); }
  .estilo-opt.sel     { transform:scale(1.1); box-shadow:0 4px 12px rgba(0,0,0,.12); }

  .tipo-row       { display:flex; gap:10px; }
  .tipo-opt       { flex:1; padding:12px 8px; border-radius:16px; border:3px solid #F0E2C8; background:#FFF8F0; text-align:center; font-family:'Fredoka',sans-serif; font-size:15px; font-weight:600; color:#8A6A45; cursor:pointer; transition:all .15s; }
  .tipo-opt:hover { border-color:#FF9EC0; }
  .tipo-opt.sel   { border-color:#FF6F91; background:#FFF0F5; color:#E2547F; }

  .btn-salvar     { width:100%; margin-top:20px; font-family:'Baloo 2',sans-serif; font-weight:700; font-size:18px; color:#fff; padding:15px; border:none; border-radius:18px; cursor:pointer; background:linear-gradient(150deg,#FF9EC0,#FF6F91); box-shadow:0 6px 0 #E2547F, 0 12px 20px rgba(226,84,127,.28); transition:transform .12s ease; }
  .btn-salvar:hover  { transform:translateY(-2px); }
  .btn-salvar:active { transform:translateY(3px); box-shadow:0 3px 0 #E2547F; }
  .btn-salvar:disabled { opacity:.6; pointer-events:none; }

  .btn-excluir    { width:100%; margin-top:10px; font-family:'Baloo 2',sans-serif; font-weight:600; font-size:15px; color:#EF4444; padding:11px; border:2px solid #FCA5A5; border-radius:16px; cursor:pointer; background:#FFF1F2; transition:background .14s; }
  .btn-excluir:hover { background:#FFE0E0; }

  .modal-err      { color:#EF4444; font-size:13px; font-weight:600; text-align:center; margin-top:10px; display:none; }
</style>
@endpush

@section('content')
<div class="perfis-wrapper">
  <div class="perfis-header">
    <h1 class="perfis-title">🌞 Olá, {{ auth()->user()->name }}!</h1>
    <p class="perfis-sub">Quem vai marcar conquistas hoje?</p>
  </div>

  @php
    $criancas = auth()->user()->criancas()->orderBy('nome')->get();
    $estilos = [
      1 => ['emoji' => '🌟', 'color' => '#F59E0B', 'soft' => '#FFFBEB'],
      2 => ['emoji' => '🦄', 'color' => '#C084FC', 'soft' => '#F5F3FF'],
      3 => ['emoji' => '🚀', 'color' => '#60A5FA', 'soft' => '#EFF6FF'],
      4 => ['emoji' => '🐯', 'color' => '#FB923C', 'soft' => '#FFF7ED'],
      5 => ['emoji' => '🌈', 'color' => '#34D399', 'soft' => '#ECFDF5'],
    ];
    $criancasJs = $criancas->map(function($c) {
        return [
            'id'              => $c->id,
            'nome'            => $c->nome,
            'data_nascimento' => optional($c->data_nascimento)->format('Y-m-d'),
            'imagem'          => $c->imagem,
            'estilo'          => $c->estilo ?? 1,
        ];
    })->values();
  @endphp

  @if($criancas->isEmpty())
    <div class="empty-state">
      <div style="font-size:64px;margin-bottom:16px;">👶</div>
      <h2 style="font-family:'Baloo 2',sans-serif;font-weight:700;font-size:22px;color:#E2547F;margin:0 0 8px;">Nenhuma criança ainda</h2>
      <p style="font-size:15px;font-weight:500;color:#B07A45;margin:0;">Clique em <strong>Nova criança</strong> para começar! ✨</p>
    </div>
  @endif

  <div class="perfis-grid">
    @foreach($criancas as $crianca)
      @php $est = $estilos[$crianca->estilo] ?? $estilos[1]; @endphp
      <div class="perfil-item">
        <a href="/crianca/{{ $crianca->id }}" class="perfil-card" style="border-color:{{ $est['color'] }}30;">
          <div class="perfil-avatar" style="background:{{ $est['soft'] }};box-shadow:0 4px 14px {{ $est['color'] }}40;">
            @if($crianca->imagem)
              <img src="{{ $crianca->imagem }}" alt="{{ $crianca->nome }}">
            @else
              {{ $est['emoji'] }}
            @endif
          </div>
          <p class="perfil-nome">{{ $crianca->nome }}</p>
          @if($crianca->data_nascimento)
            @php $anos = now()->diffInYears($crianca->data_nascimento); @endphp
            <p class="perfil-idade">🎂 {{ $anos }} {{ $anos === 1 ? 'ano' : 'anos' }}</p>
          @endif
        </a>
        <div class="perfil-actions" style="border-color:{{ $est['color'] }}30;">
          <button class="btn-acao btn-editar" onclick="abrirEditar({{ $crianca->id }})">✏️ Editar</button>
          <button class="btn-acao btn-meta"   onclick="abrirNovaMeta({{ $crianca->id }}, '{{ addslashes($crianca->nome) }}')">🎯 Meta</button>
        </div>
      </div>
    @endforeach

    <div class="add-card" onclick="abrirModalAdd()">
      <div class="add-icon">+</div>
      <div class="add-label">Nova criança</div>
    </div>
  </div>

  <div class="logout-form">
    <form method="POST" action="/logout">
      @csrf
      <button type="submit" class="btn-logout">Sair ↩</button>
    </form>
  </div>
</div>

<!-- ── Modal: Adicionar Criança ── -->
<div class="modal-overlay" id="modalAdd" onclick="fecharFora(event,'modalAdd')">
  <div class="modal-box">
    <button class="modal-close" onclick="fechar('modalAdd')">✕</button>
    <div class="modal-title">✨ Nova Criança</div>

    <div class="avatar-preview" id="addAvatarPreview" onclick="document.getElementById('addFoto').click()">
      <span id="addAvatarEmoji" style="font-size:44px;">🌟</span>
    </div>
    <div class="foto-hint" onclick="document.getElementById('addFoto').click()">📷 Adicionar foto (opcional)</div>
    <input type="file" id="addFoto" accept="image/*" style="display:none" onchange="onFotoChange(this,'addAvatarPreview','addAvatarEmoji','add')">

    <div class="field-wrap">
      <label class="field-label">Nome da criança</label>
      <input class="kid-input" type="text" id="addNome" placeholder="Ex: Maria" maxlength="100">
    </div>
    <div class="field-wrap">
      <label class="field-label">Data de nascimento</label>
      <input class="kid-input" type="date" id="addNasc">
    </div>
    <div class="field-wrap">
      <label class="field-label">Estilo favorito</label>
      <div class="estilo-grid" id="addEstiloGrid">
        <div class="estilo-opt sel" data-id="1" onclick="selecionarEstilo(this,'addEstiloGrid','addAvatarPreview','addAvatarEmoji','add')">🌟</div>
        <div class="estilo-opt"    data-id="2" onclick="selecionarEstilo(this,'addEstiloGrid','addAvatarPreview','addAvatarEmoji','add')">🦄</div>
        <div class="estilo-opt"    data-id="3" onclick="selecionarEstilo(this,'addEstiloGrid','addAvatarPreview','addAvatarEmoji','add')">🚀</div>
        <div class="estilo-opt"    data-id="4" onclick="selecionarEstilo(this,'addEstiloGrid','addAvatarPreview','addAvatarEmoji','add')">🐯</div>
        <div class="estilo-opt"    data-id="5" onclick="selecionarEstilo(this,'addEstiloGrid','addAvatarPreview','addAvatarEmoji','add')">🌈</div>
      </div>
    </div>
    <div class="modal-err" id="addErr"></div>
    <button class="btn-salvar" id="addBtn" onclick="salvarCrianca()">Adicionar! 🎉</button>
  </div>
</div>

<!-- ── Modal: Editar Criança ── -->
<div class="modal-overlay" id="modalEdit" onclick="fecharFora(event,'modalEdit')">
  <div class="modal-box">
    <button class="modal-close" onclick="fechar('modalEdit')">✕</button>
    <div class="modal-title">✏️ Editar Criança</div>

    <div class="avatar-preview" id="editAvatarPreview" onclick="document.getElementById('editFoto').click()">
      <span id="editAvatarEmoji" style="font-size:44px;">🌟</span>
    </div>
    <div class="foto-hint" onclick="document.getElementById('editFoto').click()">📷 Trocar foto</div>
    <input type="file" id="editFoto" accept="image/*" style="display:none" onchange="onFotoChange(this,'editAvatarPreview','editAvatarEmoji','edit')">

    <div class="field-wrap">
      <label class="field-label">Nome da criança</label>
      <input class="kid-input" type="text" id="editNome" placeholder="Ex: Maria" maxlength="100">
    </div>
    <div class="field-wrap">
      <label class="field-label">Data de nascimento</label>
      <input class="kid-input" type="date" id="editNasc">
    </div>
    <div class="field-wrap">
      <label class="field-label">Estilo favorito</label>
      <div class="estilo-grid" id="editEstiloGrid">
        <div class="estilo-opt sel" data-id="1" onclick="selecionarEstilo(this,'editEstiloGrid','editAvatarPreview','editAvatarEmoji','edit')">🌟</div>
        <div class="estilo-opt"    data-id="2" onclick="selecionarEstilo(this,'editEstiloGrid','editAvatarPreview','editAvatarEmoji','edit')">🦄</div>
        <div class="estilo-opt"    data-id="3" onclick="selecionarEstilo(this,'editEstiloGrid','editAvatarPreview','editAvatarEmoji','edit')">🚀</div>
        <div class="estilo-opt"    data-id="4" onclick="selecionarEstilo(this,'editEstiloGrid','editAvatarPreview','editAvatarEmoji','edit')">🐯</div>
        <div class="estilo-opt"    data-id="5" onclick="selecionarEstilo(this,'editEstiloGrid','editAvatarPreview','editAvatarEmoji','edit')">🌈</div>
      </div>
    </div>
    <div class="modal-err" id="editErr"></div>
    <button class="btn-salvar" id="editBtn" onclick="salvarEdicao()">Salvar alterações ✓</button>
    <button class="btn-excluir" onclick="excluirCrianca()">🗑️ Excluir criança</button>
  </div>
</div>

<!-- ── Modal: Nova Meta ── -->
<div class="modal-overlay" id="modalMeta" onclick="fecharFora(event,'modalMeta')">
  <div class="modal-box">
    <button class="modal-close" onclick="fechar('modalMeta')">✕</button>
    <div class="modal-title" id="metaModalTitle">🎯 Nova Meta</div>

    <div class="field-wrap">
      <label class="field-label">Descrição da meta</label>
      <input class="kid-input" type="text" id="metaDesc" placeholder="Ex: Escovar os dentes todo dia" maxlength="200">
    </div>
    <div class="field-wrap">
      <label class="field-label">Etiqueta / Missão</label>
      <input class="kid-input" type="text" id="metaMetas" placeholder="Ex: Higiene, Tarefa, Exercício..." maxlength="200">
    </div>
    <div class="field-wrap">
      <label class="field-label">Tipo de período</label>
      <div class="tipo-row">
        <div class="tipo-opt sel" data-tipo="semanal" onclick="selecionarTipo(this)">📅 Semanal</div>
        <div class="tipo-opt"    data-tipo="mensal"   onclick="selecionarTipo(this)">🗓️ Mensal</div>
      </div>
    </div>
    <div class="field-wrap">
      <label class="field-label">Período <span style="font-weight:400;color:#C7A77C;">(fim opcional)</span></label>
      <div class="date-row">
        <input class="kid-input" type="date" id="metaInicio" placeholder="Início">
        <input class="kid-input" type="date" id="metaFim"    placeholder="Fim (opcional)">
      </div>
    </div>
    <div class="field-wrap">
      <label class="field-label">Meta por período (quantas vezes?)</label>
      <input class="kid-input" type="number" id="metaValor" placeholder="Ex: 5" min="1">
    </div>
    <div class="field-wrap">
      <label class="field-label">Máximo por dia <span style="font-weight:400;color:#C7A77C;">(opcional)</span></label>
      <input class="kid-input" type="number" id="metaMaxDia" placeholder="Ex: 1" min="1" max="20">
    </div>
    <div class="modal-err" id="metaErr"></div>
    <button class="btn-salvar" id="metaBtn" onclick="salvarMeta()">Criar meta! 🚀</button>
  </div>
</div>
@endsection

@push('scripts')
<script>
const CRIANCAS_DATA = @json($criancasJs);

const ESTILOS = {
  1: { emoji:'🌟', color:'#F59E0B', soft:'#FFFBEB' },
  2: { emoji:'🦄', color:'#C084FC', soft:'#F5F3FF' },
  3: { emoji:'🚀', color:'#60A5FA', soft:'#EFF6FF' },
  4: { emoji:'🐯', color:'#FB923C', soft:'#FFF7ED' },
  5: { emoji:'🌈', color:'#34D399', soft:'#ECFDF5' },
};

const csrf = () => document.querySelector('meta[name="csrf-token"]').content;

let addEstilo    = 1, addImagem    = null, addUpload    = false;
let editEstilo   = 1, editImagem   = null, editUpload   = false, editId = null;
let metaCriancaId = null, metaTipo = 'semanal';

/* ── Utilitários de modal ── */
function abrir(id)  { document.getElementById(id).classList.add('open'); }
function fechar(id) { document.getElementById(id).classList.remove('open'); }
function fecharFora(e, id) { if (e.target === document.getElementById(id)) fechar(id); }
function mostrarErro(fieldId, msg) {
  const el = document.getElementById(fieldId);
  el.textContent = msg;
  el.style.display = 'block';
}
function limparErro(fieldId) { document.getElementById(fieldId).style.display = 'none'; }

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') ['modalAdd','modalEdit','modalMeta'].forEach(fechar);
});

/* ── Estilo selector ── */
function selecionarEstilo(el, gridId, prevId, emojiId, ctx) {
  document.querySelectorAll(`#${gridId} .estilo-opt`).forEach(o => {
    o.classList.remove('sel');
    o.style.borderColor = '';
    o.style.background  = '';
  });
  el.classList.add('sel');
  const id  = parseInt(el.dataset.id);
  const est = ESTILOS[id];
  el.style.borderColor = est.color;
  el.style.background  = est.soft;

  if (ctx === 'add') addEstilo = id;
  else               editEstilo = id;

  const img = document.querySelector(`#${prevId} img`);
  if (!img) {
    const prev = document.getElementById(prevId);
    prev.innerHTML = `<span id="${emojiId}" style="font-size:44px;">${est.emoji}</span>`;
    prev.style.background = est.soft;
  }
}

/* ── Upload de foto ── */
async function onFotoChange(input, prevId, emojiId, ctx) {
  if (!input.files || !input.files[0]) return;

  const prev = document.getElementById(prevId);
  prev.innerHTML = '<div style="font-size:22px;animation:spin .8s linear infinite;display:inline-block;">⏳</div>';
  if (ctx === 'add') addUpload = true; else editUpload = true;

  const form = new FormData();
  form.append('imagem', input.files[0]);

  try {
    const res = await fetch('/upload/imagem', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf() },
      body: form,
    });
    if (!res.ok) throw new Error();
    const data = await res.json();
    const url  = data.url;
    if (ctx === 'add') addImagem = url; else editImagem = url;

    const img = document.createElement('img');
    img.src = url;
    prev.innerHTML = '';
    prev.style.background = 'transparent';
    prev.appendChild(img);
  } catch {
    const est = ESTILOS[ctx === 'add' ? addEstilo : editEstilo];
    prev.innerHTML = `<span id="${emojiId}" style="font-size:44px;">${est.emoji}</span>`;
    prev.style.background = est.soft;
  } finally {
    if (ctx === 'add') addUpload = false; else editUpload = false;
  }
}

/* ══ MODAL ADICIONAR ══ */
function abrirModalAdd() {
  addEstilo = 1; addImagem = null; addUpload = false;
  document.getElementById('addNome').value = '';
  document.getElementById('addNasc').value = '';
  document.getElementById('addFoto').value = '';
  limparErro('addErr');
  selecionarEstilo(document.querySelector('#addEstiloGrid [data-id="1"]'), 'addEstiloGrid', 'addAvatarPreview', 'addAvatarEmoji', 'add');
  abrir('modalAdd');
  setTimeout(() => document.getElementById('addNome').focus(), 80);
}

async function salvarCrianca() {
  if (addUpload) return;
  const nome = document.getElementById('addNome').value.trim();
  const nasc = document.getElementById('addNasc').value;
  limparErro('addErr');
  if (!nome) { mostrarErro('addErr', 'Informe o nome da criança.'); return; }
  if (!nasc) { mostrarErro('addErr', 'Informe a data de nascimento.'); return; }

  const btn = document.getElementById('addBtn');
  btn.disabled = true; btn.textContent = 'Salvando...';

  try {
    const res = await fetch('/api/criancas', {
      method: 'POST',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':csrf(), 'X-Requested-With':'XMLHttpRequest' },
      body: JSON.stringify({ nome, data_nascimento:nasc, estilo:addEstilo, imagem:addImagem||null }),
    });
    if (!res.ok) {
      const err = await res.json().catch(() => ({}));
      mostrarErro('addErr', err.errors ? Object.values(err.errors).flat().join(' ') : (err.message||'Erro ao salvar.'));
      return;
    }
    location.reload();
  } catch { mostrarErro('addErr', 'Erro de conexão.'); }
  finally  { btn.disabled = false; btn.textContent = 'Adicionar! 🎉'; }
}

/* ══ MODAL EDITAR ══ */
function abrirEditar(id) {
  const c = CRIANCAS_DATA.find(x => x.id === id);
  if (!c) return;

  editId     = id;
  editEstilo = c.estilo || 1;
  editImagem = c.imagem || null;
  editUpload = false;

  document.getElementById('editNome').value = c.nome;
  document.getElementById('editNasc').value = c.data_nascimento || '';
  document.getElementById('editFoto').value = '';
  limparErro('editErr');

  // Avatar preview
  const prev  = document.getElementById('editAvatarPreview');
  const est   = ESTILOS[editEstilo];
  if (editImagem) {
    const img = document.createElement('img');
    img.src = editImagem;
    prev.innerHTML = '';
    prev.style.background = 'transparent';
    prev.appendChild(img);
  } else {
    prev.innerHTML = `<span id="editAvatarEmoji" style="font-size:44px;">${est.emoji}</span>`;
    prev.style.background = est.soft;
  }

  // Marcar estilo no grid com cores
  document.querySelectorAll('#editEstiloGrid .estilo-opt').forEach(o => {
    const isSel = parseInt(o.dataset.id) === editEstilo;
    o.classList.toggle('sel', isSel);
    o.style.borderColor = isSel ? ESTILOS[editEstilo].color : '';
    o.style.background  = isSel ? ESTILOS[editEstilo].soft  : '';
  });

  abrir('modalEdit');
  setTimeout(() => document.getElementById('editNome').focus(), 80);
}

async function salvarEdicao() {
  if (editUpload) return;
  const nome = document.getElementById('editNome').value.trim();
  const nasc = document.getElementById('editNasc').value;
  limparErro('editErr');
  if (!nome) { mostrarErro('editErr', 'Informe o nome.'); return; }
  if (!nasc) { mostrarErro('editErr', 'Informe a data de nascimento.'); return; }

  const btn = document.getElementById('editBtn');
  btn.disabled = true; btn.textContent = 'Salvando...';

  try {
    const res = await fetch(`/api/criancas/${editId}`, {
      method: 'PUT',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':csrf(), 'X-Requested-With':'XMLHttpRequest' },
      body: JSON.stringify({ nome, data_nascimento:nasc, estilo:editEstilo, imagem:editImagem||null }),
    });
    if (!res.ok) {
      const err = await res.json().catch(() => ({}));
      mostrarErro('editErr', err.errors ? Object.values(err.errors).flat().join(' ') : (err.message||'Erro ao salvar.'));
      return;
    }
    location.reload();
  } catch { mostrarErro('editErr', 'Erro de conexão.'); }
  finally  { btn.disabled = false; btn.textContent = 'Salvar alterações ✓'; }
}

async function excluirCrianca() {
  const c = CRIANCAS_DATA.find(x => x.id === editId);
  if (!confirm(`Excluir "${c?.nome}"? Todas as metas e registros serão apagados.`)) return;

  try {
    await fetch(`/api/criancas/${editId}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN':csrf(), 'X-Requested-With':'XMLHttpRequest' },
    });
    location.reload();
  } catch { mostrarErro('editErr', 'Erro ao excluir.'); }
}

/* ══ MODAL NOVA META ══ */
function abrirNovaMeta(criancaId, nome) {
  metaCriancaId = criancaId;
  metaTipo = 'semanal';

  document.getElementById('metaModalTitle').textContent = `🎯 Nova Meta — ${nome}`;
  document.getElementById('metaDesc').value    = '';
  document.getElementById('metaMetas').value   = '';
  document.getElementById('metaInicio').value  = '';
  document.getElementById('metaFim').value     = '';
  document.getElementById('metaValor').value   = '';
  document.getElementById('metaMaxDia').value  = '';
  limparErro('metaErr');

  document.querySelectorAll('.tipo-opt').forEach(o =>
    o.classList.toggle('sel', o.dataset.tipo === 'semanal')
  );

  abrir('modalMeta');
  setTimeout(() => document.getElementById('metaDesc').focus(), 80);
}

function selecionarTipo(el) {
  document.querySelectorAll('.tipo-opt').forEach(o => o.classList.remove('sel'));
  el.classList.add('sel');
  metaTipo = el.dataset.tipo;
}

async function salvarMeta() {
  const desc   = document.getElementById('metaDesc').value.trim();
  const metas  = document.getElementById('metaMetas').value.trim();
  const inicio = document.getElementById('metaInicio').value;
  const fim    = document.getElementById('metaFim').value;
  const valor  = document.getElementById('metaValor').value;
  const maxDia = document.getElementById('metaMaxDia').value;
  limparErro('metaErr');

  if (!desc)   { mostrarErro('metaErr', 'Informe a descrição da meta.'); return; }
  if (!metas)  { mostrarErro('metaErr', 'Informe a etiqueta/missão.'); return; }
  if (!inicio) { mostrarErro('metaErr', 'Informe a data de início.'); return; }
  if (fim && fim < inicio) { mostrarErro('metaErr', 'A data de fim deve ser após o início.'); return; }
  if (!valor || parseInt(valor) < 1) { mostrarErro('metaErr', 'Informe a meta por período (mínimo 1).'); return; }

  const btn = document.getElementById('metaBtn');
  btn.disabled = true; btn.textContent = 'Salvando...';

  const body = {
    descricao:      desc,
    metas:          metas,
    tipo:           metaTipo,
    data_inicio:    inicio,
    data_fim:       fim,
    valor_meta:     parseInt(valor),
  };
  if (maxDia && parseInt(maxDia) >= 1) body.maximo_por_dia = parseInt(maxDia);

  try {
    const res = await fetch(`/api/criancas/${metaCriancaId}/metas`, {
      method: 'POST',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':csrf(), 'X-Requested-With':'XMLHttpRequest' },
      body: JSON.stringify(body),
    });
    if (!res.ok) {
      const err = await res.json().catch(() => ({}));
      mostrarErro('metaErr', err.errors ? Object.values(err.errors).flat().join(' ') : (err.message||'Erro ao salvar.'));
      return;
    }
    fechar('modalMeta');
    // Vai para o dashboard da criança onde a nova meta aparece
    window.location.href = `/crianca/${metaCriancaId}`;
  } catch { mostrarErro('metaErr', 'Erro de conexão.'); }
  finally  { btn.disabled = false; btn.textContent = 'Criar meta! 🚀'; }
}
</script>
@endpush
