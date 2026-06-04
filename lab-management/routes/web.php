<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomPageController;
use App\Http\Controllers\StafLaboratoriumController;
use App\Http\Controllers\UserPageController;
use Illuminate\Support\Facades\DB;
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
        'staf_laboratorium' => app(StafLaboratoriumController::class)->index(),
        default => view('sneat.dashboard'),
    };

})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::resource('users', UserPageController::class)->middleware(['auth', 'role:administrator']);
Route::resource('rooms', RoomPageController::class)->middleware(['auth', 'role:administrator']);

Route::prefix('laboratorium')->middleware(['auth', 'role:staf_laboratorium'])->group(function () {
    Route::get('/stock-bhp', [StafLaboratoriumController::class, 'bhpIndex'])->name('stock-bhp.index');
    Route::get('/stock-bhp/create', [StafLaboratoriumController::class, 'bhpCreate'])->name('stock-bhp.create');
    Route::post('/stock-bhp', [StafLaboratoriumController::class, 'bhpStore'])->name('stock-bhp.store');
    Route::get('/stock-bhp/{id}/edit', [StafLaboratoriumController::class, 'bhpEdit'])->name('stock-bhp.edit');
    Route::put('/stock-bhp/{id}', [StafLaboratoriumController::class, 'bhpUpdate'])->name('stock-bhp.update');

    Route::get('/maintenance', [StafLaboratoriumController::class, 'maintenanceIndex'])->name('maintenance.index');
    Route::get('/maintenance/create', [StafLaboratoriumController::class, 'maintenanceCreate'])->name('maintenance.create');
    Route::post('/maintenance', [StafLaboratoriumController::class, 'maintenanceStore'])->name('maintenance.store');
});
