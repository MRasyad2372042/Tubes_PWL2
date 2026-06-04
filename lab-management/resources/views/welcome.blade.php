@extends('layouts.auth')

@section('title', 'Selamat Datang — LabProcure')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap');

#wp-root, #wp-root * { box-sizing: border-box; }
#wp-root {
  position: fixed; inset: 0; z-index: 9999;
  font-family: 'DM Sans', sans-serif;
  background: #f8fafc; /* Dominan Putih/Ice Blue */
  display: flex; align-items: center; justify-content: center;
  overflow: hidden;
}
#wp-canvas { position: absolute; inset: 0; pointer-events: none; z-index: 0; }

/* Dynamic Background Glowing Orbs */
.wp-bg-orb {
  position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.5; z-index: 0;
  animation: wpOrbFloat 20s infinite alternate ease-in-out;
}
.orb-1 { width: 500px; height: 500px; background: rgba(59, 130, 246, 0.15); top: -10%; left: -10%; }
.orb-2 { width: 600px; height: 600px; background: rgba(96, 165, 250, 0.1); bottom: -20%; right: -10%; animation-delay: -5s; }
.orb-3 { width: 400px; height: 400px; background: rgba(37, 99, 235, 0.08); top: 40%; left: 50%; animation-delay: -10s; }

@keyframes wpOrbFloat {
  0% { transform: translate(0, 0) scale(1); }
  50% { transform: translate(50px, 30px) scale(1.1); }
  100% { transform: translate(-30px, 50px) scale(0.9); }
}

/* ── HERO LAYOUT ── */
.wp-wrap {
  position: relative; z-index: 1;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  width: 100%; max-width: 1100px;
  padding: 48px 40px; min-height: 100vh;
}

