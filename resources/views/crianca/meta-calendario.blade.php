@extends('layouts.app')

@section('title', 'Calendário de Metas — Meta Kids')

@push('styles')
<style>
/* ── Layout ────────────────────────────────────── */
.cal-wrap    { position:relative; z-index:2; max-width:760px; margin:0 auto; padding:24px 16px 180px; }
.back-btn    { font-family:'Fredoka',sans-serif; font-size:15px; font-weight:600; color:#B07A45; padding:8px 18px; border:2px solid #F0E2C8; border-radius:14px; background:#fff; cursor:pointer; display:inline-flex; align-items:center; gap:6px; margin-bottom:20px; transition:background .15s; }
.back-btn:hover { background:#FFE9D0; }

/* ── Meta header ───────────────────────────────── */
.meta-card-header { background:#fff; border-radius:24px; padding:20px 22px; margin-bottom:20px; box-shadow:0 4px 20px rgba(180,120,60,.1); display:flex; flex-direction:column; gap:0; transition:background .5s ease; }
.mh-top    { display:flex; align-items:flex-start; gap:14px; }
.mh-icon   { width:52px; height:52px; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:26px; flex-shrink:0; }
.mh-desc   { font-family:'Baloo 2',sans-serif; font-weight:700; font-size:19px; color:#3D2B1A; margin:0 0 4px; }
.mh-tags   { display:flex; flex-wrap:wrap; gap:6px; align-items:center; }
.mh-tag    { font-size:12px; font-weight:700; padding:3px 10px; border-radius:20px; }
.mh-max    { font-size:12px; font-weight:600; color:#8A6A45; }

/* ── Progresso ─────────────────────────────────── */
.mh-divider     { height:2px; background:#F0E2C8; border-radius:99px; margin:14px 0; }
.mh-prog-inner  { display:flex; align-items:center; gap:14px; }
.mood-wrap      { text-align:center; flex-shrink:0; width:64px; }
.mood-emoji     { font-size:50px; line-height:1; display:block; animation:floaty 3s ease-in-out infinite; }
.mood-stars     { font-size:13px; letter-spacing:1px; margin-top:3px; min-height:18px; }
.prog-detail    { flex:1; min-width:0; }
.prog-count-row { display:flex; align-items:baseline; gap:8px; margin-bottom:6px; }
.prog-count     { font-family:'Baloo 2',sans-serif; font-weight:800; font-size:24px; line-height:1; }
.prog-label     { font-size:13px; font-weight:600; color:#B07A45; }
.prog-track     { height:14px; background:#F0EDE9; border-radius:99px; overflow:hidden; margin-bottom:4px; }
.prog-fill      { height:100%; border-radius:99px; transition:width .9s cubic-bezier(.17,.67,.45,1.5); }
.prog-pct       { font-family:'Fredoka',sans-serif; font-weight:700; font-size:13px; }
.total-badge    { flex-shrink:0; text-align:center; border-radius:18px; padding:10px 14px; border:2px solid #F0E2C8; }
.total-num      { font-family:'Baloo 2',sans-serif; font-weight:800; font-size:28px; line-height:1; color:#E2547F; }
.total-lbl      { font-size:10px; font-weight:700; color:#B07A45; line-height:1.4; margin-top:2px; }

/* ── Controls ──────────────────────────────────── */
.cal-controls { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:18px; flex-wrap:wrap; }
.view-tabs    { display:flex; background:#FFF0F4; border-radius:14px; padding:4px; gap:4px; }
.view-tab     { font-family:'Fredoka',sans-serif; font-size:14px; font-weight:600; padding:7px 18px; border-radius:10px; border:none; background:transparent; color:#C07A8A; cursor:pointer; transition:all .15s; }
.view-tab.active { background:#fff; color:#E2547F; box-shadow:0 2px 8px rgba(226,84,127,.18); }
.nav-row      { display:flex; align-items:center; gap:10px; }
.nav-btn      { width:36px; height:36px; border-radius:12px; border:2px solid #F0E2C8; background:#fff; font-size:16px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:background .14s; color:#B07A45; font-weight:700; }
.nav-btn:hover { background:#FFE9D0; }
.nav-label    { font-family:'Baloo 2',sans-serif; font-weight:700; font-size:16px; color:#5B4630; min-width:150px; text-align:center; }

/* ── Calendar grid ─────────────────────────────── */
.cal-grid     { display:grid; grid-template-columns:repeat(7,1fr); gap:6px; margin-bottom:10px; }
.cal-header   { text-align:center; font-family:'Fredoka',sans-serif; font-weight:600; font-size:13px; color:#B07A45; padding:4px 0 8px; }
.cal-cell     { min-height:80px; border-radius:16px; padding:8px 6px 6px; position:relative; transition:all .15s ease; background:#F8F4EF; border:2px solid transparent; }
.cal-cell.other-month { opacity:.4; }
.cal-cell.disabled    { background:#F0EDE9; cursor:default; }
.cal-cell.allowed     { background:#FFF8F0; border-color:#FFD9A8; cursor:default; }
.cal-cell.today       { background:#FFFBEB; border-color:#F59E0B !important; box-shadow:0 4px 14px #F59E0B28; }
.cal-cell.at-max      { background:#FFF0F0; border-color:#FFB3B3 !important; }
.cal-cell.drag-over   { border-color:#FF6F91 !important; background:#FFF0F5 !important; transform:scale(1.03); box-shadow:0 6px 20px rgba(226,84,127,.22); }

.cal-day-num  { font-family:'Fredoka',sans-serif; font-weight:600; font-size:13px; color:#8A7060; margin-bottom:4px; display:flex; align-items:center; gap:4px; }
.today-badge  { background:#F59E0B; color:#fff; font-size:9px; font-weight:700; padding:1px 6px; border-radius:20px; font-family:'Fredoka',sans-serif; }
.cal-regs     { display:flex; flex-wrap:wrap; gap:3px; min-height:28px; }
.reg-chip     { font-size:20px; cursor:grab; line-height:1; display:inline-block; transition:transform .1s; }
.reg-chip:hover { transform:scale(1.25); }
.reg-chip:active { cursor:grabbing; }

@keyframes chipPop {
  0%   { transform:scale(0) rotate(-18deg); opacity:0; }
  45%  { transform:scale(1.5) rotate(9deg);  opacity:1; }
  70%  { transform:scale(0.85) rotate(-4deg); }
  88%  { transform:scale(1.1) rotate(2deg); }
  100% { transform:scale(1) rotate(0deg);   opacity:1; }
}
.reg-chip-new { animation:chipPop .55s cubic-bezier(.17,.67,.45,1.5) both; }
.cal-max-ind  { position:absolute; bottom:5px; right:7px; font-size:10px; font-weight:700; color:#C7A77C; background:#FFF8F0; padding:1px 6px; border-radius:20px; }
.cal-max-ind.full { color:#E55; background:#FFF0F0; }

/* ── Palette ───────────────────────────────────── */
.palette      { position:fixed; bottom:0; left:0; right:0; z-index:50; background:rgba(255,255,255,.92); backdrop-filter:blur(14px); border-top:2px solid #F0E2C8; padding:14px 20px 24px; }
.palette-inner { max-width:760px; margin:0 auto; }
.palette-title { font-family:'Fredoka',sans-serif; font-weight:600; font-size:14px; color:#B07A45; margin-bottom:10px; }
.palette-icons { display:flex; flex-wrap:wrap; gap:10px; justify-content:center; }
.pal-icon     { width:52px; height:52px; border-radius:16px; background:#FFF8F0; border:2px solid #F0E2C8; display:flex; align-items:center; justify-content:center; font-size:26px; cursor:grab; transition:all .15s; user-select:none; }
.pal-icon:hover  { transform:translateY(-4px) scale(1.12); border-color:#F59E0B; background:#FFFBEB; box-shadow:0 6px 18px rgba(245,158,11,.25); }
.pal-icon:active { cursor:grabbing; }

/* ── Period history ────────────────────────────── */
.period-history { margin-top:14px; padding-top:12px; border-top:2px solid #F0E2C8; }
.ph-row    { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
.ph-stars  { font-size:20px; letter-spacing:2px; line-height:1; }
.ph-label  { font-size:13px; font-weight:700; color:#8A6A45; }
.ph-streak { font-size:13px; font-weight:700; color:#EA580C; padding:2px 10px; border-radius:20px; background:#FFF7ED; border:1.5px solid #FDBA74; }

/* ── Spinner ───────────────────────────────────── */
.spinner-wrap { display:flex; flex-direction:column; align-items:center; gap:16px; padding:80px 0; }
.spinner      { width:48px; height:48px; border:5px solid #FFD9A8; border-top-color:#FF6F91; border-radius:50%; animation:spin .8s linear infinite; }

.toast { position:fixed; top:20px; left:50%; transform:translateX(-50%); background:#E2547F; color:#fff; font-family:'Fredoka',sans-serif; font-weight:600; font-size:15px; padding:10px 22px; border-radius:20px; box-shadow:0 4px 18px rgba(226,84,127,.35); z-index:200; animation:popIn .25s ease; pointer-events:none; }

#content { display:none; }
</style>
@endpush

@section('decos')
  <div style="position:fixed;top:30px;left:-50px;width:160px;height:160px;border-radius:50%;background:#FFC9DD;opacity:.4;pointer-events:none;z-index:0;animation:floaty 8s ease-in-out infinite;"></div>
  <div style="position:fixed;bottom:-50px;right:-40px;width:200px;height:200px;border-radius:50%;background:#BDE9C9;opacity:.35;pointer-events:none;z-index:0;"></div>
  <div style="position:fixed;top:45%;right:4%;font-size:48px;animation:floaty 9s ease-in-out infinite;pointer-events:none;z-index:0;">🌟</div>
@endsection

@section('content')
<div class="cal-wrap">
  <button class="back-btn" onclick="history.back()">← Voltar</button>

  <!-- Spinner de loading -->
  <div id="loading" class="spinner-wrap">
    <div class="spinner"></div>
    <p style="margin:0;font-size:17px;font-weight:600;color:#B07A45;">Carregando...</p>
  </div>

  <div id="content">
    <!-- Header da Meta -->
    <div class="meta-card-header" id="meta-header"></div>

    <!-- Controles -->
    <div class="cal-controls">
      <div class="view-tabs">
        <button class="view-tab active" id="tab-semana" onclick="setView('week')">Semana</button>
        <button class="view-tab"        id="tab-mes"    onclick="setView('month')">Mês</button>
      </div>
      <div class="nav-row">
        <button class="nav-btn" id="btn-prev" onclick="navPrev()">‹</button>
        <div class="nav-label" id="nav-label"></div>
        <button class="nav-btn" id="btn-next" onclick="navNext()">›</button>
      </div>
    </div>

    <!-- Grade do calendário -->
    <div class="cal-grid" id="cal-headers">
      <div class="cal-header">Dom</div>
      <div class="cal-header">Seg</div>
      <div class="cal-header">Ter</div>
      <div class="cal-header">Qua</div>
      <div class="cal-header">Qui</div>
      <div class="cal-header">Sex</div>
      <div class="cal-header">Sáb</div>
    </div>
    <div class="cal-grid" id="cal-grid"></div>
  </div>
</div>

<!-- Paleta de ícones (fixa na base) -->
<div class="palette">
  <div class="palette-inner">
    <div class="palette-title">Arraste um ícone para a data ↓</div>
    <div class="palette-icons" id="palette"></div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const CRIANCA_ID = {{ $criancaId }};
const META_ID    = {{ $metaId }};
const ICONES     = ['⭐','🌟','🏆','🎯','💪','✅','🔥','🎉'];
const MESES      = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];

let viewMode   = 'week';
let navDate    = new Date();
let meta       = null;
let registros  = [];
let dragData   = null;
let animateId  = null;

// ── helpers ───────────────────────────────────────────────────────
function calcStreak(periodos, periodLen) {
  const done = [...periodos]
    .filter(p => p.concluida)
    .sort((a, b) => b.data_inicio.localeCompare(a.data_inicio));
  if (!done.length) return 0;
  let s = 1;
  for (let i = 1; i < done.length; i++) {
    const prev = new Date(done[i-1].data_inicio + 'T00:00:00');
    const curr = new Date(done[i].data_inicio  + 'T00:00:00');
    if (Math.round((prev - curr) / 86400000) === periodLen) s++;
    else break;
  }
  return s;
}

function todayDate()       { const d = new Date(); d.setHours(0,0,0,0); return d; }
function addDays(d, n)     { const r = new Date(d); r.setDate(r.getDate()+n); return r; }
function toISO(d)          { return d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate()); }
function pad(n)            { return String(n).padStart(2,'0'); }
function isoToDate(s)      { return new Date(s+'T00:00:00'); }
function fmtData(iso)      { if(!iso) return ''; const p=iso.substring(0,10).split('-'); return `${p[2]}/${p[1]}`; }

function isAllowed(iso) {
  const todayIso = toISO(todayDate());
  if (meta && meta.bloquear_dias_anteriores) return iso === todayIso;
  const t = todayDate().getTime();
  const d = isoToDate(iso).getTime();
  const diff = (t - d) / 86400000;
  return diff >= 0 && diff <= 3;
}
function regsOnDate(iso)   { return registros.filter(r => r.data.substring(0,10) === iso); }
function isAtMax(iso)      { return meta && meta.maximo_por_dia && regsOnDate(iso).length >= meta.maximo_por_dia; }
function startOfWeek(d)    { const r = new Date(d); r.setDate(r.getDate() - r.getDay()); r.setHours(0,0,0,0); return r; }

const csrf = () => document.querySelector('meta[name="csrf-token"]').content;
const hdrs = () => ({'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':csrf()});

// ── API calls ─────────────────────────────────────────────────────
async function loadMeta() {
  const r = await fetch(`/api/criancas/${CRIANCA_ID}/metas`, {headers:{'X-Requested-With':'XMLHttpRequest'}});
  const list = await r.json();
  meta = list.find(m => m.id == META_ID);
  return meta;
}

async function loadRegistros() {
  const r = await fetch(`/api/metas/${META_ID}/registros`, {headers:{'X-Requested-With':'XMLHttpRequest'}});
  registros = await r.json();
}

async function loadPeriodos() {
  const r = await fetch(`/api/metas/${META_ID}/periodos`, {headers:{'X-Requested-With':'XMLHttpRequest'}});
  meta.periodos = await r.json();
}

async function createRegistro(iso, icone) {
  const r = await fetch(`/api/metas/${META_ID}/registros`, {
    method:'POST', headers:hdrs(),
    body: JSON.stringify({data:iso, icone})
  });
  if (!r.ok) { const e = await r.json(); showToast(e.error||'Erro ao criar registro'); return null; }
  const obj = await r.json();
  return obj.id;
}

async function moveRegistro(id, iso) {
  const r = await fetch(`/api/registros/${id}`, {
    method:'PUT', headers:hdrs(),
    body: JSON.stringify({data:iso})
  });
  if (!r.ok) { const e = await r.json(); showToast(e.error||'Erro ao mover registro'); return null; }
  return id;
}

async function deleteRegistro(id) {
  await fetch(`/api/registros/${id}`, {method:'DELETE', headers:hdrs()});
  await Promise.all([loadRegistros(), loadPeriodos()]);
  renderGrid();
  renderProgress();
}

// ── Burst effect ao soltar ────────────────────────────────────────
function burstEffect(cell, icone) {
  const rect = cell.getBoundingClientRect();
  const cx = rect.left + rect.width  / 2;
  const cy = rect.top  + rect.height / 2;

  const pts = [icone, '✨', '⭐', '💫', '✨', icone];
  pts.forEach((p, i) => {
    const angle = (360 / pts.length) * i - 90;
    const dist  = 46 + Math.random() * 26;
    const tx = Math.cos(angle * Math.PI / 180) * dist;
    const ty = Math.sin(angle * Math.PI / 180) * dist;

    const el = document.createElement('span');
    el.textContent = p;
    el.style.cssText = [
      `position:fixed`,
      `left:${cx}px`,
      `top:${cy}px`,
      `font-size:${15 + Math.random() * 9}px`,
      `pointer-events:none`,
      `z-index:999`,
      `transform:translate(-50%,-50%) scale(0)`,
      `opacity:1`,
      `transition:transform .6s cubic-bezier(.17,.67,.25,1.3),opacity .6s ease`,
      `will-change:transform,opacity`,
    ].join(';');
    document.body.appendChild(el);

    // Double rAF ensures transition fires from initial state
    requestAnimationFrame(() => requestAnimationFrame(() => {
      el.style.transform = `translate(calc(-50% + ${tx}px),calc(-50% + ${ty}px)) scale(1)`;
      el.style.opacity   = '0';
    }));
    setTimeout(() => el.remove(), 700);
  });

  // Flash verde na célula
  const prev = cell.style.transition;
  cell.style.transition = 'background .1s, transform .22s cubic-bezier(.17,.67,.45,1.5)';
  cell.style.background = '#BBFFD8';
  cell.style.transform  = 'scale(1.07)';
  setTimeout(() => {
    cell.style.background = '';
    cell.style.transform  = '';
    setTimeout(() => { cell.style.transition = prev; }, 260);
  }, 180);
}

// ── Toast ─────────────────────────────────────────────────────────
function showToast(msg) {
  const t = document.createElement('div');
  t.className = 'toast';
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 2800);
}

// ── Meta header ───────────────────────────────────────────────────
function renderHeader() {
  const isSem = meta.tipo === 'semanal';
  const cor   = isSem ? '#FF8FB1' : '#C084FC';
  const max   = meta.maximo_por_dia ? `· máx ${meta.maximo_por_dia}/dia` : '';
  document.getElementById('meta-header').innerHTML = `
    <div class="mh-top">
      <div class="mh-icon" style="background:${cor}22;border:2px solid ${cor};">
        ${isSem ? '📅' : '🗓️'}
      </div>
      <div>
        <div class="mh-desc">${meta.descricao}</div>
        <div class="mh-tags">
          <span class="mh-tag" style="background:${cor};color:#fff;">${isSem?'Semanal':'Mensal'}</span>
          <span class="mh-tag" style="background:#FFF8F0;color:#B07A45;border:1.5px solid #F0E2C8;">${meta.metas}</span>
          <span class="mh-max">🏆 ${meta.valor_meta}x por período ${max}</span>
          <span class="mh-max">📅 ${fmtData(meta.data_inicio)}${meta.data_fim ? ' → '+fmtData(meta.data_fim) : ' → ∞'}</span>
          ${meta.bloquear_dias_anteriores ? '<span class="mh-tag" style="background:#FFF0F4;color:#E2547F;border:1.5px solid #FFBDD0;">🔒 Somente hoje</span>' : ''}
        </div>
      </div>
    </div>
    <div class="mh-divider"></div>
    <div id="mh-progress"></div>`;
}

// ── Progresso da meta ─────────────────────────────────────────────
function renderProgress() {
  const el = document.getElementById('mh-progress');
  if (!el || !meta) return;

  const now      = todayDate();
  const todayIso = toISO(now);
  const target   = meta.valor_meta || 1;
  const total    = registros.length;

  // Usa os períodos pré-gerados (metas_em_andamento) como fonte da verdade
  const periodos      = meta.periodos || [];
  const currentPeriod = periodos.find(p => todayIso >= p.data_inicio && todayIso <= p.data_fim);

  let periodRegs = 0, pct = 0;
  const periodLabel = meta.tipo === 'semanal' ? 'esta semana' : 'este período';

  if (currentPeriod) {
    periodRegs = currentPeriod.contador;

    if (currentPeriod.concluida) {
      pct = 100;
    } else {
      const pStart    = isoToDate(currentPeriod.data_inicio);
      const pEnd      = isoToDate(currentPeriod.data_fim);
      const daysTotal = Math.round((pEnd - pStart) / 86400000) + 1;
      const daysElap  = Math.floor((now - pStart) / 86400000) + 1;
      const expected  = (daysElap / daysTotal) * target;
      pct = expected > 0
        ? Math.min(100, Math.round((periodRegs / expected) * 100))
        : (periodRegs > 0 ? 100 : 0);
    }
  }

  let mood, cor, bg, bgTotal;
  if (pct >= 100) {
    mood='😄'; cor='#22C55E'; bg='linear-gradient(135deg,#D1FAE5 0%,#fff 80%)'; bgTotal='#ECFDF5';
  } else if (pct >= 70) {
    mood='😊'; cor='#34D399'; bg='linear-gradient(135deg,#ECFDF5 0%,#fff 80%)'; bgTotal='#F0FDF4';
  } else if (pct >= 40) {
    mood='😐'; cor='#F59E0B'; bg='linear-gradient(135deg,#FFFBEB 0%,#fff 80%)'; bgTotal='#FFFBEB';
  } else if (pct >= 15) {
    mood='😟'; cor='#FB923C'; bg='linear-gradient(135deg,#FFF7ED 0%,#fff 80%)'; bgTotal='#FFF7ED';
  } else {
    mood='😢'; cor='#EF4444'; bg='linear-gradient(135deg,#FFF1F2 0%,#fff 80%)'; bgTotal='#FFF1F2';
  }

  // Período history (periodos já declarado acima)
  const completedCount = periodos.filter(p => p.concluida).length;
  const periodLen      = meta.tipo === 'semanal' ? 7 : 30;
  const streak         = calcStreak(periodos, periodLen);
  const starsMax       = 10;
  const starsShow      = Math.min(completedCount, starsMax);
  const starsStr       = '⭐'.repeat(starsShow) + (completedCount > starsMax ? ` +${completedCount - starsMax}` : '');

  document.getElementById('meta-header').style.background = bg;

  el.innerHTML = `
    <div class="mh-prog-inner">
      <div class="mood-wrap">
        <span class="mood-emoji">${mood}</span>
      </div>
      <div class="prog-detail">
        <div class="prog-count-row">
          <span class="prog-count" style="color:${cor};">${periodRegs}/${target}</span>
          <span class="prog-label">${periodLabel}</span>
        </div>
        <div class="prog-track">
          <div class="prog-fill" style="width:0%;background:${cor};" data-pct="${pct}"></div>
        </div>
        <div class="prog-pct" style="color:${cor};">${pct}% do ritmo esperado</div>
      </div>
      <div class="total-badge" style="background:${bgTotal};border-color:${cor}44;">
        <div class="total-num">${total}</div>
        <div class="total-lbl">conquistas<br>no total</div>
      </div>
    </div>
    ${completedCount > 0 ? `
    <div class="period-history">
      <div class="ph-row">
        ${starsStr ? `<div class="ph-stars">${starsStr}</div>` : ''}
        <div class="ph-label">${completedCount} período${completedCount !== 1 ? 's' : ''} completo${completedCount !== 1 ? 's' : ''}</div>
        ${streak >= 2 ? `<div class="ph-streak">🔥 ${streak} em sequência!</div>` : ''}
      </div>
    </div>` : ''}`;

  // Anima a barra após render
  requestAnimationFrame(() => requestAnimationFrame(() => {
    const fill = el.querySelector('.prog-fill');
    if (fill) fill.style.width = fill.dataset.pct + '%';
  }));
}

// ── Palette ───────────────────────────────────────────────────────
function renderPalette() {
  const p = document.getElementById('palette');
  p.innerHTML = '';
  ICONES.forEach(ic => {
    const div = document.createElement('div');
    div.className = 'pal-icon';
    div.textContent = ic;
    div.draggable = true;
    div.title = 'Arraste para a data';
    div.addEventListener('dragstart', e => {
      dragData = {type:'new', icone:ic};
      e.dataTransfer.effectAllowed = 'copy';
    });
    div.addEventListener('dragend', () => { if(dragData?.type==='new') dragData=null; });
    p.appendChild(div);
  });
}

// ── Calendar cell ─────────────────────────────────────────────────
function makeCell(iso, currentMonth) {
  const d       = isoToDate(iso);
  const allowed = isAllowed(iso);
  const atMax   = isAtMax(iso);
  const isToday = iso === toISO(todayDate());
  const regs    = regsOnDate(iso);
  const otherM  = currentMonth !== null && d.getMonth() !== currentMonth;

  const cell = document.createElement('div');
  let cls = 'cal-cell';
  if (otherM)  cls += ' other-month';
  if (isToday) cls += ' today';
  else if (allowed) cls += (atMax ? ' allowed at-max' : ' allowed');
  else cls += ' disabled';
  cell.className = cls;
  cell.dataset.date = iso;

  // Day number
  const num = document.createElement('div');
  num.className = 'cal-day-num';
  num.innerHTML = d.getDate() + (isToday ? ' <span class="today-badge">Hoje</span>' : '');
  cell.appendChild(num);

  // Existing registros as draggable chips
  const regsDiv = document.createElement('div');
  regsDiv.className = 'cal-regs';
  regs.forEach(r => {
    const chip = document.createElement('span');
    chip.className = 'reg-chip' + (r.id === animateId ? ' reg-chip-new' : '');
    chip.textContent = r.icone;
    chip.title = 'Duplo clique para remover';
    chip.draggable = true;
    chip.dataset.id = r.id;
    chip.addEventListener('dragstart', e => {
      dragData = {type:'move', id:r.id, fromIso:iso};
      e.dataTransfer.effectAllowed = 'move';
      e.stopPropagation();
    });
    chip.addEventListener('dragend', () => { if(dragData?.type==='move') dragData=null; });
    chip.addEventListener('dblclick', async () => {
      if (!confirm('Remover este ícone?')) return;
      await deleteRegistro(r.id);
    });
    regsDiv.appendChild(chip);
  });
  cell.appendChild(regsDiv);

  // Max indicator
  if (meta.maximo_por_dia) {
    const ind = document.createElement('div');
    ind.className = 'cal-max-ind' + (atMax ? ' full' : '');
    ind.textContent = `${regs.length}/${meta.maximo_por_dia}`;
    cell.appendChild(ind);
  }

  // Drop zone (only on allowed days)
  if (allowed && !otherM) {
    cell.addEventListener('dragover', e => {
      if (!dragData) return;
      const wouldBeMax = dragData.type === 'move' && dragData.fromIso === iso;
      if (wouldBeMax || !isAtMax(iso)) {
        e.preventDefault();
        cell.classList.add('drag-over');
      }
    });
    cell.addEventListener('dragleave', () => cell.classList.remove('drag-over'));
    cell.addEventListener('drop', async e => {
      e.preventDefault();
      cell.classList.remove('drag-over');
      if (!dragData) return;

      const snap = dragData;
      dragData = null;
      let newId = null;

      if (snap.type === 'new') {
        if (!isAtMax(iso)) newId = await createRegistro(iso, snap.icone);
      } else if (snap.type === 'move') {
        if (snap.fromIso === iso) return;
        if (!isAtMax(iso)) newId = await moveRegistro(snap.id, iso);
      }

      if (newId !== null) {
        animateId = newId;
        await Promise.all([loadRegistros(), loadPeriodos()]);
        renderGrid();
        renderProgress();
        // Burst na célula após re-render
        const freshCell = document.querySelector(`[data-date="${iso}"]`);
        if (freshCell) burstEffect(freshCell, snap.icone || snap.icone);
        setTimeout(() => { animateId = null; }, 100);
      }
    });
  }

  return cell;
}

// ── Grid render ───────────────────────────────────────────────────
function renderGrid() {
  const grid = document.getElementById('cal-grid');
  grid.innerHTML = '';

  if (viewMode === 'week') {
    const start = startOfWeek(navDate);
    for (let i = 0; i < 7; i++) {
      grid.appendChild(makeCell(toISO(addDays(start, i)), null));
    }

    const s = startOfWeek(navDate);
    const e = addDays(s, 6);
    const sameMonth = s.getMonth() === e.getMonth();
    document.getElementById('nav-label').textContent = sameMonth
      ? `${MESES[s.getMonth()]} ${s.getFullYear()}`
      : `${pad(s.getDate())}/${pad(s.getMonth()+1)} – ${pad(e.getDate())}/${pad(e.getMonth()+1)}/${e.getFullYear()}`;
  } else {
    const year  = navDate.getFullYear();
    const month = navDate.getMonth();
    const first = new Date(year, month, 1);
    const last  = new Date(year, month+1, 0);

    document.getElementById('nav-label').textContent = `${MESES[month]} ${year}`;

    // Leading empty cells (day of week of first)
    const startPad = first.getDay(); // 0=Sun
    for (let i = 0; i < startPad; i++) {
      const empty = document.createElement('div');
      empty.className = 'cal-cell disabled other-month';
      grid.appendChild(empty);
    }

    for (let d = 1; d <= last.getDate(); d++) {
      grid.appendChild(makeCell(toISO(new Date(year, month, d)), month));
    }

    // Trailing empty cells to complete last row
    const total = startPad + last.getDate();
    const trail = (7 - (total % 7)) % 7;
    for (let i = 0; i < trail; i++) {
      const empty = document.createElement('div');
      empty.className = 'cal-cell disabled other-month';
      grid.appendChild(empty);
    }
  }
}

// ── Navigation ────────────────────────────────────────────────────
function setView(mode) {
  viewMode = mode;
  document.getElementById('tab-semana').classList.toggle('active', mode==='week');
  document.getElementById('tab-mes').classList.toggle('active', mode==='month');
  renderGrid();
}

function navPrev() {
  if (viewMode === 'week') navDate = addDays(navDate, -7);
  else navDate = new Date(navDate.getFullYear(), navDate.getMonth()-1, 1);
  renderGrid();
}

function navNext() {
  if (viewMode === 'week') navDate = addDays(navDate, 7);
  else navDate = new Date(navDate.getFullYear(), navDate.getMonth()+1, 1);
  renderGrid();
}

// ── Init ──────────────────────────────────────────────────────────
(async () => {
  try {
    await Promise.all([loadMeta(), loadRegistros()]);
    if (!meta) { showToast('Meta não encontrada'); return; }
    // periodos já vêm no meta via eager loading; garante atualização posterior
    if (!meta.periodos) meta.periodos = [];
    renderHeader();
    renderPalette();
    renderGrid();
    renderProgress();
  } finally {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('content').style.display  = 'block';
  }
})();
</script>
@endpush
