<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\events;
use App\Models\promos;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * GET /api/transactions
     * Riwayat transaksi user yang login
     */
    public function history(Request $request): JsonResponse
    {
        $transactions = transactions::with(['event', 'promo'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(
            $transactions->map(fn($tx) => $this->formatTransaction($tx))
        );
    }

    /**
     * POST /api/transactions/checkout
     * Body: { event_id, quantity, payment_method, promo_code? }
     */
    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_id'       => 'required|integer|exists:events,id',
            'quantity'       => 'required|integer|min:1|max:10',
            'payment_method' => 'required|string',
            'promo_code'     => 'nullable|string',
        ]);

        $event = events::findOrFail($validated['event_id']);

        // Cek kuota
        if ($event->quota < $validated['quantity']) {
            return response()->json([
                'message' => 'Kuota tiket tidak mencukupi.',
            ], 422);
        }

        $subtotal = $event->ticket_price * $validated['quantity'];
        $discount = 0;
        $promoId  = null;

        // Validasi promo jika ada
        if (! empty($validated['promo_code'])) {
            $promo = promos::where('promo_code', strtoupper($validated['promo_code']))
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($promo) {
                $discount = $subtotal * ($promo->discount_percentage / 100);
                $promoId  = $promo->id;
            } else {
                return response()->json([
                    'message' => 'Kode promo tidak valid atau sudah kadaluarsa.',
                ], 422);
            }
        }

        $total = $subtotal - $discount;

        DB::beginTransaction();
        try {
            // Kurangi kuota
            $event->decrement('quota', $validated['quantity']);

            // Buat transaksi
            $tx = transactions::create([
                'user_id'        => $request->user()->id,
                'event_id'       => $event->id,
                'promo_id'       => $promoId,
                'quantity'       => $validated['quantity'],
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'total'          => $total,
                'payment_method' => $validated['payment_method'],
                'status'         => 'pending',
            ]);

            DB::commit();

            $tx->load(['event', 'promo']);
            return response()->json($this->formatTransaction($tx), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Checkout gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/transactions/{transaction}
     */
    public function show(Request $request, transactions $transaction): JsonResponse
    {
        // Pastikan transaksi milik user yang login
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $transaction->load(['event', 'promo']);
        return response()->json($this->formatTransaction($transaction));
    }

    /**
     * POST /api/transactions/{transaction}/pay
     * Update status transaksi ke paid
     */
    public function pay(Request $request, transactions $transaction): JsonResponse
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($transaction->status !== 'pending') {
            return response()->json([
                'message' => 'Transaksi sudah diproses sebelumnya.',
            ], 422);
        }

        $transaction->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        $transaction->load(['event', 'promo']);
        return response()->json($this->formatTransaction($transaction));
    }

    private function formatTransaction(transactions $tx): array
    {
        return [
            'id'             => $tx->id,
            'user_id'        => $tx->user_id,
            'event_id'       => $tx->event_id,
            'promo_id'       => $tx->promo_id,
            'quantity'       => $tx->quantity,
            'subtotal'       => (float) $tx->subtotal,
            'discount'       => (float) $tx->discount,
            'total'          => (float) $tx->total,
            'status'         => $tx->status,
            'payment_method' => $tx->payment_method,
            'paid_at'        => $tx->paid_at?->toISOString(),
            'created_at'     => $tx->created_at?->toISOString(),
            'event'          => $tx->event ? [
                'id'         => $tx->event->id,
                'title'      => $tx->event->title,
                'event_date' => $tx->event->event_date?->toISOString(),
                'location'   => $tx->event->location,
            ] : null,
            'promo'          => $tx->promo ? [
                'id'                  => $tx->promo->id,
                'promo_code'          => $tx->promo->promo_code,
                'discount_percentage' => (float) $tx->promo->discount_percentage,
            ] : null,
        ];
    }
}
