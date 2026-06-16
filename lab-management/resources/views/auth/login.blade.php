@extends('layouts.auth')

@section('title', 'Login — LabProcure')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap');

#lp-root, #lp-root * { box-sizing: border-box; }

#lp-root {
  position: fixed; inset: 0; z-index: 9999;
  display: flex; overflow: hidden;
  font-family: 'DM Sans', sans-serif;
  background: #f8fafc; /* Dominan Putih */
}

/* 1. AURORA / MESH BACKGROUND */
.lp-orb {
  position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.6; z-index: 0;
  animation: lpOrbMove 15s infinite alternate cubic-bezier(0.45, 0, 0.55, 1);
}
.lp-orb-1 { width: 50vw; height: 50vw; background: rgba(59, 130, 246, 0.15); top: -20%; left: -10%; }
.lp-orb-2 { width: 40vw; height: 40vw; background: rgba(37, 99, 235, 0.12); bottom: -10%; right: -10%; animation-delay: -5s; }
.lp-orb-3 { width: 30vw; height: 30vw; background: rgba(96, 165, 250, 0.1); top: 30%; left: 40%; animation-delay: -10s; }

@keyframes lpOrbMove {
  0% { transform: translate(0, 0) scale(1); }
  50% { transform: translate(5vw, 10vh) scale(1.2); }
  100% { transform: translate(-10vw, 5vh) scale(0.9); }
}

#lp-canvas { position: absolute; inset: 0; pointer-events: none; z-index: 1; }

/* LEFT SIDE - INFO */
#lp-left {
  flex: 1.2; display: flex; flex-direction: column;
  justify-content: center; padding: 56px 68px;
  position: relative; z-index: 2; perspective: 1000px;
}
.lp-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2);
  border-radius: 999px; padding: 6px 16px;
  font-size: 11.5px; font-weight: 600; color: #2563eb;
  letter-spacing: 0.08em; text-transform: uppercase;
  margin-bottom: 32px; width: fit-content;
  animation: lpReveal 0.8s ease both;
}
.lp-badge-dot {
  width: 6px; height: 6px; background: #3b82f6; border-radius: 50%;
  box-shadow: 0 0 10px #3b82f6; animation: lpPulse 2s infinite;
}
@keyframes lpPulse { 0%, 100% { opacity: 1; transform: scale(1) } 50% { opacity: 0.4; transform: scale(0.6) } }

