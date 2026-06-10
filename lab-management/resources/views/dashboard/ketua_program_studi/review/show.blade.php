@extends('layouts.sneat')
@section('title', 'Review Draf — ' . ($draft['title'] ?? ''))
@section('page-title', 'Review Draf')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">{{ $draft['title'] }}</h4>
        <p class="text-muted mb-0">
          Tahun {{ $draft['year'] }} &middot; Oleh: {{ $draft['creator_name'] ?? '-' }} &middot;
          <span class="badge {{ $draft['status'] === 'locked' ? 'bg-label-warning' : 'bg-label-success' }}">
            {{ ucfirst($draft['status']) }}
          </span>
        </p>
      </div>
      <div class="d-flex gap-2">
        @if($draft['status'] === 'locked')
          <a href="{{ route('review.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-arrow-back me-1"></i> Kembali
          </a>
        @else
          <a href="{{ route('review.history') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-arrow-back me-1"></i> Kembali
          </a>
        @endif
      </div>
    </div>

    {{-- Items Table --}}
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="mb-0"><i class="bx bx-list-check me-1"></i> Daftar Barang</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Estimasi Harga</th>
                <th>Link</th>
                <th>Catatan</th>
                <th>Status</th>
                @if($draft['status'] === 'locked')
                  <th>Aksi</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @forelse($draft['items'] ?? [] as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $item['item_name'] }}</strong></td>
                <td>
                  <span class="badge {{ $item['item_type'] === 'inventory' ? 'bg-label-primary' : 'bg-label-info' }}">
                    {{ $item['item_type'] === 'inventory' ? 'Inventaris' : 'BHP' }}
                  </span>
                </td>
                <td>{{ $item['quantity'] }}</td>
                <td>Rp {{ number_format($item['estimated_price'], 0, ',', '.') }}</td>
                <td>
                  @if($item['purchase_link'])
                    <a href="{{ $item['purchase_link'] }}" target="_blank" class="btn btn-sm btn-outline-info">
                      <i class="bx bx-link-external"></i>
                    </a>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>{{ $item['notes'] ?? '-' }}</td>
                <td>
                  @php
                    $approvedBadge = match($item['approved_status']) {
                      'approved' => 'bg-label-success',
                      'rejected' => 'bg-label-danger',
                      default => 'bg-label-secondary',
                    };
                  @endphp
                  <span class="badge {{ $approvedBadge }}">{{ ucfirst($item['approved_status']) }}</span>
                </td>
                @if($draft['status'] === 'locked')
                <td>
                  <div class="d-flex gap-1">
                    @if($item['approved_status'] !== 'approved')
                      <form action="{{ route('review.items.approve', $item['id']) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success" title="Setujui">
                          <i class="bx bx-check"></i>
                        </button>
                      </form>
                    @endif
                    @if($item['approved_status'] !== 'rejected')
                      <form action="{{ route('review.items.reject', $item['id']) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" class="btn btn-sm btn-danger" title="Tolak">
                          <i class="bx bx-x"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
                @endif
              </tr>
              @empty
              <tr>
                <td colspan="{{ $draft['status'] === 'locked' ? 9 : 8 }}" class="text-center text-muted py-4">
                  Tidak ada item dalam draf ini.
                </td>
              </tr>
              @endforelse
            </tbody>
            @if(count($draft['items'] ?? []) > 0)
            <tfoot>
              <tr class="fw-bold">
                <td colspan="4" class="text-end">Total Estimasi (Disetujui):</td>
                <td>Rp {{ number_format(collect($draft['items'])->where('approved_status', 'approved')->sum('estimated_price'), 0, ',', '.') }}</td>
                <td colspan="{{ $draft['status'] === 'locked' ? 4 : 3 }}"></td>
              </tr>
            </tfoot>
            @endif
          </table>
        </div>
      </div>
    </div>

    {{-- Finalize Button --}}
    @if($draft['status'] === 'locked')
    <div class="card">
      <div class="card-body text-center">
        <p class="mb-3 text-muted">Setelah finalisasi, draf tidak dapat diubah lagi. Pastikan semua item sudah direview.</p>
        <form action="{{ route('review.finalize', $draft['id']) }}" method="POST"
              onsubmit="return confirm('Finalisasi draf ini? Tindakan ini tidak dapat dibatalkan.')">
          @csrf
          <button type="submit" class="btn btn-lg btn-success">
            <i class="bx bx-badge-check me-1"></i> Finalisasi Pengajuan
          </button>
        </form>
      </div>
    </div>
    @endif

  </div>
</div>
@endsection
