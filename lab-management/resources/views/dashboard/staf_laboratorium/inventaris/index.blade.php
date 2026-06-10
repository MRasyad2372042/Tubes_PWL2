@extends('layouts.sneat')
@section('title', 'Daftar Inventaris')
@section('page-title', 'Inventaris')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">Daftar Inventaris</h4>
        <p class="text-muted mb-0">Semua barang inventaris yang terdaftar di sistem untuk pemeliharaan.</p>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th>Kode Inventaris</th>
                <th>Ruangan</th>
                <th>Tgl Diterima</th>
                <th>Kondisi</th>
                <th>QR / Foto</th>
              </tr>
            </thead>
            <tbody>
              @forelse($inventories as $index => $inv)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $inv['item_name'] }}</strong></td>
                <td>
                  @if($inv['inventory_code'])
                    <code>{{ $inv['inventory_code'] }}</code>
                  @else
                    <span class="text-muted fst-italic">Belum diberi kode</span>
                  @endif
                </td>
                <td>{{ $inv['room_name'] ?? '-' }}</td>
                <td>
                  @if($inv['receive_date'])
                    {{ \Carbon\Carbon::parse($inv['receive_date'])->format('d M Y') }}
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>
                  @php
                    $condBadge = match($inv['condition_status']) {
                      'good' => 'bg-label-success',
                      'maintenance' => 'bg-label-warning',
                      'damaged' => 'bg-label-danger',
                      'disposed' => 'bg-label-dark',
                      'replaced' => 'bg-label-info',
                      default => 'bg-label-secondary',
                    };
                  @endphp
                  <span class="badge {{ $condBadge }}">{{ ucfirst($inv['condition_status']) }}</span>
                </td>
                <td>
                  @if($inv['qr_code_path'])
                    <a href="http://localhost:3000{{ $inv['qr_code_path'] }}" target="_blank" class="btn btn-sm btn-outline-info" title="QR Code">
                      <i class="bx bx-qr"></i>
                    </a>
                  @endif
                  @if($inv['photo_path'])
                    <a href="http://localhost:3000{{ $inv['photo_path'] }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Foto">
                      <i class="bx bx-image"></i>
                    </a>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  <i class="bx bx-folder-open fs-3 d-block mb-2"></i>
                  Belum ada data inventaris.
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
