@extends('layouts.app')

@section('title', 'Entrar — Meta Kids')

@push('styles')
<style>
  .wrapper { position:relative; z-index:2; width:100%; max-width:480px; margin:0 auto; padding:40px 20px 60px; }
  .logo-ring { text-align:center; margin-bottom:-38px; position:relative; z-index:3; }
  .logo-ring div { display:inline-flex; align-items:center; justify-content:center; width:96px; height:96px; border-radius:50%; background:#fff; box-shadow:0 10px 26px rgba(226,84,127,.25); border:4px solid #FFE0B4; }
  .logo-ring span { font-size:52px; display:inline-block; animation:wiggle 3s ease-in-out infinite; transform-origin:bottom center; }
  .card { background:#fff; border-radius:34px; padding:58px 38px 38px; box-shadow:0 22px 50px rgba(180,120,60,.25); }
  .tabs { display:flex; gap:6px; background:#FBF4E6; border-radius:18px; padding:5px; margin-bottom:28px; }
  .tab-btn { flex:1; padding:10px; border:none; border-radius:14px; cursor:pointer; font-family:'Baloo 2',sans-serif; font-weight:700; font-size:15px; color:#C79A5E; background:transparent; transition:all .2s ease; }
  .tab-btn.active { background:#FF6F91; color:#fff; box-shadow:0 4px 10px rgba(226,84,127,.3); }
  .panel { display:none; }
  .panel.active { display:block; }
  h1 { font-family:'Baloo 2',sans-serif; font-weight:700; font-size:28px; margin:0 0 6px; text-align:center; color:#E2547F; }
  .subtitle { margin:0 0 26px; text-align:center; font-size:15px; font-weight:500; color:#B07A45; }
  .form { display:flex; flex-direction:column; gap:14px; }
  .field label { display:block; font-size:14px; font-weight:600; color:#8A6A45; margin-bottom:6px; padding-left:6px; }
  .input-wrap { position:relative; display:flex; align-items:center; }
  .input-icon { position:absolute; left:18px; font-size:20px; }
  .kid-input { width:100%; font-family:'Fredoka',sans-serif; font-size:17px; font-weight:500; color:#5B4630; padding:15px 18px 15px 52px; border:none; border-radius:18px; background:#FBF4E6; box-shadow:inset 0 0 0 2px #F0E2C8; outline:none; transition:box-shadow .14s; }
  .kid-input.has-btn { padding-right:52px; }
  .toggle-pw { position:absolute; right:12px; width:36px; height:36px; border:none; background:transparent; cursor:pointer; font-size:20px; border-radius:10px; display:flex; align-items:center; justify-content:center; }
  .row-between { display:flex; align-items:center; justify-content:space-between; margin:2px 2px 6px; }
  .remember-btn { display:flex; align-items:center; gap:9px; border:none; background:transparent; cursor:pointer; padding:4px; font-family:'Fredoka',sans-serif; }
  .check-box { width:24px; height:24px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:15px; font-weight:700; color:#fff; transition:all .14s ease; box-shadow:inset 0 0 0 2px #E6D2B0; background:#FBF4E6; }
  .check-box.on { background:#FF6F91; box-shadow:none; }
  .divider { display:flex; align-items:center; gap:12px; margin:24px 0 18px; }
  .divider-text { font-size:13px; font-weight:600; color:#C7A77C; }
  .sign-link { margin:0; text-align:center; font-size:15px; font-weight:500; color:#8A6A45; }
  .section-divider { display:flex; align-items:center; gap:12px; margin:8px 0 16px; }
  .section-label { font-size:13px; font-weight:700; color:#C7A77C; letter-spacing:.4px; text-transform:uppercase; white-space:nowrap; }
  .child-card { display:flex; align-items:center; gap:12px; padding:12px 14px; border-radius:18px; margin-bottom:10px; }
  .child-name { font-size:16px; font-weight:600; color:#5B4630; }
  .child-date { font-size:13px; color:#B07A45; font-weight:500; margin-top:2px; }
  .icon-btn { width:34px; height:34px; border:none; background:#fff; border-radius:10px; cursor:pointer; font-size:16px; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 6px rgba(0,0,0,.1); flex-shrink:0; }
  .add-child-btn { width:100%; font-family:'Fredoka',sans-serif; font-size:16px; font-weight:600; color:#E2547F; padding:13px; border:2px dashed #FFB3CC; border-radius:18px; background:#FFF5F8; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; }
  .child-form { background:#FBF4E6; border-radius:22px; padding:20px; box-shadow:inset 0 0 0 2px #F0E2C8; flex-direction:column; gap:13px; display:none; }
  .child-form.open { display:flex; }
  .child-form-title { font-family:'Baloo 2',sans-serif; font-size:15px; font-weight:700; color:#E2547F; }
  .small-label { display:block; font-size:13px; font-weight:600; color:#8A6A45; margin-bottom:5px; padding-left:4px; }
  .kid-input-sm { width:100%; font-family:'Fredoka',sans-serif; font-size:16px; font-weight:500; color:#5B4630; padding:12px 16px; border:none; border-radius:14px; background:#fff; box-shadow:inset 0 0 0 2px #F0E2C8; outline:none; transition:box-shadow .14s; }
  .kid-input-url { padding-left:44px; }
  .foto-label   { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px; width:100%; padding:18px; border:2px dashed #F0E2C8; border-radius:14px; background:#fff; cursor:pointer; transition:all .14s ease; }
  .foto-label:hover { border-color:#FF8FB1; background:#FFF5F8; }
  .foto-label input { display:none; }
  .foto-preview { width:72px; height:72px; border-radius:50%; object-fit:cover; border:3px solid #FFE0B4; display:none; }
  .foto-placeholder { font-size:32px; }
  .foto-txt  { font-size:13px; font-weight:600; color:#C79A5E; }
  .foto-nome { font-size:12px; font-weight:500; color:#B07A45; max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:none; }
  .upload-spinner { width:20px; height:20px; border:3px solid #F0E2C8; border-top-color:#FF6F91; border-radius:50%; animation:spin .7s linear infinite; display:none; }
  .estilo-grid { display:flex; gap:7px; }
  .estilo-btn { display:flex; flex-direction:column; align-items:center; gap:4px; padding:10px 4px 8px; border:none; border-radius:14px; cursor:pointer; flex:1; transition:all .14s ease; background:#fff; box-shadow:inset 0 0 0 2px #F0E2C8; font-size:11px; font-weight:600; color:#8A6A45; font-family:'Fredoka',sans-serif; }
  .form-actions { display:flex; gap:10px; margin-top:2px; }
  .btn-cancel { flex:1; font-family:'Fredoka',sans-serif; font-size:15px; font-weight:600; color:#B07A45; padding:12px; border:2px solid #F0E2C8; border-radius:14px; background:#fff; cursor:pointer; }
  .btn-save { flex:2; font-family:'Baloo 2',sans-serif; font-size:16px; font-weight:700; color:#fff; padding:12px; border:none; border-radius:14px; background:linear-gradient(150deg,#FF9EC0,#FF6F91); box-shadow:0 5px 0 #E2547F; cursor:pointer; }
</style>
@endpush

@section('content')
<div class="wrapper">
  <div class="logo-ring">
    <div><span>🌞</span></div>
  </div>

  <div class="card">
    <div class="tabs">
      <button class="tab-btn active" data-tab="login">Entrar</button>
      <button class="tab-btn" data-tab="register">Cadastrar</button>
    </div>

    <!-- LOGIN -->
    <div id="panel-login" class="panel active">
      <h1>Bem-vindo de volta!</h1>
      <p class="subtitle">Entre para marcar as conquistas do dia ✨</p>

      <form class="form" method="POST" action="/login">
        @csrf
        <div class="field">
          <label>E-mail</label>
          <div class="input-wrap">
            <span class="input-icon">📧</span>
            <input class="kid-input" type="email" name="email" placeholder="seu@email.com" required>
          </div>
        </div>
        <div class="field">
          <label>Senha</label>
          <div class="input-wrap">
            <span class="input-icon">🔒</span>
            <input class="kid-input has-btn" type="password" name="password" id="pw-login" placeholder="••••••••" required>
            <button type="button" class="toggle-pw" data-target="pw-login">👁️</button>
          </div>
        </div>
        <div class="row-between">
          <button type="button" class="remember-btn" id="remember-btn">
            <span class="check-box on" id="check-remember">✓</span>
            <span style="font-size:14px;font-weight:600;color:#8A6A45;">Lembrar de mim</span>
          </button>
          <a href="#" class="link">Esqueceu a senha?</a>
        </div>
        <button type="submit" class="btn-submit">Entrar <span>🚀</span></button>
      </form>

      <div class="divider">
        <div class="divider-line"></div>
        <span class="divider-text">ou</span>
        <div class="divider-line"></div>
      </div>
      <p class="sign-link">Ainda não tem conta? <a href="#" class="link" data-switch="register">Criar agora</a></p>
    </div>

    <!-- CADASTRO -->
    <div id="panel-register" class="panel">
      <h1>Criar Conta</h1>
      <p class="subtitle">Comece sua jornada de conquistas! 🎯</p>

      <form class="form" method="POST" action="/register">
        @csrf
        <input type="hidden" name="criancas" id="criancas-json" value="[]">

        <div class="field">
          <label>Nome</label>
          <div class="input-wrap">
            <span class="input-icon">👤</span>
            <input class="kid-input" type="text" name="name" placeholder="Seu nome completo" required>
          </div>
        </div>
        <div class="field">
          <label>E-mail</label>
          <div class="input-wrap">
            <span class="input-icon">📧</span>
            <input class="kid-input" type="email" name="email" placeholder="seu@email.com" required>
          </div>
        </div>
        <div class="field">
          <label>Senha</label>
          <div class="input-wrap">
            <span class="input-icon">🔒</span>
            <input class="kid-input has-btn" type="password" name="password" id="pw-reg" placeholder="Crie uma senha" required>
            <button type="button" class="toggle-pw" data-target="pw-reg">👁️</button>
          </div>
        </div>
        <div class="field">
          <label>Confirmar Senha</label>
          <div class="input-wrap">
            <span class="input-icon">🔐</span>
            <input class="kid-input has-btn" type="password" name="password_confirmation" id="pw-conf" placeholder="Repita a senha" required>
            <button type="button" class="toggle-pw" data-target="pw-conf">👁️</button>
          </div>
        </div>

        <!-- Crianças -->
        <div>
          <div class="section-divider">
            <div class="divider-line"></div>
            <span class="section-label">Crianças</span>
            <div class="divider-line"></div>
          </div>

          <div id="child-list"></div>

          <button type="button" class="add-child-btn" id="add-child-btn">
            <span>➕</span> Adicionar Criança
          </button>

          <div class="child-form" id="child-form">
            <div class="child-form-title" id="child-form-title">🧒 Nova Criança</div>
            <div>
              <span class="small-label">Nome da criança</span>
              <input class="kid-input-sm" type="text" id="cf-nome" placeholder="Ex: Ana, Pedro...">
            </div>
            <div>
              <span class="small-label">Data de nascimento</span>
              <input class="kid-input-sm" type="date" id="cf-data">
            </div>
            <div>
              <span class="small-label">Foto da criança</span>
              <label class="foto-label" id="foto-label">
                <input type="file" id="cf-imagem-file" accept="image/*">
                <img class="foto-preview" id="foto-preview" alt="preview">
                <span class="foto-placeholder" id="foto-placeholder">📷</span>
                <div class="upload-spinner" id="upload-spinner"></div>
                <span class="foto-txt" id="foto-txt">Toque para escolher uma foto</span>
                <span class="foto-nome" id="foto-nome"></span>
              </label>
            </div>
            <div>
              <span class="small-label">Estilo</span>
              <div class="estilo-grid" id="estilo-grid"></div>
            </div>
            <div class="form-actions">
              <button type="button" class="btn-cancel" id="cf-cancel">Cancelar</button>
              <button type="button" class="btn-save" id="cf-save">Salvar ✓</button>
            </div>
          </div>
        </div>

        <button type="submit" class="btn-submit" style="margin-top:8px;">Criar Conta <span>🚀</span></button>
      </form>

      <p class="sign-link" style="margin-top:20px;">Já tem conta? <a href="#" class="link" data-switch="login">Entrar agora</a></p>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const ESTILOS = [
  { id:1, emoji:'🌟', label:'Estrela',   color:'#F59E0B', soft:'#FFFBEB' },
  { id:2, emoji:'🦄', label:'Unicórnio', color:'#C084FC', soft:'#F5F3FF' },
  { id:3, emoji:'🚀', label:'Foguete',   color:'#60A5FA', soft:'#EFF6FF' },
  { id:4, emoji:'🐯', label:'Tigre',     color:'#FB923C', soft:'#FFF7ED' },
  { id:5, emoji:'🌈', label:'Arco-íris', color:'#34D399', soft:'#ECFDF5' },
];
let children = [], editingIdx = null, selEstilo = 1, remember = true;
let uploadedImageUrl = null;

document.querySelectorAll('.tab-btn').forEach(btn =>
  btn.addEventListener('click', () => switchTab(btn.dataset.tab)));
document.querySelectorAll('[data-switch]').forEach(a =>
  a.addEventListener('click', e => { e.preventDefault(); switchTab(a.dataset.switch); }));

function switchTab(tab) {
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.toggle('active', b.dataset.tab === tab));
  document.querySelectorAll('.panel').forEach(p => p.classList.toggle('active', p.id === 'panel-' + tab));
}

document.querySelectorAll('.toggle-pw').forEach(btn =>
  btn.addEventListener('click', () => {
    const inp = document.getElementById(btn.dataset.target);
    const show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    btn.textContent = show ? '🙈' : '👁️';
  }));

document.getElementById('remember-btn').addEventListener('click', () => {
  remember = !remember;
  const box = document.getElementById('check-remember');
  box.classList.toggle('on', remember);
  box.textContent = remember ? '✓' : '';
});

function renderEstiloGrid() {
  const grid = document.getElementById('estilo-grid');
  grid.innerHTML = '';
  ESTILOS.forEach(e => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'estilo-btn';
    btn.innerHTML = `<span style="font-size:22px;line-height:1;">${e.emoji}</span>${e.label}`;
    btn.style.background = selEstilo === e.id ? e.soft : '#fff';
    btn.style.boxShadow  = selEstilo === e.id ? `inset 0 0 0 3px ${e.color}` : 'inset 0 0 0 2px #F0E2C8';
    btn.addEventListener('click', () => { selEstilo = e.id; renderEstiloGrid(); });
    grid.appendChild(btn);
  });
}
renderEstiloGrid();

function getEstilo(id) { return ESTILOS.find(e => e.id === id) || ESTILOS[0]; }

function syncHidden() {
  document.getElementById('criancas-json').value = JSON.stringify(children);
}

function renderChildren() {
  const list = document.getElementById('child-list');
  list.innerHTML = '';
  children.forEach((c, idx) => {
    const est = getEstilo(c.estilo);
    const card = document.createElement('div');
    card.className = 'child-card';
    card.style.background = est.soft;
    card.style.boxShadow  = `inset 0 0 0 2px ${est.color}`;
    card.innerHTML = `
      <span style="font-size:34px;line-height:1;flex-shrink:0;">${est.emoji}</span>
      <div style="flex:1;min-width:0;">
        <div class="child-name">${c.nome}</div>
        <div class="child-date">🎂 ${c.dataNascimento || '—'}</div>
      </div>
      <button class="icon-btn" data-edit="${idx}">✏️</button>
      <button class="icon-btn" data-remove="${idx}">🗑️</button>`;
    list.appendChild(card);
  });
  list.querySelectorAll('[data-edit]').forEach(btn =>
    btn.addEventListener('click', () => openEditChild(+btn.dataset.edit)));
  list.querySelectorAll('[data-remove]').forEach(btn =>
    btn.addEventListener('click', () => { children.splice(+btn.dataset.remove, 1); renderChildren(); syncHidden(); }));
  syncHidden();
}

function resetFotoUI(url) {
  uploadedImageUrl = url || null;
  const preview     = document.getElementById('foto-preview');
  const placeholder = document.getElementById('foto-placeholder');
  const txt         = document.getElementById('foto-txt');
  const nome        = document.getElementById('foto-nome');
  if (url) {
    preview.src           = url;
    preview.style.display = 'block';
    placeholder.style.display = 'none';
    txt.textContent = 'Trocar foto';
    nome.style.display = 'none';
  } else {
    preview.style.display     = 'none';
    placeholder.style.display = '';
    txt.textContent = 'Toque para escolher uma foto';
    nome.textContent   = '';
    nome.style.display = 'none';
    document.getElementById('cf-imagem-file').value = '';
  }
}

document.getElementById('cf-imagem-file').addEventListener('change', async function () {
  const file = this.files[0];
  if (!file) return;

  const spinner     = document.getElementById('upload-spinner');
  const placeholder = document.getElementById('foto-placeholder');
  const txt         = document.getElementById('foto-txt');
  const nome        = document.getElementById('foto-nome');
  const preview     = document.getElementById('foto-preview');

  placeholder.style.display = 'none';
  preview.style.display     = 'none';
  spinner.style.display     = 'block';
  txt.textContent            = 'Enviando...';
  nome.style.display         = 'none';

  const fd = new FormData();
  fd.append('imagem', file);
  fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);

  try {
    const res  = await fetch('/upload/imagem', { method: 'POST', body: fd });
    const data = await res.json();
    if (data.url) {
      uploadedImageUrl   = data.url;
      preview.src        = data.url;
      preview.style.display = 'block';
      txt.textContent    = 'Trocar foto';
      nome.textContent   = file.name;
      nome.style.display = 'block';
    } else {
      txt.textContent = 'Erro no upload';
      placeholder.style.display = '';
    }
  } catch {
    txt.textContent = 'Erro no upload';
    placeholder.style.display = '';
  } finally {
    spinner.style.display = 'none';
  }
});

function openAddChild() {
  editingIdx = null; selEstilo = 1;
  document.getElementById('cf-nome').value = '';
  document.getElementById('cf-data').value = '';
  resetFotoUI(null);
  document.getElementById('child-form-title').textContent = '🧒 Nova Criança';
  document.getElementById('add-child-btn').style.display = 'none';
  document.getElementById('child-form').classList.add('open');
  renderEstiloGrid();
}

function openEditChild(idx) {
  editingIdx = idx;
  const c = children[idx];
  selEstilo = c.estilo;
  document.getElementById('cf-nome').value = c.nome;
  document.getElementById('cf-data').value = c.dataNascimento;
  resetFotoUI(c.imagem || null);
  document.getElementById('child-form-title').textContent = '✏️ Editar Criança';
  document.getElementById('add-child-btn').style.display = 'none';
  document.getElementById('child-form').classList.add('open');
  renderEstiloGrid();
}

function closeChildForm() {
  document.getElementById('child-form').classList.remove('open');
  document.getElementById('add-child-btn').style.display = '';
  editingIdx = null;
}

document.getElementById('add-child-btn').addEventListener('click', openAddChild);
document.getElementById('cf-cancel').addEventListener('click', closeChildForm);
document.getElementById('cf-save').addEventListener('click', () => {
  const nome = document.getElementById('cf-nome').value.trim();
  if (!nome) { document.getElementById('cf-nome').focus(); return; }
  const child = {
    nome,
    dataNascimento: document.getElementById('cf-data').value,
    imagem: uploadedImageUrl,
    estilo: selEstilo,
  };
  if (editingIdx !== null) children[editingIdx] = child;
  else children.push(child);
  renderChildren();
  closeChildForm();
});
</script>
@endpush
