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
                    <select name="role" class="form-select" required>
                        <option value="administrator" {{ $user['role'] === 'administrator' ? 'selected' : '' }}>Administrator</option>
                        <option value="kepala_laboratorium" {{ $user['role'] === 'kepala_laboratorium' ? 'selected' : '' }}>Kepala Laboratorium</option>
                        <option value="ketua_program_studi" {{ $user['role'] === 'ketua_program_studi' ? 'selected' : '' }}>Ketua Program Studi</option>
                        <option value="staf_administrasi" {{ $user['role'] === 'staf_administrasi' ? 'selected' : '' }}>Staf Administrasi</option>
                        <option value="staf_laboratorium" {{ $user['role'] === 'staf_laboratorium' ? 'selected' : '' }}>Staf Laboratorium</option>
                    </select>
                </div>
                <button class="btn btn-primary">Save</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary ms-2">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
