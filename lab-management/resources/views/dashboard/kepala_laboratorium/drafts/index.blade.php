@extends('layouts.sneat')
@section('title', 'Daftar Draf Pengadaan')
@section('page-title', 'Draf Pengadaan')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">Draf Pengadaan</h4>
        <p class="text-muted mb-0">Kelola semua draf pengadaan inventaris dan BHP tahunan.</p>
      </div>
      <a href="{{ route('pengadaan.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Buat Draf Baru
      </a>
    </div>

    {{-- Stat Cards --}}
    <div class="row mb-4">
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Total Draf</span>
                <h3 class="card-title mb-0">{{ $stats['total'] ?? 0 }}</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-file-blank"></i></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Draf Aktif</span>
                <h3 class="card-title mb-0">{{ $stats['draft'] ?? 0 }}</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-info"><i class="bx bx-edit"></i></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Menunggu Review</span>
                <h3 class="card-title mb-0">{{ $stats['locked'] ?? 0 }}</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-time"></i></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Difinalisasi</span>
                <h3 class="card-title mb-0">{{ $stats['finalized'] ?? 0 }}</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-success"><i class="bx bx-check-circle"></i></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Table --}}
    <div class="card">
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
                <th>Terakhir Diubah</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($drafts as $index => $draft)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $draft['title'] }}</strong></td>
                <td>{{ $draft['year'] }}</td>
                <td>{{ $draft['item_count'] ?? 0 }}</td>
                <td>
                  @php
                    $badgeClass = match($draft['status']) {
                      'draft' => 'bg-label-info',
                      'locked' => 'bg-label-warning',
                      'reviewed' => 'bg-label-primary',
                      'finalized' => 'bg-label-success',
                      default => 'bg-label-secondary',
                    };
                    $statusLabel = match($draft['status']) {
                      'draft' => 'Draf',
                      'locked' => 'Dikunci',
                      'reviewed' => 'Direview',
                      'finalized' => 'Final',
                      default => $draft['status'],
                    };
                  @endphp
                  <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                </td>
                <td>{{ \Carbon\Carbon::parse($draft['updated_at'])->format('d M Y') }}</td>
                <td>
                  <a href="{{ route('pengadaan.show', $draft['id']) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-show"></i>
                  </a>
                  @if($draft['status'] === 'draft')
                    <a href="{{ route('pengadaan.edit', $draft['id']) }}" class="btn btn-sm btn-outline-warning">
                      <i class="bx bx-edit"></i>
                    </a>
                    <form action="{{ route('pengadaan.destroy', $draft['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus draf ini?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                    </form>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  <i class="bx bx-folder-open fs-3 d-block mb-2"></i>
                  Belum ada draf pengadaan.<br>
                  <a href="{{ route('pengadaan.create') }}">Buat draf pertama Anda.</a>
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
