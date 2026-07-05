<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\events;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    /**
     * GET /api/events
     * Query params: search, status
     */
    public function index(Request $request): JsonResponse
    {
        $query = events::query()->where('status', 'Published');

        // Search by title atau description
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $events = $query->orderBy('event_date', 'asc')->get();

        return response()->json(
            $events->map(fn($e) => $this->formatEvent($e))
        );
    }

    /**
     * GET /api/events/{event}
     */
    public function show(events $event): JsonResponse
    {
        return response()->json($this->formatEvent($event));
    }

    private function formatEvent(events $event): array
    {
        return [
            'id'           => $event->id,
            'title'        => $event->title,
            'description'  => $event->description,
            'event_date'   => $event->event_date?->toISOString(),
            'location'     => $event->location,
            'ticket_price' => (float) $event->ticket_price,
            'quota'        => $event->quota,
            'image'        => $event->image,
            'status'       => $event->status,
            'created_at'   => $event->created_at?->toISOString(),
        ];
    }
}
