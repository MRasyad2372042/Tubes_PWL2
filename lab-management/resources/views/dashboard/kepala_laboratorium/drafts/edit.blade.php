@extends('layouts.sneat')
@section('title', 'Edit Draf Pengadaan')

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

.db-page-title { font-family:'Syne',sans-serif!important;font-size:24px!important;font-weight:800!important;letter-spacing:-0.025em;color:#0f172a!important; }

.db-card {
  background:rgba(255,255,255,0.78)!important;
  backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);
  border:1px solid rgba(255,255,255,0.95)!important;
  border-radius:18px!important;
  box-shadow:0 4px 24px rgba(15,23,42,0.06)!important;
  overflow:hidden;
}
.db-card .card-header { background:transparent!important;border-bottom:1px solid rgba(226,232,240,0.6)!important;padding:16px 22px!important; }
.db-card .card-header h5 { font-family:'Syne',sans-serif!important;font-size:14px!important;font-weight:800!important;color:#0f172a!important;margin:0!important; }
.db-card .card-body { padding:24px!important; }

.db-card .form-label { font-size:12.5px!important;font-weight:600!important;color:#475569!important;margin-bottom:7px!important; }
.db-card .form-control {
  font-family:'DM Sans',sans-serif!important;font-size:13.5px!important;
  border:1px solid #e2e8f0!important;border-radius:10px!important;
  background:#f8fafc!important;color:#0f172a!important;padding:10px 14px!important;
  transition:border-color 0.25s,box-shadow 0.25s,background 0.25s!important;
}
.db-card .form-control:focus { background:#fff!important;border-color:#3b82f6!important;box-shadow:0 0 0 4px rgba(59,130,246,0.12)!important;outline:none!important; }
.db-card .form-control.is-invalid { border-color:#ef4444!important;box-shadow:0 0 0 4px rgba(239,68,68,0.1)!important; }
.db-card .invalid-feedback { font-size:12px!important;color:#ef4444!important;margin-top:5px!important; }

.db-btn-save {
  display:inline-flex;align-items:center;gap:7px;padding:11px 24px;
  background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 60%,#60a5fa 100%);background-size:200% auto;
  border:none;border-radius:11px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:600;
  color:#fff!important;cursor:pointer;transition:background-position 0.4s,transform 0.2s,box-shadow 0.2s;
  box-shadow:0 4px 14px rgba(37,99,235,0.25);position:relative;overflow:hidden;
}
.db-btn-save::before { content:"";position:absolute;top:0;left:-100%;width:50%;height:100%;background:linear-gradient(to right,transparent,rgba(255,255,255,0.35),transparent);transform:skewX(-20deg);transition:0.5s; }
.db-btn-save:hover { background-position:right center;transform:translateY(-2px);box-shadow:0 8px 22px rgba(37,99,235,0.35); }
.db-btn-save:hover::before { left:150%; }

.db-btn-cancel {
  display:inline-flex;align-items:center;gap:6px;padding:11px 20px;
  background:transparent;border:1px solid rgba(100,116,139,0.3);border-radius:11px;
  font-family:'DM Sans',sans-serif;font-size:14px;font-weight:600;color:#64748b!important;
  text-decoration:none;transition:background 0.2s,border-color 0.2s;
}
.db-btn-cancel:hover { background:rgba(100,116,139,0.07);border-color:rgba(100,116,139,0.5);color:#475569!important; }
</style>

<div class="layout-page db-page">
  <div class="db-orb db-orb-1"></div>
  <div class="db-orb db-orb-2"></div>
  <div class="db-orb db-orb-3"></div>

  @include('sneat.partials.navbar')

  <div class="content-wrapper container-xxl container-p-y">

    <h4 class="db-page-title py-3 mb-4">Edit Draf Pengadaan</h4>

    <div class="card db-card" style="max-width:640px">
      <div class="card-header">
        <h5><i class="bx bx-edit me-1"></i> Ubah Informasi Draf</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('pengadaan.update', $draft['id']) }}" method="POST">
          @csrf @method('PUT')

          <div class="mb-4">
            <label for="title" class="form-label">Judul Draf <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title"
              class="form-control @error('title') is-invalid @enderror"
              value="{{ old('title', $draft['title']) }}" required>
            @error('title')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-4">
            <label for="year" class="form-label">Tahun Pengadaan <span class="text-danger">*</span></label>
            <input type="number" name="year" id="year"
              class="form-control @error('year') is-invalid @enderror"
              value="{{ old('year', $draft['year']) }}" min="2020" max="2099" required
              style="max-width:180px">
            @error('year')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex gap-2 mt-2">
            <button type="submit" class="db-btn-save">
              <i class="bx bx-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('pengadaan.show', $draft['id']) }}" class="db-btn-cancel">
              Batal
            </a>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection