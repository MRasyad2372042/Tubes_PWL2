<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomPageController;
use App\Http\Controllers\StafLaboratoriumController;
use App\Http\Controllers\UserPageController;
use App\Http\Controllers\KepalaLabController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\StafAdminController;
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
        'kepala_laboratorium' => app(KepalaLabController::class)->dashboard(),
        'ketua_program_studi' => app(KaprodiController::class)->dashboard(),
        'staf_administrasi' => app(StafAdminController::class)->dashboard(),
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

    // BHP Procurement Receiving
    Route::get('/bhp-pending', [StafLaboratoriumController::class, 'bhpPendingIndex'])->name('bhp-pending.index');
    Route::post('/bhp-receive', [StafLaboratoriumController::class, 'bhpReceiveStore'])->name('bhp-receive.store');

    // BHP Usage
    Route::get('/bhp-usage', [StafLaboratoriumController::class, 'bhpUsageCreate'])->name('bhp-usage.create');
    Route::post('/bhp-usage', [StafLaboratoriumController::class, 'bhpUsageStore'])->name('bhp-usage.store');

    // BHP Stock Logs
    Route::get('/bhp-logs', [StafLaboratoriumController::class, 'bhpStockLogs'])->name('bhp-logs.index');
});

// === KEPALA LABORATORIUM — Draf Pengadaan ===
Route::prefix('pengadaan')->middleware(['auth', 'role:kepala_laboratorium'])->group(function () {
    Route::get('/', [KepalaLabController::class, 'draftsIndex'])->name('pengadaan.index');
    Route::get('/buat', [KepalaLabController::class, 'draftsCreate'])->name('pengadaan.create');
    Route::post('/', [KepalaLabController::class, 'draftsStore'])->name('pengadaan.store');
    Route::get('/{id}', [KepalaLabController::class, 'draftsShow'])->name('pengadaan.show');
    Route::get('/{id}/edit', [KepalaLabController::class, 'draftsEdit'])->name('pengadaan.edit');
    Route::put('/{id}', [KepalaLabController::class, 'draftsUpdate'])->name('pengadaan.update');
    Route::delete('/{id}', [KepalaLabController::class, 'draftsDestroy'])->name('pengadaan.destroy');
    Route::post('/{id}/lock', [KepalaLabController::class, 'lockDraft'])->name('pengadaan.lock');
    Route::post('/{id}/items', [KepalaLabController::class, 'itemStore'])->name('pengadaan.items.store');
    Route::put('/items/{id}', [KepalaLabController::class, 'itemUpdate'])->name('pengadaan.items.update');
    Route::delete('/items/{id}', [KepalaLabController::class, 'itemDestroy'])->name('pengadaan.items.destroy');
});

// === KETUA PROGRAM STUDI — Review & Finalisasi ===
Route::prefix('review')->middleware(['auth', 'role:ketua_program_studi'])->group(function () {
    Route::get('/', [KaprodiController::class, 'reviewIndex'])->name('review.index');
    Route::get('/history', [KaprodiController::class, 'historyIndex'])->name('review.history');
    Route::get('/history/{id}', [KaprodiController::class, 'historyShow'])->name('review.history.show');
    Route::get('/{id}', [KaprodiController::class, 'reviewShow'])->name('review.show');
    Route::put('/items/{id}/approve', [KaprodiController::class, 'approveItem'])->name('review.items.approve');
    Route::put('/items/{id}/reject', [KaprodiController::class, 'rejectItem'])->name('review.items.reject');
    Route::post('/{id}/finalize', [KaprodiController::class, 'finalize'])->name('review.finalize');
});

// === STAF ADMINISTRASI — Penerimaan Inventaris ===
Route::prefix('administrasi')->middleware(['auth', 'role:staf_administrasi'])->group(function () {
    Route::get('/pending', [StafAdminController::class, 'pendingIndex'])->name('administrasi.pending');
    Route::get('/receive/{id}', [StafAdminController::class, 'receiveForm'])->name('administrasi.receive');
    Route::post('/receive/{id}', [StafAdminController::class, 'receiveStore'])->name('administrasi.receive.store');
    Route::get('/inventaris', [StafAdminController::class, 'inventoryIndex'])->name('administrasi.inventaris');
});

