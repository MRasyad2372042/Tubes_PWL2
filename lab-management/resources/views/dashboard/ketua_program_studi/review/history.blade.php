@extends('layouts.sneat')
@section('title', 'Riwayat Finalisasi')
@section('page-title', 'Riwayat Finalisasi')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">Riwayat Finalisasi</h4>
        <p class="text-muted mb-0">Draf pengadaan yang sudah selesai difinalisasi.</p>
      </div>
      <a href="{{ route('review.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bx bx-clipboard me-1"></i> Draf Menunggu Review
      </a>
    </div>

    <div class="card">
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
                <th>Tanggal Finalisasi</th>
                <th>Detail</th>
              </tr>
            </thead>
            <tbody>
              @forelse($drafts as $index => $draft)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $draft['title'] }}</strong></td>
                <td>{{ $draft['creator_name'] ?? '-' }}</td>
                <td>{{ $draft['year'] }}</td>
                <td>{{ $draft['item_count'] ?? 0 }}</td>
                <td>{{ \Carbon\Carbon::parse($draft['updated_at'])->format('d M Y') }}</td>
                <td>
                  <a href="{{ route('review.history.show', $draft['id']) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-show"></i> Lihat
                  </a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
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
