<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\transactions;
use Illuminate\Http\Request;

class TransactionAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = transactions::with(['user', 'event', 'promo'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->input('event_id'));
        }

        $transactions = $query->paginate(20);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($transactions);
        }

        return view('admin.transactions.index', compact('transactions'));
    }

    public function jsonIndex(Request $request)
    {
        $transactions = transactions::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(fn($tx) => [
                'id'             => $tx->id,
                'user_name'      => $tx->user?->name,
                'user_email'     => $tx->user?->email,
                'event_title'    => $tx->event?->title,
                'quantity'       => $tx->quantity,
                'total'          => (float) $tx->total,
                'status'         => $tx->status,
                'payment_method' => $tx->payment_method,
                'paid_at'        => $tx->paid_at?->toISOString(),
                'created_at'     => $tx->created_at?->toISOString(),
            ]);

        return response()->json($transactions);
    }
}
