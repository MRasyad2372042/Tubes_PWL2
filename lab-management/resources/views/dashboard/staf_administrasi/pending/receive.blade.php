@extends('layouts.sneat')
@section('title', 'Penerimaan Barang — ' . ($item['item_name'] ?? ''))
@section('page-title', 'Form Penerimaan')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <h4 class="fw-bold py-3 mb-4">Penerimaan Barang: {{ $item['item_name'] }}</h4>

    <div class="row">
      {{-- Item Info --}}
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <div class="card-header"><h6 class="mb-0">Info Barang</h6></div>
          <div class="card-body">
            <dl class="mb-0">
              <dt>Nama Barang</dt>
              <dd>{{ $item['item_name'] }}</dd>
              <dt>Jumlah</dt>
              <dd>{{ $item['quantity'] }}</dd>
              <dt>Estimasi Harga</dt>
              <dd>Rp {{ number_format($item['estimated_price'] ?? 0, 0, ',', '.') }}</dd>
              <dt>Draf Asal</dt>
              <dd>{{ $item['draft_title'] ?? '-' }} ({{ $item['draft_year'] ?? '-' }})</dd>
              @if($item['purchase_link'] ?? false)
              <dt>Link Pembelian</dt>
              <dd><a href="{{ $item['purchase_link'] }}" target="_blank">Buka Link <i class="bx bx-link-external"></i></a></dd>
              @endif
            </dl>
          </div>
        </div>
      </div>

      {{-- Receive Form --}}
      <div class="col-md-8 mb-4">
        <div class="card">
          <div class="card-header"><h6 class="mb-0">Form Penerimaan</h6></div>
          <div class="card-body">
            <form action="{{ route('administrasi.receive.store', $item['procurement_item_id']) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="item_name" value="{{ $item['item_name'] }}">

              <div class="row">
                <div class="col-md-12 mb-3">
                  <label for="receive_date" class="form-label">Tanggal Penerimaan <span class="text-danger">*</span></label>
                  <input type="date" name="receive_date" id="receive_date" class="form-control @error('receive_date') is-invalid @enderror"
                         value="{{ old('receive_date', date('Y-m-d')) }}" required>
                  @error('receive_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              @if(($item['item_type'] ?? '') === 'inventory')
                <input type="hidden" name="item_type" value="inventory">
                <div class="mb-3">
                  <label for="inventory_code" class="form-label">Nomor Label / Kode Inventaris</label>
                  <input type="text" name="inventory_code" id="inventory_code" class="form-control @error('inventory_code') is-invalid @enderror"
                         value="{{ old('inventory_code') }}" placeholder="Contoh: INV-2026-001">
                  <small class="text-muted d-block mt-1">
                    <i class="bx bx-info-circle"></i> QR Code akan dibuat otomatis dari kode ini
                  </small>
                  @error('inventory_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="room_id" class="form-label">Ruangan Penempatan</label>
                  <select name="room_id" id="room_id" class="form-select">
                    <option value="">Pilih ruangan (opsional)</option>
                    @foreach($rooms ?? [] as $room)
                      <option value="{{ $room['id'] }}" {{ old('room_id') == $room['id'] ? 'selected' : '' }}>
                        {{ $room['name'] }} — {{ $room['location'] ?? '' }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="barcode" class="form-label">Foto Barcode (Opsional)</label>
                    <input type="file" name="barcode" id="barcode" class="form-control @error('barcode') is-invalid @enderror" accept="image/*">
                    <small class="text-muted">Max 5MB</small>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="photo" class="form-label">Foto Barang (Opsional)</label>
                    <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                    <small class="text-muted">Max 5MB</small>
                  </div>
                </div>

              @elseif(($item['item_type'] ?? '') === 'bhp')
                <input type="hidden" name="item_type" value="bhp">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="initial_stock" class="form-label">Stok Awal <span class="text-danger">*</span></label>
                    <input type="number" name="initial_stock" id="initial_stock" class="form-control" value="{{ old('initial_stock', $item['quantity']) }}" required min="0">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="unit" class="form-label">Satuan <span class="text-danger">*</span></label>
                    <input type="text" name="unit" id="unit" class="form-control" value="{{ old('unit', 'pcs') }}" placeholder="Contoh: pcs, kotak, botol" required>
                  </div>
                </div>
              @endif

              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                  <i class="bx bx-check me-1"></i> Simpan Penerimaan
                </button>
                <a href="{{ route('administrasi.pending') }}" class="btn btn-outline-secondary">Batal</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
