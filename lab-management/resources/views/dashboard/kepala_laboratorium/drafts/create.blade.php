@extends('layouts.sneat')
@section('title', 'Buat Draf Pengadaan')

@section('content')
@include('sneat.partials.sidebar')

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap');

.db-orb {
  position: absolute; border-radius: 50%; filter: blur(90px);
  opacity: 0.55; pointer-events: none; z-index: 0;
  animation: dbOrbMove 18s infinite alternate cubic-bezier(0.45,0,0.55,1);
}
.db-orb-1 { width:600px;height:600px;background:rgba(59,130,246,0.13);top:-200px;right:-100px; }
.db-orb-2 { width:500px;height:500px;background:rgba(37,99,235,0.09);bottom:-150px;left:-100px;animation-delay:-6s; }
.db-orb-3 { width:350px;height:350px;background:rgba(96,165,250,0.08);top:40%;left:30%;animation-delay:-12s; }
@keyframes dbOrbMove {
  0%   { transform:translate(0,0) scale(1); }
  50%  { transform:translate(40px,60px) scale(1.15); }
  100% { transform:translate(-60px,30px) scale(0.92); }
}

.db-page { position:relative; overflow:hidden; }
.db-page > .content-wrapper { position:relative; z-index:2; }

/* Page title */
.db-page-title {
  font-family:'Syne',sans-serif !important;
  font-size:24px !important; font-weight:800 !important;
  letter-spacing:-0.025em; color:#0f172a !important;
}

/* Cards */
.db-card {
  background:rgba(255,255,255,0.78) !important;
  backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);
  border:1px solid rgba(255,255,255,0.95) !important;
  border-radius:18px !important;
  box-shadow:0 4px 24px rgba(15,23,42,0.06) !important;
  overflow:hidden;
}
.db-card .card-header {
  background:transparent !important;
  border-bottom:1px solid rgba(226,232,240,0.6) !important;
  padding:16px 22px !important;
}
.db-card .card-header h5 {
  font-family:'Syne',sans-serif !important;
  font-size:14px !important; font-weight:800 !important;
  color:#0f172a !important; margin:0 !important;
}
.db-card .card-body { padding:22px !important; }

