@extends('layouts.sneat')
@section('title', 'Daftar Draf Pengadaan')

@section('content')
@include('sneat.partials.sidebar')

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap');

.db-orb {
  position: absolute;
  border-radius: 50%;
  filter: blur(90px);
  opacity: 0.55;
  pointer-events: none;
  z-index: 0;
  animation: dbOrbMove 18s infinite alternate cubic-bezier(0.45, 0, 0.55, 1);
}
.db-orb-1 { width: 600px; height: 600px; background: rgba(59,130,246,0.13); top: -200px; right: -100px; }
.db-orb-2 { width: 500px; height: 500px; background: rgba(37,99,235,0.09); bottom: -150px; left: -100px; animation-delay: -6s; }
.db-orb-3 { width: 350px; height: 350px; background: rgba(96,165,250,0.08); top: 40%; left: 30%; animation-delay: -12s; }
@keyframes dbOrbMove {
  0%   { transform: translate(0,0) scale(1); }
  50%  { transform: translate(40px,60px) scale(1.15); }
  100% { transform: translate(-60px,30px) scale(0.92); }
}

.db-page { position: relative; overflow: hidden; }
.db-page > .content-wrapper { position: relative; z-index: 2; }

/* Page header */
.db-page-title {
  font-family: 'Syne', sans-serif !important;
  font-size: 24px !important;
  font-weight: 800 !important;
  letter-spacing: -0.025em;
  color: #0f172a !important;
  margin-bottom: 4px !important;
}
.db-page-sub {
  font-size: 13.5px;
  color: #64748b;
  margin-bottom: 0 !important;
}

