// Helpers compartilhados entre as views do Meta Kids

// O dia lógico vira às 03:00 — antes disso ainda é "ontem"
function logicalToday() {
  const d = new Date();
  d.setTime(d.getTime() - 3 * 60 * 60 * 1000);
  d.setHours(0, 0, 0, 0);
  return d;
}

function calcPeriodoPct(meta) {
  const now = logicalToday();
  const todayIso = now.getFullYear()+'-'+String(now.getMonth()+1).padStart(2,'0')+'-'+String(now.getDate()).padStart(2,'0');
  const target   = meta.valor_meta || 1;
  const periodos = Array.isArray(meta.periodos) ? meta.periodos : [];
  const current  = periodos.find(p => todayIso >= p.data_inicio && todayIso <= p.data_fim);
  if (!current) return { count: 0, pct: 0 };
  const count = current.contador;
  if (current.concluida) return { count, pct: 100 };
  const pStart    = new Date(current.data_inicio + 'T00:00:00');
  const pEnd      = new Date(current.data_fim    + 'T00:00:00');
  const daysTotal = Math.round((pEnd - pStart) / 86400000) + 1;
  const daysElap  = Math.floor((now  - pStart) / 86400000) + 1;
  const expected  = (daysElap / daysTotal) * target;
  const pct = expected > 0
    ? Math.min(100, Math.round((count / expected) * 100))
    : (count > 0 ? 100 : 0);
  return { count, pct };
}

function moodOf(pct) {
  if (pct >= 100) return { emoji:'😄', cor:'#22C55E', bg:'linear-gradient(135deg,#D1FAE5,#fff)', bgSoft:'#ECFDF5', lbl:'Incrível! 🎉' };
  if (pct >=  70) return { emoji:'😊', cor:'#34D399', bg:'linear-gradient(135deg,#ECFDF5,#fff)', bgSoft:'#F0FDF4', lbl:'Muito bem!' };
  if (pct >=  40) return { emoji:'😐', cor:'#F59E0B', bg:'linear-gradient(135deg,#FFFBEB,#fff)', bgSoft:'#FFFBEB', lbl:'Quase lá!' };
  if (pct >=  15) return { emoji:'😟', cor:'#FB923C', bg:'linear-gradient(135deg,#FFF7ED,#fff)', bgSoft:'#FFF7ED', lbl:'Vamos melhorar!' };
  return           { emoji:'😢', cor:'#EF4444', bg:'linear-gradient(135deg,#FFF1F2,#fff)', bgSoft:'#FFF1F2', lbl:'Atenção!' };
}

function calcStreak(periodos, periodLen) {
  const done = [...periodos]
    .filter(p => p.concluida)
    .sort((a, b) => b.data_inicio.localeCompare(a.data_inicio));
  if (!done.length) return 0;
  let s = 1;
  for (let i = 1; i < done.length; i++) {
    const prev = new Date(done[i-1].data_inicio + 'T00:00:00');
    const curr = new Date(done[i].data_inicio   + 'T00:00:00');
    if (Math.round((prev - curr) / 86400000) === periodLen) s++;
    else break;
  }
  return s;
}
