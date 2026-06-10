<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KaprodiController extends Controller
{
    private string $apiBase = 'http://localhost:3000/api';

    // ========================
    // REVIEW
    // ========================

    public function reviewIndex()
    {
        $response = Http::get("{$this->apiBase}/procurement/drafts/locked");
        $drafts = $response->successful() ? $response->json() : [];

        return view('dashboard.ketua_program_studi.review.index', compact('drafts'));
    }

    public function reviewShow($id)
    {
        $response = Http::get("{$this->apiBase}/procurement/drafts/{$id}");

        if (! $response->successful()) {
            return redirect()->route('review.index')->with('error', 'Draf tidak ditemukan.');
        }

        $draft = $response->json();

        // Ensure the draft is in locked status
        if ($draft['status'] !== 'locked') {
            return redirect()->route('review.index')
                ->with('error', 'Draf ini tidak dalam status review.');
        }

        return view('dashboard.ketua_program_studi.review.show', compact('draft'));
    }

    public function approveItem($id)
    {
        $response = Http::put("{$this->apiBase}/procurement/items/{$id}/approve");

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Item berhasil disetujui.');
        }

        $message = $response->json('error') ?: 'Gagal menyetujui item.';
        return redirect()->back()->with('error', $message);
    }

    public function rejectItem($id)
    {
        $response = Http::put("{$this->apiBase}/procurement/items/{$id}/reject");

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Item berhasil ditolak.');
        }

        $message = $response->json('error') ?: 'Gagal menolak item.';
        return redirect()->back()->with('error', $message);
    }

    public function finalize($id)
    {
        $response = Http::post("{$this->apiBase}/procurement/drafts/{$id}/finalize");

        if ($response->successful()) {
            return redirect()->route('review.index')
                ->with('success', 'Draf berhasil difinalisasi.');
        }

        $message = $response->json('error') ?: 'Gagal memfinalisasi draf.';
        return redirect()->route('review.show', $id)->with('error', $message);
    }

    // ========================
    // HISTORY
    // ========================

    public function historyIndex()
    {
        $response = Http::get("{$this->apiBase}/procurement/drafts/finalized");
        $drafts = $response->successful() ? $response->json() : [];

        return view('dashboard.ketua_program_studi.review.history', compact('drafts'));
    }

    public function historyShow($id)
    {
        $response = Http::get("{$this->apiBase}/procurement/drafts/{$id}");

        if (! $response->successful()) {
            return redirect()->route('review.history')->with('error', 'Draf tidak ditemukan.');
        }

        $draft = $response->json();

        return view('dashboard.ketua_program_studi.review.show', compact('draft'));
    }

    // ========================
    // DASHBOARD (dynamic stats)
    // ========================

    public function dashboard()
    {
        $lockedRes = Http::get("{$this->apiBase}/procurement/drafts/locked");
        $finalizedRes = Http::get("{$this->apiBase}/procurement/drafts/finalized");

        $lockedDrafts = $lockedRes->successful() ? collect($lockedRes->json()) : collect();
        $finalizedDrafts = $finalizedRes->successful() ? collect($finalizedRes->json()) : collect();

        $stats = [
            'needs_review' => $lockedDrafts->count(),
            'finalized' => $finalizedDrafts->count(),
        ];

        return view('dashboard.ketua_program_studi.index', compact('stats', 'lockedDrafts', 'finalizedDrafts'));
    }
}
