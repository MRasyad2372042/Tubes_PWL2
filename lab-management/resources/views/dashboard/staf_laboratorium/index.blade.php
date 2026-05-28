@extends('layouts.sneat')
@section('title', 'Dashboard — Staf Laboratorium')
@section('page-title', 'Dashboard')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <h4 class="fw-bold py-3 mb-1">Selamat datang, {{ auth()->user()->name }} 👋</h4>
    <p class="text-muted mb-4">Staf Laboratorium — Pantau stok BHP dan catat log maintenance barang inventaris.</p>

    {{-- Stat Cards --}}
    <div class="row mb-4">
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Total BHP</span>
                <h3 class="card-title mb-0">0</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-primary">
                  <i class="bx bx-cabinet"></i>
                </span>
              </div>
            </div>
            <small class="text-muted mt-2 d-block">Jenis BHP terdaftar</small>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Stok Kritis</span>
                <h3 class="card-title mb-0">0</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-danger">
                  <i class="bx bx-error"></i>
                </span>
              </div>
            </div>
            <small class="text-danger mt-2 d-block">BHP hampir habis</small>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Total Inventaris</span>
                <h3 class="card-title mb-0">0</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-info">
                  <i class="bx bx-desktop"></i>
                </span>
              </div>
            </div>
            <small class="text-muted mt-2 d-block">Barang di semua ruangan</small>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Log Maintenance</span>
                <h3 class="card-title mb-0">0</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-warning">
                  <i class="bx bx-wrench"></i>
                </span>
              </div>
            </div>
            <small class="text-muted mt-2 d-block">Catatan bulan ini</small>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      {{-- BHP Stok Kritis --}}
      <div class="col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
              <i class="bx bx-error text-danger me-1"></i> BHP Stok Kritis
            </h5>
            <a href="#" class="btn btn-outline-primary btn-sm">Kelola Stok</a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Nama BHP</th>
                    <th>Satuan</th>
                    <th>Stok</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                      <i class="bx bx-check-shield fs-3 d-block mb-2 text-success"></i>
                      Semua stok BHP dalam kondisi aman.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- Log Maintenance Terbaru --}}
      <div class="col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
              <i class="bx bx-history me-1"></i> Log Maintenance Terbaru
            </h5>
            <a href="#" class="btn btn-outline-primary btn-sm">+ Tambah Log</a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Barang</th>
                    <th>Tanggal</th>
                    <th>Kondisi</th>
                    <th>BHP Dipakai</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                      <i class="bx bx-folder-open fs-3 d-block mb-2"></i>
                      Belum ada log maintenance.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Aksi Cepat --}}
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Aksi Cepat</h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-3">
            <a href="#" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center gap-2">
              <i class="bx bx-cabinet fs-3"></i>
              <span>Kelola Stok BHP</span>
            </a>
          </div>
          <div class="col-md-3">
            <a href="#" class="btn btn-outline-warning w-100 py-3 d-flex flex-column align-items-center gap-2">
              <i class="bx bx-wrench fs-3"></i>
              <span>Tambah Log Maintenance</span>
            </a>
          </div>
          <div class="col-md-3">
            <a href="#" class="btn btn-outline-info w-100 py-3 d-flex flex-column align-items-center gap-2">
              <i class="bx bx-search fs-3"></i>
              <span>Cari Inventaris</span>
            </a>
          </div>
          <div class="col-md-3">
            <a href="#" class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center gap-2">
              <i class="bx bx-list-ul fs-3"></i>
              <span>Lihat Semua Barang</span>
            </a>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
