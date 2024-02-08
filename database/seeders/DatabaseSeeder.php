<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\SnsMessage;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        SnsMessage::create(['content' => 'This is my first message']);
        SnsMessage::create(['content' => 'This is my second message']);
        SnsMessage::create(['content' => 'This is my third message']);
    }
}
