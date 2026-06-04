@extends('layouts.sneat')

@section('title', 'Tambah BHP')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Tambah BHP Baru</h4>
    <div class="card mb-4">
        <div class="card-body">
            <div class="alert alert-warning">
                Staf BHP tidak dapat menambahkan item baru. Silakan gunakan halaman edit untuk mengelola stok item yang sudah ada, atau hubungi Staf Administrasi jika perlu menambahkan item baru.
            </div>
            <a href="{{ route('stock-bhp.index') }}" class="btn btn-secondary">Kembali ke Daftar BHP</a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary ms-2">Kembali ke Dashboard</a>
        </div>
    </div>
</div>
@endsection
