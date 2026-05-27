<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RoomPageController extends Controller
{
    public function index()
    {
        $response = Http::get('http://localhost:3000/api/rooms');
        $rooms = $response->successful() ? $response->json() : [];
        return view('dashboard.administrator.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('dashboard.administrator.rooms.create');
    }

    public function store(Request $request)
    {
        Http::post('http://localhost:3000/api/rooms', [
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return redirect()->route('rooms.index');
    }

    public function edit($id)
    {
        $response = Http::get('http://localhost:3000/api/rooms');
        $rooms = $response->successful() ? $response->json() : [];
        $room = collect($rooms)->firstWhere('id', (int)$id);
        return view('dashboard.administrator.rooms.edit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        Http::put("http://localhost:3000/api/rooms/{$id}", [
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return redirect()->route('rooms.index');
    }

    public function destroy($id)
    {
        Http::delete("http://localhost:3000/api/rooms/{$id}");
        return redirect()->route('rooms.index');
    }
}
