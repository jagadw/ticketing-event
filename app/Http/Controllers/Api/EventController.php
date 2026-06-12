<?php

namespace App\Http\Controllers\Api;

use App\Models\events;
use Illuminate\Http\Request;

class EventController
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10);
            $search = $request->query('search', '');
            $status = $request->query('status', 'Published');

            $query = events::query();

            if ($search) {
                $query->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('location', 'like', "%$search%");
            }

            if ($status) {
                $query->where('status', $status);
            }

            $events = $query->orderBy('event_date', 'asc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $events->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'title' => $event->title,
                        'description' => $event->description,
                        'image' => $event->image ? asset('storage/' . $event->image) : null,
                        'event_date' => $event->event_date->format('Y-m-d H:i'),
                        'location' => $event->location,
                        'ticket_price' => (float) $event->ticket_price,
                        'quota' => $event->quota,
                        'status' => $event->status,
                    ];
                }),
                'meta' => [
                    'current_page' => $events->currentPage(),
                    'total' => $events->total(),
                    'per_page' => $events->perPage(),
                    'last_page' => $events->lastPage(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(events $event)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'image' => $event->image ? asset('storage/' . $event->image) : null,
                    'event_date' => $event->event_date->format('Y-m-d H:i'),
                    'location' => $event->location,
                    'ticket_price' => (float) $event->ticket_price,
                    'quota' => $event->quota,
                    'status' => $event->status,
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
