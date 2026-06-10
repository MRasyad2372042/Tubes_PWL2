@php $role = auth()->user()->role; @endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ url('/dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <i class="bx bx-test-tube fs-3 text-primary"></i>
      </span>
      <span class="app-brand-text demo menu-text fw-bolder ms-2">LabSystem</span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">

    {{-- Dashboard (semua role) --}}
    <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
      <a href="{{ url('/dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div>Dashboard</div>
      </a>
    </li>

    {{-- ADMINISTRATOR --}}
    @if($role === 'administrator')
      <li class="menu-header small text-uppercase"><span class="menu-header-text">Manajemen</span></li>
      <li class="menu-item {{ request()->is('users*') ? 'active' : '' }}">
        <a href="{{ route('users.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-group"></i>
          <div>Pengguna</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('rooms*') ? 'active' : '' }}">
        <a href="{{ route('rooms.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-building"></i>
          <div>Ruangan</div>
        </a>
      </li>
    @endif

    {{-- KEPALA LABORATORIUM --}}
    @if($role === 'kepala_laboratorium')
      <li class="menu-header small text-uppercase"><span class="menu-header-text">Pengadaan</span></li>
      <li class="menu-item {{ request()->is('pengadaan/buat*') ? 'active' : '' }}">
        <a href="{{ route('pengadaan.create') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-file-blank"></i>
          <div>Buat Draf</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('pengadaan') || request()->is('pengadaan/[0-9]*') ? 'active' : '' }}">
        <a href="{{ route('pengadaan.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-list-ul"></i>
          <div>Riwayat Draf</div>
        </a>
      </li>
    @endif

    {{-- KETUA PROGRAM STUDI --}}
    @if($role === 'ketua_program_studi')
      <li class="menu-header small text-uppercase"><span class="menu-header-text">Review</span></li>
      <li class="menu-item {{ request()->is('review') || request()->is('review/[0-9]*') ? 'active' : '' }}">
        <a href="{{ route('review.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-check-shield"></i>
          <div>Review Pengadaan</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('review/history*') ? 'active' : '' }}">
        <a href="{{ route('review.history') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-history"></i>
          <div>Riwayat Finalisasi</div>
        </a>
      </li>
    @endif

    {{-- STAF ADMINISTRASI --}}
    @if($role === 'staf_administrasi')
      <li class="menu-header small text-uppercase"><span class="menu-header-text">Inventaris</span></li>
      <li class="menu-item {{ request()->is('administrasi/pending*') || request()->is('administrasi/receive*') ? 'active' : '' }}">
        <a href="{{ route('administrasi.pending') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-package"></i>
          <div>Penerimaan Barang</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('administrasi/inventaris*') ? 'active' : '' }}">
        <a href="{{ route('administrasi.inventaris') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-desktop"></i>
          <div>Daftar Inventaris</div>
        </a>
      </li>
    @endif

    {{-- STAF LABORATORIUM --}}
    @if($role === 'staf_laboratorium')
      <li class="menu-header small text-uppercase"><span class="menu-header-text">Laboratorium</span></li>
      <li class="menu-item {{ request()->is('laboratorium/stock-bhp*') ? 'active' : '' }}">
        <a href="{{ route('stock-bhp.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-cabinet"></i>
          <div>Stok BHP</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('laboratorium/bhp-pending*') ? 'active' : '' }}">
        <a href="{{ route('bhp-pending.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-plus-circle"></i>
          <div>Penerimaan BHP</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('laboratorium/bhp-usage*') ? 'active' : '' }}">
        <a href="{{ route('bhp-usage.create') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-minus-circle"></i>
          <div>Pemakaian BHP</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('laboratorium/bhp-logs*') ? 'active' : '' }}">
        <a href="{{ route('bhp-logs.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-transfer"></i>
          <div>Log Stok BHP</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('laboratorium/maintenance*') ? 'active' : '' }}">
        <a href="{{ route('maintenance.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-wrench"></i>
          <div>Log Maintenance</div>
        </a>
      </li>
    @endif

  </ul>
</aside>
