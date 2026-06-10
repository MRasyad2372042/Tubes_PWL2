@extends('layouts.sneat')
@section('title', 'BHP Menunggu Penerimaan')
@section('page-title', 'Penerimaan BHP')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">BHP Menunggu Penerimaan</h4>
        <p class="text-muted mb-0">Daftar Barang Habis Pakai dari pengadaan yang sudah disetujui dan menunggu diterima ke stok.</p>
      </div>
      <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bx bx-arrow-back me-1"></i> Dashboard
      </a>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th>Jumlah Diajukan</th>
                <th>Estimasi Harga</th>
                <th>Draf Asal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($pendingItems as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $item['item_name'] }}</strong></td>
                <td>{{ $item['quantity'] }}</td>
                <td>Rp {{ number_format($item['estimated_price'], 0, ',', '.') }}</td>
                <td>{{ $item['draft_title'] ?? '-' }} ({{ $item['draft_year'] ?? '-' }})</td>
                <td>
                  <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#receiveModal{{ $item['id'] }}">
                    <i class="bx bx-plus-circle me-1"></i> Terima ke Stok
                  </button>
                </td>
              </tr>

              {{-- Receive Modal --}}
              <div class="modal fade" id="receiveModal{{ $item['id'] }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="{{ route('bhp-receive.store') }}" method="POST">
                      @csrf
                      <input type="hidden" name="procurement_item_id" value="{{ $item['id'] }}">
                      <input type="hidden" name="item_name" value="{{ $item['item_name'] }}">
                      <div class="modal-header">
                        <h5 class="modal-title">Terima BHP: {{ $item['item_name'] }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label class="form-label">Satuan <span class="text-danger">*</span></label>
                          <input type="text" name="unit" class="form-control" placeholder="Contoh: pcs, kg, liter, box" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Jumlah Stok Awal <span class="text-danger">*</span></label>
                          <input type="number" name="initial_stock" class="form-control" value="{{ $item['quantity'] }}" min="0" required>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                          <i class="bx bx-check me-1"></i> Terima
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  <i class="bx bx-check-double fs-3 d-block mb-2 text-success"></i>
                  Semua BHP sudah diterima. Tidak ada yang menunggu penerimaan.
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
