@extends('layouts.sneat')
@section('title','Admin Dashboard')
@section('content')
  @include('sneat.partials.sidebar')
  <div class="layout-page">
    @include('sneat.partials.navbar')
    <div class="content-wrapper container-xxl container-p-y">
      <h4>Administrator</h4>
      <p>Manage users and rooms here.</p>

      <div class="row mt-3">
        <div class="col-md-6 mb-3">
          <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h5 class="mb-0">Users</h5>
                <small class="text-muted">Total users</small>
                <h2 class="mt-2">{{ $userCount ?? 0 }}</h2>
              </div>
              <div>
                <a href="{{ route('users.index') }}" class="btn btn-outline-primary">Manage Users</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 mb-3">
          <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h5 class="mb-0">Rooms</h5>
                <small class="text-muted">Total rooms</small>
                <h2 class="mt-2">{{ $roomCount ?? 0 }}</h2>
              </div>
              <div>
                <a href="{{ route('rooms.index') }}" class="btn btn-outline-primary">Manage Rooms</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">Recent Users</div>
            <div class="card-body">
              <table class="table table-sm mb-0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($recentUsers ?? [] as $u)
                  <tr>
                    <td>{{ $u->id }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              <div class="mt-2">
                <a href="{{ route('users.index') }}">View all users</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-header">Recent Rooms</div>
            <div class="card-body">
              <table class="table table-sm mb-0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Location</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($recentRooms ?? [] as $r)
                  <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->name }}</td>
                    <td>{{ $r->location }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              <div class="mt-2">
                <a href="{{ route('rooms.index') }}">View all rooms</a>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
@endsection
