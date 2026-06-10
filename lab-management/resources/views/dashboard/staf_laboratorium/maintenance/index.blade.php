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
                            <th>Kondisi (Sebelum &rarr; Sesudah)</th>
                            <th>BHP yang Digunakan</th>
                            <th>Dilakukan Oleh</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($maintenanceLogs as $log)
                            <tr>
                                <td>{{ date('d M Y', strtotime($log['maintenance_date'] ?? 'now')) }}</td>
                                <td>
                                    <strong>{{ $log['inventory_item_name'] ?? '—' }}</strong><br>
                                    <small class="text-muted">{{ $log['inventory_code'] ?? 'Tanpa Kode' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($log['condition_before'] ?? '') }}</span> &rarr;
                                    <span class="badge bg-primary">{{ ucfirst($log['condition_after'] ?? '') }}</span>
                                </td>
                                <td>{{ $log['bhp_used_info'] ?? '—' }}</td>
                                <td>{{ $log['performed_by_name'] ?? 'Sistem' }}</td>
                                <td>{{ $log['notes'] ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada catatan pemeliharaan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
