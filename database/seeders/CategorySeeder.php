<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Bencana Alam', 'slug' => 'bencana', 'icon' => '🌊', 'color' => '#e74c3c', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kecelakaan Lalu Lintas', 'slug' => 'kecelakaan', 'icon' => '💥', 'color' => '#e67e22', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kemacetan', 'slug' => 'kemacetan', 'icon' => '🚦', 'color' => '#f1c40f', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Upacara', 'slug' => 'upacara', 'icon' => '🏛️', 'color' => '#3498db', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Event', 'slug' => 'event', 'icon' => '🎉', 'color' => '#2ecc71', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Berita Umum', 'slug' => 'umum', 'icon' => '📰', 'color' => '#95a5a6', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
