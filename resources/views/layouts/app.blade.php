<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Meta Kids')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Baloo+2:wght@500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; }
    body {
      font-family: 'Fredoka', sans-serif;
      -webkit-font-smoothing: antialiased;
      background: radial-gradient(120% 120% at 50% 0%, #FFF3D6 0%, #FFE7C0 55%, #FFD9A8 100%);
      min-height: 100vh;
    }
    @keyframes floaty { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-14px); } }
    @keyframes wiggle  { 0%,100% { transform: rotate(-7deg); } 50% { transform: rotate(7deg); } }
    @keyframes spin    { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    @keyframes fadeIn  { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes popIn   { from { opacity: 0; transform: scale(.88); } to { opacity: 1; transform: scale(1); } }
    .kid-input::placeholder  { color: #C7A77C; font-weight: 500; }
    .kid-input:focus         { outline: none; box-shadow: inset 0 0 0 3px #FF8FB1 !important; background: #fff !important; }
    .kid-input-sm::placeholder { color: #C7A77C; font-weight: 500; }
    .kid-input-sm:focus        { outline: none; box-shadow: inset 0 0 0 3px #FF8FB1 !important; }
    .btn-submit { width:100%; font-family:'Baloo 2',sans-serif; font-weight:700; font-size:20px; color:#fff; padding:16px; border:none; border-radius:20px; cursor:pointer; background:linear-gradient(150deg,#FF9EC0,#FF6F91); box-shadow:0 8px 0 #E2547F, 0 14px 24px rgba(226,84,127,.3); display:flex; align-items:center; justify-content:center; gap:10px; transition:transform .12s ease; }
    .btn-submit:hover  { transform:translateY(-2px); }
    .btn-submit:active { transform:translateY(4px); box-shadow:0 4px 0 #E2547F, 0 8px 14px rgba(226,84,127,.3); }
    .link { font-size:14px; font-weight:600; color:#E2547F; text-decoration:none; }
    .divider-line { flex:1; height:2px; background:#F0E2C8; border-radius:2px; }
    .top-bar { position:fixed;top:12px;right:14px;z-index:300;display:flex;gap:6px;align-items:center; }
    .top-bar-btn { font-family:'Fredoka',sans-serif;font-size:13px;font-weight:600;color:#B07A45;background:rgba(255,255,255,.88);border:2px solid #F0E2C8;border-radius:12px;padding:6px 13px;cursor:pointer;backdrop-filter:blur(10px);transition:background .14s;text-decoration:none;display:flex;align-items:center;gap:5px;white-space:nowrap; }
    .top-bar-btn:hover { background:#FFE9D0; }
  </style>
  @stack('styles')
</head>
<body>

  @auth
  <div class="top-bar">
    <a href="/metricas" class="top-bar-btn" title="Métricas da Família">📊 Família</a>
    <a href="/perfis"   class="top-bar-btn" title="Trocar de perfil">👨‍👩‍👧</a>
    <form method="POST" action="/logout" style="margin:0;">
      @csrf
      <button type="submit" class="top-bar-btn">Sair ↩</button>
    </form>
  </div>
  @endauth

  @section('decos')
    <div style="position:fixed;top:50px;left:-40px;width:150px;height:150px;border-radius:50%;background:#FFC9DD;opacity:.55;animation:floaty 7s ease-in-out infinite;pointer-events:none;z-index:0;"></div>
    <div style="position:fixed;top:90px;right:70px;font-size:80px;animation:floaty 6s ease-in-out infinite;pointer-events:none;z-index:0;">☁️</div>
    <div style="position:fixed;bottom:40px;left:70px;font-size:64px;animation:floaty 8s ease-in-out infinite;pointer-events:none;z-index:0;">🌈</div>
    <div style="position:fixed;bottom:-50px;right:-30px;width:200px;height:200px;border-radius:50%;background:#BDE9C9;opacity:.5;pointer-events:none;z-index:0;"></div>
    <div style="position:fixed;top:30%;left:8%;font-size:40px;animation:floaty 9s ease-in-out infinite;pointer-events:none;z-index:0;">⭐</div>
  @show

  @yield('content')

  <script src="/meta-kids-helpers.js"></script>
  @stack('scripts')
</body>
</html>
