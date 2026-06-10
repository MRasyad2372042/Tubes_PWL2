@extends('layouts.sneat')
@section('title', 'Daftar Inventaris')
@section('page-title', 'Inventaris')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">Daftar Inventaris</h4>
        <p class="text-muted mb-0">Semua barang inventaris yang sudah diterima dan terdaftar di sistem.</p>
      </div>
      <a href="{{ route('administrasi.pending') }}" class="btn btn-outline-primary btn-sm">
        <i class="bx bx-package me-1"></i> Penerimaan Barang
      </a>
    </div>

    <div class="nav-align-top mb-4">
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
          <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-inventaris" aria-controls="navs-inventaris" aria-selected="true">
            Daftar Inventaris
          </button>
        </li>
        <li class="nav-item">
          <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-bhp" aria-controls="navs-bhp" aria-selected="false">
            Daftar BHP
          </button>
        </li>
      </ul>
      <div class="tab-content">
        {{-- TAB INVENTARIS --}}
        <div class="tab-pane fade show active" id="navs-inventaris" role="tabpanel">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nama Barang</th>
                  <th>Kode Inventaris</th>
                  <th>Ruangan</th>
                  <th>Tgl Diterima</th>
                  <th>Kondisi</th>
                  <th>QR / Foto</th>
                </tr>
              </thead>
              <tbody>
                @forelse($inventories as $index => $inv)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td><strong>{{ $inv['item_name'] }}</strong></td>
                  <td>
                    @if($inv['inventory_code'])
                      <code>{{ $inv['inventory_code'] }}</code>
                    @else
                      <span class="text-muted fst-italic">Belum diberi kode</span>
                    @endif
                  </td>
                  <td>{{ $inv['room_name'] ?? '-' }}</td>
                  <td>
                    @if($inv['receive_date'])
                      {{ \Carbon\Carbon::parse($inv['receive_date'])->format('d M Y') }}
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>
                    @php
                      $condBadge = match($inv['condition_status']) {
                        'good' => 'bg-label-success',
                        'maintenance' => 'bg-label-warning',
                        'damaged' => 'bg-label-danger',
                        'disposed' => 'bg-label-dark',
                        'replaced' => 'bg-label-info',
                        default => 'bg-label-secondary',
                      };
                    @endphp
                    <span class="badge {{ $condBadge }}">{{ ucfirst($inv['condition_status']) }}</span>
                  </td>
                  <td>
                    @if($inv['qr_code_path'])
                      <a href="http://localhost:3000{{ $inv['qr_code_path'] }}" target="_blank" class="btn btn-sm btn-outline-info" title="QR Code">
                        <i class="bx bx-qr"></i>
                      </a>
                    @endif
                    @if($inv['photo_path'])
                      <a href="http://localhost:3000{{ $inv['photo_path'] }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Foto">
                        <i class="bx bx-image"></i>
                      </a>
                    @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="text-center text-muted py-4">
                    <i class="bx bx-folder-open fs-3 d-block mb-2"></i>
                    Belum ada data inventaris.
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        {{-- TAB BHP --}}
        <div class="tab-pane fade" id="navs-bhp" role="tabpanel">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nama BHP</th>
                  <th>Stok Tersedia</th>
                  <th>Satuan</th>
                  <th>Tanggal Diterima</th>
                </tr>
              </thead>
              <tbody>
                @forelse($bhpItems ?? [] as $index => $bhp)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td><strong>{{ $bhp['name'] }}</strong></td>
                  <td>
                    <span class="badge bg-label-warning">{{ $bhp['stock'] }}</span>
                  </td>
                  <td>{{ $bhp['unit'] }}</td>
                  <td>
                    @if(isset($bhp['receive_date']))
                      {{ \Carbon\Carbon::parse($bhp['receive_date'])->format('d M Y') }}
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">
                    <i class="bx bx-box fs-3 d-block mb-2"></i>
                    Belum ada data BHP.
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
</div>
@endsection
