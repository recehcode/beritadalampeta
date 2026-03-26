<?php

use App\Http\Controllers\Api\MapController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public map API endpoints
Route::get('/events', [MapController::class, 'events']);
Route::get('/categories', [MapController::class, 'categories']);
Route::get('/map-settings', [MapController::class, 'settings']);

// Webhook endpoint (authenticated via API key)
Route::post('/webhook/events', [WebhookController::class, 'receiveEvent']);
