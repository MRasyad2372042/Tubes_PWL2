@extends('layouts.sneat')

@section('title','Users')

@section('content')
<div class="container-xxl py-4">
    <h3>Users (Administrator)</h3>
    <a class="btn btn-primary mb-3" href="{{ route('users.create') }}">Create User</a>
    <a class="btn btn-danger mb-3" href="{{ route('dashboard') }}">Kembali</a>
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user['id'] }}</td>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>{{ $user['role'] ?? '-' }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user['id']) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <form action="{{ route('users.destroy', $user['id']) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Yakin ingin menghapus user {{ $user['name'] }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
