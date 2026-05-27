@extends('layouts.sneat')

@section('title','Dashboard')

@section('content')
  @include('sneat.partials.sidebar')
  <div class="layout-page">
    @include('sneat.partials.navbar')

    <div class="content-wrapper">
      <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
          <div class="col-lg-8 mb-4 order-0">
            <div class="card">
              <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                  <div class="card-body">
                    <h5 class="card-title text-primary">Selamat datang</h5>
                    <p class="mb-4">Gunakan menu di samping untuk navigasi modul manajemen laboratorium.</p>
                  </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                  <div class="card-body pb-0 px-0 px-md-4">
                    <img src="{{ asset('sneat/assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="Illustration" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
