@extends('layouts.sneat')

@section('title','Create Room')

@section('content')
<div class="container-xxl py-4">
    <h3>Create Room (Administrator)</h3>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control">
                </div>
                <button class="btn btn-primary">Create</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary ms-2">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
