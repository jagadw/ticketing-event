<?php

namespace App\Http\Controllers\Api;

use App\Models\transactions;
use App\Models\events;
use App\Models\promos;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionController
{
    public function checkout(Request $request)
    {
        try {
            $validated = $request->validate([
                'event_id' => 'required|exists:events,id',
                'quantity' => 'required|integer|min:1',
                'promo_code' => 'nullable|string|exists:promos,promo_code',
                'payment_method' => 'required|in:bank_transfer,e_wallet,credit_card',
            ]);

            $event = events::findOrFail($validated['event_id']);
            $user = $request->user();

            if ($event->quota < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kuota ticket tidak cukup',
                ], 400);
            }

            $subtotal = $event->ticket_price * $validated['quantity'];
            $discount = 0;

            if ($validated['promo_code']) {
                $promo = promos::where('promo_code', $validated['promo_code'])->first();

                if (!$promo || !$promo->is_active) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kode promo tidak valid',
                    ], 400);
                }

                $discount = ($subtotal * $promo->discount_percentage) / 100;
            }

            $total = $subtotal - $discount;

            $transaction = transactions::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'promo_id' => $promo->id ?? null,
                'quantity' => $validated['quantity'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Checkout berhasil',
                'data' => [
                    'id' => $transaction->id,
                    'event' => [
                        'id' => $event->id,
                        'title' => $event->title,
                        'ticket_price' => (float) $event->ticket_price,
                    ],
                    'quantity' => $transaction->quantity,
                    'subtotal' => (float) $transaction->subtotal,
                    'discount' => (float) $transaction->discount,
                    'total' => (float) $transaction->total,
                    'payment_method' => $transaction->payment_method,
                    'status' => $transaction->status,
                    'created_at' => $transaction->created_at,
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Checkout gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function pay(Request $request, transactions $transaction)
    {
        try {
            $user = $request->user();

            if ($transaction->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            if ($transaction->status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi sudah dibayar',
                ], 400);
            }

            $request->validate([
                'payment_proof' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            ]);

            $paymentProof = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProof = $request->file('payment_proof')->store('payments', 'public');
            }

            $transaction->update([
                'status' => 'paid',
                'payment_proof' => $paymentProof,
                'paid_at' => now(),
            ]);

            $transaction->event->update([
                'quota' => $transaction->event->quota - $transaction->quantity,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil',
                'data' => [
                    'id' => $transaction->id,
                    'status' => $transaction->status,
                    'paid_at' => $transaction->paid_at,
                    'total' => (float) $transaction->total,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, transactions $transaction)
    {
        try {
            $user = $request->user();

            if ($transaction->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $transaction->id,
                    'event' => [
                        'id' => $transaction->event->id,
                        'title' => $transaction->event->title,
                        'event_date' => $transaction->event->event_date->format('Y-m-d H:i'),
                        'location' => $transaction->event->location,
                    ],
                    'quantity' => $transaction->quantity,
                    'subtotal' => (float) $transaction->subtotal,
                    'discount' => (float) $transaction->discount,
                    'total' => (float) $transaction->total,
                    'payment_method' => $transaction->payment_method,
                    'status' => $transaction->status,
                    'created_at' => $transaction->created_at,
                    'paid_at' => $transaction->paid_at,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function history(Request $request)
    {
        try {
            $user = $request->user();
            $perPage = $request->query('per_page', 10);
            $status = $request->query('status', null);

            $query = transactions::where('user_id', $user->id);

            if ($status) {
                $query->where('status', $status);
            }

            $transactions = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $transactions->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'event' => [
                            'id' => $transaction->event->id,
                            'title' => $transaction->event->title,
                            'image' => $transaction->event->image ? asset('storage/' . $transaction->event->image) : null,
                        ],
                        'quantity' => $transaction->quantity,
                        'total' => (float) $transaction->total,
                        'status' => $transaction->status,
                        'created_at' => $transaction->created_at->format('Y-m-d H:i'),
                    ];
                }),
                'meta' => [
                    'current_page' => $transactions->currentPage(),
                    'total' => $transactions->total(),
                    'per_page' => $transactions->perPage(),
                    'last_page' => $transactions->lastPage(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
