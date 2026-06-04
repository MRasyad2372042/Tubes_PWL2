@extends('layouts.sneat')

@section('title', 'Daftar Stok BHP')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Stok BHP Laboratorium</h4>
    <div class="card mb-4">
            <div class="card-body">
            <div class="alert alert-info mb-3">
                Staf BHP hanya dapat mengelola stok untuk item yang sudah ada. Penambahan item baru dilakukan oleh Staf Administrasi setelah persetujuan.
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama BHP</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Stok Minimum</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bhpItems as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['unit'] }}</td>
                                <td>{{ $item['stock'] }}</td>
                                <td>{{ $item['min_stock'] }}</td>
                                <td>
                                    <a href="{{ route('stock-bhp.edit', $item['id']) }}" class="btn btn-sm btn-warning">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada BHP terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