.lp-headline {
  font-family: 'Syne', sans-serif; font-size: clamp(38px, 4vw, 56px);
  font-weight: 800; line-height: 1.05; letter-spacing: -0.03em; color: #0f172a;
  margin: 0 0 16px; animation: lpReveal 0.8s 0.1s ease both;
}
.lp-headline span { background: linear-gradient(135deg, #1e3a8a, #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: block; }

.lp-sub {
  font-size: 16px; font-weight: 400; color: #475569; line-height: 1.7;
  max-width: 420px; margin: 0 0 48px; animation: lpReveal 0.8s 0.2s ease both;
}

/* 3D TILT FEATURES */
.lp-features { display: flex; flex-direction: column; gap: 16px; animation: lpReveal 0.8s 0.3s ease both; transform-style: preserve-3d; }
.lp-feat {
  display: flex; align-items: center; gap: 16px; padding: 16px 20px;
  background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.8); border-radius: 16px;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03);
  transition: transform 0.1s ease-out, box-shadow 0.1s ease-out; /* Controlled by JS */
  will-change: transform;
}
.lp-feat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.lp-feat-icon.em { background: #eff6ff; color: #2563eb; }
.lp-feat-icon.gld { background: #fffbeb; color: #d97706; }
.lp-feat-icon.bl { background: #f0fdf4; color: #16a34a; }
.lp-feat-txt strong { display: block; font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 3px; }
.lp-feat-txt span { font-size: 13px; color: #64748b; font-weight: 400; }

/* RIGHT SIDE - FORM (Glassmorphism White) */
#lp-right {
  flex: 0 0 480px; display: flex; align-items: center; justify-content: center;
  padding: 40px 50px; position: relative; z-index: 5;
  background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px);
  border-left: 1px solid rgba(255, 255, 255, 1);
  box-shadow: -20px 0 50px rgba(0, 0, 0, 0.03);
  overflow-y: auto;
}

.lp-card { width: 100%; max-width: 360px; animation: lpRevealRight 0.8s 0.2s cubic-bezier(0.16, 1, 0.3, 1) both; }

.lp-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 40px; }
.lp-brand-icon {
  width: 46px; height: 46px; background: linear-gradient(135deg, #1d4ed8, #3b82f6);
  border-radius: 14px; display: flex; align-items: center; justify-content: center;
  box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25); animation: lpGlowBlue 3s infinite;
}
@keyframes lpGlowBlue { 0%, 100% { box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3); } 50% { box-shadow: 0 10px 30px rgba(37, 99, 235, 0.5); transform: translateY(-2px); } }
.lp-brand-name { font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; }
.lp-brand-sub { display: block; font-family: 'DM Sans', sans-serif; font-size: 10.5px; font-weight: 500; color: #64748b; letter-spacing: 0.05em; text-transform: uppercase; margin-top: 2px; }

.lp-title { font-family: 'Syne', sans-serif; font-size: 26px; font-weight: 800; letter-spacing: -0.03em; color: #0f172a; margin: 0 0 6px; }
.lp-subtitle { font-size: 14px; color: #64748b; font-weight: 400; margin: 0 0 32px; }

/* ERROR ALERT */
.lp-alert { background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 12px 16px; margin-bottom: 24px; font-size: 13px; color: #ef4444; }
.lp-alert ul { padding-left: 18px; margin: 0; }

/* INPUT FIELDS */
.lp-field { margin-bottom: 20px; }
.lp-label { display: block; font-size: 12.5px; font-weight: 600; color: #475569; margin-bottom: 8px; letter-spacing: 0.01em; }
.lp-input-wrap { position: relative; }
.lp-input-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; display: flex; transition: color 0.3s; }
.lp-input {
  width: 100%; height: 50px; background: #f1f5f9; border: 1px solid #e2e8f0;
  border-radius: 12px; padding: 0 46px; font-family: 'DM Sans', sans-serif;
  font-size: 14px; color: #0f172a; outline: none;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.lp-input::placeholder { color: #94a3b8; }
.lp-input:focus { background: #ffffff; border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15); }
.lp-input-wrap:focus-within .lp-input-icon { color: #3b82f6; }

.lp-toggle-pwd { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; display: flex; padding: 4px; transition: color 0.3s; }
.lp-toggle-pwd:hover { color: #3b82f6; }

.lp-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
.lp-check-wrap { display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; color: #64748b; font-weight: 500; user-select: none; }
.lp-check {
  width: 18px; height: 18px; border-radius: 5px; border: 1px solid #cbd5e1;
  background: #fff; appearance: none; -webkit-appearance: none; cursor: pointer;
  position: relative; transition: all 0.2s; flex-shrink: 0;
}
.lp-check:checked { background: #3b82f6; border-color: #3b82f6; }
.lp-check:checked::after { content: ''; position: absolute; left: 5px; top: 2px; width: 5px; height: 9px; border: 2px solid #fff; border-top: none; border-left: none; transform: rotate(45deg); }
.lp-forgot { font-size: 13px; font-weight: 600; color: #3b82f6; text-decoration: none; transition: color 0.2s; }
.lp-forgot:hover { color: #1d4ed8; }

/* MAGNETIC BUTTON */
.lp-btn {
  width: 100%; height: 52px; background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 50%, #60a5fa 100%);
  background-size: 200% auto; border: none; border-radius: 12px;
  font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 700;
  color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;
  position: relative; overflow: hidden; box-shadow: 0 10px 25px rgba(37, 99, 235, 0.25);
  transition: background-position 0.4s, transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  margin-bottom: 24px;
}
.lp-btn:hover { background-position: right center; box-shadow: 0 15px 35px rgba(37, 99, 235, 0.4); }
/* Shine Effect */
.lp-btn::before {
  content: ""; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
  background: linear-gradient(to right, transparent, rgba(255,255,255,0.4), transparent);
  transform: skewX(-20deg); transition: 0.5s; z-index: 1; pointer-events: none;
}
.lp-btn:hover::before { left: 150%; }

.lp-btn-inner { position: relative; z-index: 2; display: flex; align-items: center; gap: 8px; transition: transform 0.3s; }
.lp-btn:hover .lp-btn-arrow { transform: translateX(6px); }
.lp-spinner { position: absolute; width: 22px; height: 22px; border: 2.5px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: lpSpin 0.7s linear infinite; opacity: 0; transition: opacity 0.2s; }
.lp-btn.loading .lp-btn-inner { opacity: 0; } .lp-btn.loading .lp-spinner { opacity: 1; }
@keyframes lpSpin { to { transform: rotate(360deg); } }

.lp-divider { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; font-size: 12px; color: #94a3b8; font-weight: 500; }
.lp-divider::before, .lp-divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }

.lp-hint { text-align: center; font-size: 13px; color: #64748b; font-weight: 500; }
.lp-hint a { color: #2563eb; text-decoration: none; font-weight: 700; transition: color 0.2s; }
.lp-hint a:hover { color: #1d4ed8; }

.lp-footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; }
.lp-copy { font-size: 11px; color: #94a3b8; font-weight: 500; }
.lp-links { display: flex; gap: 16px; }
.lp-links a { font-size: 11px; color: #94a3b8; text-decoration: none; font-weight: 500; transition: color 0.2s; }
.lp-links a:hover { color: #3b82f6; }

/* ANIMATIONS */
@keyframes lpReveal { from { opacity: 0; transform: translateY(30px); filter: blur(10px); } to { opacity: 1; transform: translateY(0); filter: blur(0); } }
@keyframes lpRevealRight { from { opacity: 0; transform: translateX(40px); filter: blur(10px); } to { opacity: 1; transform: translateX(0); filter: blur(0); } }
@keyframes lpShake { 0%, 100% { transform: translateX(0); } 20% { transform: translateX(-8px); } 40% { transform: translateX(8px); } 60% { transform: translateX(-4px); } 80% { transform: translateX(4px); } }
.lp-shake { animation: lpShake 0.4s ease !important; }

#lp-right::-webkit-scrollbar { width: 4px; }
#lp-right::-webkit-scrollbar-track { background: transparent; }
#lp-right::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

@media(max-width: 900px){ #lp-left { display: none; } #lp-right { flex: 1; border-left: none; border-radius: 0; padding: 30px; background: rgba(255,255,255,0.9); } }
</style>

<div id="lp-root">
  <div class="lp-orb lp-orb-1"></div>
  <div class="lp-orb lp-orb-2"></div>
  <div class="lp-orb lp-orb-3"></div>
  
  <canvas id="lp-canvas"></canvas>

  <div id="lp-left">
    <div class="lp-badge">
      <span class="lp-badge-dot"></span>
      Sistem Aktif &amp; Online
    </div>
    <h1 class="lp-headline">
      Digitalisasi
      <span>Aset &amp; Pengadaan</span>
      Laboratorium
    </h1>
    <p class="lp-sub">
      Platform terpadu untuk inventaris digital, pengajuan pengadaan aset dan BHP,
      serta pelacakan siklus barang laboratorium secara real-time.
    </p>
    
    <div class="lp-features" id="lp-3d-wrap">
      <div class="lp-feat lp-3d-card">
        <div class="lp-feat-icon em">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
        </div>
        <div class="lp-feat-txt"><strong>Inventaris Digital</strong><span>Kelola aset &amp; BHP secara terpusat</span></div>
      </div>
      <div class="lp-feat lp-3d-card">
        <div class="lp-feat-icon gld">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0-4.97-4.03-9-9-9S3 7.03 3 12s4.03 9 9 9 9-4.03 9-9"/></svg>
        </div>
        <div class="lp-feat-txt"><strong>Sistem Pengadaan</strong><span>Alur pengajuan yang transparan</span></div>
      </div>
      <div class="lp-feat lp-3d-card">
        <div class="lp-feat-icon bl">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="lp-feat-txt"><strong>Pelacakan Siklus</strong><span>Dari pembelian hingga penghapusan</span></div>
      </div>
    </div>
  </div>

  <div id="lp-right">
    <div class="lp-card">
      <div class="lp-brand">
        <div class="lp-brand-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"/></svg>
        </div>
        <div>
          <div class="lp-brand-name">LabProcure</div>
          <span class="lp-brand-sub">Lab Management System</span>
        </div>
      </div>

      <h2 class="lp-title">Selamat Datang</h2>
      <p class="lp-subtitle">Masuk ke akun Anda untuk melanjutkan</p>

      @if ($errors->any())
        <div class="lp-alert" id="lp-alert">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}" id="lp-form" novalidate>
        @csrf

        <div class="lp-field">
          <label class="lp-label" for="lp-email">Alamat Email</label>
          <div class="lp-input-wrap">
            <span class="lp-input-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            </span>
            <input class="lp-input" type="email" id="lp-email" name="email"
              value="{{ old('email') }}" placeholder="nama@instansi.ac.id"
              required autofocus autocomplete="email"/>
          </div>
        </div>

        <div class="lp-field">
          <label class="lp-label" for="lp-password">Kata Sandi</label>
          <div class="lp-input-wrap">
            <span class="lp-input-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input class="lp-input" type="password" id="lp-password" name="password"
              placeholder="Masukkan kata sandi" required autocomplete="current-password"/>
            <button type="button" class="lp-toggle-pwd" id="lp-toggle-pwd" aria-label="Lihat kata sandi">
              <svg id="lp-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <div class="lp-row">
          <label class="lp-check-wrap">
            <input class="lp-check" type="checkbox" name="remember" id="lp-remember"/>
            Ingat Saya
          </label>
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="lp-forgot">Lupa Sandi?</a>
          @endif
        </div>

        <button type="submit" class="lp-btn" id="lp-submit">
          <div class="lp-spinner"></div>
          <span class="lp-btn-inner">
            Masuk Sekarang
            <span class="lp-btn-arrow">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </span>
          </span>
        </button>

        <div class="lp-divider">ATAU</div>
        <p class="lp-hint">Belum punya akun? <a href="#">Hubungi Admin</a></p>
      </form>

      <div class="lp-footer">
        <span class="lp-copy">© {{ date('Y') }} LabProcure</span>
        <div class="lp-links">
          <a href="#">Privasi</a>
          <a href="#">Bantuan</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  /* 1. GILA: Interactive Canvas Particles (Connects to mouse) */
  const cvs = document.getElementById('lp-canvas');
  const ctx = cvs.getContext('2d');
  let W, H, nodes = [];
  let mouse = { x: -1000, y: -1000, radius: 180 };

  window.addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY; });
  window.addEventListener('mouseout', () => { mouse.x = -1000; mouse.y = -1000; });

  function initCanvas() {
    W = cvs.width = window.innerWidth; H = cvs.height = window.innerHeight;
    nodes = [];
    let numNodes = window.innerWidth < 768 ? 40 : 80;
    for (let i = 0; i < numNodes; i++) {
      nodes.push({
        x: Math.random() * W, y: Math.random() * H,
        vx: (Math.random() - 0.5) * 0.5, vy: (Math.random() - 0.5) * 0.5,
        r: Math.random() * 2 + 1
      });
    }
  }

  function drawCanvas() {
    ctx.clearRect(0, 0, W, H);
    let MD = 130; 
    
    for (let i = 0; i < nodes.length; i++) {
      let n = nodes[i];
      
      // Mouse Interaction (Magnetic Repel + Connect)
      let dxMouse = mouse.x - n.x; let dyMouse = mouse.y - n.y;
      let distMouse = Math.sqrt(dxMouse * dxMouse + dyMouse * dyMouse);
      
      if (distMouse < mouse.radius) {
        ctx.beginPath();
        ctx.strokeStyle = `rgba(37, 99, 235, ${(1 - distMouse/mouse.radius) * 0.4})`;
        ctx.lineWidth = 1;
        ctx.moveTo(n.x, n.y); ctx.lineTo(mouse.x, mouse.y); ctx.stroke();
        
        let force = (mouse.radius - distMouse) / mouse.radius;
        n.x -= (dxMouse / distMouse) * force * 1.2;
        n.y -= (dyMouse / distMouse) * force * 1.2;
      }

      // Connect Nodes
      for (let j = i + 1; j < nodes.length; j++) {
        let dx = n.x - nodes[j].x, dy = n.y - nodes[j].y;
        let d = Math.sqrt(dx * dx + dy * dy);
        if (d < MD) {
          ctx.beginPath();
          ctx.strokeStyle = `rgba(96, 165, 250, ${(1 - d/MD) * 0.3})`;
          ctx.lineWidth = 0.8;
          ctx.moveTo(n.x, n.y); ctx.lineTo(nodes[j].x, nodes[j].y); ctx.stroke();
        }
      }

      ctx.beginPath(); ctx.fillStyle = 'rgba(37, 99, 235, 0.5)';
      ctx.arc(n.x, n.y, n.r, 0, Math.PI * 2); ctx.fill();

      n.x += n.vx; n.y += n.vy;
      if (n.x < 0 || n.x > W) n.vx *= -1; if (n.y < 0 || n.y > H) n.vy *= -1;
    }
    requestAnimationFrame(drawCanvas);
  }
  
  window.addEventListener('resize', initCanvas);
  initCanvas(); drawCanvas();

  /* 2. GILA: 3D Tilt Effect pada Fitur Kiri */
  const leftPanel = document.getElementById('lp-left');
  const cards = document.querySelectorAll('.lp-3d-card');
  
  leftPanel.addEventListener('mousemove', (e) => {
    let xAxis = (window.innerWidth / 4 - e.pageX) / 25;
    let yAxis = (window.innerHeight / 2 - e.pageY) / 25;
    cards.forEach(card => {
      card.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg) translateZ(20px)`;
      card.style.boxShadow = `${-xAxis}px ${yAxis}px 20px rgba(37, 99, 235, 0.1)`;
    });
  });
  leftPanel.addEventListener('mouseleave', () => {
    cards.forEach(card => {
      card.style.transform = `rotateY(0deg) rotateX(0deg) translateZ(0)`;
      card.style.boxShadow = `0 10px 30px rgba(15, 23, 42, 0.03)`;
    });
  });

  /* 3. GILA: Magnetic Button Effect pada tombol Submit */
  const btn = document.getElementById('lp-submit');
  btn.addEventListener('mousemove', (e) => {
    const rect = btn.getBoundingClientRect();
    const x = e.clientX - rect.left - rect.width / 2;
    const y = e.clientY - rect.top - rect.height / 2;
    btn.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px) scale(1.02)`;
  });
  btn.addEventListener('mouseleave', () => {
    btn.style.transform = 'translate(0px, 0px) scale(1)';
  });

  /* Toggle Password Script */
  const tBtn = document.getElementById('lp-toggle-pwd');
  const pInp = document.getElementById('lp-password');
  const eye = document.getElementById('lp-eye');
  const OPEN = '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
  const CLOSED = '<path d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 0 1 1.563-4.432m5.921-.78a2.25 2.25 0 0 0-2.846-2.846m7.973 7.973A10.05 10.05 0 0 1 19.503 19a10.048 10.048 0 0 1-9.546-5.746m0 0A9.97 9.97 0 0 0 5.564 6.964m9.986 2.02A7.5 7.5 0 1 1 6.5 12a7.5 7.5 0 0 1 9.986 2.984z"/><line x1="3" y1="3" x2="21" y2="21"/>';
  let shown = false;
  tBtn.addEventListener('click', (e) => {
    e.preventDefault();
    shown = !shown;
    pInp.type = shown ? 'text' : 'password';
    eye.innerHTML = shown ? CLOSED : OPEN;
    tBtn.style.color = shown ? '#3b82f6' : '#94a3b8';
  });

  /* Form Validation & Shake Animation */
  const form = document.getElementById('lp-form');
  const emInp = document.getElementById('lp-email');
  
  function shake(el) {
    el.classList.remove('lp-shake'); void el.offsetWidth;
    el.classList.add('lp-shake'); el.style.borderColor = '#ef4444';
    setTimeout(() => { el.style.borderColor = ''; el.classList.remove('lp-shake'); }, 500);
  }

  form.addEventListener('submit', (e) => {
    let ok = true;
    if (!emInp.value.trim() || !/\S+@\S+\.\S+/.test(emInp.value)) { shake(emInp); ok = false; }
    if (!pInp.value) { shake(pInp); ok = false; }
    if (!ok) { e.preventDefault(); return; }
    
    // Loading State
    btn.classList.add('loading'); btn.style.transform = 'scale(0.98)'; btn.disabled = true;
  });

  if (document.getElementById('lp-alert')) { shake(emInp); shake(pInp); }
});
</script>
@endsection