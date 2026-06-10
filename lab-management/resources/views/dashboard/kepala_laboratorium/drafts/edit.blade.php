@extends('layouts.sneat')
@section('title', 'Edit Draf Pengadaan')
@section('page-title', 'Edit Draf')

@section('content')
@include('sneat.partials.sidebar')
<div class="layout-page">
  @include('sneat.partials.navbar')
  <div class="content-wrapper container-xxl container-p-y">

    <h4 class="fw-bold py-3 mb-4">Edit Draf Pengadaan</h4>

    <div class="card">
      <div class="card-body">
        <form action="{{ route('pengadaan.update', $draft['id']) }}" method="POST">
          @csrf @method('PUT')
          <div class="mb-3">
            <label for="title" class="form-label">Judul Draf <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $draft['title']) }}" required>
            @error('title')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="year" class="form-label">Tahun Pengadaan <span class="text-danger">*</span></label>
            <input type="number" name="year" id="year" class="form-control @error('year') is-invalid @enderror"
                   value="{{ old('year', $draft['year']) }}" min="2020" max="2099" required>
            @error('year')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bx bx-save me-1"></i> Simpan Perubahan
            </button>
            <a href="{{ route('pengadaan.show', $draft['id']) }}" class="btn btn-outline-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection
