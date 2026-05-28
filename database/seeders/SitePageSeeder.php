<?php

namespace Database\Seeders;

use App\Models\SitePage;
use Illuminate\Database\Seeder;

class SitePageSeeder extends Seeder
{
    public function run(): void
    {
        SitePage::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Welcome to SwapNet',
                'content' => '<h1>Your Premier Swap Network</h1><p>Find, swap, and connect with ease.</p>',
                'is_active' => true,
            ]
        );
    }
}
