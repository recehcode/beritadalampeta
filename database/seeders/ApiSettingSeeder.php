<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApiSettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('api_settings')->insert([
            // LLM Settings
            ['setting_key' => 'llm_provider', 'setting_value' => 'ollama', 'setting_group' => 'llm', 'description' => 'AI Provider: ollama, openai, gemini', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'llm_api_url', 'setting_value' => 'http://localhost:11434', 'setting_group' => 'llm', 'description' => 'LLM API endpoint URL', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'llm_api_key', 'setting_value' => '', 'setting_group' => 'llm', 'description' => 'API key (for OpenAI/Gemini)', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'llm_model', 'setting_value' => 'llama3', 'setting_group' => 'llm', 'description' => 'Model name to use', 'created_at' => now(), 'updated_at' => now()],

            // Geocoding Settings
            ['setting_key' => 'geocoding_provider', 'setting_value' => 'nominatim', 'setting_group' => 'geocoding', 'description' => 'Geocoding: nominatim, google', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'geocoding_api_key', 'setting_value' => '', 'setting_group' => 'geocoding', 'description' => 'API key (for Google Maps)', 'created_at' => now(), 'updated_at' => now()],

            // Webhook Settings
            ['setting_key' => 'webhook_api_key', 'setting_value' => Str::random(64), 'setting_group' => 'webhook', 'description' => 'API key for webhook authentication', 'created_at' => now(), 'updated_at' => now()],

            // Map Settings
            ['setting_key' => 'map_center_lat', 'setting_value' => '-0.7893', 'setting_group' => 'map', 'description' => 'Default map center latitude', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'map_center_lng', 'setting_value' => '113.9213', 'setting_group' => 'map', 'description' => 'Default map center longitude', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'map_zoom', 'setting_value' => '5', 'setting_group' => 'map', 'description' => 'Default map zoom level', 'created_at' => now(), 'updated_at' => now()],

            // General Settings
            ['setting_key' => 'polling_interval', 'setting_value' => '30', 'setting_group' => 'general', 'description' => 'Frontend polling interval in seconds', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
