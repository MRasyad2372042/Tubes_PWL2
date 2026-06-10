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
                <h3 class="card-title mb-0">{{ $stats['needs_review'] ?? 0 }}</h3>
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
                <h3 class="card-title mb-0">{{ $stats['finalized'] ?? 0 }}</h3>
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
                <span class="fw-semibold d-block mb-1 text-muted">Aksi Cepat</span>
                <a href="{{ route('review.index') }}" class="btn btn-sm btn-warning mt-1">
                  <i class="bx bx-clipboard me-1"></i> Review Draf
                </a>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-info">
                  <i class="bx bx-check-shield"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Draf Menunggu Review --}}
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="bx bx-time-five text-warning me-1"></i>
          Draf Menunggu Review
        </h5>
        <a href="{{ route('review.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
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
              @forelse($lockedDrafts->take(5) as $index => $draft)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $draft['title'] }}</td>
                <td>{{ $draft['creator_name'] ?? '-' }}</td>
                <td>{{ $draft['year'] }}</td>
                <td>{{ $draft['item_count'] ?? 0 }}</td>
                <td>
                  <a href="{{ route('review.show', $draft['id']) }}" class="btn btn-sm btn-primary">Review</a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  <i class="bx bx-check-double fs-3 d-block mb-2 text-success"></i>
                  Tidak ada draf yang perlu direview saat ini.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Riwayat Finalisasi --}}
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="bx bx-history me-1"></i>
          Riwayat Finalisasi
        </h5>
        <a href="{{ route('review.history') }}" class="btn btn-outline-secondary btn-sm">Lihat Semua</a>
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
                <th>Detail</th>
              </tr>
            </thead>
            <tbody>
              @forelse($finalizedDrafts->take(5) as $index => $draft)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $draft['title'] }}</td>
                <td>{{ $draft['year'] }}</td>
                <td>{{ \Carbon\Carbon::parse($draft['updated_at'])->format('d M Y') }}</td>
                <td>
                  <a href="{{ route('review.history.show', $draft['id']) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-show"></i>
                  </a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-4">
                  <i class="bx bx-folder-open fs-3 d-block mb-2"></i>
                  Belum ada riwayat finalisasi.
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
