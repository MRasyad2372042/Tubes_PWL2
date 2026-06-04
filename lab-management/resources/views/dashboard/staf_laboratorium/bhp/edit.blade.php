@extends('layouts.sneat')

@section('title', 'Edit BHP')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Edit BHP</h4>
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('stock-bhp.update', $bhpItem['id'] ?? '') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama BHP</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $bhpItem['name'] ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Stok</label>
                    <input type="number" name="stock" id="stock" class="form-control" min="0" value="{{ old('stock', $bhpItem['stock'] ?? 0) }}" required>
                </div>
                <div class="mb-3">
                    <label for="unit" class="form-label">Satuan</label>
                    <input type="text" name="unit" id="unit" class="form-control" value="{{ old('unit', $bhpItem['unit'] ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label for="min_stock" class="form-label">Stok Minimum</label>
                    <input type="number" name="min_stock" id="min_stock" class="form-control" min="0" value="{{ old('min_stock', $bhpItem['min_stock'] ?? 5) }}" required>
                </div>
                <div class="alert alert-info">Stok fisik dikelola oleh Staf Administrasi setelah ACC draf, BHP hanya mengelola data item.</div>
                <button type="submit" class="btn btn-success">Perbarui</button>
                <a href="{{ route('stock-bhp.index') }}" class="btn btn-secondary">Batal</a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary ms-2">Kembali ke Dashboard</a>
            </form>
        </div>
    </div>
</div>
@endsection
