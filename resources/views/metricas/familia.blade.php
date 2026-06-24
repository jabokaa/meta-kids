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
.meta-row    { display:flex; align-items:flex-start; gap:12px; padding:10px 0; border-bottom:1.5px dashed #F0E2C8; cursor:pointer; transition:background .15s; border-radius:10px; }
.meta-row:last-child { border-bottom:none; padding-bottom:0; }
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
.mr-total    { font-size:11px; font-weight:600; color:#B07A45; margin-left:auto; }

/* ── Nota geral da família ──────────────────────── */
.familia-score { background:#fff; border-radius:28px; padding:24px 28px; box-shadow:0 6px 28px rgba(180,120,60,.1); display:flex; align-items:center; gap:20px; margin-top:24px; }
.fs-char     { font-size:80px; line-height:1; animation:floaty 3s ease-in-out infinite; flex-shrink:0; }
.fs-info     { flex:1; }
.fs-titulo   { font-family:'Baloo 2',sans-serif; font-weight:700; font-size:14px; color:#8A6A45; margin:0 0 4px; text-transform:uppercase; letter-spacing:1px; }
.fs-label    { font-family:'Baloo 2',sans-serif; font-weight:800; font-size:26px; color:#3D2B1A; margin:0 0 2px; }
.fs-pct      { font-size:13px; font-weight:600; }

.empty-state { text-align:center; padding:60px 20px; background:#fff; border-radius:28px; box-shadow:0 4px 20px rgba(180,120,60,.1); border:2px dashed #F0E2C8; }

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

// ── Helpers ───────────────────────────────────────────────────────
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

function toISO(d) {
  return d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-'+String(d.getDate()).padStart(2,'0');
}
function fmtDia(iso) { const p=iso.split('-'); return `${p[2]}/${p[1]}`; }
function addDays(d,n){ const r=new Date(d); r.setDate(r.getDate()+n); return r; }
function startOfWeek(d){ const r=new Date(d); r.setHours(0,0,0,0); r.setDate(r.getDate()-r.getDay()); return r; }
function calcIdade(iso){ if(!iso) return null; const h=new Date(),n=new Date(iso+'T00:00:00'); let a=h.getFullYear()-n.getFullYear(); const m=h.getMonth()-n.getMonth(); if(m<0||(m===0&&h.getDate()<n.getDate())) a--; return a<0?null:a; }

function moodOf(pct) {
  if (pct>=100) return {emoji:'😄', cor:'#22C55E', bg:'linear-gradient(135deg,#D1FAE5,#fff)', lbl:'Incrível! 🎉'};
  if (pct>= 70) return {emoji:'😊', cor:'#34D399', bg:'linear-gradient(135deg,#ECFDF5,#fff)', lbl:'Muito bem!'};
  if (pct>= 40) return {emoji:'😐', cor:'#F59E0B', bg:'linear-gradient(135deg,#FFFBEB,#fff)', lbl:'Mais ou menos'};
  if (pct>= 15) return {emoji:'😟', cor:'#FB923C', bg:'linear-gradient(135deg,#FFF7ED,#fff)', lbl:'Precisa melhorar'};
  return           {emoji:'😢', cor:'#EF4444', bg:'linear-gradient(135deg,#FFF1F2,#fff)', lbl:'Atenção!'};
}

// Retorna { count, pct } usando o período atual de metas_em_andamento
function calcOnTrackPct(registros, meta) {
  const now      = new Date(); now.setHours(0,0,0,0);
  const todayIso = now.getFullYear()+'-'+String(now.getMonth()+1).padStart(2,'0')+'-'+String(now.getDate()).padStart(2,'0');
  const target   = meta.valor_meta || 1;

  const periodos      = meta.periodos || [];
  const currentPeriod = periodos.find(p => todayIso >= p.data_inicio && todayIso <= p.data_fim);

  if (!currentPeriod) return { count: 0, pct: 0 };

  const count = currentPeriod.contador;

  if (currentPeriod.concluida) return { count, pct: 100 };

  const pStart    = new Date(currentPeriod.data_inicio + 'T00:00:00');
  const pEnd      = new Date(currentPeriod.data_fim    + 'T00:00:00');
  const daysTotal = Math.round((pEnd - pStart) / 86400000) + 1;
  const daysElap  = Math.floor((now  - pStart) / 86400000) + 1;
  const expected  = (daysElap / daysTotal) * target;
  const pct = expected > 0
    ? Math.min(100, Math.round((count / expected) * 100))
    : (count > 0 ? 100 : 0);

  return { count, pct };
}

function starsHTML(count, total, cor) {
  const cap    = Math.min(total, 10);
  const filled = Math.min(count, cap);
  const empty  = cap - filled;
  return `<span style="color:#F59E0B;">${'⭐'.repeat(filled)}</span>`
       + `<span style="filter:grayscale(1);opacity:.35;">${'⭐'.repeat(empty)}</span>`;
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

// ── Render ────────────────────────────────────────────────────────
function renderPeriodo() {
  const now = new Date(); now.setHours(0,0,0,0);
  const ws = startOfWeek(now), we = addDays(ws,6);
  const mes = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
  document.getElementById('periodo-label').textContent =
    `Semana ${fmtDia(toISO(ws))} – ${fmtDia(toISO(we))} · ${mes[now.getMonth()]} ${now.getFullYear()}`;
}

function renderCrianca(c, delay) {
  const est = ESTILOS[c.estilo] || ESTILOS[1];

  // Calcula progresso médio desta criança (relativo ao ritmo esperado)
  let sumPct = 0, countMetas = c.metas.length;
  c.metas.forEach(m => {
    sumPct += calcOnTrackPct(m.registros, m).pct;
  });
  const mediaPct = countMetas ? Math.round(sumPct / countMetas) : 0;
  const mood     = moodOf(mediaPct);

  const card = document.createElement('div');
  card.className = 'fc-card';
  card.style.borderLeft = `6px solid ${est.color}`;
  card.style.background = mood.bg;
  card.style.animationDelay = delay + 's';

  // Header da criança
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
      const { count: cnt, pct } = calcOnTrackPct(m.registros, m);
      const mm     = moodOf(pct);
      const isSem  = m.tipo === 'semanal';
      const periodLabel = isSem ? 'esta semana' : 'este mês';

      // Period completion (from eager-loaded periodos)
      const periodos       = m.periodos || [];
      const completedCount = periodos.filter(p => p.concluida).length;
      const periodLen      = isSem ? 7 : 30;
      const streak         = calcStreak(periodos, periodLen);
      const starsMax       = 8;
      const starsShow      = Math.min(completedCount, starsMax);
      const starsStr       = '⭐'.repeat(starsShow) + (completedCount > starsMax ? ` +${completedCount - starsMax}` : '');

      const row = document.createElement('div');
      row.className = 'meta-row';
      row.style.cursor = 'pointer';
      row.addEventListener('click', () => { window.location.href = `/crianca/${c.id}/meta/${m.id}`; });
      row.addEventListener('mouseenter', () => { row.style.background = 'rgba(0,0,0,.04)'; });
      row.addEventListener('mouseleave', () => { row.style.background = ''; });
      row.innerHTML = `
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
          </div>
        </div>`;
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
      sumAll += calcOnTrackPct(m.registros, m).pct;
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

      // Anima barras após render
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
