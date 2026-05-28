@extends('layouts.sneat')
@section('title', 'Dashboard — Ketua Program Studi')
@section('page-title', 'Dashboard')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <h4 class="fw-bold py-3 mb-1">Selamat datang, {{ auth()->user()->name }} 👋</h4>
    <p class="text-muted mb-4">Ketua Program Studi — Review dan finalisasi draf pengadaan dari Kepala Laboratorium.</p>

    {{-- Stat Cards --}}
    <div class="row mb-4">
      <div class="col-sm-6 col-xl-4 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Perlu Direview</span>
                <h3 class="card-title mb-0">0</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-warning">
                  <i class="bx bx-clipboard"></i>
                </span>
              </div>
            </div>
            <small class="text-warning mt-2 d-block">Draf masuk dari Kepala Lab</small>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-4 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Sudah Difinalisasi</span>
                <h3 class="card-title mb-0">0</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-success">
                  <i class="bx bx-badge-check"></i>
                </span>
              </div>
            </div>
            <small class="text-success mt-2 d-block">Draf terkunci & selesai</small>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-4 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Total Item Disetujui</span>
                <h3 class="card-title mb-0">0</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-info">
                  <i class="bx bx-box"></i>
                </span>
              </div>
            </div>
            <small class="text-info mt-2 d-block">Dari semua draf</small>
          </div>
        </div>
      </div>
    </div>

    {{-- Draf Menunggu Review --}}
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="bx bx-time-five text-warning me-1"></i>
          Draf Menunggu Review
        </h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Judul Draf</th>
                <th>Kepala Lab</th>
                <th>Tahun</th>
                <th>Jumlah Item</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  <i class="bx bx-check-double fs-3 d-block mb-2 text-success"></i>
                  Tidak ada draf yang perlu direview saat ini.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Riwayat Finalisasi --}}
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="bx bx-history me-1"></i>
          Riwayat Finalisasi
        </h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Judul Draf</th>
                <th>Tahun</th>
                <th>Tanggal Finalisasi</th>
                <th>Status</th>
                <th>Detail</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  <i class="bx bx-folder-open fs-3 d-block mb-2"></i>
                  Belum ada riwayat finalisasi.
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
