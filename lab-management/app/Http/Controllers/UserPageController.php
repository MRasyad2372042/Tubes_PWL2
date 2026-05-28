<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserPageController extends Controller
{
    public function index()
    {
        $response = Http::get('http://localhost:3000/api/users');
        $users = $response->successful() ? $response->json() : [];
        return view('dashboard.administrator.users.index', compact('users'));
    }

    public function create()
    {
        return view('dashboard.administrator.users.create');
    }

    public function store(Request $request)
    {
        Http::post('http://localhost:3000/api/users', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index');
    }

    public function edit($id)
    {
        $response = Http::get("http://localhost:3000/api/users");
        $users = $response->successful() ? $response->json() : [];
        $user = collect($users)->firstWhere('id', (int)$id);
        return view('dashboard.administrator.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        Http::put("http://localhost:3000/api/users/{$id}", [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);
        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        Http::delete("http://localhost:3000/api/users/{$id}");
        return redirect()->route('users.index');
    }
}
