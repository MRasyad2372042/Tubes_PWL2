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
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $response = Http::post('http://localhost:3000/api/rooms', [
            'name' => $request->name,
            'location' => $request->location,
        ]);

        if ($response->successful()) {
            return redirect()->route('rooms.index')->with('success', 'Room berhasil ditambahkan.');
        }

        $message = $response->json('error') ?: 'Gagal menambahkan room. Silakan coba lagi.';
        return redirect()->route('rooms.create')->with('error', $message);
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
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $response = Http::put("http://localhost:3000/api/rooms/{$id}", [
            'name' => $request->name,
            'location' => $request->location,
        ]);

        if ($response->successful()) {
            return redirect()->route('rooms.index')->with('success', 'Room berhasil diperbarui.');
        }

        $message = $response->json('error') ?: 'Gagal memperbarui room. Silakan coba lagi.';
        return redirect()->route('rooms.edit', $id)->with('error', $message);
    }

    public function destroy($id)
    {
        Http::delete("http://localhost:3000/api/rooms/{$id}");
        return redirect()->route('rooms.index');
    }
}
