@extends('layouts.sneat')
@section('title', 'Barang Menunggu Penerimaan')
@section('page-title', 'Penerimaan Barang')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">Barang Menunggu Penerimaan</h4>
        <p class="text-muted mb-0">Daftar inventaris yang sudah disetujui dan menunggu proses penerimaan fisik.</p>
      </div>
      <a href="{{ route('administrasi.inventaris') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bx bx-list-ul me-1"></i> Lihat Semua Inventaris
      </a>
    </div>

    <div class="card">
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
                <th>Draf Asal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($items as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $item['item_name'] }}</strong></td>
                <td>
                  @if(isset($item['item_type']) && $item['item_type'] === 'bhp')
                    <span class="badge bg-label-warning">BHP</span>
                  @else
                    <span class="badge bg-label-primary">Inventaris</span>
                  @endif
                </td>
                <td>{{ $item['quantity'] }}</td>
                <td>Rp {{ number_format($item['estimated_price'] ?? 0, 0, ',', '.') }}</td>
                <td>{{ $item['draft_title'] ?? '-' }} ({{ $item['draft_year'] ?? '-' }})</td>
                <td>
                  <a href="{{ route('administrasi.receive', $item['procurement_item_id'] ?? $item['id']) }}" class="btn btn-sm btn-success">
                    <i class="bx bx-package me-1"></i> Terima
                  </a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  <i class="bx bx-check-double fs-3 d-block mb-2 text-success"></i>
                  Semua barang sudah diterima. Tidak ada barang yang menunggu penerimaan.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