/* Primary create button */
.db-btn-create {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 10px 20px;
  background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 60%, #60a5fa 100%);
  background-size: 200% auto;
  border: none;
  border-radius: 11px;
  font-family: 'DM Sans', sans-serif;
  font-size: 13.5px;
  font-weight: 600;
  color: #fff !important;
  text-decoration: none;
  transition: background-position 0.4s, transform 0.2s, box-shadow 0.2s;
  box-shadow: 0 4px 14px rgba(37,99,235,0.25);
  position: relative;
  overflow: hidden;
}
.db-btn-create::before {
  content: "";
  position: absolute;
  top: 0; left: -100%; width: 50%; height: 100%;
  background: linear-gradient(to right, transparent, rgba(255,255,255,0.35), transparent);
  transform: skewX(-20deg);
  transition: 0.5s;
}
.db-btn-create:hover { background-position: right center; transform: translateY(-2px); box-shadow: 0 8px 22px rgba(37,99,235,0.35); color: #fff !important; }
.db-btn-create:hover::before { left: 150%; }

/* Stat cards */
.db-stat-card {
  background: rgba(255,255,255,0.78) !important;
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(255,255,255,0.95) !important;
  border-radius: 18px !important;
  box-shadow: 0 4px 24px rgba(15,23,42,0.06) !important;
  transition: transform 0.25s cubic-bezier(0.175,0.885,0.32,1.275), box-shadow 0.25s !important;
}
.db-stat-card:hover { transform: translateY(-5px) !important; box-shadow: 0 14px 36px rgba(15,23,42,0.10) !important; }
.db-stat-card .card-body { padding: 20px !important; }
.db-stat-label {
  font-size: 11.5px !important;
  font-weight: 600 !important;
  color: #64748b !important;
  letter-spacing: 0.01em;
}
.db-stat-num {
  font-family: 'Syne', sans-serif !important;
  font-size: 34px !important;
  font-weight: 800 !important;
  color: #0f172a !important;
  letter-spacing: -0.04em;
  line-height: 1 !important;
  margin: 0 !important;
}
.db-icon {
  width: 44px !important; height: 44px !important;
  border-radius: 13px !important;
  display: flex; align-items: center; justify-content: center;
}
.db-icon i { font-size: 20px !important; }
.ic-blue  { background: #eff6ff !important; color: #2563eb !important; }
.ic-sky   { background: #f0f9ff !important; color: #0284c7 !important; }
.ic-amber { background: #fffbeb !important; color: #d97706 !important; }
.ic-green { background: #f0fdf4 !important; color: #16a34a !important; }

/* Table card */
.db-table-card {
  background: rgba(255,255,255,0.78) !important;
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(255,255,255,0.95) !important;
  border-radius: 18px !important;
  box-shadow: 0 4px 24px rgba(15,23,42,0.06) !important;
  overflow: hidden;
}
.db-table-card .card-body { padding: 0 !important; }

.db-table thead th {
  font-size: 11px !important;
  font-weight: 700 !important;
  color: #94a3b8 !important;
  text-transform: uppercase !important;
  letter-spacing: 0.07em !important;
  padding: 12px 20px !important;
  background: rgba(248,250,252,0.5) !important;
  border-bottom: 1px solid rgba(226,232,240,0.6) !important;
}
.db-table tbody td {
  padding: 13px 20px !important;
  font-size: 13.5px !important;
  color: #334155 !important;
  border-bottom: 1px solid rgba(226,232,240,0.4) !important;
  vertical-align: middle !important;
}
.db-table tbody tr:last-child td { border-bottom: none !important; }
.db-table tbody tr:hover td { background: rgba(241,245,249,0.6) !important; }
.td-title { font-weight: 600 !important; color: #0f172a !important; }
.td-idx   { font-size: 12px !important; color: #94a3b8 !important; font-weight: 600 !important; }
.td-date  { font-size: 12.5px !important; color: #94a3b8 !important; }

/* Badges */
.db-badge {
  display: inline-flex; align-items: center;
  padding: 4px 12px; border-radius: 999px;
  font-size: 12px; font-weight: 600;
}
.db-badge-draft     { background: #eff6ff; color: #1d4ed8; }
.db-badge-locked    { background: #fffbeb; color: #b45309; }
.db-badge-reviewed  { background: #f5f3ff; color: #6d28d9; }
.db-badge-finalized { background: #f0fdf4; color: #15803d; }
.db-badge-default   { background: #f1f5f9; color: #475569; }

/* Action buttons */
.db-act-btn {
  display: inline-flex; align-items: center; justify-content: center;
  width: 32px; height: 32px;
  border-radius: 8px; border: 1px solid transparent;
  background: transparent; text-decoration: none;
  transition: background 0.2s, transform 0.15s, border-color 0.2s;
  cursor: pointer;
}
.db-act-btn i { font-size: 15px !important; }
.db-act-view  { border-color: rgba(37,99,235,0.22);  color: #2563eb; }
.db-act-view:hover  { background: #eff6ff; transform: scale(1.1); color: #1d4ed8; }
.db-act-edit  { border-color: rgba(217,119,6,0.22);  color: #d97706; }
.db-act-edit:hover  { background: #fffbeb; transform: scale(1.1); color: #b45309; }
.db-act-del   { border-color: rgba(220,38,38,0.22);  color: #dc2626; }
.db-act-del:hover   { background: #fef2f2; transform: scale(1.1); color: #b91c1c; }

/* Empty state */
.db-empty { padding: 52px 24px; text-align: center; color: #94a3b8; }
.db-empty i { font-size: 40px; margin-bottom: 10px; display: block; }
.db-empty p { font-size: 14px; margin-bottom: 6px; }
.db-empty a { color: #2563eb; font-weight: 600; text-decoration: none; }
</style>

<div class="layout-page db-page">
  <div class="db-orb db-orb-1"></div>
  <div class="db-orb db-orb-2"></div>
  <div class="db-orb db-orb-3"></div>

  @include('sneat.partials.navbar')

  <div class="content-wrapper container-xxl container-p-y">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="db-page-title">Draf Pengadaan</h4>
        <p class="db-page-sub">Kelola semua draf pengadaan inventaris dan BHP tahunan.</p>
      </div>
      <a href="{{ route('pengadaan.create') }}" class="db-btn-create">
        <i class="bx bx-plus"></i> Buat Draf Baru
      </a>
    </div>

    {{-- Stat Cards --}}
    <div class="row mb-4">

      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card db-stat-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="db-stat-label d-block mb-2">Total Draf</span>
                <div class="db-stat-num">{{ $stats['total'] ?? 0 }}</div>
              </div>
              <div class="db-icon ic-blue"><i class="bx bx-file-blank"></i></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card db-stat-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="db-stat-label d-block mb-2">Draf Aktif</span>
                <div class="db-stat-num">{{ $stats['draft'] ?? 0 }}</div>
              </div>
              <div class="db-icon ic-sky"><i class="bx bx-edit"></i></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card db-stat-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="db-stat-label d-block mb-2">Menunggu Review</span>
                <div class="db-stat-num">{{ $stats['locked'] ?? 0 }}</div>
              </div>
              <div class="db-icon ic-amber"><i class="bx bx-time"></i></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card db-stat-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="db-stat-label d-block mb-2">Difinalisasi</span>
                <div class="db-stat-num">{{ $stats['finalized'] ?? 0 }}</div>
              </div>
              <div class="db-icon ic-green"><i class="bx bx-check-circle"></i></div>
            </div>
          </div>
        </div>
      </div>

    </div>

    {{-- Table --}}
    <div class="card db-table-card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover mb-0 db-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Judul Draf</th>
                <th>Tahun</th>
                <th>Jumlah Item</th>
                <th>Status</th>
                <th>Terakhir Diubah</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($drafts as $index => $draft)
              <tr>
                <td class="td-idx">{{ $index + 1 }}</td>
                <td class="td-title">{{ $draft['title'] }}</td>
                <td>{{ $draft['year'] }}</td>
                <td>{{ $draft['item_count'] ?? 0 }}</td>
                <td>
                  @php
                    $badgeClass = match($draft['status']) {
                      'draft'     => 'db-badge-draft',
                      'locked'    => 'db-badge-locked',
                      'reviewed'  => 'db-badge-reviewed',
                      'finalized' => 'db-badge-finalized',
                      default     => 'db-badge-default',
                    };
                    $statusLabel = match($draft['status']) {
                      'draft'     => 'Draf',
                      'locked'    => 'Dikunci',
                      'reviewed'  => 'Direview',
                      'finalized' => 'Final',
                      default     => $draft['status'],
                    };
                  @endphp
                  <span class="db-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                </td>
                <td class="td-date">{{ \Carbon\Carbon::parse($draft['updated_at'])->format('d M Y') }}</td>
                <td>
                  <div class="d-flex gap-1">
                    <a href="{{ route('pengadaan.show', $draft['id']) }}" class="db-act-btn db-act-view" title="Lihat">
                      <i class="bx bx-show"></i>
                    </a>
                    @if($draft['status'] === 'draft')
                      <a href="{{ route('pengadaan.edit', $draft['id']) }}" class="db-act-btn db-act-edit" title="Edit">
                        <i class="bx bx-edit"></i>
                      </a>
                      <form action="{{ route('pengadaan.destroy', $draft['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus draf ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="db-act-btn db-act-del" title="Hapus">
                          <i class="bx bx-trash"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="p-0">
                  <div class="db-empty">
                    <i class="bx bx-folder-open"></i>
                    <p>Belum ada draf pengadaan.</p>
                    <a href="{{ route('pengadaan.create') }}">Buat draf pertama Anda.</a>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection