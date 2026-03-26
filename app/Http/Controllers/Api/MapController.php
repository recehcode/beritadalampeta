<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use App\Models\ApiSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MapController extends Controller
{
    /**
     * Get events for the map (with optional filters)
     * GET /api/events
     */
    public function events(Request $request): JsonResponse
    {
        $query = Event::query();

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('published_at', $request->date);
        }

        // Filter since timestamp (for polling)
        if ($request->filled('since')) {
            $query->where('created_at', '>', $request->since);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_slug', $request->category);
        }

        $events = $query->latest('published_at')->limit(500)->get();

        return response()->json($events);
    }

    /**
     * Get all categories
     * GET /api/categories
     */
    public function categories(): JsonResponse
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Get map settings
     * GET /api/map-settings
     */
    public function settings(): JsonResponse
    {
        return response()->json([
            'center_lat' => (float) ApiSetting::getValue('map_center_lat', '-0.7893'),
            'center_lng' => (float) ApiSetting::getValue('map_center_lng', '113.9213'),
            'zoom' => (int) ApiSetting::getValue('map_zoom', '5'),
            'polling_interval' => (int) ApiSetting::getValue('polling_interval', '30'),
        ]);
    }
}
