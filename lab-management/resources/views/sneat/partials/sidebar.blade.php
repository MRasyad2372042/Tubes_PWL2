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
        <a href="#" class="menu-link">
          <i class="menu-icon tf-icons bx bx-file-blank"></i>
          <div>Buat Draf</div>
        </a>
      </li>
      <li class="menu-item {{ request()->is('pengadaan*') ? 'active' : '' }}">
        <a href="#" class="menu-link">
          <i class="menu-icon tf-icons bx bx-list-ul"></i>
          <div>Riwayat Draf</div>
        </a>
      </li>
    @endif

    {{-- KETUA PROGRAM STUDI --}}
    @if($role === 'ketua_program_studi')
      <li class="menu-header small text-uppercase"><span class="menu-header-text">Review</span></li>
      <li class="menu-item">
        <a href="#" class="menu-link">
          <i class="menu-icon tf-icons bx bx-check-shield"></i>
          <div>Review Pengadaan</div>
        </a>
      </li>
    @endif

    {{-- STAF ADMINISTRASI --}}
    @if($role === 'staf_administrasi')
      <li class="menu-header small text-uppercase"><span class="menu-header-text">Inventaris</span></li>
      <li class="menu-item">
        <a href="#" class="menu-link">
          <i class="menu-icon tf-icons bx bx-package"></i>
          <div>Penerimaan Barang</div>
        </a>
      </li>
      <li class="menu-item">
        <a href="#" class="menu-link">
          <i class="menu-icon tf-icons bx bx-qr"></i>
          <div>Label QR / Barcode</div>
        </a>
      </li>
    @endif

    {{-- STAF LABORATORIUM --}}
    @if($role === 'staf_laboratorium')
      <li class="menu-header small text-uppercase"><span class="menu-header-text">Laboratorium</span></li>
      <li class="menu-item">
        <a href="#" class="menu-link">
          <i class="menu-icon tf-icons bx bx-cabinet"></i>
          <div>Stok BHP</div>
        </a>
      </li>
      <li class="menu-item">
        <a href="#" class="menu-link">
          <i class="menu-icon tf-icons bx bx-wrench"></i>
          <div>Log Maintenance</div>
        </a>
      </li>
    @endif

  </ul>
</aside>
