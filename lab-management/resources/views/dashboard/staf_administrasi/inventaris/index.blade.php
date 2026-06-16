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
                    <button type="button" class="btn btn-sm btn-outline-warning edit-inventory-btn" data-id="{{ $inv['id'] }}" title="Edit">
                      <i class="bx bx-edit"></i>
                    </button>
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

<!-- Modal Edit Inventaris -->
<div class="modal fade" id="editInventoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Inventaris</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editInventoryForm" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" id="inventoryId" name="id">

          <!-- Inventory Code -->
          <div class="mb-3">
            <label class="form-label" for="inventoryCode">Kode Inventaris <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="inventoryCode" name="inventory_code" placeholder="Contoh: INV-001" required>
            <small class="text-muted">QR Code akan dibuat otomatis dari kode ini</small>
          </div>

          <!-- Condition Status -->
          <div class="mb-3">
            <label class="form-label" for="conditionStatus">Kondisi Barang</label>
            <select class="form-select" id="conditionStatus" name="condition_status">
              <option value="good">Baik</option>
              <option value="maintenance">Memerlukan Perbaikan</option>
              <option value="damaged">Rusak</option>
              <option value="disposed">Dipbuang</option>
              <option value="replaced">Diganti</option>
            </select>
          </div>

          <!-- Barcode Upload -->
          <div class="mb-3">
            <label class="form-label" for="barcodeUpload">Upload Barcode (Opsional)</label>
            <input type="file" class="form-control" id="barcodeUpload" name="barcode" accept="image/*">
            <small class="text-muted">Format: JPG, PNG, GIF, WEBP. Max 5MB</small>
            <div id="barcodePreview" class="mt-2"></div>
          </div>

          <!-- Photo Upload -->
          <div class="mb-3">
            <label class="form-label" for="photoUpload">Upload Foto (Opsional)</label>
            <input type="file" class="form-control" id="photoUpload" name="photo" accept="image/*">
            <small class="text-muted">Format: JPG, PNG, GIF, WEBP. Max 5MB</small>
            <div id="photoPreview" class="mt-2"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const editModal = new bootstrap.Modal(document.getElementById('editInventoryModal'));
  const editForm = document.getElementById('editInventoryForm');

  // Handle edit button click
  document.querySelectorAll('.edit-inventory-btn').forEach(button => {
    button.addEventListener('click', function() {
      const inventoryId = this.getAttribute('data-id');
      const row = this.closest('tr');
      
      // Get item name from row
      const itemName = row.cells[1].textContent.trim();
      
      // Set modal title with item name
      document.querySelector('#editInventoryModal .modal-title').textContent = 
        `Update Inventaris - ${itemName}`;
      
      // Set inventory ID
      document.getElementById('inventoryId').value = inventoryId;
      
      // Clear form
      editForm.reset();
      document.getElementById('barcodePreview').innerHTML = '';
      document.getElementById('photoPreview').innerHTML = '';
      
      editModal.show();
    });
  });

  // Handle file preview
  function setupFilePreview(inputId, previewId) {
    document.getElementById(inputId).addEventListener('change', function(e) {
      const file = e.target.files[0];
      const previewDiv = document.getElementById(previewId);
      previewDiv.innerHTML = '';

      if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
          const img = document.createElement('img');
          img.src = event.target.result;
          img.style.maxWidth = '150px';
          img.style.maxHeight = '150px';
          img.className = 'rounded border';
          previewDiv.appendChild(img);
        };
        reader.readAsDataURL(file);
      }
    });
  }

  setupFilePreview('barcodeUpload', 'barcodePreview');
  setupFilePreview('photoUpload', 'photoPreview');

  // Handle form submission
  editForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const inventoryId = document.getElementById('inventoryId').value;
    const formData = new FormData(editForm);
    
    // Remove the hidden id field from formData
    formData.delete('id');

    fetch(`http://localhost:3000/api/inventories/${inventoryId}`, {
      method: 'PUT',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.message) {
        // Show success message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show';
        alertDiv.innerHTML = `
          <strong>Berhasil!</strong> ${data.message}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.content-wrapper').prepend(alertDiv);

        // Close modal
        editModal.hide();

        // Reload page after 1 second
        setTimeout(() => location.reload(), 1000);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      const alertDiv = document.createElement('div');
      alertDiv.className = 'alert alert-danger alert-dismissible fade show';
      alertDiv.innerHTML = `
        <strong>Error!</strong> Terjadi kesalahan saat memperbarui data.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      document.querySelector('.content-wrapper').prepend(alertDiv);
    });
  });
});
</script>
@endsection
