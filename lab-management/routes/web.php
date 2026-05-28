<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }

    return view('welcome');
});

Route::get('/dashboard', function () {

    $role = auth()->user()->role;

    if ($role === 'administrator') {
        $userCount = DB::table('users')->count();
        $roomCount = DB::table('rooms')->count();
        $recentUsers = DB::table('users')->select('id','name','email','role')->orderByDesc('id')->limit(5)->get();
        $recentRooms = DB::table('rooms')->select('id','name','location')->orderByDesc('id')->limit(5)->get();

        return view('dashboard.administrator.index', compact('userCount','roomCount','recentUsers','recentRooms'));
    }

    return match ($role) {
        'kepala_laboratorium' => view('dashboard.kepala_laboratorium.index'),
        'ketua_program_studi' => view('dashboard.ketua_program_studi.index'),
        'staf_administrasi' => view('dashboard.staf_administrasi.index'),
        'staf_laboratorium' => view('dashboard.staf_laboratorium.index'),
        default => view('sneat.dashboard'),
    };

})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\UserPageController;
use App\Http\Controllers\RoomPageController;
use Illuminate\Support\Facades\DB;

Route::resource('users', UserPageController::class)->middleware(['auth', 'role:administrator']);
Route::resource('rooms', RoomPageController::class)->middleware(['auth', 'role:administrator']);