/* Top bar (Glassmorphism White) */
.wp-topbar {
  position: fixed; top: 0; left: 0; right: 0; z-index: 10;
  display: flex; align-items: center; justify-content: space-between;
  padding: 20px 48px;
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
  border-bottom: 1px solid rgba(37, 99, 235, 0.1);
  animation: wpFD 0.8s ease both;
}
.wp-topbrand { display: flex; align-items: center; gap: 12px; }
.wp-topicon {
  width: 38px; height: 38px; 
  background: linear-gradient(135deg, #1d4ed8, #3b82f6); 
  border-radius: 10px; display: flex; align-items: center; justify-content: center;
  box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
  animation: wpIconPulse 3s infinite;
}
@keyframes wpIconPulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
.wp-topname { font-family: 'Syne', sans-serif; font-size: 17px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; }
.wp-topname sub { display: block; font-family: 'DM Sans', sans-serif; font-size: 10px; font-weight: 500; color: #64748b; letter-spacing: 0.05em; text-transform: uppercase; }
.wp-topbadge {
  display: inline-flex; align-items: center; gap: 7px;
  background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2);
  border-radius: 999px; padding: 5px 14px; font-size: 11px; font-weight: 600;
  color: #2563eb; letter-spacing: 0.05em; text-transform: uppercase;
}
.wp-topbadge-dot { width: 6px; height: 6px; background: #3b82f6; border-radius: 50%; box-shadow: 0 0 8px #3b82f6; animation: wpPulse 2s infinite; }
@keyframes wpPulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

/* HERO CONTENT */
.wp-hero {
  display: flex; flex-direction: column; align-items: center; text-align: center;
  padding-top: 80px; max-width: 800px; width: 100%;
}

.wp-eyebrow {
  display: inline-flex; align-items: center; gap: 8px;
  background: #ffffff; border: 1px solid rgba(37, 99, 235, 0.15);
  box-shadow: 0 4px 20px rgba(37, 99, 235, 0.08);
  border-radius: 999px; padding: 6px 16px;
  font-size: 11.5px; font-weight: 600; color: #2563eb;
  letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 32px;
  animation: wpFU 0.8s 0.1s cubic-bezier(0.16, 1, 0.3, 1) both;
}

.wp-headline {
  font-family: 'Syne', sans-serif; font-size: clamp(40px, 6vw, 72px);
  font-weight: 800; line-height: 1.1; letter-spacing: -0.04em; color: #0f172a;
  margin: 0 0 24px; animation: wpFU 0.8s 0.2s cubic-bezier(0.16, 1, 0.3, 1) both;
}
.wp-headline .em {
  background: linear-gradient(135deg, #1e3a8a, #3b82f6);
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.wp-headline .dim { color: #94a3b8; }

.wp-tagline {
  font-size: clamp(16px, 1.8vw, 18px); font-weight: 400; color: #475569;
  line-height: 1.7; max-width: 600px; margin: 0 auto 52px;
  animation: wpFU 0.8s 0.3s cubic-bezier(0.16, 1, 0.3, 1) both;
}

/* CTA Button */
.wp-cta-group {
  display: flex; align-items: center; gap: 16px; flex-wrap: wrap; justify-content: center;
  animation: wpFU 0.8s 0.4s cubic-bezier(0.16, 1, 0.3, 1) both; margin-bottom: 80px;
}

.wp-btn-primary {
  display: inline-flex; align-items: center; gap: 10px;
  height: 54px; padding: 0 36px;
  background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 50%, #60a5fa 100%);
  background-size: 200% auto; border: none; border-radius: 14px;
  font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700;
  color: #fff; cursor: pointer; text-decoration: none;
  position: relative; overflow: hidden;
  box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
  transition: all 0.3s ease;
}
.wp-btn-primary:hover { background-position: right center; transform: translateY(-4px); box-shadow: 0 15px 35px rgba(37, 99, 235, 0.4); }
.wp-btn-primary:active { transform: translateY(0); }
.wp-btn-primary .arr { transition: transform 0.3s; }
.wp-btn-primary:hover .arr { transform: translateX(6px); }

/* Shimmer Effect on Button */
.wp-btn-primary::after {
  content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
  background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
  transform: skewX(-25deg); animation: wpShine 4s infinite;
}
@keyframes wpShine { 0%, 50% { left: -100%; } 100% { left: 200%; } }

.wp-btn-ghost {
  display: inline-flex; align-items: center; gap: 9px;
  height: 54px; padding: 0 32px; background: #ffffff;
  border: 1px solid #cbd5e1; border-radius: 14px;
  font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 600;
  color: #475569; cursor: pointer; text-decoration: none;
  box-shadow: 0 4px 10px rgba(0,0,0,0.02); transition: all 0.3s ease;
}
.wp-btn-ghost:hover { border-color: #94a3b8; color: #0f172a; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.06); }

/* STAT STRIP */
.wp-stats {
  display: flex; align-items: stretch; gap: 1px;
  background: #e2e8f0; border: 1px solid #e2e8f0;
  border-radius: 16px; overflow: hidden; margin-bottom: 80px;
  animation: wpFU 0.8s 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
  width: 100%; max-width: 720px;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
}
.wp-stat {
  flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;
  padding: 24px 16px; background: #ffffff; transition: background 0.3s;
}
.wp-stat:hover { background: #f8fafc; }
.wp-stat-num {
  font-family: 'Syne', sans-serif; font-size: clamp(24px, 2.5vw, 32px);
  font-weight: 800; color: #1d4ed8; letter-spacing: -0.02em; margin-bottom: 6px;
}
.wp-stat-lbl { font-size: 12px; color: #64748b; font-weight: 500; letter-spacing: 0.02em; text-align: center; }

/* FEATURE CARDS (With Continuous Float) */
.wp-cards {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; width: 100%;
  animation: wpFU 0.8s 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
}
@media(max-width: 768px){ .wp-cards { grid-template-columns: 1fr; } }

.wp-card {
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
  border: 1px solid #e2e8f0; border-radius: 20px;
  padding: 30px 24px; display: flex; flex-direction: column; gap: 16px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.03);
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  cursor: default; position: relative; overflow: hidden;
  animation: floatCards 6s ease-in-out infinite alternate;
}
.wp-card:nth-child(2) { animation-delay: 1s; }
.wp-card:nth-child(3) { animation-delay: 2s; }

@keyframes floatCards { 0% { transform: translateY(0); } 100% { transform: translateY(-10px); } }

.wp-card:hover {
  border-color: #93c5fd; transform: translateY(-15px) scale(1.02);
  box-shadow: 0 20px 40px rgba(37, 99, 235, 0.12);
  animation-play-state: paused;
}
/* Shine effect on hover */
.wp-card::before {
  content: ""; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
  background: linear-gradient(to right, transparent, rgba(255,255,255,0.8), transparent);
  transform: skewX(-20deg); transition: 0.5s; z-index: 1; pointer-events: none;
}
.wp-card:hover::before { left: 150%; }

.wp-card-icon {
  width: 48px; height: 48px; border-radius: 14px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  position: relative; z-index: 2;
}
.wp-card-icon.em { background: #eff6ff; color: #2563eb; }
.wp-card-icon.gld { background: #fffbeb; color: #d97706; }
.wp-card-icon.bl { background: #f0fdf4; color: #16a34a; }

.wp-card-title { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; color: #0f172a; margin: 0; position: relative; z-index: 2; }
.wp-card-desc { font-size: 13.5px; color: #475569; font-weight: 400; line-height: 1.6; margin: 0; position: relative; z-index: 2; }
.wp-card-tag {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 999px;
  width: fit-content; position: relative; z-index: 2;
}
.wp-card-tag.em { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
.wp-card-tag.gld { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
.wp-card-tag.bl { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }

/* footer */
.wp-footer {
  position: fixed; bottom: 0; left: 0; right: 0; z-index: 10;
  display: flex; align-items: center; justify-content: center; gap: 32px;
  padding: 16px 48px; background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
  border-top: 1px solid rgba(0,0,0,0.05);
  animation: wpFD 0.8s 0.8s ease both;
}
.wp-footer a { font-size: 12px; color: #64748b; text-decoration: none; transition: color 0.2s; font-weight: 500; }
.wp-footer a:hover { color: #2563eb; }
.wp-footer span { font-size: 12px; color: #cbd5e1; }

@keyframes wpFU { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes wpFD { from { opacity: 0; } to { opacity: 1; } }
</style>

<div id="wp-root">
  <div class="wp-bg-orb orb-1"></div>
  <div class="wp-bg-orb orb-2"></div>
  <div class="wp-bg-orb orb-3"></div>
  <canvas id="wp-canvas"></canvas>

  <div class="wp-topbar">
    <div class="wp-topbrand">
      <div class="wp-topicon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"/></svg>
      </div>
      <div>
        <div class="wp-topname">LabProcure <sub>Lab Management System</sub></div>
      </div>
    </div>
    <div class="wp-topbadge">
      <span class="wp-topbadge-dot"></span>
      v2.4 · Sistem Online
    </div>
  </div>

  <div class="wp-wrap">
    <div class="wp-hero">
      <div class="wp-eyebrow">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m13 2-2 2.5h3L12 7"/><path d="M10 14v-3"/><path d="M14 14v-3"/><path d="M11 19H6.93a2 2 0 0 1-1.96-2.27L6 10h12l1.03 6.73A2 2 0 0 1 17.07 19H13"/><path d="M19 19h2"/><path d="M3 19h2"/></svg>
        Platform Manajemen Laboratorium
      </div>

      <h1 class="wp-headline">
        Kelola Lab Anda<br>
        <span class="em">Lebih Cerdas.</span>
        <span class="dim"> Lebih Efisien.</span>
      </h1>

      <p class="wp-tagline">
        Sistem terpadu untuk digitalisasi inventaris aset dan BHP, pengajuan pengadaan,
        serta pelacakan siklus barang laboratorium dari pengadaan hingga penghapusan.
      </p>

      <div class="wp-cta-group">
        <a href="{{ route('login') }}" class="wp-btn-primary" id="wp-cta">
          Masuk ke Dashboard
          <span class="arr">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
          </span>
        </a>
        <a href="#" class="wp-btn-ghost">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
          Panduan Penggunaan
        </a>
      </div>

      <div class="wp-stats">
        <div class="wp-stat">
          <div class="wp-stat-num" id="wp-n1">0</div>
          <div class="wp-stat-lbl">Aset Terdaftar</div>
        </div>
        <div class="wp-stat">
          <div class="wp-stat-num" id="wp-n2">0</div>
          <div class="wp-stat-lbl">Pengajuan Bulan Ini</div>
        </div>
        <div class="wp-stat">
          <div class="wp-stat-num" id="wp-n3">0</div>
          <div class="wp-stat-lbl">Jenis BHP Tersedia</div>
        </div>
        <div class="wp-stat">
          <div class="wp-stat-num" id="wp-n4">0%</div>
          <div class="wp-stat-lbl">Tingkat Akurasi Data</div>
        </div>
      </div>

      <div class="wp-cards">
        <div class="wp-card">
          <div class="wp-card-icon em">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
          </div>
          <div>
            <p class="wp-card-title">Inventaris Digital</p>
            <p class="wp-card-desc">Catat dan kelola seluruh aset dan barang habis pakai dalam satu platform terpusat.</p>
          </div>
          <span class="wp-card-tag em">Aset &amp; BHP</span>
        </div>

        <div class="wp-card">
          <div class="wp-card-icon gld">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
          </div>
          <div>
            <p class="wp-card-title">Sistem Pengadaan</p>
            <p class="wp-card-desc">Ajukan permintaan pengadaan dengan alur persetujuan yang transparan dan terstruktur.</p>
          </div>
          <span class="wp-card-tag gld">Pengajuan</span>
        </div>

        <div class="wp-card">
          <div class="wp-card-icon bl">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
          </div>
          <div>
            <p class="wp-card-title">Pelacakan Siklus</p>
            <p class="wp-card-desc">Monitor perjalanan barang dari pengadaan, pemeliharaan, hingga penghapusan.</p>
          </div>
          <span class="wp-card-tag bl">Monitoring</span>
        </div>
      </div>
    </div>
  </div>

  <div class="wp-footer">
    <a href="#">Kebijakan Privasi</a>
    <span>·</span>
    <a href="#">Panduan Pengguna</a>
    <span>·</span>
    <a href="#">Hubungi Admin</a>
    <span>·</span>
    <a href="#">© {{ date('Y') }} LabProcure</a>
  </div>
</div>

<script>
(function(){
  /* Advanced Interactive Canvas */
  const cvs = document.getElementById('wp-canvas');
  const ctx = cvs.getContext('2d');
  let W, H, nodes = [];
  let mouse = { x: null, y: null, radius: 150 };

  window.addEventListener('mousemove', function(e) { mouse.x = e.x; mouse.y = e.y; });
  window.addEventListener('mouseout', function() { mouse.x = null; mouse.y = null; });

  function resize() { W = cvs.width = window.innerWidth; H = cvs.height = window.innerHeight; }
  
  function mkNodes(n) {
    nodes = [];
    for (let i = 0; i < n; i++) {
      nodes.push({
        x: Math.random() * W, y: Math.random() * H,
        vx: (Math.random() - 0.5) * 0.8, vy: (Math.random() - 0.5) * 0.8,
        r: Math.random() * 2 + 1.5,
        baseX: this.x, baseY: this.y
      });
    }
  }

  function draw() {
    ctx.clearRect(0, 0, W, H);
    let MD = 140; // Max distance for connection
    
    for (let i = 0; i < nodes.length; i++) {
      let n = nodes[i];
      // Mouse Interaction (Repel/Connect)
      if (mouse.x != null) {
        let dxMouse = mouse.x - n.x;
        let dyMouse = mouse.y - n.y;
        let distMouse = Math.sqrt(dxMouse * dxMouse + dyMouse * dyMouse);
        if (distMouse < mouse.radius) {
          // Draw line to mouse
          ctx.beginPath();
          ctx.strokeStyle = `rgba(37, 99, 235, ${(1 - distMouse / mouse.radius) * 0.3})`;
          ctx.lineWidth = 1;
          ctx.moveTo(n.x, n.y); ctx.lineTo(mouse.x, mouse.y); ctx.stroke();
          
          // Slight repel effect
          let force = (mouse.radius - distMouse) / mouse.radius;
          n.x -= (dxMouse / distMouse) * force * 1.5;
          n.y -= (dyMouse / distMouse) * force * 1.5;
        }
      }

      // Connect Nodes
      for (let j = i + 1; j < nodes.length; j++) {
        let dx = n.x - nodes[j].x, dy = n.y - nodes[j].y;
        let d = Math.sqrt(dx * dx + dy * dy);
        if (d < MD) {
          ctx.beginPath();
          ctx.strokeStyle = `rgba(96, 165, 250, ${(1 - d / MD) * 0.25})`;
          ctx.lineWidth = 0.8;
          ctx.moveTo(n.x, n.y); ctx.lineTo(nodes[j].x, nodes[j].y); ctx.stroke();
        }
      }

      // Draw Node
      ctx.beginPath();
      ctx.fillStyle = 'rgba(37, 99, 235, 0.6)';
      ctx.arc(n.x, n.y, n.r, 0, Math.PI * 2); ctx.fill();

      // Move Node
      n.x += n.vx; n.y += n.vy;
      if (n.x < 0 || n.x > W) n.vx *= -1;
      if (n.y < 0 || n.y > H) n.vy *= -1;
    }
    requestAnimationFrame(draw);
  }

  window.addEventListener('resize', function() { resize(); mkNodes(70); });
  resize(); mkNodes(70); draw();

  /* Counter animation */
  function animCount(el, target, suffix, dur) {
    suffix = suffix || ''; dur = dur || 2000;
    let start = null, from = 0;
    function step(ts) {
      if (!start) start = ts;
      let prog = Math.min((ts - start) / dur, 1);
      // Ease Out Expo
      let ease = prog === 1 ? 1 : 1 - Math.pow(2, -10 * prog); 
      let val = Math.round(from + (target - from) * ease);
      el.textContent = val.toLocaleString() + suffix;
      if (prog < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }
  
  setTimeout(function() {
    animCount(document.getElementById('wp-n1'), 1248, '');
    animCount(document.getElementById('wp-n2'), 37, '');
    animCount(document.getElementById('wp-n3'), 214, '');
    animCount(document.getElementById('wp-n4'), 99, '%');
  }, 800);
})();
</script>
@endsection