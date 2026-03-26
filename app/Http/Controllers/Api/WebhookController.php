<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\ApiSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WebhookController extends Controller
{
    /**
     * Receive events from n8n via webhook
     * POST /api/webhook/events
     */
    public function receiveEvent(Request $request): JsonResponse
    {
        // Validate API key
        $apiKey = $request->header('X-API-Key') ?? $request->input('api_key');
        $storedKey = ApiSetting::getValue('webhook_api_key');

        if (!$apiKey || $apiKey !== $storedKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate event data
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:50',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'source_url' => 'nullable|string|max:500',
            'image_url' => 'nullable|string|max:500',
            'source_name' => 'nullable|string|max:100',
            'published_at' => 'nullable|date',
        ]);

        $event = Event::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category_slug' => $validated['category'] ?? 'umum',
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'source_url' => $validated['source_url'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
            'source_name' => $validated['source_name'] ?? null,
            'published_at' => $validated['published_at'] ?? now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'event_id' => $event->id,
        ], 201);
    }
}
