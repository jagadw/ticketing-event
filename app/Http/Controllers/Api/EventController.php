<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\events;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $query = events::query()->where('status', 'Published');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $eventsData = $query->orderBy('event_date', 'asc')->get();

        return response()->json(
            $eventsData->map(fn($e) => $this->formatEvent($e))
        );
    }

    public function show(events $event): JsonResponse
    {
        return response()->json($this->formatEvent($event));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'event_date'   => 'required|date',
            'location'     => 'required|string|max:255',
            'ticket_price' => 'required|numeric|min:0',
            'quota'        => 'required|integer|min:1',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'       => 'nullable|string|in:Draft,Published',
        ]);

        $imagePath = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        $event = events::create([
            'title'        => $validated['title'],
            'description'  => $validated['description'],
            'event_date'   => $validated['event_date'],
            'location'     => $validated['location'],
            'ticket_price' => $validated['ticket_price'],
            'quota'        => $validated['quota'],
            'image'        => $imagePath,
            'status'       => $validated['status'] ?? 'Draft',
        ]);

        return response()->json($this->formatEvent($event), 201);
    }

    public function update(Request $request, events $event): JsonResponse
    {
        $validated = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'description'  => 'sometimes|string',
            'event_date'   => 'sometimes|date',
            'location'     => 'sometimes|string|max:255',
            'ticket_price' => 'sometimes|numeric|min:0',
            'quota'        => 'sometimes|integer|min:0',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'       => 'sometimes|string|in:Draft,Published',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')
                ->store('events', 'public');
        }

        $event->update($validated);

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
            'quota'        => (int) $event->quota,
            'image'        => $event->image,
            'status'       => $event->status,
            'created_at'   => $event->created_at?->toISOString(),
        ];
    }
}
