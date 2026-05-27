@extends('layouts.sneat')

@section('title','Edit User')

@section('content')
<div class="container-xxl py-4">
    <h3>Edit User (Administrator)</h3>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('users.update', $user['id']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $user['name'] }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user['email'] }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <input type="text" name="role" class="form-control" value="{{ $user['role'] ?? '' }}">
                </div>
                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection
