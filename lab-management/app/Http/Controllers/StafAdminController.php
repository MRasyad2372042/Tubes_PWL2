<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StafAdminController extends Controller
{
    private string $apiBase = 'http://localhost:3000/api';

    // ========================
    // PENDING ITEMS
    // ========================

    public function pendingIndex()
    {
        $response = Http::get("{$this->apiBase}/inventories/pending");
        $items = $response->successful() ? $response->json() : [];

        return view('dashboard.staf_administrasi.pending.index', compact('items'));
    }

    // ========================
    // RECEIVE FORM
    // ========================

    public function receiveForm($id)
    {
        // $id = procurement_item_id
        // Get item details from pending
        $response = Http::get("{$this->apiBase}/inventories/pending");
        $items = $response->successful() ? $response->json() : [];
        $item = collect($items)->firstWhere('procurement_item_id', (int) $id);

        if (! $item) {
            return redirect()->route('administrasi.pending')
                ->with('error', 'Item tidak ditemukan atau sudah diterima.');
        }

        // Get rooms for the dropdown
        $roomResponse = Http::get("{$this->apiBase}/rooms");
        $rooms = $roomResponse->successful() ? $roomResponse->json() : [];

        return view('dashboard.staf_administrasi.pending.receive', compact('item', 'rooms'));
    }

    // ========================
    // RECEIVE STORE
    // ========================

    public function receiveStore(Request $request, $id)
    {
        $request->validate([
            'item_type' => 'required|in:inventory,bhp',
            'item_name' => 'required|string|max:255',
            'receive_date' => 'required|date',
            // fields for inventory
            'inventory_code' => 'nullable|string|max:100',
            'room_id' => 'nullable|integer',
            'qr_code' => 'nullable|image|max:5120',
            'barcode' => 'nullable|image|max:5120',
            'photo' => 'nullable|image|max:5120',
            // fields for bhp
            'unit' => 'nullable|string|max:50',
            'initial_stock' => 'nullable|integer|min:0',
        ]);

        // Build multipart request to Node.js API
        $http = Http::asMultipart();
        $http = $http->attach('', '', ''); // Initialize
        $multipart = [];

        $multipart[] = ['name' => 'procurement_item_id', 'contents' => (string) $id];
        $multipart[] = ['name' => 'item_type', 'contents' => $request->item_type];
        $multipart[] = ['name' => 'item_name', 'contents' => $request->item_name];
        $multipart[] = ['name' => 'receive_date', 'contents' => $request->receive_date];

        if ($request->item_type === 'inventory') {
            if ($request->inventory_code) {
                $multipart[] = ['name' => 'inventory_code', 'contents' => $request->inventory_code];
            }
            if ($request->room_id) {
                $multipart[] = ['name' => 'room_id', 'contents' => (string) $request->room_id];
            }
        } elseif ($request->item_type === 'bhp') {
            if ($request->unit) {
                $multipart[] = ['name' => 'unit', 'contents' => $request->unit];
            }
            if ($request->initial_stock !== null) {
                $multipart[] = ['name' => 'initial_stock', 'contents' => (string) $request->initial_stock];
            }
        }

        if ($request->hasFile('qr_code')) {
            $file = $request->file('qr_code');
            $multipart[] = [
                'name' => 'qr_code',
                'contents' => fopen($file->getRealPath(), 'r'),
                'filename' => $file->getClientOriginalName(),
            ];
        }
        if ($request->hasFile('barcode')) {
            $file = $request->file('barcode');
            $multipart[] = [
                'name' => 'barcode',
                'contents' => fopen($file->getRealPath(), 'r'),
                'filename' => $file->getClientOriginalName(),
            ];
        }
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $multipart[] = [
                'name' => 'photo',
                'contents' => fopen($file->getRealPath(), 'r'),
                'filename' => $file->getClientOriginalName(),
            ];
        }

        $response = Http::asMultipart()->post("{$this->apiBase}/inventories/receive", $multipart);

        if ($response->successful()) {
            return redirect()->route('administrasi.pending')
                ->with('success', 'Barang berhasil diterima dan dicatat ke inventaris.');
        }

        $message = $response->json('error') ?: 'Gagal menerima barang. Silakan coba lagi.';
        return redirect()->route('administrasi.receive', $id)->with('error', $message)->withInput();
    }

    // ========================
    // INVENTORY LIST
    // ========================

    public function inventoryIndex()
    {
        $invResponse = Http::get("{$this->apiBase}/inventories");
        $inventories = $invResponse->successful() ? $invResponse->json() : [];

        $bhpResponse = Http::get("{$this->apiBase}/bhp");
        $bhpItems = $bhpResponse->successful() ? $bhpResponse->json() : [];

        return view('dashboard.staf_administrasi.inventaris.index', compact('inventories', 'bhpItems'));
    }

    // ========================
    // DASHBOARD (dynamic stats)
    // ========================

    public function dashboard()
    {
        $pendingRes = Http::get("{$this->apiBase}/inventories/pending");
        $inventoryRes = Http::get("{$this->apiBase}/inventories");

        $pendingItems = $pendingRes->successful() ? collect($pendingRes->json()) : collect();
        $inventories = $inventoryRes->successful() ? collect($inventoryRes->json()) : collect();

        $stats = [
            'pending_count' => $pendingItems->count(),
            'received_count' => $inventories->where('receive_date', '!=', null)->count(),
            'total_inventory' => $inventories->count(),
            'no_label' => $inventories->where('inventory_code', null)->count(),
        ];

        $recentInventories = $inventories->take(5);

        return view('dashboard.staf_administrasi.index', compact('stats', 'pendingItems', 'recentInventories'));
    }
}
