@extends('layouts.sneat')

@section('title', 'Tambah Catatan Pemeliharaan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Tambah Catatan Pemeliharaan</h4>
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('maintenance.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="inventory_id" class="form-label">Inventaris yang Dipelihara <span class="text-danger">*</span></label>
                    <select name="inventory_id" id="inventory_id" class="form-select" required>
                        <option value="">-- Pilih Inventaris --</option>
                        @foreach($inventories ?? [] as $inv)
                            <option value="{{ $inv['id'] }}" {{ old('inventory_id') == $inv['id'] ? 'selected' : '' }}>
                                {{ $inv['item_name'] }} ({{ $inv['inventory_code'] ?? 'Tanpa Kode' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="maintenance_date" class="form-label">Tanggal Pemeliharaan <span class="text-danger">*</span></label>
                    <input type="date" name="maintenance_date" id="maintenance_date" class="form-control" value="{{ old('maintenance_date') }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="condition_before" class="form-label">Kondisi Sebelum <span class="text-danger">*</span></label>
                        <select name="condition_before" id="condition_before" class="form-select" required>
                            <option value="good" {{ old('condition_before') == 'good' ? 'selected' : '' }}>Baik</option>
                            <option value="maintenance" {{ old('condition_before') == 'maintenance' ? 'selected' : '' }}>Dalam Perbaikan</option>
                            <option value="damaged" {{ old('condition_before') == 'damaged' ? 'selected' : '' }}>Rusak</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="condition_after" class="form-label">Kondisi Sesudah <span class="text-danger">*</span></label>
                        <select name="condition_after" id="condition_after" class="form-select" required>
                            <option value="good" {{ old('condition_after') == 'good' ? 'selected' : '' }}>Baik</option>
                            <option value="maintenance" {{ old('condition_after') == 'maintenance' ? 'selected' : '' }}>Dalam Perbaikan</option>
                            <option value="damaged" {{ old('condition_after') == 'damaged' ? 'selected' : '' }}>Rusak</option>
                            <option value="disposed" {{ old('condition_after') == 'disposed' ? 'selected' : '' }}>Dihapus / Dibuang</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="bhp_item_id" class="form-label">BHP yang Digunakan (Opsional)</label>
                    <select name="bhp_item_id" id="bhp_item_id" class="form-select">
                        <option value="">-- Pilih BHP --</option>
                        @foreach($bhpItems as $item)
                            <option value="{{ $item['id'] }}" {{ old('bhp_item_id') == $item['id'] ? 'selected' : '' }}>{{ $item['name'] }} (Stok: {{ $item['stock'] }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="bhp_used" class="form-label">Jumlah BHP Digunakan</label>
                    <input type="number" name="bhp_used" id="bhp_used" class="form-control" min="0" value="{{ old('bhp_used', 0) }}">
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">Batal</a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary ms-2">Kembali ke Dashboard</a>
            </form>
        </div>
    </div>
</div>
@endsection
