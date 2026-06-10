@extends('layouts.sneat')
@section('title', 'Buat Draf Pengadaan')
@section('page-title', 'Buat Draf Baru')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <h4 class="fw-bold py-3 mb-4">Buat Draf Pengadaan Baru</h4>

    <form action="{{ route('pengadaan.store') }}" method="POST">
      @csrf
      
      <!-- Draft Info -->
      <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Informasi Draf</h5></div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-8 mb-3">
              <label for="title" class="form-label">Judul Draf <span class="text-danger">*</span></label>
              <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="Contoh: Pengadaan Inventaris Lab 2026" required>
            </div>
            <div class="col-md-4 mb-3">
              <label for="year" class="form-label">Tahun Pengadaan <span class="text-danger">*</span></label>
              <input type="number" name="year" id="year" class="form-control" value="{{ old('year', date('Y')) }}" min="2020" max="2099" required>
            </div>
          </div>
        </div>
      </div>

      <!-- Items Info -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Detail Barang yang Diajukan</h5>
          <button type="button" class="btn btn-sm btn-primary" id="btn-add-item">
            <i class="bx bx-plus me-1"></i> Tambah Baris
          </button>
        </div>
        <div class="card-body">
          <div id="items-container">
            <!-- Dynamic rows will be inserted here -->
          </div>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">
          <i class="bx bx-save me-1"></i> Simpan Draf & Item
        </button>
        <a href="{{ route('pengadaan.index') }}" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>

  </div>
</div>

<!-- Template for dynamic item row -->
<template id="item-row-template">
  <div class="item-row border rounded p-3 mb-3 position-relative bg-lighter">
    <button type="button" class="btn btn-sm btn-danger btn-remove-item position-absolute top-0 end-0 m-2" title="Hapus Baris"><i class="bx bx-trash"></i></button>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
        <input type="text" name="items[__INDEX__][item_name]" class="form-control" required>
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
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Estimasi Harga (Rp) <span class="text-danger">*</span></label>
        <input type="number" name="items[__INDEX__][estimated_price]" class="form-control" min="0" step="1000" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Menggantikan (Opsi)</label>
        <select name="items[__INDEX__][replaced_inventory_id]" class="form-select">
          <option value="">-- Tidak menggantikan --</option>
          @foreach($inventories ?? [] as $inv)
            <option value="{{ $inv['id'] }}">{{ $inv['item_name'] }} ({{ $inv['inventory_code'] ?? 'Tanpa Kode' }})</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Link Pembelian</label>
        <input type="url" name="items[__INDEX__][purchase_link]" class="form-control" placeholder="https://...">
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <label class="form-label">Catatan</label>
        <input type="text" name="items[__INDEX__][notes]" class="form-control" placeholder="Opsional">
      </div>
    </div>
  </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('items-container');
    const template = document.getElementById('item-row-template').innerHTML;
    const btnAdd = document.getElementById('btn-add-item');
    let itemIndex = 0;

    function addItemRow() {
        const rowHtml = template.replace(/__INDEX__/g, itemIndex);
        container.insertAdjacentHTML('beforeend', rowHtml);
        itemIndex++;
    }

    // Add first row by default
    addItemRow();

    btnAdd.addEventListener('click', addItemRow);

    container.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-item')) {
            const row = e.target.closest('.item-row');
            if (container.children.length > 1) {
                row.remove();
            } else {
                alert('Minimal harus ada 1 barang dalam draf.');
            }
        }
    });
});
</script>
@endsection
