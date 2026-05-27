@extends('layouts.sneat')

@section('title','Rooms')

@section('content')
<div class="container-xxl py-4">
    <h3>Rooms (Administrator)</h3>
    <a class="btn btn-primary mb-3" href="{{ route('rooms.create') }}">Create Room</a>
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                    <tr>
                        <td>{{ $room['id'] }}</td>
                        <td>{{ $room['name'] }}</td>
                        <td>{{ $room['location'] }}</td>
                        <td>
                            <a href="{{ route('rooms.edit', $room['id']) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <form action="{{ route('rooms.destroy', $room['id']) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
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
