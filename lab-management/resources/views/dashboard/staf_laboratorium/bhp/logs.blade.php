@extends('layouts.sneat')
@section('title', 'Riwayat Log Stok BHP')
@section('page-title', 'Log Stok BHP')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">Riwayat Log Stok BHP</h4>
        <p class="text-muted mb-0">Catatan keluar-masuk barang habis pakai.</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('bhp-usage.create') }}" class="btn btn-outline-primary btn-sm">
          <i class="bx bx-minus-circle me-1"></i> Catat Pemakaian
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
          <i class="bx bx-arrow-back me-1"></i> Dashboard
        </a>
      </div>
    </div>

    {{-- Current Stock Summary --}}
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="mb-0"><i class="bx bx-cabinet me-1"></i> Ringkasan Stok Saat Ini</h5>
      </div>
      <div class="card-body">
        <div class="row">
          @forelse($bhpItems as $item)
          <div class="col-sm-6 col-md-4 col-xl-3 mb-3">
            <div class="card border {{ $item['stock'] == 0 ? 'border-danger' : ($item['stock'] <= 5 ? 'border-warning' : '') }}">
              <div class="card-body p-3">
                <h6 class="mb-1">{{ $item['item_name'] }}</h6>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="fs-4 fw-bold {{ $item['stock'] == 0 ? 'text-danger' : ($item['stock'] <= 5 ? 'text-warning' : 'text-success') }}">
                    {{ $item['stock'] }}
                  </span>
                  <span class="text-muted">{{ $item['unit'] }}</span>
                </div>
              </div>
            </div>
          </div>
          @empty
          <div class="col-12 text-center text-muted py-3">Belum ada item BHP terdaftar.</div>
          @endforelse
        </div>
      </div>
    </div>

    {{-- Log Table --}}
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0"><i class="bx bx-history me-1"></i> Riwayat Keluar-Masuk</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Nama BHP</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th>Oleh</th>
              </tr>
            </thead>
            <tbody>
              @forelse($logs as $index => $log)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($log['created_at'])->format('d M Y H:i') }}</td>
                <td>{{ $log['item_name'] ?? '-' }}</td>
                <td>
                  @php
                    $typeBadge = match($log['type']) {
                      'incoming' => 'bg-label-success',
                      'outgoing' => 'bg-label-danger',
                      'maintenance_usage' => 'bg-label-warning',
                      'adjustment' => 'bg-label-info',
                      default => 'bg-label-secondary',
                    };
                    $typeLabel = match($log['type']) {
                      'incoming' => 'Masuk',
                      'outgoing' => 'Keluar',
                      'maintenance_usage' => 'Maintenance',
                      'adjustment' => 'Penyesuaian',
                      default => $log['type'],
                    };
                  @endphp
                  <span class="badge {{ $typeBadge }}">{{ $typeLabel }}</span>
                </td>
                <td>
                  @if($log['type'] === 'incoming' || $log['type'] === 'adjustment')
                    <span class="text-success">+{{ $log['quantity'] }}</span>
                  @else
                    <span class="text-danger">-{{ $log['quantity'] }}</span>
                  @endif
                  {{ $log['unit'] ?? '' }}
                </td>
                <td>{{ $log['description'] ?? '-' }}</td>
                <td>{{ $log['created_by_name'] ?? '-' }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  <i class="bx bx-folder-open fs-3 d-block mb-2"></i>
                  Belum ada riwayat log stok BHP.
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
