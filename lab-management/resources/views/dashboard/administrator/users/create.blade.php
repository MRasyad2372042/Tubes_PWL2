@extends('layouts.sneat')

@section('title','Create User')

@section('content')
<div class="container-xxl py-4">
    <h3>Create User (Administrator)</h3>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <input type="text" name="role" class="form-control" value="staf_laboratorium">
                </div>
                <button class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
</div>
@endsection
