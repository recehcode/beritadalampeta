<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ApiSetting;

class MapPageController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $mapSettings = [
            'center_lat' => (float) ApiSetting::getValue('map_center_lat', '-0.7893'),
            'center_lng' => (float) ApiSetting::getValue('map_center_lng', '113.9213'),
            'zoom' => (int) ApiSetting::getValue('map_zoom', '5'),
            'polling_interval' => (int) ApiSetting::getValue('polling_interval', '30'),
        ];

        return view('map', compact('categories', 'mapSettings'));
    }
}
