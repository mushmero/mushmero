<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,
        ]);

        if (env('SEED_DEMO_DATA', false) || app()->environment('demo', 'testing')) {
            $this->call(DemoDataSeeder::class);
        }
    }
}
