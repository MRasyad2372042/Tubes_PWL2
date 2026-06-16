@extends('layouts.sneat')
@section('title', 'Detail Draf — ' . ($draft['title'] ?? ''))

@section('content')
@include('sneat.partials.sidebar')

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap');

.db-orb { position:absolute;border-radius:50%;filter:blur(90px);opacity:0.55;pointer-events:none;z-index:0;animation:dbOrbMove 18s infinite alternate cubic-bezier(0.45,0,0.55,1); }
.db-orb-1 { width:600px;height:600px;background:rgba(59,130,246,0.13);top:-200px;right:-100px; }
.db-orb-2 { width:500px;height:500px;background:rgba(37,99,235,0.09);bottom:-150px;left:-100px;animation-delay:-6s; }
.db-orb-3 { width:350px;height:350px;background:rgba(96,165,250,0.08);top:40%;left:30%;animation-delay:-12s; }
@keyframes dbOrbMove { 0%{transform:translate(0,0) scale(1)}50%{transform:translate(40px,60px) scale(1.15)}100%{transform:translate(-60px,30px) scale(0.92)} }

.db-page { position:relative;overflow:hidden; }
.db-page > .content-wrapper { position:relative;z-index:2; }

/* Header */
.db-page-title { font-family:'Syne',sans-serif!important;font-size:22px!important;font-weight:800!important;letter-spacing:-0.025em;color:#0f172a!important;margin-bottom:4px!important; }
.db-page-sub   { font-size:13px;color:#64748b; }

/* Status badges */
.db-badge { display:inline-flex;align-items:center;padding:4px 12px;border-radius:999px;font-size:12px;font-weight:600; }
.db-badge-draft     { background:#eff6ff;color:#1d4ed8; }
.db-badge-locked    { background:#fffbeb;color:#b45309; }
.db-badge-reviewed  { background:#f5f3ff;color:#6d28d9; }
.db-badge-finalized { background:#f0fdf4;color:#15803d; }
.db-badge-default   { background:#f1f5f9;color:#475569; }
.db-badge-approved  { background:#f0fdf4;color:#15803d; }
.db-badge-rejected  { background:#fef2f2;color:#b91c1c; }
.db-badge-pending   { background:#f1f5f9;color:#475569; }
.db-badge-inv       { background:#f5f3ff;color:#6d28d9; }
.db-badge-bhp       { background:#eff6ff;color:#1d4ed8; }

/* Header action buttons */
.db-btn-back {
  display:inline-flex;align-items:center;gap:6px;padding:8px 16px;
  background:transparent;border:1px solid rgba(100,116,139,0.3);border-radius:10px;
  font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#64748b!important;
  text-decoration:none;transition:background 0.2s,border-color 0.2s;
}
.db-btn-back:hover { background:rgba(100,116,139,0.07);border-color:rgba(100,116,139,0.5);color:#475569!important; }

.db-btn-edit {
  display:inline-flex;align-items:center;gap:6px;padding:8px 16px;
  background:rgba(217,119,6,0.08);border:1px solid rgba(217,119,6,0.3);border-radius:10px;
  font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#d97706!important;
  text-decoration:none;transition:background 0.2s,border-color 0.2s;
}
.db-btn-edit:hover { background:rgba(217,119,6,0.14);border-color:rgba(217,119,6,0.5);color:#b45309!important; }

.db-btn-lock {
  display:inline-flex;align-items:center;gap:6px;padding:8px 16px;
  background:linear-gradient(135deg,#d97706,#f59e0b);border:none;border-radius:10px;
  font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#fff!important;
  cursor:pointer;transition:transform 0.2s,box-shadow 0.2s;
  box-shadow:0 4px 12px rgba(217,119,6,0.25);
}
.db-btn-lock:hover { transform:translateY(-1px);box-shadow:0 6px 18px rgba(217,119,6,0.35); }

/* Cards */
.db-card {
  background:rgba(255,255,255,0.78)!important;
  backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);
  border:1px solid rgba(255,255,255,0.95)!important;
  border-radius:18px!important;
  box-shadow:0 4px 24px rgba(15,23,42,0.06)!important;
  overflow:hidden;
}
.db-card .card-header {
  background:transparent!important;
  border-bottom:1px solid rgba(226,232,240,0.6)!important;
  padding:16px 22px!important;
}
.db-card .card-header h5 { font-family:'Syne',sans-serif!important;font-size:14px!important;font-weight:800!important;color:#0f172a!important;margin:0!important; }
.db-card .card-body { padding:0!important; }

/* Tambah barang button */
.db-btn-add-item {
  display:inline-flex;align-items:center;gap:6px;padding:7px 14px;
  background:rgba(37,99,235,0.07);border:1.5px dashed rgba(37,99,235,0.35);border-radius:9px;
  font-family:'DM Sans',sans-serif;font-size:12.5px;font-weight:600;color:#2563eb;
  cursor:pointer;transition:background 0.2s,border-color 0.2s;
}
.db-btn-add-item:hover { background:rgba(37,99,235,0.12);border-color:rgba(37,99,235,0.5); }

/* Table */
.db-table thead th { font-size:11px!important;font-weight:700!important;color:#94a3b8!important;text-transform:uppercase!important;letter-spacing:0.07em!important;padding:12px 20px!important;background:rgba(248,250,252,0.5)!important;border-bottom:1px solid rgba(226,232,240,0.6)!important; }
.db-table tbody td { padding:12px 20px!important;font-size:13px!important;color:#334155!important;border-bottom:1px solid rgba(226,232,240,0.4)!important;vertical-align:middle!important; }
.db-table tbody tr:last-child td { border-bottom:none!important; }
.db-table tbody tr:hover td { background:rgba(241,245,249,0.6)!important; }
.db-table tfoot td { padding:12px 20px!important;font-size:13.5px!important;font-weight:700!important;color:#0f172a!important;border-top:1px solid rgba(226,232,240,0.8)!important;background:rgba(248,250,252,0.4)!important; }
.td-title { font-weight:600!important;color:#0f172a!important; }
.td-idx   { font-size:12px!important;color:#94a3b8!important;font-weight:600!important; }

.db-act-btn { display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:8px;border:1px solid rgba(220,38,38,0.22);background:transparent;color:#dc2626;cursor:pointer;transition:background 0.2s,transform 0.15s; }
.db-act-btn:hover { background:#fef2f2;transform:scale(1.1); }
.db-act-btn i { font-size:14px!important; }

.db-link-btn { display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:8px;border:1px solid rgba(37,99,235,0.22);background:transparent;color:#2563eb;text-decoration:none;transition:background 0.2s; }
.db-link-btn:hover { background:#eff6ff;color:#1d4ed8; }

/* Add item form (collapsible) */
.db-add-form-wrap {
  background:rgba(255,255,255,0.78)!important;
  backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);
  border:1px solid rgba(255,255,255,0.95)!important;
  border-radius:18px!important;
  box-shadow:0 4px 24px rgba(15,23,42,0.06)!important;
  overflow:hidden;
}
.db-add-form-wrap .card-header { background:transparent!important;border-bottom:1px solid rgba(226,232,240,0.6)!important;padding:16px 22px!important; }
.db-add-form-wrap .card-header h5 { font-family:'Syne',sans-serif!important;font-size:14px!important;font-weight:800!important;color:#0f172a!important;margin:0!important; }
.db-add-form-wrap .card-body { padding:22px!important; }
.db-add-form-wrap .form-label { font-size:12.5px!important;font-weight:600!important;color:#475569!important;margin-bottom:7px!important; }
.db-add-form-wrap .form-control,
.db-add-form-wrap .form-select { font-family:'DM Sans',sans-serif!important;font-size:13.5px!important;border:1px solid #e2e8f0!important;border-radius:10px!important;background:#f8fafc!important;color:#0f172a!important;padding:9px 14px!important;transition:border-color 0.25s,box-shadow 0.25s,background 0.25s!important; }
.db-add-form-wrap .form-control:focus,
.db-add-form-wrap .form-select:focus { background:#fff!important;border-color:#3b82f6!important;box-shadow:0 0 0 4px rgba(59,130,246,0.12)!important;outline:none!important; }

.db-btn-save-item {
  display:inline-flex;align-items:center;gap:6px;padding:10px 20px;
  background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 60%,#60a5fa 100%);background-size:200% auto;
  border:none;border-radius:10px;font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;
  color:#fff!important;cursor:pointer;transition:background-position 0.4s,transform 0.2s,box-shadow 0.2s;
  box-shadow:0 4px 14px rgba(37,99,235,0.25);
}
.db-btn-save-item:hover { background-position:right center;transform:translateY(-1px);box-shadow:0 8px 22px rgba(37,99,235,0.35); }

/* Empty */
.db-empty { padding:48px 24px;text-align:center;color:#94a3b8; }
.db-empty i { font-size:36px;margin-bottom:10px;display:block; }
</style>

<div class="layout-page db-page">
  <div class="db-orb db-orb-1"></div>
  <div class="db-orb db-orb-2"></div>
  <div class="db-orb db-orb-3"></div>

  @include('sneat.partials.navbar')

  <div class="content-wrapper container-xxl container-p-y">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
      <div>
        <h4 class="db-page-title">{{ $draft['title'] }}</h4>
        <p class="db-page-sub">
          Tahun {{ $draft['year'] }} &middot;
          @php
            $badgeClass = match($draft['status']) {
              'draft'     => 'db-badge-draft',
              'locked'    => 'db-badge-locked',
              'reviewed'  => 'db-badge-reviewed',
              'finalized' => 'db-badge-finalized',
              default     => 'db-badge-default',
            };
          @endphp
          <span class="db-badge {{ $badgeClass }}">{{ ucfirst($draft['status']) }}</span>
        </p>
      </div>
      <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('pengadaan.index') }}" class="db-btn-back">
          <i class="bx bx-arrow-back"></i> Kembali
        </a>
        @if($draft['status'] === 'draft')
          <a href="{{ route('pengadaan.edit', $draft['id']) }}" class="db-btn-edit">
            <i class="bx bx-edit"></i> Edit Draf
          </a>
          <form action="{{ route('pengadaan.lock', $draft['id']) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Setelah dikunci, draf tidak dapat diubah lagi. Lanjutkan?')">
            @csrf
            <button type="submit" class="db-btn-lock">
              <i class="bx bx-lock"></i> Kunci Draf
            </button>
          </form>
        @endif
      </div>
    </div>

    {{-- Item List --}}
    <div class="card db-card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="bx bx-list-ul me-1"></i> Daftar Barang</h5>
        @if($draft['status'] === 'draft')
          <button type="button" class="db-btn-add-item" data-bs-toggle="collapse" data-bs-target="#addItemForm">
            <i class="bx bx-plus"></i> Tambah Barang
          </button>
        @endif
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover mb-0 db-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Estimasi Harga</th>
                <th>Menggantikan</th>
                <th>Link</th>
                <th>Status</th>
                @if($draft['status'] === 'draft')<th>Aksi</th>@endif
              </tr>
            </thead>
            <tbody>
              @forelse($draft['items'] ?? [] as $index => $item)
              <tr>
                <td class="td-idx">{{ $index + 1 }}</td>
                <td class="td-title">{{ $item['item_name'] }}</td>
                <td>
                  <span class="db-badge {{ $item['item_type'] === 'inventory' ? 'db-badge-inv' : 'db-badge-bhp' }}">
                    {{ $item['item_type'] === 'inventory' ? 'Inventaris' : 'BHP' }}
                  </span>
                </td>
                <td>{{ $item['quantity'] }}</td>
                <td style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;">
                  Rp {{ number_format($item['estimated_price'], 0, ',', '.') }}
                </td>
                <td>
                  @if($item['replaced_inventory_id'])
                    <span class="db-badge db-badge-default" title="ID: {{ $item['replaced_inventory_id'] }}">
                      {{ $item['replaced_inventory_name'] ?? 'Inventaris #'.$item['replaced_inventory_id'] }}
                    </span>
                  @else
                    <span style="color:#cbd5e1">—</span>
                  @endif
                </td>
                <td>
                  @if($item['purchase_link'])
                    <a href="{{ $item['purchase_link'] }}" target="_blank" class="db-link-btn">
                      <i class="bx bx-link-external" style="font-size:14px"></i>
                    </a>
                  @else
                    <span style="color:#cbd5e1">—</span>
                  @endif
                </td>
                <td>
                  @php
                    $approvedClass = match($item['approved_status']) {
                      'approved' => 'db-badge-approved',
                      'rejected' => 'db-badge-rejected',
                      default    => 'db-badge-pending',
                    };
                  @endphp
                  <span class="db-badge {{ $approvedClass }}">{{ ucfirst($item['approved_status']) }}</span>
                </td>
                @if($draft['status'] === 'draft')
                <td>
                  <form action="{{ route('pengadaan.items.destroy', $item['id']) }}?draft_id={{ $draft['id'] }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Hapus item ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="db-act-btn"><i class="bx bx-trash"></i></button>
                  </form>
                </td>
                @endif
              </tr>
              @empty
              <tr>
                <td colspan="{{ $draft['status'] === 'draft' ? 9 : 8 }}" class="p-0">
                  <div class="db-empty">
                    <i class="bx bx-package"></i>
                    <p>Belum ada item. Tambahkan barang ke draf ini.</p>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
            @if(count($draft['items'] ?? []) > 0)
            <tfoot>
              <tr>
                <td colspan="4" class="text-end" style="font-size:12px!important;color:#64748b!important;font-weight:600!important;text-transform:uppercase;letter-spacing:0.05em;">Total Estimasi</td>
                <td style="font-family:'Syne',sans-serif!important;font-size:15px!important;font-weight:800!important;color:#0f172a!important;">
                  Rp {{ number_format(collect($draft['items'])->sum('estimated_price'), 0, ',', '.') }}
                </td>
                <td colspan="{{ $draft['status'] === 'draft' ? 4 : 3 }}"></td>
              </tr>
            </tfoot>
            @endif
          </table>
        </div>
      </div>
    </div>

    {{-- Add Item Form --}}
    @if($draft['status'] === 'draft')
    <div class="collapse mb-4" id="addItemForm">
      <div class="card db-add-form-wrap">
        <div class="card-header">
          <h5><i class="bx bx-plus-circle me-1"></i> Tambah Barang Baru</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('pengadaan.items.store', $draft['id']) }}" method="POST">
            @csrf
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                <input type="text" name="item_name" class="form-control" value="{{ old('item_name') }}" required>
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label">Tipe <span class="text-danger">*</span></label>
                <select name="item_type" class="form-select" required>
                  <option value="inventory" {{ old('item_type')=='inventory'?'selected':'' }}>Inventaris</option>
                  <option value="bhp"       {{ old('item_type')=='bhp'?'selected':'' }}>BHP</option>
                </select>
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity',1) }}" min="1" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Estimasi Harga (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="estimated_price" class="form-control" value="{{ old('estimated_price') }}" min="0" step="1000" placeholder="0" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Menggantikan <span style="font-weight:400;color:#94a3b8">(Opsional)</span></label>
                <select name="replaced_inventory_id" class="form-select">
                  <option value="">— Tidak menggantikan —</option>
                  @foreach($inventories as $inv)
                    <option value="{{ $inv['id'] }}" {{ old('replaced_inventory_id')==$inv['id']?'selected':'' }}>
                      {{ $inv['item_name'] }} ({{ $inv['inventory_code'] ?? 'Tanpa Kode' }})
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Link Pembelian <span style="font-weight:400;color:#94a3b8">(Opsional)</span></label>
                <input type="url" name="purchase_link" class="form-control" value="{{ old('purchase_link') }}" placeholder="https://...">
              </div>
            </div>
            <div class="row">
              <div class="col-12 mb-3">
                <label class="form-label">Catatan <span style="font-weight:400;color:#94a3b8">(Opsional)</span></label>
                <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="Opsional...">
              </div>
            </div>
            <button type="submit" class="db-btn-save-item">
              <i class="bx bx-check"></i> Simpan Item
            </button>
          </form>
        </div>
      </div>
    </div>
    @endif

  </div>
</div>
@endsection