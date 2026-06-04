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
                    <label for="inventory_item" class="form-label">Nama Inventaris</label>
                    <input type="text" name="inventory_item" id="inventory_item" class="form-control" value="{{ old('inventory_item') }}" required>
                </div>
                <div class="mb-3">
                    <label for="maintenance_date" class="form-label">Tanggal Pemeliharaan</label>
                    <input type="date" name="maintenance_date" id="maintenance_date" class="form-control" value="{{ old('maintenance_date') }}" required>
                </div>
                <div class="mb-3">
                    <label for="condition" class="form-label">Kondisi</label>
                    <input type="text" name="condition" id="condition" class="form-control" value="{{ old('condition') }}" required>
                </div>
                <div class="mb-3">
                    <label for="replacement_item" class="form-label">Stok diganti dengan apa?</label>
                    <input type="text" name="replacement_item" id="replacement_item" class="form-control" value="{{ old('replacement_item') }}">
                </div>
                <div class="mb-3">
                    <label for="replaced_by" class="form-label">Diganti oleh siapa</label>
                    <input type="text" name="replaced_by" id="replaced_by" class="form-control" value="{{ old('replaced_by') }}">
                </div>
                <div class="mb-3">
                    <label for="bhp_item_id" class="form-label">BHP yang digunakan</label>
                    <select name="bhp_item_id" id="bhp_item_id" class="form-select">
                        <option value="">Pilih BHP (opsional)</option>
                        @foreach($bhpItems as $item)
                            <option value="{{ $item['id'] }}" {{ old('bhp_item_id') == $item['id'] ? 'selected' : '' }}>{{ $item['name'] }} ({{ $item['stock'] }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="quantity_used" class="form-label">Jumlah Digunakan</label>
                    <input type="number" name="quantity_used" id="quantity_used" class="form-control" min="0" value="{{ old('quantity_used', 0) }}">
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
