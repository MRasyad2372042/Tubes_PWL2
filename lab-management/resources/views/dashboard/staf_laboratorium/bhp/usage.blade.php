@extends('layouts.sneat')
@section('title', 'Pemakaian BHP')
@section('page-title', 'Pemakaian BHP')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">Pemakaian BHP</h4>
        <p class="text-muted mb-0">Catat penggunaan Barang Habis Pakai harian.</p>
      </div>
      <a href="{{ route('bhp-logs.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bx bx-history me-1"></i> Riwayat Log
      </a>
    </div>

    <div class="row">
      {{-- Usage Form --}}
      <div class="col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-header"><h5 class="mb-0"><i class="bx bx-minus-circle me-1"></i> Form Pemakaian</h5></div>
          <div class="card-body">
            <form action="{{ route('bhp-usage.store') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="bhp_item_id" class="form-label">Item BHP <span class="text-danger">*</span></label>
                <select name="bhp_item_id" id="bhp_item_id" class="form-select" required>
                  <option value="">Pilih BHP</option>
                  @foreach($bhpItems as $item)
                    <option value="{{ $item['id'] }}" {{ old('bhp_item_id') == $item['id'] ? 'selected' : '' }}>
                      {{ $item['item_name'] }} (Stok: {{ $item['stock'] }} {{ $item['unit'] }})
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="quantity_used" class="form-label">Jumlah Digunakan <span class="text-danger">*</span></label>
                <input type="number" name="quantity_used" id="quantity_used" class="form-control"
                       value="{{ old('quantity_used', 1) }}" min="1" required>
              </div>
              <div class="mb-3">
                <label for="type" class="form-label">Tipe Penggunaan <span class="text-danger">*</span></label>
                <select name="type" id="type" class="form-select" required>
                  <option value="outgoing" {{ old('type') == 'outgoing' ? 'selected' : '' }}>Pemakaian Harian</option>
                  <option value="maintenance_usage" {{ old('type') == 'maintenance_usage' ? 'selected' : '' }}>Untuk Maintenance</option>
                </select>
              </div>
              <div class="mb-3" id="maintenanceIdField" style="display:none;">
                <label for="maintenance_id" class="form-label">ID Maintenance (opsional)</label>
                <input type="number" name="maintenance_id" id="maintenance_id" class="form-control"
                       value="{{ old('maintenance_id') }}" placeholder="Masukkan ID log maintenance terkait">
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Keterangan</label>
                <textarea name="description" id="description" class="form-control" rows="2"
                          placeholder="Catatan opsional">{{ old('description') }}</textarea>
              </div>
              <button type="submit" class="btn btn-primary">
                <i class="bx bx-check me-1"></i> Catat Pemakaian
              </button>
            </form>
          </div>
        </div>
      </div>

      {{-- Current Stock Overview --}}
      <div class="col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-header"><h5 class="mb-0"><i class="bx bx-cabinet me-1"></i> Sisa Stok BHP</h5></div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm table-hover">
                <thead>
                  <tr>
                    <th>Nama BHP</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($bhpItems as $item)
                  <tr>
                    <td>{{ $item['item_name'] }}</td>
                    <td class="fw-bold {{ $item['stock'] <= 5 ? 'text-danger' : '' }}">{{ $item['stock'] }}</td>
                    <td>{{ $item['unit'] }}</td>
                    <td>
                      @if($item['stock'] == 0)
                        <span class="badge bg-label-danger">Habis</span>
                      @elseif($item['stock'] <= 5)
                        <span class="badge bg-label-warning">Hampir Habis</span>
                      @else
                        <span class="badge bg-label-success">Aman</span>
                      @endif
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted">Belum ada item BHP terdaftar.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const maintenanceField = document.getElementById('maintenanceIdField');

    function toggleMaintenanceField() {
        maintenanceField.style.display = typeSelect.value === 'maintenance_usage' ? 'block' : 'none';
    }

    typeSelect.addEventListener('change', toggleMaintenanceField);
    toggleMaintenanceField();
});
</script>
@endpush
