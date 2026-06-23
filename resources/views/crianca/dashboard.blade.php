@extends('layouts.app')

@section('title', 'Dashboard — Meta Kids')

@push('styles')
<style>
  .dash-wrap  { position:relative; z-index:2; max-width:640px; margin:0 auto; padding:28px 20px 100px; }

  .back-btn   { font-family:'Fredoka',sans-serif; font-size:15px; font-weight:600; color:#B07A45; padding:8px 18px; border:2px solid #F0E2C8; border-radius:14px; background:#fff; cursor:pointer; display:inline-flex; align-items:center; gap:6px; margin-bottom:20px; transition:background .15s; }
  .back-btn:hover { background:#FFE9D0; }

  /* spinner */
  .spinner-wrap { display:flex; flex-direction:column; align-items:center; gap:16px; padding:80px 0; }
  .spinner      { width:52px; height:52px; border:5px solid #FFD9A8; border-top-color:#FF6F91; border-radius:50%; animation:spin .8s linear infinite; }

  /* header criança */
  .crianca-header { text-align:center; margin-bottom:32px; animation:popIn .3s ease; }
  .crianca-avatar { width:100px; height:100px; border-radius:50%; object-fit:cover; border:5px solid #fff; box-shadow:0 8px 28px rgba(226,84,127,.28); margin:0 auto 14px; display:flex; align-items:center; justify-content:center; font-size:52px; }
  .crianca-avatar img { width:100%; height:100%; border-radius:50%; object-fit:cover; }
  .crianca-nome   { font-family:'Baloo 2',sans-serif; font-weight:700; font-size:30px; margin:0 0 4px; color:#E2547F; }
  .crianca-idade  { margin:0; font-size:15px; font-weight:600; color:#B07A45; }

  /* metas */
  .metas-header  { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
  .metas-titulo  { font-family:'Baloo 2',sans-serif; font-weight:700; font-size:20px; color:#5B4630; }
  .metas-badge   { font-size:13px; font-weight:600; color:#C7A77C; background:#FFF8F0; padding:5px 12px; border-radius:20px; border:2px solid #F0E2C8; }

  .meta-card     { display:flex; align-items:flex-start; gap:14px; padding:16px 18px; border-radius:20px; background:#fff; margin-bottom:14px; box-shadow:0 4px 18px rgba(0,0,0,.08); animation:fadeIn .22s ease both; transition:transform .18s ease, box-shadow .18s ease; cursor:pointer; text-decoration:none; color:inherit; border-left:6px solid #F0E2C8; }
  .meta-card:hover { transform:translateY(-3px); box-shadow:0 8px 28px rgba(0,0,0,.14); }
  .meta-icon     { width:44px; height:44px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0; }
  .meta-desc     { font-family:'Baloo 2',sans-serif; font-weight:700; font-size:17px; color:#3D2B1A; margin-bottom:4px; line-height:1.2; }
  .meta-tag      { display:inline-block; font-size:12px; font-weight:600; padding:2px 10px; border-radius:20px; margin-bottom:10px; }

  /* progresso */
  .meta-progress { display:flex; align-items:center; gap:10px; margin-top:6px; }
  .bonequinho    { font-size:32px; flex-shrink:0; line-height:1; filter:drop-shadow(0 2px 4px rgba(0,0,0,.15)); transition:transform .3s ease; }
  .bonequinho:hover { transform:scale(1.2) rotate(-5deg); }
  .progress-wrap { flex:1; min-width:0; }
  .progress-bar  { height:10px; border-radius:20px; background:#F0E2C8; overflow:hidden; margin-bottom:5px; }
  .progress-fill { height:100%; border-radius:20px; transition:width .6s cubic-bezier(.34,1.56,.64,1); }
  .progress-labels { display:flex; align-items:center; justify-content:space-between; }
  .progress-pct  { font-size:13px; font-weight:700; }
  .progress-count{ font-size:12px; font-weight:600; color:#8A6A45; }

  .empty-metas   { text-align:center; padding:50px 20px; background:#fff; border-radius:28px; box-shadow:0 4px 20px rgba(180,120,60,.1); border:2px dashed #F0E2C8; }

  #content { display:none; }
</style>
@endpush

@section('decos')
  <div style="position:fixed;top:40px;left:-40px;width:140px;height:140px;border-radius:50%;background:#FFC9DD;opacity:.5;animation:floaty 7s ease-in-out infinite;pointer-events:none;z-index:0;"></div>
  <div style="position:fixed;top:80px;right:60px;font-size:72px;animation:floaty 5s ease-in-out infinite;pointer-events:none;z-index:0;">🎯</div>
  <div style="position:fixed;bottom:50px;left:60px;font-size:56px;animation:floaty 8s ease-in-out infinite;pointer-events:none;z-index:0;">⭐</div>
  <div style="position:fixed;bottom:-40px;right:-30px;width:180px;height:180px;border-radius:50%;background:#BDE9C9;opacity:.45;pointer-events:none;z-index:0;"></div>
  <div style="position:fixed;top:35%;left:6%;font-size:36px;animation:floaty 10s ease-in-out infinite;pointer-events:none;z-index:0;">🌟</div>
@endsection

@section('content')
<div class="dash-wrap">
  <button class="back-btn" onclick="history.back()">← Voltar</button>

  <!-- Loading -->
  <div id="loading" class="spinner-wrap">
    <div class="spinner"></div>
    <p style="margin:0;font-size:17px;font-weight:600;color:#B07A45;">Carregando...</p>
  </div>

  <!-- Conteúdo -->
  <div id="content">
    <div class="crianca-header">
      <div class="crianca-avatar" id="avatar"></div>
      <h1 class="crianca-nome" id="crianca-nome"></h1>
      <p class="crianca-idade" id="crianca-idade"></p>
    </div>

    <div class="metas-header">
      <div class="metas-titulo">🎯 Minhas Metas</div>
      <div class="metas-badge" id="metas-badge"></div>
    </div>

    <div id="metas-list"></div>

    <div id="empty-metas" class="empty-metas" style="display:none;">
      <div style="font-size:72px;margin-bottom:16px;animation:floaty 4s ease-in-out infinite;display:inline-block;">🎯</div>
      <h2 style="font-family:'Baloo 2',sans-serif;font-weight:700;font-size:22px;color:#E2547F;margin:0 0 8px;">Nenhuma meta ainda!</h2>
      <p style="font-size:15px;font-weight:500;color:#B07A45;margin:0;line-height:1.5;">Peça para um adulto adicionar<br>suas primeiras metas ✨</p>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const ESTILOS = {
  1: { emoji:'🌟', color:'#F59E0B', soft:'#FFFBEB' },
  2: { emoji:'🦄', color:'#C084FC', soft:'#F5F3FF' },
  3: { emoji:'🚀', color:'#60A5FA', soft:'#EFF6FF' },
  4: { emoji:'🐯', color:'#FB923C', soft:'#FFF7ED' },
  5: { emoji:'🌈', color:'#34D399', soft:'#ECFDF5' },
};

function getEstilo(id) { return ESTILOS[id] || ESTILOS[1]; }

function calcIdade(dataNasc) {
  if (!dataNasc) return null;
  const hoje = new Date(), nasc = new Date(dataNasc + 'T00:00:00');
  let anos = hoje.getFullYear() - nasc.getFullYear();
  const m = hoje.getMonth() - nasc.getMonth();
  if (m < 0 || (m === 0 && hoje.getDate() < nasc.getDate())) anos--;
  return anos < 0 ? '< 1 ano' : anos === 1 ? '1 ano' : `${anos} anos`;
}

function fmtData(iso) {
  if (!iso) return '';
  const d = iso.substring(0, 10).split('-');
  return `${d[2]}/${d[1]}`;
}

function getId() {
  const m = location.pathname.match(/\/crianca\/(\d+)/);
  return m ? m[1] : null;
}

function progressInfo(cumpridas, total) {
  const pct = total > 0 ? Math.min(100, Math.round((cumpridas / total) * 100)) : 0;

  let cor, bonequinho, label;
  if (pct >= 80) {
    cor = '#22C55E';
    bonequinho = '😄';
    label = 'Arrasando!';
  } else if (pct >= 50) {
    cor = '#F59E0B';
    bonequinho = '😐';
    label = 'Quase lá!';
  } else {
    cor = '#EF4444';
    bonequinho = '😢';
    label = 'Vamos lá!';
  }

  return { pct, cor, bonequinho, label };
}

async function load() {
  const id = getId();
  if (!id) return;

  const headers = { 'X-Requested-With': 'XMLHttpRequest' };

  try {
    const [crianca, metas] = await Promise.all([
      fetch(`/api/criancas/${id}`, { headers }).then(r => r.json()),
      fetch(`/api/criancas/${id}/metas`, { headers }).then(r => r.json()),
    ]);

    const metasArr = Array.isArray(metas) ? metas : [];

    const registrosPorMeta = await Promise.all(
      metasArr.map(m =>
        fetch(`/api/metas/${m.id}/registros`, { headers })
          .then(r => r.json())
          .then(regs => ({ metaId: m.id, count: Array.isArray(regs) ? regs.length : 0 }))
          .catch(() => ({ metaId: m.id, count: 0 }))
      )
    );

    const contagemMap = {};
    registrosPorMeta.forEach(r => { contagemMap[r.metaId] = r.count; });

    renderCrianca(crianca);
    renderMetas(metasArr, crianca, contagemMap);
  } catch (e) {
    console.error(e);
  } finally {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('content').style.display = 'block';
  }
}

function renderCrianca(c) {
  const est = getEstilo(c.estilo || 1);
  const avatar = document.getElementById('avatar');

  avatar.style.background = est.soft;
  avatar.style.boxShadow  = `0 8px 28px ${est.color}44`;
  avatar.style.border     = '5px solid #fff';

  if (c.imagem) {
    const img = document.createElement('img');
    img.src = c.imagem;
    img.onerror = () => { img.remove(); avatar.textContent = est.emoji; };
    avatar.appendChild(img);
  } else {
    avatar.textContent = est.emoji;
    avatar.style.animation = 'wiggle 3.5s ease-in-out infinite';
    avatar.style.transformOrigin = 'bottom center';
  }

  document.getElementById('crianca-nome').textContent  = c.nome;
  const idade = calcIdade((c.data_nascimento || '').substring(0, 10));
  if (idade) document.getElementById('crianca-idade').textContent = '🎂 ' + idade;
}

function renderMetas(metas, crianca, contagemMap) {
  const est    = getEstilo(crianca.estilo || 1);
  const list   = document.getElementById('metas-list');
  const empty  = document.getElementById('empty-metas');
  const badge  = document.getElementById('metas-badge');

  badge.textContent = `${metas.length} meta${metas.length === 1 ? '' : 's'}`;

  if (metas.length === 0) { empty.style.display = 'block'; return; }

  metas.forEach((m, i) => {
    const isSemanal  = m.tipo === 'semanal';
    const cumpridas  = contagemMap[m.id] || 0;
    const total      = m.valor_meta || 1;
    const { pct, cor, bonequinho, label } = progressInfo(cumpridas, total);

    const card = document.createElement('a');
    card.className = 'meta-card';
    card.href = `/crianca/${crianca.id}/meta/${m.id}`;
    card.style.borderLeftColor = cor;
    card.style.animationDelay  = `${i * 0.06}s`;

    const periodo = fmtData(m.data_inicio) + (m.data_fim ? ` → ${fmtData(m.data_fim)}` : ' → ∞');

    card.innerHTML = `
      <div class="meta-icon" style="background:${est.soft};box-shadow:inset 0 0 0 2px ${est.color};">
        ${isSemanal ? '📅' : '🗓️'}
      </div>
      <div style="flex:1;min-width:0;">
        <div class="meta-desc">${m.descricao}</div>
        <div>
          <span class="meta-tag" style="color:${cor};background:${cor}18;border:1.5px solid ${cor};">${m.metas}</span>
        </div>
        <div class="meta-progress">
          <div class="bonequinho" title="${label}">${bonequinho}</div>
          <div class="progress-wrap">
            <div class="progress-bar">
              <div class="progress-fill" style="width:${pct}%;background:${cor};"></div>
            </div>
            <div class="progress-labels">
              <span class="progress-pct" style="color:${cor};">${pct}%</span>
              <span class="progress-count">✅ ${cumpridas} de ${total} ${total === 1 ? 'vez' : 'vezes'}</span>
            </div>
          </div>
        </div>
      </div>`;

    list.appendChild(card);
  });
}

load();
</script>
@endpush
