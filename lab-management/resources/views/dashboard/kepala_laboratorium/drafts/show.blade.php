@extends('layouts.sneat')
@section('title', 'Detail Draf — ' . ($draft['title'] ?? ''))
@section('page-title', 'Detail Draf')

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
          Tahun {{ $draft['year'] }} &middot;
          @php
            $badgeClass = match($draft['status']) {
              'draft' => 'bg-label-info',
              'locked' => 'bg-label-warning',
              'reviewed' => 'bg-label-primary',
              'finalized' => 'bg-label-success',
              default => 'bg-label-secondary',
            };
          @endphp
          <span class="badge {{ $badgeClass }}">{{ ucfirst($draft['status']) }}</span>
        </p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('pengadaan.index') }}" class="btn btn-outline-secondary btn-sm">
          <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
        @if($draft['status'] === 'draft')
          <a href="{{ route('pengadaan.edit', $draft['id']) }}" class="btn btn-outline-warning btn-sm">
            <i class="bx bx-edit me-1"></i> Edit Draf
          </a>
          <form action="{{ route('pengadaan.lock', $draft['id']) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Setelah dikunci, draf tidak dapat diubah lagi. Lanjutkan?')">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm">
              <i class="bx bx-lock me-1"></i> Kunci Draf
            </button>
          </form>
        @endif
      </div>
    </div>

    {{-- Item List --}}
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bx bx-list-ul me-1"></i> Daftar Barang</h5>
        @if($draft['status'] === 'draft')
          <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addItemForm">
            <i class="bx bx-plus me-1"></i> Tambah Barang
          </button>
        @endif
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
                <th>Menggantikan</th>
                <th>Link Pembelian</th>
                <th>Status</th>
                @if($draft['status'] === 'draft')
                  <th>Aksi</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @forelse($draft['items'] ?? [] as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['item_name'] }}</td>
                <td>
                  <span class="badge {{ $item['item_type'] === 'inventory' ? 'bg-label-primary' : 'bg-label-info' }}">
                    {{ $item['item_type'] === 'inventory' ? 'Inventaris' : 'BHP' }}
                  </span>
                </td>
                <td>{{ $item['quantity'] }}</td>
                <td>Rp {{ number_format($item['estimated_price'], 0, ',', '.') }}</td>
                <td>
                  @if($item['replaced_inventory_id'])
                    <span class="badge bg-label-secondary" title="ID: {{ $item['replaced_inventory_id'] }}">
                      {{ $item['replaced_inventory_name'] ?? 'Inventaris #'.$item['replaced_inventory_id'] }}
                    </span>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>
                  @if($item['purchase_link'])
                    <a href="{{ $item['purchase_link'] }}" target="_blank" class="btn btn-sm btn-outline-info">
                      <i class="bx bx-link-external"></i>
                    </a>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
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
                @if($draft['status'] === 'draft')
                <td>
                  <form action="{{ route('pengadaan.items.destroy', $item['id']) }}?draft_id={{ $draft['id'] }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Hapus item ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                  </form>
                </td>
                @endif
              </tr>
              @empty
              <tr>
                <td colspan="{{ $draft['status'] === 'draft' ? 9 : 8 }}" class="text-center text-muted py-4">
                  <i class="bx bx-package fs-3 d-block mb-2"></i>
                  Belum ada item. Tambahkan barang ke draf ini.
                </td>
              </tr>
              @endforelse
            </tbody>
            @if(count($draft['items'] ?? []) > 0)
            <tfoot>
              <tr class="fw-bold">
                <td colspan="4" class="text-end">Total Estimasi:</td>
                <td>Rp {{ number_format(collect($draft['items'])->sum('estimated_price'), 0, ',', '.') }}</td>
                <td colspan="{{ $draft['status'] === 'draft' ? 4 : 3 }}"></td>
              </tr>
            </tfoot>
            @endif
          </table>
        </div>
      </div>
    </div>

    {{-- Add Item Form (collapsible) --}}
    @if($draft['status'] === 'draft')
    <div class="collapse mb-4" id="addItemForm">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0"><i class="bx bx-plus-circle me-1"></i> Tambah Barang Baru</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('pengadaan.items.store', $draft['id']) }}" method="POST">
            @csrf
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="item_name" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                <input type="text" name="item_name" id="item_name" class="form-control"
                       value="{{ old('item_name') }}" required>
              </div>
              <div class="col-md-3 mb-3">
                <label for="item_type" class="form-label">Tipe <span class="text-danger">*</span></label>
                <select name="item_type" id="item_type" class="form-select" required>
                  <option value="inventory" {{ old('item_type') == 'inventory' ? 'selected' : '' }}>Inventaris</option>
                  <option value="bhp" {{ old('item_type') == 'bhp' ? 'selected' : '' }}>BHP</option>
                </select>
              </div>
              <div class="col-md-3 mb-3">
                <label for="quantity" class="form-label">Jumlah <span class="text-danger">*</span></label>
                <input type="number" name="quantity" id="quantity" class="form-control"
                       value="{{ old('quantity', 1) }}" min="1" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="estimated_price" class="form-label">Estimasi Harga (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="estimated_price" id="estimated_price" class="form-control"
                       value="{{ old('estimated_price') }}" min="0" step="1000" required>
              </div>
              <div class="col-md-4 mb-3">
                <label for="replaced_inventory_id" class="form-label">Menggantikan (Opsi)</label>
                <select name="replaced_inventory_id" id="replaced_inventory_id" class="form-select">
                  <option value="">-- Tidak menggantikan --</option>
                  @foreach($inventories as $inv)
                    <option value="{{ $inv['id'] }}" {{ old('replaced_inventory_id') == $inv['id'] ? 'selected' : '' }}>
                      {{ $inv['item_name'] }} ({{ $inv['inventory_code'] ?? 'Tanpa Kode' }})
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label for="purchase_link" class="form-label">Link Pembelian</label>
                <input type="url" name="purchase_link" id="purchase_link" class="form-control"
                       value="{{ old('purchase_link') }}" placeholder="https://...">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="notes" class="form-label">Catatan</label>
                <input type="text" name="notes" id="notes" class="form-control"
                       value="{{ old('notes') }}" placeholder="Opsional">
              </div>
            </div>
            <button type="submit" class="btn btn-success">
              <i class="bx bx-check me-1"></i> Simpan Item
            </button>
          </form>
        </div>
      </div>
    </div>
    @endif

  </div>
</div>
@endsection
