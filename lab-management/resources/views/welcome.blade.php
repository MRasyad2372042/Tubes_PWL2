@extends('layouts.auth')

@section('title','Welcome')

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="card">
        <div class="card-body text-center">
          <div class="app-brand justify-content-center mb-3">
            <a href="{{ url('/') }}" class="app-brand-link gap-2">
              <span class="app-brand-text demo text-body fw-bolder">Lab Management</span>
            </a>
          </div>

          <h4 class="mb-2">Selamat datang di Sistem Manajemen Laboratorium</h4>
          <p class="mb-4">Silakan masuk atau buat akun untuk mengelola inventaris, pengadaan, dan stok BHP.</p>

          <div class="d-flex justify-content-center gap-2">
            <a href="{{ route('login') }}" class="btn btn-primary">Sign in</a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary">Create Account</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
