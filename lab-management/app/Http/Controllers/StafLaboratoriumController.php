<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StafLaboratoriumController extends Controller
{
    private string $apiBase = 'http://localhost:3000/api';

    public function index()
    {
        $bhpResponse = Http::get("{$this->apiBase}/bhp");
        $maintenanceResponse = Http::get("{$this->apiBase}/maintenance");

        $bhpItems = $bhpResponse->successful() ? $bhpResponse->json() : [];
        $maintenanceLogs = $maintenanceResponse->successful() ? $maintenanceResponse->json() : [];

        $criticalItems = collect($bhpItems)
            ->filter(fn ($item) => isset($item['stock'], $item['min_stock']) && $item['stock'] <= $item['min_stock'])
            ->values();

        $totals = [
            'total_bhp' => $bhpItems ? count($bhpItems) : 0,
            'critical_count' => $criticalItems->count(),
            'total_inventory' => collect($maintenanceLogs)->pluck('inventory_item')->unique()->count(),
            'maintenance_count' => $maintenanceLogs ? count($maintenanceLogs) : 0,
        ];

        $latestLogs = collect($maintenanceLogs)
            ->sortByDesc(fn ($log) => $log['maintenance_date'] ?? $log['created_at'] ?? '')
            ->take(5)
            ->values();

        return view('dashboard.staf_laboratorium.index', compact('bhpItems', 'criticalItems', 'latestLogs', 'totals'));
    }

    public function bhpIndex()
    {
        $response = Http::get("{$this->apiBase}/bhp");
        $bhpItems = $response->successful() ? $response->json() : [];

        return view('dashboard.staf_laboratorium.bhp.index', compact('bhpItems'));
    }

    public function bhpCreate()
    {
        return redirect()->route('stock-bhp.index')->with('error', 'Staf BHP tidak dapat menambahkan item baru. Gunakan halaman edit untuk mengelola stok item yang sudah ada.');
    }

    public function bhpStore(Request $request)
    {
        return redirect()->route('stock-bhp.index')->with('error', 'Staf BHP tidak memiliki hak untuk menambahkan item baru.');
    }

    public function bhpEdit($id)
    {
        $response = Http::get("{$this->apiBase}/bhp");
        $bhpItems = $response->successful() ? $response->json() : [];
        $item = collect($bhpItems)->firstWhere('id', (int) $id);

        if (! $item) {
            return redirect()->route('stock-bhp.index')->with('error', 'Item BHP tidak ditemukan.');
        }

        return view('dashboard.staf_laboratorium.bhp.edit', ['bhpItem' => $item]);
    }

    public function bhpUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
        ]);

        $response = Http::put("{$this->apiBase}/bhp/{$id}", [
            'name' => $request->name,
            'unit' => $request->unit,
            'stock' => $request->stock,
            'min_stock' => $request->min_stock,
        ]);

        if ($response->successful()) {
            return redirect()->route('stock-bhp.index')->with('success', 'Item BHP berhasil diperbarui.');
        }

        $message = $response->json('error') ?: 'Gagal memperbarui stok BHP. Silakan coba lagi.';
        return redirect()->route('stock-bhp.edit', $id)->with('error', $message)->withInput();
    }

    public function maintenanceIndex()
    {
        $response = Http::get("{$this->apiBase}/maintenance");
        $maintenanceLogs = $response->successful() ? $response->json() : [];

        return view('dashboard.staf_laboratorium.maintenance.index', compact('maintenanceLogs'));
    }

    public function maintenanceCreate()
    {
        $response = Http::get("{$this->apiBase}/bhp");
        $bhpItems = $response->successful() ? $response->json() : [];

        return view('dashboard.staf_laboratorium.maintenance.create', compact('bhpItems'));
    }

    public function maintenanceStore(Request $request)
    {
        $request->validate([
            'inventory_item' => 'required|string|max:255',
            'maintenance_date' => 'required|date',
            'condition' => 'required|string|max:100',
            'replacement_item' => 'nullable|string|max:255',
            'replaced_by' => 'nullable|string|max:255',
            'bhp_item_id' => 'nullable|integer',
            'bhp_used' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $response = Http::post("{$this->apiBase}/maintenance", [
            'inventory_item' => $request->inventory_item,
            'maintenance_date' => $request->maintenance_date,
            'condition' => $request->condition,
            'replacement_item' => $request->replacement_item,
            'replaced_by' => $request->replaced_by,
            'bhp_item_id' => $request->bhp_item_id,
            'bhp_used' => $request->bhp_used ?? 0,
            'notes' => $request->notes,
        ]);

        if ($response->successful()) {
            return redirect()->route('maintenance.index')->with('success', 'Log maintenance berhasil disimpan.');
        }

        $message = $response->json('error') ?: 'Gagal menyimpan log maintenance. Silakan coba lagi.';
        return redirect()->route('maintenance.create')->with('error', $message)->withInput();
    }

    // ========================
    // BHP PROCUREMENT RECEIVING
    // ========================

    public function bhpPendingIndex()
    {
        $response = Http::get("{$this->apiBase}/bhp-stock/pending");
        $pendingItems = $response->successful() ? $response->json() : [];

        return view('dashboard.staf_laboratorium.bhp.pending', compact('pendingItems'));
    }

    public function bhpReceiveStore(Request $request)
    {
        $request->validate([
            'procurement_item_id' => 'nullable|integer',
            'item_name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'initial_stock' => 'required|integer|min:0',
        ]);

        $response = Http::post("{$this->apiBase}/bhp-stock/receive", [
            'procurement_item_id' => $request->procurement_item_id,
            'item_name' => $request->item_name,
            'unit' => $request->unit,
            'initial_stock' => $request->initial_stock,
            'created_by' => auth()->id(),
        ]);

        if ($response->successful()) {
            return redirect()->route('bhp-pending.index')->with('success', 'BHP berhasil diterima ke stok.');
        }

        $message = $response->json('error') ?: 'Gagal menerima BHP. Silakan coba lagi.';
        return redirect()->route('bhp-pending.index')->with('error', $message)->withInput();
    }

    // ========================
    // BHP USAGE
    // ========================

    public function bhpUsageCreate()
    {
        $response = Http::get("{$this->apiBase}/bhp-stock/items");
        $bhpItems = $response->successful() ? $response->json() : [];

        return view('dashboard.staf_laboratorium.bhp.usage', compact('bhpItems'));
    }

    public function bhpUsageStore(Request $request)
    {
        $request->validate([
            'bhp_item_id' => 'required|integer',
            'quantity_used' => 'required|integer|min:1',
            'type' => 'required|in:outgoing,maintenance_usage',
            'description' => 'nullable|string|max:1000',
            'maintenance_id' => 'nullable|integer',
        ]);

        $response = Http::post("{$this->apiBase}/bhp-stock/usage", [
            'bhp_item_id' => $request->bhp_item_id,
            'quantity_used' => $request->quantity_used,
            'type' => $request->type,
            'description' => $request->description,
            'created_by' => auth()->id(),
            'maintenance_id' => $request->maintenance_id,
        ]);

        if ($response->successful()) {
            return redirect()->route('bhp-usage.create')->with('success', 'Pemakaian BHP berhasil dicatat.');
        }

        $message = $response->json('error') ?: 'Gagal mencatat pemakaian BHP.';
        return redirect()->route('bhp-usage.create')->with('error', $message)->withInput();
    }

    // ========================
    // BHP STOCK LOGS
    // ========================

    public function bhpStockLogs()
    {
        $logsResponse = Http::get("{$this->apiBase}/bhp-stock/logs");
        $logs = $logsResponse->successful() ? $logsResponse->json() : [];

        $itemsResponse = Http::get("{$this->apiBase}/bhp-stock/items");
        $bhpItems = $itemsResponse->successful() ? $itemsResponse->json() : [];

        return view('dashboard.staf_laboratorium.bhp.logs', compact('logs', 'bhpItems'));
    }
}

