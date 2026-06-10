@extends('layouts.sneat')
@section('title', 'Review Pengadaan')
@section('page-title', 'Review Pengadaan')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">Draf Menunggu Review</h4>
        <p class="text-muted mb-0">Review dan setujui/tolak item pengadaan dari Kepala Laboratorium.</p>
      </div>
      <a href="{{ route('review.history') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bx bx-history me-1"></i> Riwayat Finalisasi
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
                <th>Aksi</th>
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
                <td>
                  <a href="{{ route('review.show', $draft['id']) }}" class="btn btn-sm btn-primary">
                    <i class="bx bx-search-alt me-1"></i> Review
                  </a>
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

  </div>
</div>
@endsection
