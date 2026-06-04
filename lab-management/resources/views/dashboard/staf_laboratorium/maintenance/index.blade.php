@extends('layouts.sneat')

@section('title', 'Catatan Pemeliharaan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Catatan Pemeliharaan</h4>
    <div class="card mb-4">
            <div class="card-body">
            <a href="{{ route('maintenance.create') }}" class="btn btn-primary mb-3">Tambah Catatan</a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3 ms-2">Kembali ke Dashboard</a>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Inventaris</th>
                            <th>Kondisi</th>
                            <th>BHP</th>
                            <th>Jumlah</th>
                            <th>Diganti Dengan</th>
                            <th>Diganti Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($maintenanceLogs as $log)
                            <tr>
                                <td>{{ $log['maintenance_date'] ?? '—' }}</td>
                                <td>{{ $log['inventory_item'] ?? '—' }}</td>
                                <td>{{ $log['condition'] ?? '—' }}</td>
                                <td>{{ $log['bhp_name'] ?? '—' }}</td>
                                <td>{{ $log['bhp_used'] ?? 0 }}</td>
                                <td>{{ $log['replacement_item'] ?? '—' }}</td>
                                <td>{{ $log['replaced_by'] ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada catatan pemeliharaan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
