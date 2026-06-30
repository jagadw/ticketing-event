<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\events;
use App\Models\promos;
use App\Models\transactions;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

    public function history(Request $request): JsonResponse
    {
        $txList = transactions::with(['event', 'promo'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(
            $txList->map(fn($tx) => $this->formatTransaction($tx))
        );
    }

    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_id'       => 'required|integer|exists:events,id',
            'quantity'       => 'required|integer|min:1|max:10',
            'payment_method' => 'nullable|string',
            'promo_code'     => 'nullable|string',
        ]);

        $event = events::findOrFail($validated['event_id']);

        if ($event->quota < $validated['quantity']) {
            return response()->json([
                'message' => 'Kuota tidak mencukupi. Tersisa ' . $event->quota . ' tiket.',
            ], 422);
        }

        $subtotal = (float) $event->ticket_price * $validated['quantity'];
        $discount = 0.0;
        $promoId  = null;

        if (!empty($validated['promo_code'])) {
            $promo = promos::where('promo_code', strtoupper($validated['promo_code']))
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if (!$promo) {
                return response()->json([
                    'message' => 'Kode promo tidak valid atau sudah kadaluarsa.',
                ], 422);
            }

            $discount = $subtotal * ($promo->discount_percentage / 100);
            $promoId  = $promo->id;
        }

        $total     = $subtotal - $discount;
        $grossAmount = (int) round($total); 

        DB::beginTransaction();
        try {
            $event->decrement('quota', $validated['quantity']);

            $orderId = 'TKT-' . $request->user()->id . '-' . time();

            $tx = transactions::create([
                'user_id'        => $request->user()->id,
                'event_id'       => $event->id,
                'promo_id'       => $promoId,
                'quantity'       => $validated['quantity'],
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'total'          => $total,
                'payment_method' => $validated['payment_method'] ?? 'midtrans',
                'status'         => 'pending',
                'midtrans_order_id' => $orderId,
            ]);

            $snapResponse = $this->midtrans->createSnapToken([
                'order_id'       => $orderId,
                'gross_amount'   => $grossAmount,
                'customer_name'  => $request->user()->name,
                'customer_email' => $request->user()->email,
                'event_id'       => (string) $event->id,
                'event_name'     => $event->title,
                'items'          => [
                    [
                        'id'       => (string) $event->id,
                        'price'    => (int) round($event->ticket_price),
                        'quantity' => $validated['quantity'],
                        'name'     => $event->title,
                    ],
                ],
            ]);

            $tx->update([
                'snap_token' => $snapResponse['token'] ?? null,
            ]);

            DB::commit();

            $tx->load(['event', 'promo']);

            return response()->json([
                ...$this->formatTransaction($tx),
                'snap_token'   => $snapResponse['token'] ?? null,
                'redirect_url' => $snapResponse['redirect_url'] ?? null,
                'client_key'   => $this->midtrans->getClientKey(),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Checkout gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, transactions $transaction): JsonResponse
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $transaction->load(['event', 'promo']);
        return response()->json($this->formatTransaction($transaction));
    }

    public function pay(Request $request, transactions $transaction): JsonResponse
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($transaction->status === 'paid') {
            $transaction->load(['event', 'promo']);
            return response()->json($this->formatTransaction($transaction));
        }

        if ($transaction->status === 'cancelled') {
            return response()->json([
                'message' => 'Transaksi sudah dibatalkan.',
            ], 422);
        }

        try {
            $orderId = $transaction->midtrans_order_id
                ?? 'TKT-' . $transaction->user_id . '-' . $transaction->id;

            $midtransStatus = $this->midtrans->checkStatus($orderId);

            $transactionStatus = $midtransStatus['transaction_status'] ?? 'pending';
            $fraudStatus       = $midtransStatus['fraud_status'] ?? '';
            $paymentType       = $midtransStatus['payment_type'] ?? $transaction->payment_method;

            $localStatus = $this->midtrans->mapStatus($transactionStatus, $fraudStatus);

            $updateData = [
                'status'         => $localStatus,
                'payment_method' => $paymentType,
            ];

            if ($localStatus === 'paid') {
                $updateData['paid_at'] = now();
            }

            $transaction->update($updateData);
            $transaction->load(['event', 'promo']);

            return response()->json($this->formatTransaction($transaction));

        } catch (\Exception $e) {
            return response()->json([
                ...$this->formatTransaction($transaction),
                'midtrans_error' => $e->getMessage(),
            ]);
        }
    }

    public function webhook(Request $request): JsonResponse
    {
        $data = $request->all();

        $signatureKey    = $data['signature_key'] ?? '';
        $orderId         = $data['order_id'] ?? '';
        $statusCode      = $data['status_code'] ?? '';
        $grossAmount     = $data['gross_amount'] ?? '';
        $transactionStatus = $data['transaction_status'] ?? '';
        $fraudStatus     = $data['fraud_status'] ?? '';
        $paymentType     = $data['payment_type'] ?? '';

        if (!$this->midtrans->verifySignature(
            $orderId, $statusCode, $grossAmount, $signatureKey
        )) {
            return response()->json(['message' => 'Invalid signature.'], 403);
        }

        $tx = transactions::where('midtrans_order_id', $orderId)->first();

        if (!$tx) {
            return response()->json(['message' => 'Transaction not found.'], 404);
        }

        $localStatus = $this->midtrans->mapStatus($transactionStatus, $fraudStatus);

        $updateData = [
            'status'         => $localStatus,
            'payment_method' => $paymentType,
        ];

        if ($localStatus === 'paid') {
            $updateData['paid_at'] = now();
        }

        if ($localStatus === 'cancelled') {
            $tx->event?->increment('quota', $tx->quantity);
        }

        $tx->update($updateData);

        return response()->json(['message' => 'OK']);
    }

    public function checkStatus(Request $request, transactions $transaction): JsonResponse
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $transaction->load(['event', 'promo']);
        return response()->json([
            ...$this->formatTransaction($transaction),
            'is_paid' => $transaction->status === 'paid',
        ]);
    }

    private function formatTransaction(transactions $tx): array
    {
        return [
            'id'                 => $tx->id,
            'user_id'            => $tx->user_id,
            'event_id'           => $tx->event_id,
            'promo_id'           => $tx->promo_id,
            'quantity'           => $tx->quantity,
            'subtotal'           => (float) $tx->subtotal,
            'discount'           => (float) $tx->discount,
            'total'              => (float) $tx->total,
            'status'             => $tx->status,
            'payment_method'     => $tx->payment_method,
            'midtrans_order_id'  => $tx->midtrans_order_id ?? null,
            'snap_token'         => $tx->snap_token ?? null,
            'paid_at'            => $tx->paid_at?->toISOString(),
            'created_at'         => $tx->created_at?->toISOString(),
            'event' => $tx->event ? [
                'id'         => $tx->event->id,
                'title'      => $tx->event->title,
                'event_date' => $tx->event->event_date?->toISOString(),
                'location'   => $tx->event->location,
            ] : null,
            'promo' => $tx->promo ? [
                'id'                  => $tx->promo->id,
                'promo_code'          => $tx->promo->promo_code,
                'discount_percentage' => (float) $tx->promo->discount_percentage,
            ] : null,
        ];
    }
}
