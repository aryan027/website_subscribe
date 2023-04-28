<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $websites = [
        [
            'name' => 'Example News',
            'url' => 'https://www.example.com/news',
        ],
        [
            'name' => 'Tech News',
            'url' => 'https://www.technews.com',
        ],
        [
            'name' => 'Fashion Blog',
            'url' => 'https://www.fashionblog.com',
        ],
    ];

        foreach ($websites as $website) {
            DB::table('websites')->insert($website);
        }
    }
}
