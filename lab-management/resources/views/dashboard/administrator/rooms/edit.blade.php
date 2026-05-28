@extends('layouts.sneat')

@section('title','Edit Room')

@section('content')
<div class="container-xxl py-4">
    <h3>Edit Room (Administrator)</h3>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('rooms.update', $room['id']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $room['name'] }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="{{ $room['location'] }}">
                </div>
                <button class="btn btn-primary">Save</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary ms-2">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
