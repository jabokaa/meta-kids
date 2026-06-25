@extends('layouts.app')

@section('title', 'Métricas da Família — Meta Kids')

@push('styles')
<style>
.fam-wrap    { max-width:720px; margin:0 auto; padding:70px 16px 60px; position:relative; z-index:2; }

/* ── Header ────────────────────────────────────── */
.fam-title   { font-family:'Baloo 2',sans-serif; font-weight:800; font-size:28px; color:#E2547F; margin:0 0 4px; }
.fam-periodo { font-size:14px; font-weight:600; color:#B07A45; margin:0 0 28px; }

/* ── Spinner ────────────────────────────────────── */
.spinner-wrap { display:flex; flex-direction:column; align-items:center; gap:16px; padding:80px 0; }
.spinner      { width:48px; height:48px; border:5px solid #FFD9A8; border-top-color:#FF6F91; border-radius:50%; animation:spin .8s linear infinite; }

/* ── Criança card ───────────────────────────────── */
.fc-card     { background:#fff; border-radius:28px; padding:22px 22px 18px; margin-bottom:18px; box-shadow:0 6px 28px rgba(180,120,60,.1); animation:fadeIn .25s ease both; }
.fc-header   { display:flex; align-items:center; gap:14px; margin-bottom:16px; }
.fc-avatar   { width:60px; height:60px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:30px; flex-shrink:0; border:4px solid #fff; box-shadow:0 4px 14px rgba(0,0,0,.1); }
.fc-avatar img { width:100%; height:100%; border-radius:50%; object-fit:cover; }
.fc-nome     { font-family:'Baloo 2',sans-serif; font-weight:700; font-size:20px; color:#3D2B1A; margin:0 0 2px; }
.fc-media    { font-size:13px; font-weight:600; color:#8A6A45; }
.fc-mood-geral { margin-left:auto; text-align:center; }
.fc-mood-emoji { font-size:42px; line-height:1; display:block; }
.fc-mood-lbl   { font-size:10px; font-weight:700; margin-top:2px; }

/* ── Meta row ───────────────────────────────────── */
.fc-divider  { height:2px; background:#F0E2C8; border-radius:99px; margin:0 0 14px; }
.meta-row    { border-bottom:1.5px dashed #F0E2C8; border-radius:10px; overflow:hidden; }
.meta-row:last-child { border-bottom:none; }
.meta-row-top { display:flex; align-items:flex-start; gap:12px; padding:10px 8px; cursor:pointer; border-radius:10px; transition:background .15s; }
.meta-row-top:hover { background:rgba(0,0,0,.04); }
.mr-mood     { font-size:28px; flex-shrink:0; line-height:1; margin-top:2px; }
.mr-body     { flex:1; min-width:0; }
.mr-desc     { font-family:'Baloo 2',sans-serif; font-weight:700; font-size:15px; color:#3D2B1A; margin-bottom:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.mr-stars    { font-size:16px; letter-spacing:1px; margin-bottom:5px; line-height:1; display:flex; align-items:center; gap:6px; flex-wrap:wrap; min-height:22px; }
.mr-streak   { font-size:11px; font-weight:700; color:#EA580C; padding:1px 8px; border-radius:20px; background:#FFF7ED; border:1.5px solid #FDBA74; }
.mr-track    { height:10px; background:#F0EDE9; border-radius:99px; overflow:hidden; margin-bottom:4px; }
.mr-fill     { height:100%; border-radius:99px; transition:width .9s cubic-bezier(.17,.67,.45,1.5); }
.mr-info-row { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
.mr-count    { font-size:12px; font-weight:700; }
.mr-tipo-badge { font-size:11px; font-weight:700; color:#fff; padding:2px 8px; border-radius:20px; }
.mr-total    { font-size:11px; font-weight:600; color:#B07A45; }
.mr-link-btn { font-size:11px; font-weight:700; color:#E2547F; padding:2px 8px; border:1.5px solid #FFBDD0; border-radius:20px; background:#FFF0F4; text-decoration:none; white-space:nowrap; margin-left:auto; }
.mr-link-btn:hover { background:#FFE0EA; }
.mr-expand   { flex-shrink:0; font-size:18px; color:#B07A45; transition:transform .2s; line-height:1; padding:4px 2px; margin-top:2px; }

/* ── Meta calendar panel ────────────────────────── */
.meta-cal-panel    { display:none; padding:4px 8px 12px; }
.meta-cal-panel.open { display:block; }

.mini-pal-row  { display:flex; flex-wrap:wrap; align-items:center; gap:6px; padding:8px 10px; background:#FFF8F0; border-radius:12px; margin-bottom:10px; }
.mini-pal-title { font-size:11px; font-weight:700; color:#B07A45; width:100%; margin-bottom:2px; }
.mini-pal-icons { display:flex; flex-wrap:wrap; gap:6px; }
.mini-pal-icon  { width:36px; height:36px; border-radius:10px; background:#fff; border:2px solid #F0E2C8; display:flex; align-items:center; justify-content:center; font-size:19px; cursor:grab; user-select:none; transition:all .15s; }
.mini-pal-icon:hover { transform:translateY(-3px) scale(1.12); border-color:#F59E0B; background:#FFFBEB; box-shadow:0 4px 12px rgba(245,158,11,.2); }
.mini-pal-icon:active { cursor:grabbing; }

.mini-cal-controls { display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; gap:6px; flex-wrap:wrap; }
.mini-view-tabs    { display:flex; background:#FFF0F4; border-radius:10px; padding:3px; gap:3px; }
.mini-view-tab     { font-family:'Fredoka',sans-serif; font-size:12px; font-weight:600; padding:4px 12px; border-radius:7px; border:none; background:transparent; color:#C07A8A; cursor:pointer; transition:all .15s; }
.mini-view-tab.active { background:#fff; color:#E2547F; box-shadow:0 2px 6px rgba(226,84,127,.15); }
.mini-nav-row      { display:flex; align-items:center; gap:5px; }
.mini-nav-btn      { width:26px; height:26px; border-radius:8px; border:1.5px solid #F0E2C8; background:#fff; font-size:13px; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#B07A45; font-weight:700; }
.mini-nav-btn:hover { background:#FFE9D0; }
.mini-nav-label    { font-family:'Baloo 2',sans-serif; font-weight:700; font-size:12px; color:#5B4630; min-width:90px; text-align:center; }

.mini-cal-grid     { display:grid; grid-template-columns:repeat(7,1fr); gap:3px; }
.mini-cal-header   { text-align:center; font-size:10px; font-weight:700; color:#B07A45; padding:2px 0 5px; }
.mini-cal-cell     { min-height:48px; border-radius:9px; padding:4px 3px 3px; position:relative; transition:all .15s; background:#F8F4EF; border:1.5px solid transparent; }
.mini-cal-cell.other-month { opacity:.4; }
.mini-cal-cell.disabled    { background:#F0EDE9; }
.mini-cal-cell.allowed     { background:#FFF8F0; border-color:#FFD9A8; }
.mini-cal-cell.today       { background:#FFFBEB; border-color:#F59E0B !important; box-shadow:0 2px 8px #F59E0B22; }
.mini-cal-cell.at-max      { background:#FFF0F0; border-color:#FFB3B3 !important; }
.mini-cal-cell.drag-over   { border-color:#FF6F91 !important; background:#FFF0F5 !important; transform:scale(1.05); box-shadow:0 4px 14px rgba(226,84,127,.22); }
.mini-day-num      { font-size:10px; font-weight:700; color:#8A7060; margin-bottom:2px; display:flex; align-items:center; gap:2px; }
.mini-today-badge  { background:#F59E0B; color:#fff; font-size:7px; font-weight:700; padding:1px 4px; border-radius:7px; }
.mini-regs         { display:flex; flex-wrap:wrap; gap:2px; min-height:18px; }
.mini-reg-chip     { font-size:16px; cursor:grab; line-height:1; transition:transform .1s; display:inline-block; }
.mini-reg-chip:hover { transform:scale(1.22); }
.mini-reg-chip:active { cursor:grabbing; }
.mini-max-ind      { position:absolute; bottom:2px; right:4px; font-size:8px; font-weight:700; color:#C7A77C; background:#FFF8F0; padding:1px 4px; border-radius:7px; }
.mini-max-ind.full { color:#E55; background:#FFF0F0; }

/* ── Nota geral da família ──────────────────────── */
.familia-score { background:#fff; border-radius:28px; padding:24px 28px; box-shadow:0 6px 28px rgba(180,120,60,.1); display:flex; align-items:center; gap:20px; margin-top:24px; }
.fs-char     { font-size:80px; line-height:1; animation:floaty 3s ease-in-out infinite; flex-shrink:0; }
.fs-info     { flex:1; }
.fs-titulo   { font-family:'Baloo 2',sans-serif; font-weight:700; font-size:14px; color:#8A6A45; margin:0 0 4px; text-transform:uppercase; letter-spacing:1px; }
.fs-label    { font-family:'Baloo 2',sans-serif; font-weight:800; font-size:26px; color:#3D2B1A; margin:0 0 2px; }
.fs-pct      { font-size:13px; font-weight:600; }

.empty-state { text-align:center; padding:60px 20px; background:#fff; border-radius:28px; box-shadow:0 4px 20px rgba(180,120,60,.1); border:2px dashed #F0E2C8; }

@keyframes chipPop {
  0%   { transform:scale(0) rotate(-18deg); opacity:0; }
  45%  { transform:scale(1.5) rotate(9deg);  opacity:1; }
  70%  { transform:scale(0.85) rotate(-4deg); }
  88%  { transform:scale(1.1) rotate(2deg); }
  100% { transform:scale(1) rotate(0deg);   opacity:1; }
}
.reg-chip-new { animation:chipPop .55s cubic-bezier(.17,.67,.45,1.5) both; }

.toast { position:fixed; top:20px; left:50%; transform:translateX(-50%); background:#E2547F; color:#fff; font-family:'Fredoka',sans-serif; font-weight:600; font-size:15px; padding:10px 22px; border-radius:20px; box-shadow:0 4px 18px rgba(226,84,127,.35); z-index:200; animation:popIn .25s ease; pointer-events:none; }

#content { display:none; }
</style>
@endpush

@section('decos')
  <div style="position:fixed;top:30px;left:-50px;width:180px;height:180px;border-radius:50%;background:#C3F0CA;opacity:.45;pointer-events:none;z-index:0;animation:floaty 9s ease-in-out infinite;"></div>
  <div style="position:fixed;bottom:-60px;right:-40px;width:220px;height:220px;border-radius:50%;background:#FFC9DD;opacity:.4;pointer-events:none;z-index:0;"></div>
  <div style="position:fixed;top:40%;left:4%;font-size:52px;animation:floaty 8s ease-in-out infinite;pointer-events:none;z-index:0;">🏆</div>
  <div style="position:fixed;top:12%;right:6%;font-size:44px;animation:floaty 11s ease-in-out infinite;pointer-events:none;z-index:0;">🌟</div>
@endsection

@section('content')
<div class="fam-wrap">
  <div id="loading" class="spinner-wrap">
    <div class="spinner"></div>
    <p style="margin:0;font-size:17px;font-weight:600;color:#B07A45;">Carregando métricas...</p>
  </div>

  <div id="content">
    <h1 class="fam-title">🏠 Métricas da Família</h1>
    <p class="fam-periodo" id="periodo-label"></p>
    <div id="cards-wrap"></div>
    <div id="familia-score-wrap"></div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const ESTILOS = {
  1:{emoji:'🌟',color:'#F59E0B',soft:'#FFFBEB'},
  2:{emoji:'🦄',color:'#C084FC',soft:'#F5F3FF'},
  3:{emoji:'🚀',color:'#60A5FA',soft:'#EFF6FF'},
  4:{emoji:'🐯',color:'#FB923C',soft:'#FFF7ED'},
  5:{emoji:'🌈',color:'#34D399',soft:'#ECFDF5'},
};
const H = {'X-Requested-With':'XMLHttpRequest'};
const ICONES_PAL = ['⭐','🌟','🏆','🎯','💪','✅','🔥','🎉'];
const MESES_CAL  = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];

// ── Helpers ───────────────────────────────────────────────────────
function toISO(d) {
  return d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-'+String(d.getDate()).padStart(2,'0');
}
function fmtDia(iso) { const p=iso.split('-'); return `${p[2]}/${p[1]}`; }
function addDays(d,n){ const r=new Date(d); r.setDate(r.getDate()+n); return r; }
function startOfWeek(d){ const r=new Date(d); r.setHours(0,0,0,0); r.setDate(r.getDate()-r.getDay()); return r; }
function calcIdade(iso){ if(!iso) return null; const h=new Date(),n=new Date(iso+'T00:00:00'); let a=h.getFullYear()-n.getFullYear(); const m=h.getMonth()-n.getMonth(); if(m<0||(m===0&&h.getDate()<n.getDate())) a--; return a<0?null:a; }

function showToast(msg) {
  const t = document.createElement('div');
  t.className = 'toast';
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 2800);
}

// ── Load data ─────────────────────────────────────────────────────
async function loadAll() {
  const criancas = await fetch('/api/criancas', {headers:H}).then(r=>r.json());
  if (!criancas.length) return [];

  const metasByCrianca = await Promise.all(
    criancas.map(c => fetch(`/api/criancas/${c.id}/metas`, {headers:H}).then(r=>r.json()))
  );

  const allMetas = metasByCrianca.flat();
  const regsByMeta = allMetas.length
    ? await Promise.all(allMetas.map(m => fetch(`/api/metas/${m.id}/registros`, {headers:H}).then(r=>r.json())))
    : [];

  let idx = 0;
  return criancas.map((c, i) => ({
    ...c,
    metas: metasByCrianca[i].map(m => ({...m, registros: regsByMeta[idx++] ?? []})),
  }));
}

// ── Mini inline calendar ──────────────────────────────────────────
function createMetaCalendar(meta, criancaId, container) {
  let viewMode  = 'week';
  let navDate   = logicalToday();
  let dragData  = null;
  let animateId = null;
  let registros = Array.isArray(meta.registros) ? [...meta.registros] : [];
  let periodos  = Array.isArray(meta.periodos)  ? [...meta.periodos]  : [];

  function pad(n)         { return String(n).padStart(2,'0'); }
  function toISOl(d)      { return d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate()); }
  function addDaysL(d,n)  { const r=new Date(d); r.setDate(r.getDate()+n); return r; }
  function isoToDate(s)   { return new Date(s+'T00:00:00'); }
  function swk(d)         { const r=new Date(d); r.setDate(r.getDate()-r.getDay()); r.setHours(0,0,0,0); return r; }

  function isAllowed(iso) {
    const todayIso = toISOl(logicalToday());
    if (meta.bloquear_dias_anteriores) return iso === todayIso;
    const diff = (logicalToday().getTime() - isoToDate(iso).getTime()) / 86400000;
    return diff >= 0 && diff <= 3;
  }
  function regsOnDate(iso) { return registros.filter(r => r.data.substring(0,10) === iso); }
  function isAtMax(iso)    { return meta.maximo_por_dia && regsOnDate(iso).length >= meta.maximo_por_dia; }

  const csrf  = () => document.querySelector('meta[name="csrf-token"]').content;
  const hdrsJ = () => ({'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':csrf()});

  async function reloadData() {
    const [regsData, perData] = await Promise.all([
      fetch(`/api/metas/${meta.id}/registros`, {headers:H}).then(r=>r.json()),
      fetch(`/api/metas/${meta.id}/periodos`,  {headers:H}).then(r=>r.json()),
    ]);
    registros = regsData;
    periodos  = perData;
    meta.registros = registros;
    meta.periodos  = periodos;
  }

  async function createRegistro(iso, icone) {
    const r = await fetch(`/api/metas/${meta.id}/registros`, {
      method:'POST', headers:hdrsJ(),
      body: JSON.stringify({data:iso, icone})
    });
    if (!r.ok) { showToast((await r.json()).error || 'Erro ao criar registro'); return null; }
    return (await r.json()).id;
  }

  async function moveRegistro(id, iso) {
    const r = await fetch(`/api/registros/${id}`, {
      method:'PUT', headers:hdrsJ(),
      body: JSON.stringify({data:iso})
    });
    if (!r.ok) { showToast((await r.json()).error || 'Erro ao mover'); return null; }
    return id;
  }

  async function deleteRegistroLocal(id) {
    await fetch(`/api/registros/${id}`, {method:'DELETE', headers:hdrsJ()});
    await reloadData();
    renderGrid();
  }

  function burstEffect(cell, icone) {
    const rect = cell.getBoundingClientRect();
    const cx = rect.left + rect.width/2, cy = rect.top + rect.height/2;
    ['✨','⭐','💫','✨',icone,icone].forEach((p,i) => {
      const angle = (360/6)*i - 90;
      const dist  = 32 + Math.random()*18;
      const tx = Math.cos(angle*Math.PI/180)*dist;
      const ty = Math.sin(angle*Math.PI/180)*dist;
      const el = document.createElement('span');
      el.textContent = p;
      el.style.cssText = `position:fixed;left:${cx}px;top:${cy}px;font-size:${12+Math.random()*7}px;pointer-events:none;z-index:999;transform:translate(-50%,-50%) scale(0);opacity:1;transition:transform .6s cubic-bezier(.17,.67,.25,1.3),opacity .6s ease;will-change:transform,opacity`;
      document.body.appendChild(el);
      requestAnimationFrame(() => requestAnimationFrame(() => {
        el.style.transform = `translate(calc(-50% + ${tx}px),calc(-50% + ${ty}px)) scale(1)`;
        el.style.opacity = '0';
      }));
      setTimeout(() => el.remove(), 700);
    });
    const prev = cell.style.transition;
    cell.style.transition = 'background .1s,transform .22s cubic-bezier(.17,.67,.45,1.5)';
    cell.style.background = '#BBFFD8';
    cell.style.transform  = 'scale(1.08)';
    setTimeout(() => {
      cell.style.background = '';
      cell.style.transform  = '';
      setTimeout(() => { cell.style.transition = prev; }, 260);
    }, 180);
  }

  function makeCell(iso, currentMonth) {
    const d       = isoToDate(iso);
    const allowed = isAllowed(iso);
    const atMax   = isAtMax(iso);
    const isToday = iso === toISOl(logicalToday());
    const regs    = regsOnDate(iso);
    const otherM  = currentMonth !== null && d.getMonth() !== currentMonth;

    const cell = document.createElement('div');
    let cls = 'mini-cal-cell';
    if (otherM)  cls += ' other-month';
    if (isToday) cls += ' today';
    else if (allowed) cls += (atMax ? ' allowed at-max' : ' allowed');
    else cls += ' disabled';
    cell.className = cls;
    cell.dataset.date = iso;

    const num = document.createElement('div');
    num.className = 'mini-day-num';
    num.innerHTML = d.getDate() + (isToday ? ' <span class="mini-today-badge">Hoje</span>' : '');
    cell.appendChild(num);

    const regsDiv = document.createElement('div');
    regsDiv.className = 'mini-regs';
    regs.forEach(reg => {
      const chip = document.createElement('span');
      chip.className = 'mini-reg-chip' + (reg.id === animateId ? ' reg-chip-new' : '');
      chip.textContent = reg.icone;
      chip.title = 'Duplo clique para remover';
      chip.draggable = true;
      chip.dataset.id = reg.id;
      chip.addEventListener('dragstart', e => {
        dragData = {type:'move', id:reg.id, fromIso:iso};
        e.dataTransfer.effectAllowed = 'move';
        e.stopPropagation();
      });
      chip.addEventListener('dragend', () => { if(dragData?.type==='move') dragData=null; });
      chip.addEventListener('dblclick', async () => {
        if (!confirm('Remover este ícone?')) return;
        await deleteRegistroLocal(reg.id);
      });
      regsDiv.appendChild(chip);
    });
    cell.appendChild(regsDiv);

    if (meta.maximo_por_dia) {
      const ind = document.createElement('div');
      ind.className = 'mini-max-ind' + (atMax ? ' full' : '');
      ind.textContent = `${regs.length}/${meta.maximo_por_dia}`;
      cell.appendChild(ind);
    }

    if (allowed && !otherM) {
      cell.addEventListener('dragover', e => {
        if (!dragData) return;
        const sameSpot = dragData.type === 'move' && dragData.fromIso === iso;
        if (sameSpot || !isAtMax(iso)) { e.preventDefault(); cell.classList.add('drag-over'); }
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
          await reloadData();
          renderGrid();
          const freshCell = container.querySelector(`[data-date="${iso}"]`);
          if (freshCell) burstEffect(freshCell, snap.icone);
          setTimeout(() => { animateId = null; }, 100);
        }
      });
    }

    return cell;
  }

  function renderGrid() {
    const grid  = container.querySelector('.mini-cal-grid-body');
    const label = container.querySelector('.mini-nav-label');
    grid.innerHTML = '';

    if (viewMode === 'week') {
      const start = swk(navDate);
      for (let i = 0; i < 7; i++) grid.appendChild(makeCell(toISOl(addDaysL(start,i)), null));
      const e = addDaysL(start,6);
      label.textContent = start.getMonth() === e.getMonth()
        ? `${MESES_CAL[start.getMonth()]} ${start.getFullYear()}`
        : `${pad(start.getDate())}/${pad(start.getMonth()+1)} – ${pad(e.getDate())}/${pad(e.getMonth()+1)}/${e.getFullYear()}`;
    } else {
      const year=navDate.getFullYear(), month=navDate.getMonth();
      const first=new Date(year,month,1), last=new Date(year,month+1,0);
      label.textContent = `${MESES_CAL[month]} ${year}`;
      const startPad = first.getDay();
      for (let i=0;i<startPad;i++) {
        const empty=document.createElement('div');
        empty.className='mini-cal-cell disabled other-month';
        grid.appendChild(empty);
      }
      for (let day=1;day<=last.getDate();day++) grid.appendChild(makeCell(toISOl(new Date(year,month,day)), month));
      const total=startPad+last.getDate();
      for (let i=0;i<(7-(total%7))%7;i++) {
        const empty=document.createElement('div');
        empty.className='mini-cal-cell disabled other-month';
        grid.appendChild(empty);
      }
    }
  }

  // Build DOM
  container.innerHTML = `
    <div class="mini-pal-row">
      <div class="mini-pal-title">Arraste um ícone para a data ↓</div>
      <div class="mini-pal-icons"></div>
    </div>
    <div class="mini-cal-controls">
      <div class="mini-view-tabs">
        <button class="mini-view-tab active" data-view="week">Semana</button>
        <button class="mini-view-tab" data-view="month">Mês</button>
      </div>
      <div class="mini-nav-row">
        <button class="mini-nav-btn mini-prev">‹</button>
        <div class="mini-nav-label"></div>
        <button class="mini-nav-btn mini-next">›</button>
      </div>
    </div>
    <div class="mini-cal-grid" style="margin-bottom:4px;">
      <div class="mini-cal-header">Dom</div>
      <div class="mini-cal-header">Seg</div>
      <div class="mini-cal-header">Ter</div>
      <div class="mini-cal-header">Qua</div>
      <div class="mini-cal-header">Qui</div>
      <div class="mini-cal-header">Sex</div>
      <div class="mini-cal-header">Sáb</div>
    </div>
    <div class="mini-cal-grid mini-cal-grid-body"></div>`;

  // Palette
  const palEl = container.querySelector('.mini-pal-icons');
  ICONES_PAL.forEach(ic => {
    const div = document.createElement('div');
    div.className = 'mini-pal-icon';
    div.textContent = ic;
    div.draggable = true;
    div.title = 'Arraste para a data';
    div.addEventListener('dragstart', e => {
      dragData = {type:'new', icone:ic};
      e.dataTransfer.effectAllowed = 'copy';
    });
    div.addEventListener('dragend', () => { if(dragData?.type==='new') dragData=null; });
    palEl.appendChild(div);
  });

  // View tabs
  container.querySelectorAll('.mini-view-tab').forEach(btn => {
    btn.addEventListener('click', () => {
      viewMode = btn.dataset.view;
      container.querySelectorAll('.mini-view-tab').forEach(b => b.classList.toggle('active', b===btn));
      renderGrid();
    });
  });

  // Nav
  container.querySelector('.mini-prev').addEventListener('click', () => {
    navDate = viewMode==='week' ? addDaysL(navDate,-7) : new Date(navDate.getFullYear(), navDate.getMonth()-1, 1);
    renderGrid();
  });
  container.querySelector('.mini-next').addEventListener('click', () => {
    navDate = viewMode==='week' ? addDaysL(navDate,7) : new Date(navDate.getFullYear(), navDate.getMonth()+1, 1);
    renderGrid();
  });

  renderGrid();
}

// ── Render ────────────────────────────────────────────────────────
function renderPeriodo() {
  const now = logicalToday();
  const ws = startOfWeek(now), we = addDays(ws,6);
  const mes = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
  document.getElementById('periodo-label').textContent =
    `Semana ${fmtDia(toISO(ws))} – ${fmtDia(toISO(we))} · ${mes[now.getMonth()]} ${now.getFullYear()}`;
}

function renderCrianca(c, delay) {
  const est = ESTILOS[c.estilo] || ESTILOS[1];

  let sumPct = 0, countMetas = c.metas.length;
  c.metas.forEach(m => { sumPct += calcPeriodoPct(m).pct; });
  const mediaPct = countMetas ? Math.round(sumPct / countMetas) : 0;
  const mood     = moodOf(mediaPct);

  const card = document.createElement('div');
  card.className = 'fc-card';
  card.style.borderLeft = `6px solid ${est.color}`;
  card.style.background = mood.bg;
  card.style.animationDelay = delay + 's';

  const idade = calcIdade((c.data_nascimento||'').substring(0,10));
  const idadeStr = idade !== null ? `· ${idade} ano${idade===1?'':'s'}` : '';
  const avatarContent = c.imagem
    ? `<img src="${c.imagem}" alt="${c.nome}">`
    : est.emoji;

  card.innerHTML = `
    <div class="fc-header" style="cursor:default;">
      <div class="fc-avatar fc-nav-crianca" style="background:${est.soft};box-shadow:0 4px 14px ${est.color}44;cursor:pointer;">
        ${avatarContent}
      </div>
      <div class="fc-nav-crianca" style="cursor:pointer;flex:1;">
        <div class="fc-nome">${c.nome} ${idadeStr}</div>
        <div class="fc-media" style="color:${mood.cor};">Média ${mediaPct}% ${mood.lbl}</div>
      </div>
      <div class="fc-mood-geral">
        <span class="fc-mood-emoji">${mood.emoji}</span>
        <div class="fc-mood-lbl" style="color:${mood.cor};">${mediaPct}%</div>
      </div>
      <div class="fc-chevron" style="margin-left:10px;font-size:20px;color:#B07A45;transition:transform .25s;cursor:pointer;padding:4px 6px;">▾</div>
    </div>
    <div class="fc-collapsible" style="display:none;">
      <div class="fc-divider"></div>
      <div class="fc-metas-list"></div>
    </div>`;

  const collapsible = card.querySelector('.fc-collapsible');
  const chevron     = card.querySelector('.fc-chevron');
  chevron.style.transform = 'rotate(-90deg)';
  chevron.addEventListener('click', () => {
    const open = collapsible.style.display !== 'none';
    collapsible.style.display = open ? 'none' : '';
    chevron.style.transform   = open ? 'rotate(-90deg)' : '';
  });
  card.querySelectorAll('.fc-nav-crianca').forEach(el => {
    el.addEventListener('click', () => { window.location.href = `/crianca/${c.id}`; });
  });

  const metasList = card.querySelector('.fc-metas-list');

  if (!c.metas.length) {
    metasList.innerHTML = `<p style="text-align:center;color:#C7A77C;font-size:14px;padding:12px 0;margin:0;">Nenhuma meta cadastrada</p>`;
  } else {
    c.metas.forEach(m => {
      const { count: cnt, pct } = calcPeriodoPct(m);
      const mm     = moodOf(pct);
      const isSem  = m.tipo === 'semanal';
      const periodLabel = isSem ? 'esta semana' : 'este mês';

      const periodos       = m.periodos || [];
      const completedCount = periodos.filter(p => p.concluida).length;
      const periodLen      = isSem ? 7 : 30;
      const streak         = calcStreak(periodos, periodLen);
      const starsMax       = 8;
      const starsShow      = Math.min(completedCount, starsMax);
      const starsStr       = '⭐'.repeat(starsShow) + (completedCount > starsMax ? ` +${completedCount - starsMax}` : '');

      const row = document.createElement('div');
      row.className = 'meta-row';

      // Top clickable part
      const rowTop = document.createElement('div');
      rowTop.className = 'meta-row-top';
      rowTop.innerHTML = `
        <div class="mr-mood">${mm.emoji}</div>
        <div class="mr-body">
          <div class="mr-desc" title="${m.descricao}">${m.descricao}</div>
          <div class="mr-stars">
            ${completedCount > 0
              ? `<span>${starsStr}</span><span style="font-size:11px;font-weight:700;color:#B07A45;">${completedCount} período${completedCount!==1?'s':''}</span>${streak>=2?`<span class="mr-streak">🔥 ${streak} seguidos</span>`:''}`
              : `<span style="font-size:11px;color:#C7A77C;">Nenhum período completo ainda</span>`}
          </div>
          <div class="mr-track">
            <div class="mr-fill" style="width:0%;background:${mm.cor};" data-pct="${pct}"></div>
          </div>
          <div class="mr-info-row">
            <span class="mr-count" style="color:${mm.cor};">${cnt}/${m.valor_meta} ${periodLabel}</span>
            <span class="mr-tipo-badge" style="background:${isSem?'#FF8FB1':'#C084FC'};">${isSem?'Semanal':'Mensal'}</span>
            <span class="mr-total">✅ ${m.registros.length} total</span>
            <a class="mr-link-btn" href="/crianca/${c.id}/meta/${m.id}">↗ Ver</a>
          </div>
        </div>
        <div class="mr-expand" style="transform:rotate(-90deg);">▾</div>`;

      // Calendar panel (lazy-created on first open)
      const calPanel = document.createElement('div');
      calPanel.className = 'meta-cal-panel';

      let calCreated = false;
      const expandIcon = rowTop.querySelector('.mr-expand');

      // Stop link from toggling calendar
      rowTop.querySelector('.mr-link-btn').addEventListener('click', e => e.stopPropagation());

      rowTop.addEventListener('click', () => {
        const isOpen = calPanel.classList.toggle('open');
        expandIcon.style.transform = isOpen ? '' : 'rotate(-90deg)';
        if (isOpen && !calCreated) {
          calCreated = true;
          createMetaCalendar(m, c.id, calPanel);
        }
      });

      row.appendChild(rowTop);
      row.appendChild(calPanel);
      metasList.appendChild(row);
    });
  }

  return card;
}

function renderFamiliaScore(criancas) {
  const wrap = document.getElementById('familia-score-wrap');
  if (!criancas.length) return;

  let sumAll = 0, countAll = 0;
  criancas.forEach(c => {
    c.metas.forEach(m => {
      sumAll += calcPeriodoPct(m).pct;
      countAll++;
    });
  });

  const pctFam = countAll ? Math.round(sumAll / countAll) : 0;
  const mood   = moodOf(pctFam);

  wrap.innerHTML = `
    <div class="familia-score" style="background:${mood.bg};border-left:6px solid ${mood.cor};">
      <div class="fs-char">${mood.emoji}</div>
      <div class="fs-info">
        <div class="fs-titulo">🏠 Nota Geral da Família</div>
        <div class="fs-label" style="color:${mood.cor};">${mood.lbl}</div>
        <div class="fs-pct" style="color:${mood.cor};">Média familiar: <strong>${pctFam}%</strong> de conclusão esta semana</div>
      </div>
    </div>`;
}

// ── Init ──────────────────────────────────────────────────────────
(async () => {
  try {
    renderPeriodo();
    const data = await loadAll();

    const wrap = document.getElementById('cards-wrap');

    if (!data.length) {
      wrap.innerHTML = `
        <div class="empty-state">
          <div style="font-size:72px;margin-bottom:16px;">👶</div>
          <h2 style="font-family:'Baloo 2',sans-serif;font-weight:700;font-size:22px;color:#E2547F;margin:0 0 8px;">Nenhuma criança ainda</h2>
          <p style="font-size:15px;font-weight:500;color:#B07A45;margin:0;">Cadastre crianças para ver as métricas!</p>
        </div>`;
    } else {
      data.forEach((c, i) => wrap.appendChild(renderCrianca(c, i * 0.08)));
      renderFamiliaScore(data);

      requestAnimationFrame(() => requestAnimationFrame(() => {
        document.querySelectorAll('.mr-fill[data-pct]').forEach(el => {
          el.style.width = el.dataset.pct + '%';
        });
      }));
    }
  } finally {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('content').style.display = 'block';
  }
})();
</script>
@endpush
