@extends('layouts.sneat')
@section('title', 'Dashboard — Staf Administrasi')
@section('page-title', 'Dashboard')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <h4 class="fw-bold py-3 mb-1">Selamat datang, {{ auth()->user()->name }} 👋</h4>
    <p class="text-muted mb-4">Staf Administrasi — Kelola penerimaan barang, penomoran, dan label QR/Barcode inventaris.</p>

    {{-- Stat Cards --}}
    <div class="row mb-4">
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Menunggu Diterima</span>
                <h3 class="card-title mb-0">{{ $stats['pending_count'] ?? 0 }}</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-warning">
                  <i class="bx bx-package"></i>
                </span>
              </div>
            </div>
            <small class="text-warning mt-2 d-block">Barang belum diterima</small>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Sudah Diterima</span>
                <h3 class="card-title mb-0">{{ $stats['received_count'] ?? 0 }}</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-success">
                  <i class="bx bx-check-circle"></i>
                </span>
              </div>
            </div>
            <small class="text-success mt-2 d-block">Sudah ada tanggal penerimaan</small>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Total Inventaris</span>
                <h3 class="card-title mb-0">{{ $stats['total_inventory'] ?? 0 }}</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-primary">
                  <i class="bx bx-desktop"></i>
                </span>
              </div>
            </div>
            <small class="text-muted mt-2 d-block">Barang terdaftar di sistem</small>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-semibold d-block mb-1 text-muted">Label Belum Cetak</span>
                <h3 class="card-title mb-0">{{ $stats['no_label'] ?? 0 }}</h3>
              </div>
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-info">
                  <i class="bx bx-qr"></i>
                </span>
              </div>
            </div>
            <small class="text-info mt-2 d-block">QR/Barcode belum digenerate</small>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      {{-- Barang Menunggu Penerimaan --}}
      <div class="col-md-7 mb-4">
        <div class="card h-100">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
              <i class="bx bx-time text-warning me-1"></i>
              Barang Menunggu Penerimaan
            </h5>
            <a href="{{ route('administrasi.pending') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($pendingItems->take(5) as $item)
                  <tr>
                    <td>{{ $item['item_name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>
                      <a href="{{ route('administrasi.receive', $item['procurement_item_id']) }}" class="btn btn-sm btn-success">Terima</a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="3" class="text-center text-muted py-4">
                      <i class="bx bx-check-double fs-3 d-block mb-2 text-success"></i>
                      Semua barang sudah diterima.
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- Aksi Cepat --}}
      <div class="col-md-5 mb-4">
        <div class="card h-100">
          <div class="card-header">
            <h5 class="mb-0">Aksi Cepat</h5>
          </div>
          <div class="card-body d-flex flex-column gap-3">
            <a href="{{ route('administrasi.pending') }}" class="btn btn-outline-primary w-100 text-start">
              <i class="bx bx-package me-2"></i> Input Penerimaan Barang
            </a>
            <a href="{{ route('administrasi.inventaris') }}" class="btn btn-outline-secondary w-100 text-start">
              <i class="bx bx-list-ul me-2"></i> Lihat Semua Inventaris
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Penerimaan Terbaru --}}
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bx bx-history me-1"></i> Inventaris Terbaru</h5>
        <a href="{{ route('administrasi.inventaris') }}" class="btn btn-outline-secondary btn-sm">Lihat Semua</a>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Nama Barang</th>
                <th>No. Inventaris</th>
                <th>Ruangan</th>
                <th>Tgl Diterima</th>
                <th>Kondisi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentInventories as $inv)
              <tr>
                <td>{{ $inv['item_name'] }}</td>
                <td>{{ $inv['inventory_code'] ?? '-' }}</td>
                <td>{{ $inv['room_name'] ?? '-' }}</td>
                <td>{{ $inv['receive_date'] ? \Carbon\Carbon::parse($inv['receive_date'])->format('d M Y') : '-' }}</td>
                <td>
                  <span class="badge {{ $inv['condition_status'] === 'good' ? 'bg-label-success' : 'bg-label-warning' }}">
                    {{ ucfirst($inv['condition_status']) }}
                  </span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-4">
                  <i class="bx bx-folder-open fs-3 d-block mb-2"></i>
                  Belum ada data penerimaan.
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