/* Form controls override */
.db-card .form-label {
  font-size:12.5px !important; font-weight:600 !important;
  color:#475569 !important; margin-bottom:7px !important;
}
.db-card .form-control,
.db-card .form-select {
  font-family:'DM Sans',sans-serif !important;
  font-size:13.5px !important;
  border:1px solid #e2e8f0 !important;
  border-radius:10px !important;
  background:#f8fafc !important;
  color:#0f172a !important;
  padding:9px 14px !important;
  transition:border-color 0.25s, box-shadow 0.25s, background 0.25s !important;
}
.db-card .form-control:focus,
.db-card .form-select:focus {
  background:#fff !important;
  border-color:#3b82f6 !important;
  box-shadow:0 0 0 4px rgba(59,130,246,0.12) !important;
  outline:none !important;
}
.db-card .form-control::placeholder { color:#94a3b8 !important; }

/* Item rows */
.db-item-row {
  background:rgba(248,250,252,0.7) !important;
  border:1px solid rgba(226,232,240,0.7) !important;
  border-radius:14px !important;
  padding:20px !important;
  margin-bottom:14px !important;
  position:relative;
  transition:box-shadow 0.2s, border-color 0.2s;
  animation:dbRowIn 0.25s cubic-bezier(0.175,0.885,0.32,1.275) both;
}
.db-item-row:hover {
  border-color:rgba(59,130,246,0.25) !important;
  box-shadow:0 4px 16px rgba(59,130,246,0.07);
}
@keyframes dbRowIn {
  from { opacity:0; transform:translateY(12px) scale(0.98); }
  to   { opacity:1; transform:translateY(0) scale(1); }
}

/* Row number badge */
.db-row-num {
  display:inline-flex; align-items:center; justify-content:center;
  width:24px; height:24px; border-radius:50%;
  background:#eff6ff; color:#2563eb;
  font-family:'Syne',sans-serif; font-size:12px; font-weight:800;
  margin-bottom:14px; flex-shrink:0;
}

/* Remove button */
.db-btn-remove {
  position:absolute; top:14px; right:14px;
  width:30px; height:30px; border-radius:8px;
  border:1px solid rgba(220,38,38,0.2);
  background:transparent; color:#dc2626;
  display:flex; align-items:center; justify-content:center;
  cursor:pointer; transition:background 0.2s, transform 0.15s;
  padding:0;
}
.db-btn-remove:hover { background:#fef2f2; transform:scale(1.1); }
.db-btn-remove i { font-size:15px; }

/* Add row button */
.db-btn-add {
  display:inline-flex; align-items:center; gap:6px;
  padding:8px 16px;
  background:rgba(37,99,235,0.07);
  border:1.5px dashed rgba(37,99,235,0.35);
  border-radius:11px;
  font-family:'DM Sans',sans-serif; font-size:13px; font-weight:600;
  color:#2563eb; cursor:pointer;
  transition:background 0.2s, border-color 0.2s, transform 0.15s;
}
.db-btn-add:hover { background:rgba(37,99,235,0.12); border-color:rgba(37,99,235,0.5); transform:translateY(-1px); }
.db-btn-add i { font-size:16px; }

/* Save button */
.db-btn-save {
  display:inline-flex; align-items:center; gap:7px;
  padding:11px 24px;
  background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 60%,#60a5fa 100%);
  background-size:200% auto;
  border:none; border-radius:11px;
  font-family:'DM Sans',sans-serif; font-size:14px; font-weight:600;
  color:#fff !important; cursor:pointer;
  transition:background-position 0.4s, transform 0.2s, box-shadow 0.2s;
  box-shadow:0 4px 14px rgba(37,99,235,0.25);
  position:relative; overflow:hidden;
}
.db-btn-save::before {
  content:""; position:absolute; top:0; left:-100%; width:50%; height:100%;
  background:linear-gradient(to right,transparent,rgba(255,255,255,0.35),transparent);
  transform:skewX(-20deg); transition:0.5s;
}
.db-btn-save:hover { background-position:right center; transform:translateY(-2px); box-shadow:0 8px 22px rgba(37,99,235,0.35); }
.db-btn-save:hover::before { left:150%; }

/* Cancel button */
.db-btn-cancel {
  display:inline-flex; align-items:center; gap:6px;
  padding:11px 20px;
  background:transparent;
  border:1px solid rgba(100,116,139,0.3);
  border-radius:11px;
  font-family:'DM Sans',sans-serif; font-size:14px; font-weight:600;
  color:#64748b !important; text-decoration:none;
  transition:background 0.2s, border-color 0.2s;
}
.db-btn-cancel:hover { background:rgba(100,116,139,0.07); border-color:rgba(100,116,139,0.5); color:#475569 !important; }

/* Section divider inside item row */
.db-row-divider {
  border:none; border-top:1px solid rgba(226,232,240,0.7);
  margin:14px 0 16px;
}
</style>

<div class="layout-page db-page">
  <div class="db-orb db-orb-1"></div>
  <div class="db-orb db-orb-2"></div>
  <div class="db-orb db-orb-3"></div>

  @include('sneat.partials.navbar')

  <div class="content-wrapper container-xxl container-p-y">

    <h4 class="db-page-title py-3 mb-4">Buat Draf Pengadaan Baru</h4>

    <form action="{{ route('pengadaan.store') }}" method="POST">
      @csrf

      {{-- Informasi Draf --}}
      <div class="card db-card mb-4">
        <div class="card-header">
          <h5>Informasi Draf</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-8 mb-3">
              <label for="title" class="form-label">Judul Draf <span class="text-danger">*</span></label>
              <input type="text" name="title" id="title" class="form-control"
                value="{{ old('title') }}"
                placeholder="Contoh: Pengadaan Inventaris Lab 2026" required>
            </div>
            <div class="col-md-4 mb-3">
              <label for="year" class="form-label">Tahun Pengadaan <span class="text-danger">*</span></label>
              <input type="number" name="year" id="year" class="form-control"
                value="{{ old('year', date('Y')) }}" min="2020" max="2099" required>
            </div>
          </div>
        </div>
      </div>

      {{-- Detail Barang --}}
      <div class="card db-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5>Detail Barang yang Diajukan</h5>
          <button type="button" class="db-btn-add" id="btn-add-item">
            <i class="bx bx-plus"></i> Tambah Baris
          </button>
        </div>
        <div class="card-body">
          <div id="items-container"></div>
        </div>
      </div>

      {{-- Footer Actions --}}
      <div class="d-flex gap-2 pb-4">
        <button type="submit" class="db-btn-save">
          <i class="bx bx-save"></i> Simpan Draf &amp; Item
        </button>
        <a href="{{ route('pengadaan.index') }}" class="db-btn-cancel">
          Batal
        </a>
      </div>

    </form>
  </div>
</div>

<template id="item-row-template">
  <div class="db-item-row">
    <div class="d-flex align-items-center gap-2 mb-2">
      <span class="db-row-num">__NUM__</span>
      <span style="font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:#475569;">Barang #__NUM__</span>
    </div>
    <button type="button" class="db-btn-remove btn-remove-item" title="Hapus baris">
      <i class="bx bx-trash"></i>
    </button>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
        <input type="text" name="items[__INDEX__][item_name]" class="form-control" placeholder="Nama barang..." required>
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Tipe <span class="text-danger">*</span></label>
        <select name="items[__INDEX__][item_type]" class="form-select" required>
          <option value="inventory">Inventaris</option>
          <option value="bhp">BHP</option>
        </select>
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Jumlah <span class="text-danger">*</span></label>
        <input type="number" name="items[__INDEX__][quantity]" class="form-control" value="1" min="1" required>
      </div>
    </div>
    <hr class="db-row-divider">
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Estimasi Harga (Rp) <span class="text-danger">*</span></label>
        <input type="number" name="items[__INDEX__][estimated_price]" class="form-control" min="0" step="1000" placeholder="0" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Menggantikan <span style="font-weight:400;color:#94a3b8">(Opsional)</span></label>
        <select name="items[__INDEX__][replaced_inventory_id]" class="form-select">
          <option value="">— Tidak menggantikan —</option>
          @foreach($inventories ?? [] as $inv)
            <option value="{{ $inv['id'] }}">{{ $inv['item_name'] }} ({{ $inv['inventory_code'] ?? 'Tanpa Kode' }})</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Link Pembelian <span style="font-weight:400;color:#94a3b8">(Opsional)</span></label>
        <input type="url" name="items[__INDEX__][purchase_link]" class="form-control" placeholder="https://...">
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <label class="form-label">Catatan <span style="font-weight:400;color:#94a3b8">(Opsional)</span></label>
        <input type="text" name="items[__INDEX__][notes]" class="form-control" placeholder="Tambahkan catatan jika perlu...">
      </div>
    </div>
  </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const container = document.getElementById('items-container');
  const template  = document.getElementById('item-row-template').innerHTML;
  const btnAdd    = document.getElementById('btn-add-item');
  let itemIndex   = 0;

  function addItemRow() {
    const html = template
      .replace(/__INDEX__/g, itemIndex)
      .replace(/__NUM__/g, itemIndex + 1);
    container.insertAdjacentHTML('beforeend', html);
    itemIndex++;
    updateNumbers();
  }

  function updateNumbers() {
    container.querySelectorAll('.db-item-row').forEach((row, i) => {
      row.querySelectorAll('.db-row-num').forEach(el => el.textContent = i + 1);
      row.querySelectorAll('[data-label]').forEach(el => el.textContent = 'Barang #' + (i + 1));
    });
  }

  addItemRow();

  btnAdd.addEventListener('click', addItemRow);

  container.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-remove-item');
    if (!btn) return;
    if (container.children.length > 1) {
      btn.closest('.db-item-row').remove();
      updateNumbers();
    } else {
      alert('Minimal harus ada 1 barang dalam draf.');
    }
  });
});
</script>
@endsection