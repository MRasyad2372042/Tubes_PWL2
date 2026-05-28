@extends('layouts.sneat')
@section('title', 'Dashboard — Kepala Laboratorium')
@section('page-title', 'Dashboard')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <h4 class="fw-bold py-3 mb-1">Selamat datang, {{ auth()->user()->name }} 👋</h4>
    <p class="text-muted mb-4">Kepala Laboratorium — Kelola draf pengadaan inventaris dan BHP tahunan.</p>

    {{-- Stat Cards --}}
    <div class="row mb-4">
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Total Draf</span>
                <h3 class="card-title mb-0">0</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-primary">
                  <i class="bx bx-file-blank"></i>
                </span>
              </div>
            </div>
            <small class="text-muted mt-2 d-block">Semua draf pengadaan</small>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Menunggu Review</span>
                <h3 class="card-title mb-0">0</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-warning">
                  <i class="bx bx-time"></i>
                </span>
              </div>
            </div>
            <small class="text-warning mt-2 d-block">Belum diproses Kaprodi</small>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Disetujui</span>
                <h3 class="card-title mb-0">0</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-success">
                  <i class="bx bx-check-circle"></i>
                </span>
              </div>
            </div>
            <small class="text-success mt-2 d-block">Draf ter-finalisasi</small>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Ditolak</span>
                <h3 class="card-title mb-0">0</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-danger">
                  <i class="bx bx-x-circle"></i>
                </span>
              </div>
            </div>
            <small class="text-danger mt-2 d-block">Item ditolak Kaprodi</small>
          </div>
        </div>
      </div>
    </div>

    {{-- Draf Terbaru --}}
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Draf Pengadaan Terbaru</h5>
        <a href="#" class="btn btn-primary btn-sm">
          <i class="bx bx-plus me-1"></i> Buat Draf Baru
        </a>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Judul Draf</th>
                <th>Tahun</th>
                <th>Jumlah Item</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  <i class="bx bx-folder-open fs-3 d-block mb-2"></i>
                  Belum ada draf pengadaan.<br>
                  <a href="#">Buat draf pertama Anda.</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
