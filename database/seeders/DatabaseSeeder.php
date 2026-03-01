<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CrmUserSeeder::class,
            LeadSeeder::class,
            CustomerSeeder::class,
            DealSeeder::class,
            TaskSeeder::class,
        ]);
    }
}