<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KepalaLabController extends Controller
{
    private string $apiBase = 'http://localhost:3000/api';

    // ========================
    // DRAFTS
    // ========================

    public function draftsIndex()
    {
        $userId = auth()->id();
        $response = Http::get("{$this->apiBase}/procurement/drafts", [
            'created_by' => $userId,
        ]);
        $drafts = $response->successful() ? $response->json() : [];

        // Count stats
        $allDrafts = collect($drafts);
        $stats = [
            'total' => $allDrafts->count(),
            'draft' => $allDrafts->where('status', 'draft')->count(),
            'locked' => $allDrafts->where('status', 'locked')->count(),
            'finalized' => $allDrafts->where('status', 'finalized')->count(),
        ];

        return view('dashboard.kepala_laboratorium.drafts.index', compact('drafts', 'stats'));
    }

    public function draftsCreate()
    {
        $invResponse = Http::get("{$this->apiBase}/inventories");
        $inventories = $invResponse->successful() ? $invResponse->json() : [];

        return view('dashboard.kepala_laboratorium.drafts.create', compact('inventories'));
    }

    public function draftsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:2020|max:2099',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'required|in:inventory,bhp',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.estimated_price' => 'required|numeric|min:0',
            'items.*.purchase_link' => 'nullable|url|max:500',
            'items.*.replaced_inventory_id' => 'nullable|integer',
            'items.*.notes' => 'nullable|string|max:1000',
        ]);

        $response = Http::post("{$this->apiBase}/procurement/drafts", [
            'title' => $request->title,
            'year' => $request->year,
            'created_by' => auth()->id(),
        ]);

        if ($response->successful()) {
            $draftId = $response->json('id');

            foreach ($request->items as $item) {
                Http::post("{$this->apiBase}/procurement/drafts/{$draftId}/items", [
                    'item_type' => $item['item_type'],
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'estimated_price' => $item['estimated_price'],
                    'purchase_link' => $item['purchase_link'] ?? null,
                    'replaced_inventory_id' => $item['replaced_inventory_id'] ?? null,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            return redirect()->route('pengadaan.show', $draftId)
                ->with('success', 'Draf pengadaan beserta item berhasil dibuat.');
        }

        $message = $response->json('error') ?: 'Gagal membuat draf. Silakan coba lagi.';
        return redirect()->route('pengadaan.create')->with('error', $message)->withInput();
    }

    public function draftsShow($id)
    {
        $response = Http::get("{$this->apiBase}/procurement/drafts/{$id}");

        if (! $response->successful()) {
            return redirect()->route('pengadaan.index')->with('error', 'Draf tidak ditemukan.');
        }

        $draft = $response->json();

        // Security: ensure the draft belongs to the current user
        if ($draft['created_by'] != auth()->id()) {
            abort(403);
        }

        $invResponse = Http::get("{$this->apiBase}/inventories");
        $inventories = $invResponse->successful() ? $invResponse->json() : [];

        return view('dashboard.kepala_laboratorium.drafts.show', compact('draft', 'inventories'));
    }

    public function draftsEdit($id)
    {
        $response = Http::get("{$this->apiBase}/procurement/drafts/{$id}");

        if (! $response->successful()) {
            return redirect()->route('pengadaan.index')->with('error', 'Draf tidak ditemukan.');
        }

        $draft = $response->json();

        if ($draft['created_by'] != auth()->id()) {
            abort(403);
        }

        if ($draft['status'] !== 'draft') {
            return redirect()->route('pengadaan.show', $id)->with('error', 'Draf yang sudah dikunci tidak dapat diedit.');
        }

        return view('dashboard.kepala_laboratorium.drafts.edit', compact('draft'));
    }

    public function draftsUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:2020|max:2099',
        ]);

        $response = Http::put("{$this->apiBase}/procurement/drafts/{$id}", [
            'title' => $request->title,
            'year' => $request->year,
        ]);

        if ($response->successful()) {
            return redirect()->route('pengadaan.show', $id)->with('success', 'Draf berhasil diperbarui.');
        }

        $message = $response->json('error') ?: 'Gagal memperbarui draf.';
        return redirect()->route('pengadaan.edit', $id)->with('error', $message)->withInput();
    }

    public function draftsDestroy($id)
    {
        Http::delete("{$this->apiBase}/procurement/drafts/{$id}");
        return redirect()->route('pengadaan.index')->with('success', 'Draf berhasil dihapus.');
    }

    public function lockDraft($id)
    {
        $response = Http::post("{$this->apiBase}/procurement/drafts/{$id}/lock");

        if ($response->successful()) {
            return redirect()->route('pengadaan.show', $id)->with('success', 'Draf berhasil dikunci dan dikirim untuk review.');
        }

        $message = $response->json('error') ?: 'Gagal mengunci draf.';
        return redirect()->route('pengadaan.show', $id)->with('error', $message);
    }

    // ========================
    // ITEMS
    // ========================

    public function itemStore(Request $request, $draftId)
    {
        $request->validate([
            'item_type' => 'required|in:inventory,bhp',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'estimated_price' => 'required|numeric|min:0',
            'purchase_link' => 'nullable|url|max:500',
            'replaced_inventory_id' => 'nullable|integer',
            'notes' => 'nullable|string|max:1000',
        ]);

        $response = Http::post("{$this->apiBase}/procurement/drafts/{$draftId}/items", [
            'item_type' => $request->item_type,
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
            'estimated_price' => $request->estimated_price,
            'purchase_link' => $request->purchase_link,
            'replaced_inventory_id' => $request->replaced_inventory_id,
            'notes' => $request->notes,
        ]);

        if ($response->successful()) {
            return redirect()->route('pengadaan.show', $draftId)->with('success', 'Item berhasil ditambahkan.');
        }

        $message = $response->json('error') ?: 'Gagal menambahkan item.';
        return redirect()->route('pengadaan.show', $draftId)->with('error', $message)->withInput();
    }

    public function itemUpdate(Request $request, $id)
    {
        $request->validate([
            'item_type' => 'required|in:inventory,bhp',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'estimated_price' => 'required|numeric|min:0',
            'purchase_link' => 'nullable|url|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $response = Http::put("{$this->apiBase}/procurement/items/{$id}", [
            'item_type' => $request->item_type,
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
            'estimated_price' => $request->estimated_price,
            'purchase_link' => $request->purchase_link,
            'notes' => $request->notes,
        ]);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Item berhasil diperbarui.');
        }

        $message = $response->json('error') ?: 'Gagal memperbarui item.';
        return redirect()->back()->with('error', $message)->withInput();
    }

    public function itemDestroy($id, Request $request)
    {
        $response = Http::delete("{$this->apiBase}/procurement/items/{$id}");
        $draftId = $request->query('draft_id', '');

        if ($response->successful()) {
            return redirect()->route('pengadaan.show', $draftId)->with('success', 'Item berhasil dihapus.');
        }

        $message = $response->json('error') ?: 'Gagal menghapus item.';
        return redirect()->route('pengadaan.show', $draftId)->with('error', $message);
    }

    // ========================
    // DASHBOARD (dynamic stats)
    // ========================

    public function dashboard()
    {
        $userId = auth()->id();
        $response = Http::get("{$this->apiBase}/procurement/drafts", [
            'created_by' => $userId,
        ]);
        $drafts = $response->successful() ? collect($response->json()) : collect();

        $stats = [
            'total' => $drafts->count(),
            'locked' => $drafts->where('status', 'locked')->count(),
            'finalized' => $drafts->where('status', 'finalized')->count(),
        ];

        $recentDrafts = $drafts->take(5);

        return view('dashboard.kepala_laboratorium.index', compact('stats', 'recentDrafts'));
    }
}
